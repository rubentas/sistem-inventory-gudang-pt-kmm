<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\BarangKeluar;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class BarangTerlaris extends Component {
  public string $filterType     = 'month';
  public string $filterKategori = '';
  public string $tanggalAwal    = '';
  public string $tanggalAkhir   = '';
  public int $limit             = 10;

  public function mount(): void {
    $this->tanggalAwal  = now()->startOfMonth()->format('Y-m-d');
    $this->tanggalAkhir = now()->format('Y-m-d');
  }

  public function setFilter(string $type): void {
    $this->filterType = $type;
    switch ($type) {
    case 'today':$this->tanggalAwal = $this->tanggalAkhir = now()->format('Y-m-d');
      break;
    case 'week':$this->tanggalAwal = now()->subDays(6)->format('Y-m-d');
      $this->tanggalAkhir            = now()->format('Y-m-d');
      break;
    case 'month':$this->tanggalAwal = now()->startOfMonth()->format('Y-m-d');
      $this->tanggalAkhir             = now()->format('Y-m-d');
      break;
    }
  }

  public function getTopBarang(): array {
    $data = BarangKeluar::with('barang')
      ->whereBetween('tanggal_keluar', [$this->tanggalAwal, $this->tanggalAkhir])
      ->when($this->filterKategori, fn($q) => $q->whereHas('barang', fn($b) => $b->where('kategori', $this->filterKategori)))
      ->selectRaw('id_barang, SUM(jumlah) as total_keluar')
      ->groupBy('id_barang')
      ->orderByDesc('total_keluar')
      ->limit($this->limit)
      ->get();

    return [
      'labels' => $data->map(fn($d) => strlen($d->barang->nama_barang) > 30 ? substr($d->barang->nama_barang, 0, 30) . '...' : $d->barang->nama_barang)->toArray(),
      'values' => $data->pluck('total_keluar')->toArray(),
      'full'   => $data,
    ];
  }

  public function getKategoriList(): array {
    return \App\Models\Barang::distinct()->pluck('kategori')->toArray();
  }

  public function cetakPdf() {
    $data = $this->getTopBarang()['full'];
    $pdf  = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.barang-terlaris', [
      'data'          => $data,
      'tanggal_awal'  => Carbon::parse($this->tanggalAwal)->translatedFormat('d F Y'),
      'tanggal_akhir' => Carbon::parse($this->tanggalAkhir)->translatedFormat('d F Y'),
      'total_keluar'  => $data->sum('total_keluar'),
      'dicetak_oleh'  => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'portrait');
    return response()->stream(fn() => print($pdf->output()), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline']);
  }

  public function exportExcel() {
    return (new \App\Exports\BarangTerlarisExport($this->tanggalAwal, $this->tanggalAkhir, $this->filterKategori, $this->limit))->download();
  }

  public function render() {
    return view('components.admin.laporan.barang-terlaris', [
      'topBarang'    => $this->getTopBarang(),
      'kategoriList' => $this->getKategoriList(),
    ]);
  }
}