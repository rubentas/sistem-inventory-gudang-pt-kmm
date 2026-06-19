<?php
namespace App\Livewire\Admin\Laporan;

use App\Exports\BarangKeluarExport;
use App\Models\BarangKeluar as BarangKeluarModel;
use App\Models\Wilayah;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class BarangKeluar extends Component {
  public string $filterType    = 'month';
  public string $filterWilayah = '';
  public string $tanggalAwal   = '';
  public string $tanggalAkhir  = '';

  public function mount(): void {
    $this->tanggalAwal  = now()->startOfMonth()->format('Y-m-d');
    $this->tanggalAkhir = now()->format('Y-m-d');
  }

  public function setFilter(string $type): void {
    $this->filterType = $type;

    switch ($type) {
    case 'today':
      $this->tanggalAwal  = now()->format('Y-m-d');
      $this->tanggalAkhir = now()->format('Y-m-d');
      break;
    case 'week':
      $this->tanggalAwal  = now()->subDays(6)->format('Y-m-d');
      $this->tanggalAkhir = now()->format('Y-m-d');
      break;
    case 'month':
      $this->tanggalAwal  = now()->startOfMonth()->format('Y-m-d');
      $this->tanggalAkhir = now()->format('Y-m-d');
      break;
    }
  }

  protected function query(): \Illuminate\Database\Eloquent\Builder {
    return BarangKeluarModel::with(['barang', 'wilayah', 'user'])
      ->whereBetween('tanggal_keluar', [$this->tanggalAwal, $this->tanggalAkhir])
      ->when($this->filterWilayah, fn($q) => $q->where('id_wilayah', $this->filterWilayah));
  }

  public function getDataPerHari(): array {
    $data = (clone $this->query())
      ->selectRaw('DATE(tanggal_keluar) as tanggal, SUM(jumlah) as total')
      ->groupBy('tanggal')
      ->orderBy('tanggal')
      ->get();

    return [
      'labels' => $data->pluck('tanggal')->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  public function getDataPerBulan(): array {
    $tahun = now()->year;

    $data = (clone $this->query())
      ->whereYear('tanggal_keluar', $tahun)
      ->selectRaw('MONTH(tanggal_keluar) as bulan, SUM(jumlah) as total')
      ->groupBy('bulan')
      ->orderBy('bulan')
      ->get();

    $bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    $values    = array_fill(0, 12, 0);

    foreach ($data as $d) {
      $values[$d->bulan - 1] = (int) $d->total;
    }

    return ['labels' => $bulanNama, 'values' => $values];
  }

  public function getDataPerWilayah(): array {
    $data = (clone $this->query())
      ->selectRaw('id_wilayah, SUM(jumlah) as total')
      ->groupBy('id_wilayah')
      ->with('wilayah')
      ->get();

    return [
      'labels' => $data->map(fn($d) => $d->wilayah->nama_wilayah ?? 'Tanpa Wilayah')->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  public function getRingkasan(): array {
    $totalKeluar  = (clone $this->query())->sum('jumlah');
    $totalItem    = (clone $this->query())->count();
    $rataRata     = $totalItem > 0 ? round($totalKeluar / $totalItem) : 0;
    $totalWilayah = (clone $this->query())->distinct('id_wilayah')->count('id_wilayah');

    return [
      'total_keluar'  => $totalKeluar,
      'total_item'    => $totalItem,
      'rata_rata'     => $rataRata,
      'total_wilayah' => $totalWilayah,
    ];
  }

  public function getTabelRingkas() {
    return (clone $this->query())
      ->orderByDesc('tanggal_keluar')
      ->limit(5)
      ->get();
  }

  public function cetakPdf() {
    $data = $this->query()->orderByDesc('tanggal_keluar')->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.barang-keluar', [
      'data'          => $data,
      'tanggal_awal'  => Carbon::parse($this->tanggalAwal)->translatedFormat('d F Y'),
      'tanggal_akhir' => Carbon::parse($this->tanggalAkhir)->translatedFormat('d F Y'),
      'total_jumlah'  => $data->sum('jumlah'),
      'dicetak_oleh'  => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'landscape');

    return response()->stream(
      fn() => print($pdf->output()),
      200,
      [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="laporan-barang-keluar-' . $this->tanggalAwal . '.pdf"',
      ]
    );
  }

  public function exportExcel() {
    return (new BarangKeluarExport($this->tanggalAwal, $this->tanggalAkhir, $this->filterWilayah))->download();
  }

  public function render() {
    return view('components.admin.laporan.barang-keluar', [
      'perHari'      => $this->getDataPerHari(),
      'perBulan'     => $this->getDataPerBulan(),
      'perWilayah'   => $this->getDataPerWilayah(),
      'ringkasan'    => $this->getRingkasan(),
      'tabelRingkas' => $this->getTabelRingkas(),
      'wilayahList'  => Wilayah::orderBy('nama_wilayah')->get(),
    ]);
  }
}