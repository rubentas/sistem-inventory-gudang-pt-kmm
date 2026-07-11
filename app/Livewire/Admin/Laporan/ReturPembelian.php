<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\ReturPembelian as ReturPembelianModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ReturPembelian extends Component {
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
    $data = ReturPembelianModel::whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->selectRaw('DATE(tanggal_retur) as tanggal, SUM(jumlah) as total')
      ->groupBy('tanggal')
      ->orderBy('tanggal')
      ->get();

    return [
      'labels' => $data->pluck('tanggal')->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  public function getDataPerSupplier(): array {
    $data = ReturPembelianModel::with('supplier')
      ->whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->selectRaw('id_supplier, SUM(jumlah) as total')
      ->groupBy('id_supplier')
      ->get();

    return [
      'labels' => $data->map(fn($d) => $d->supplier->nama_supplier ?? '-')->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  public function getRingkasan(): array {
    $totalRetur     = ReturPembelianModel::whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])->sum('jumlah');
    $totalSupplier  = ReturPembelianModel::whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])->distinct('id_supplier')->count('id_supplier');
    $totalTransaksi = ReturPembelianModel::whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])->count();

    return [
      'total_retur'     => $totalRetur,
      'total_supplier'  => $totalSupplier,
      'total_transaksi' => $totalTransaksi,
    ];
  }

  public function getTabelRingkas() {
    return ReturPembelianModel::with(['supplier', 'barang'])
      ->whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->orderByDesc('tanggal_retur')
      ->limit(5)
      ->get();
  }

  public function cetakPdf() {
    set_time_limit(120);

    $data = ReturPembelianModel::with(['supplier', 'barang', 'user'])
      ->whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->orderByDesc('tanggal_retur')
      ->get();

    $pdf = Pdf::loadView('laporan.retur-pembelian', [
      'data'          => $data,
      'dicetak_oleh'  => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
      'total_retur'   => $data->sum('jumlah'),
    ])->setPaper('a4', 'landscape');

    return response()->stream(
      fn() => print($pdf->output()),
      200,
      [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="laporan-retur-pembelian-' . $this->tanggalAwal . '-sd-' . $this->tanggalAkhir . '.pdf"',
      ]
    );
  }

  public function exportExcel() {
    return redirect()->route('admin.laporan.retur-pembelian.excel', [
      'tanggal_awal'  => $this->tanggalAwal,
      'tanggal_akhir' => $this->tanggalAkhir,
    ]);
  }

  public function render() {
    return view('components.admin.laporan.retur-pembelian', [
      'perHari'      => $this->getDataPerHari(),
      'perSupplier'  => $this->getDataPerSupplier(),
      'ringkasan'    => $this->getRingkasan(),
      'tabelRingkas' => $this->getTabelRingkas(),
    ]);
  }
}