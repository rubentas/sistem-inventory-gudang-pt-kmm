<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\DetailReturPenjualan;
use App\Models\Inventory;
use App\Models\ReturPembelian;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class LaporanInventory extends Component {
  use WithPagination;

  public string $search        = '';
  public string $tanggalAwal   = '';
  public string $tanggalAkhir  = '';
  public string $filterSelisih = '';

  public function mount(): void {
    $this->tanggalAwal  = now()->startOfMonth()->format('Y-m-d');
    $this->tanggalAkhir = now()->endOfMonth()->format('Y-m-d');
  }

  protected function query() {
    return Inventory::with(['barang.stok', 'user'])
      ->whereBetween('tanggal', [$this->tanggalAwal, $this->tanggalAkhir])
      ->when($this->search, fn($q) => $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%')))
      ->when($this->filterSelisih === 'negatif', fn($q) => $q->where('selisih', '<', 0))
      ->when($this->filterSelisih === 'nol', fn($q) => $q->where('selisih', 0))
      ->when($this->filterSelisih === 'positif', fn($q) => $q->where('selisih', '>', 0));
  }

  public function getRingkasan(): array {
    $query     = $this->query();
    $returJual = $this->getTotalReturPenjualan();
    $returBeli = $this->getTotalReturPembelian();

    return [
      'total_data'       => $query->count(),
      'total_selisih'    => $query->sum('selisih'),
      'rata_selisih'     => $query->count() > 0 ? round($query->sum('selisih') / $query->count(), 2) : 0,
      'total_retur_jual' => $returJual,
      'total_retur_beli' => $returBeli,
    ];
  }

  public function getChartData(): array {
    $data = $this->query()
      ->selectRaw('DATE(tanggal) as tgl, COUNT(*) as total')
      ->groupBy('tgl')->orderBy('tgl')->get();

    return [
      'labels' => $data->pluck('tgl')->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  // Retur Penjualan yang masuk stok utama
  public function getTotalReturPenjualan(): int {
    return DetailReturPenjualan::whereHas('retur', function ($q) {
      $q->where('status', 'Selesai')
        ->whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir]);
    })
      ->where('kondisi_barang', 'Bagus')
      ->where('tujuan', 'Stok Utama')
      ->sum('jumlah_retur');
  }

  // Retur Pembelian (barang keluar ke supplier)
  public function getTotalReturPembelian(): int {
    return ReturPembelian::whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->sum('jumlah');
  }

  public function cetakPdf() {
    $data      = $this->query()->orderByDesc('tanggal')->get();
    $returJual = $this->getTotalReturPenjualan();
    $returBeli = $this->getTotalReturPembelian();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.inventory', [
      'stoks'                    => $data,
      'tanggal_awal'             => \Carbon\Carbon::parse($this->tanggalAwal)->translatedFormat('d F Y'),
      'tanggal_akhir'            => \Carbon\Carbon::parse($this->tanggalAkhir)->translatedFormat('d F Y'),
      'total_masuk_keseluruhan'  => $data->sum('barang_masuk') + $returJual,
      'total_keluar_keseluruhan' => $data->sum('barang_keluar') + $returBeli,
      'total_stok_akhir'         => $data->sum('stok_fisik'),
      'dicetak_oleh'             => auth()->user()->nama ?? 'System',
      'tanggal_cetak'            => now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'portrait');

    return response()->stream(fn() => print($pdf->output()), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline']);
  }

  public function exportExcel() {
    return (new \App\Exports\InventoryExport($this->tanggalAwal, $this->tanggalAkhir))->download();
  }

  public function render() {
    return view('components.admin.laporan.inventory', [
      'ringkasan' => $this->getRingkasan(),
      'chartData' => $this->getChartData(),
      'tabelData' => $this->query()->orderByDesc('tanggal')->paginate(10),
    ]);
  }
}