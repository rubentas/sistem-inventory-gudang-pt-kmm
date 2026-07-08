<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\ReturPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ReturBarang extends Component {
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
    $data = ReturPenjualan::whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->selectRaw('DATE(tanggal_retur) as tanggal, COUNT(*) as total')
      ->groupBy('tanggal')
      ->orderBy('tanggal')
      ->get();

    return [
      'labels' => $data->pluck('tanggal')->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  public function getDataPerAlasan(): array {
    $data = ReturPenjualan::whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->join('detail_retur_penjualans', 'retur_penjualans.id_retur', '=', 'detail_retur_penjualans.id_retur')
      ->selectRaw('detail_retur_penjualans.alasan, COUNT(*) as total')
      ->groupBy('detail_retur_penjualans.alasan')
      ->get();

    return [
      'labels' => $data->pluck('alasan')->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  public function getRingkasan(): array {
    $totalRetur    = ReturPenjualan::whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])->count();
    $totalMenunggu = ReturPenjualan::whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])->where('status', 'Menunggu')->count();
    $totalSelesai  = ReturPenjualan::whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])->where('status', 'Selesai')->count();

    return [
      'total_retur'    => $totalRetur,
      'total_menunggu' => $totalMenunggu,
      'total_selesai'  => $totalSelesai,
    ];
  }

  public function getTabelRingkas() {
    return ReturPenjualan::with(['detailRetur.barang', 'order'])
      ->whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->orderByDesc('tanggal_retur')
      ->limit(5)
      ->get();
  }

  public function cetakPdf() {
    set_time_limit(120);

    $data = ReturPenjualan::with(['detailRetur.barang', 'order'])
      ->whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->orderByDesc('tanggal_retur')
      ->get();

    $pdf = Pdf::loadView('laporan.retur-barang', [
      'data'          => $data,
      'dicetak_oleh'  => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
      'total_retur'   => $data->count(),
    ])->setPaper('a4', 'landscape');

    return response()->stream(
      fn() => print($pdf->output()),
      200,
      [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="laporan-retur-barang-' . $this->tanggalAwal . '-sd-' . $this->tanggalAkhir . '.pdf"',
      ]
    );
  }

  public function render() {
    return view('components.admin.laporan.retur-barang', [
      'perHari'      => $this->getDataPerHari(),
      'perAlasan'    => $this->getDataPerAlasan(),
      'ringkasan'    => $this->getRingkasan(),
      'tabelRingkas' => $this->getTabelRingkas(),
    ]);
  }
}