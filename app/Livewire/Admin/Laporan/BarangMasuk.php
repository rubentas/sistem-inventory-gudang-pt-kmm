<?php
namespace App\Livewire\Admin\Laporan;

use App\Exports\BarangMasukExport;
use App\Models\BarangMasuk as BarangMasukModel;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class BarangMasuk extends Component {
  public string $filterType   = 'month';
  public string $tanggalAwal  = '';
  public string $tanggalAkhir = '';

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

  public function getDataPerHari(): array {
    $data = BarangMasukModel::whereBetween('tanggal_masuk', [$this->tanggalAwal, $this->tanggalAkhir])
      ->selectRaw('DATE(tanggal_masuk) as tanggal, SUM(jumlah) as total')
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

    $data = BarangMasukModel::whereYear('tanggal_masuk', $tahun)
      ->selectRaw('MONTH(tanggal_masuk) as bulan, SUM(jumlah) as total')
      ->groupBy('bulan')
      ->orderBy('bulan')
      ->get();

    // Set 12 bulan selalu ada
    $bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    $values    = array_fill(0, 12, 0);

    foreach ($data as $d) {
      $values[$d->bulan - 1] = (int) $d->total;
    }

    return [
      'labels' => $bulanNama,
      'values' => $values,
    ];
  }
  public function getDataPerSupplier(): array {
    $data = BarangMasukModel::whereBetween('tanggal_masuk', [$this->tanggalAwal, $this->tanggalAkhir])
      ->selectRaw('id_supplier, SUM(jumlah) as total')
      ->groupBy('id_supplier')
      ->with('supplier')
      ->get();

    return [
      'labels' => $data->map(fn($d) => $d->supplier->nama_supplier ?? 'Tanpa Supplier')->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  public function getRingkasan(): array {
    $totalMasuk    = BarangMasukModel::whereBetween('tanggal_masuk', [$this->tanggalAwal, $this->tanggalAkhir])->sum('jumlah');
    $totalItem     = BarangMasukModel::whereBetween('tanggal_masuk', [$this->tanggalAwal, $this->tanggalAkhir])->count();
    $rataRata      = $totalItem > 0 ? round($totalMasuk / $totalItem) : 0;
    $supplierAktif = BarangMasukModel::whereBetween('tanggal_masuk', [$this->tanggalAwal, $this->tanggalAkhir])
      ->distinct('id_supplier')->count('id_supplier');

    return [
      'total_masuk'    => $totalMasuk,
      'total_item'     => $totalItem,
      'rata_rata'      => $rataRata,
      'supplier_aktif' => $supplierAktif,
    ];
  }

  public function getTabelRingkas() {
    return BarangMasukModel::with(['barang', 'supplier'])
      ->whereBetween('tanggal_masuk', [$this->tanggalAwal, $this->tanggalAkhir])
      ->orderByDesc('tanggal_masuk')
      ->limit(5)
      ->get();
  }

  public function cetakPdf() {

    set_time_limit(120);

    $data = BarangMasukModel::with(['barang', 'supplier', 'user'])
      ->whereBetween('tanggal_masuk', [$this->tanggalAwal, $this->tanggalAkhir])
      ->orderByDesc('tanggal_masuk')
      ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.barang-masuk', [
      'data'          => $data,
      'tanggal_awal'  => Carbon::parse($this->tanggalAwal)->translatedFormat('d F Y'),
      'tanggal_akhir' => Carbon::parse($this->tanggalAkhir)->translatedFormat('d F Y'),
      'total_jumlah'  => $data->sum('jumlah'),
      'dicetak_oleh'  => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
      'nama_supplier' => null,
    ])->setPaper('a4', 'landscape');

    return response()->stream(
      fn() => print($pdf->output()),
      200,
      [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="laporan-barang-masuk-' . $this->tanggalAwal . '-sd-' . $this->tanggalAkhir . '.pdf"',
      ]
    );
  }

  public function exportExcel() {
    return (new BarangMasukExport($this->tanggalAwal, $this->tanggalAkhir))->download();
  }

  public function render() {
    return view('components.admin.laporan.barang-masuk', [
      'perHari'      => $this->getDataPerHari(),
      'perBulan'     => $this->getDataPerBulan(),
      'perSupplier'  => $this->getDataPerSupplier(),
      'ringkasan'    => $this->getRingkasan(),
      'tabelRingkas' => $this->getTabelRingkas(),
    ]);
  }
}
