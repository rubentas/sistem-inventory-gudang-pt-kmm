<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\BarangMasuk;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class BarangExpired extends Component {
  use WithPagination;

  public string $filterStatus = '';
  public string $search       = '';

  public function getRingkasan(): array {
    $totalExpired       = BarangMasuk::whereNotNull('tanggal_expired')->where('status_expired', 'expired')->count();
    $totalHampirExpired = BarangMasuk::whereNotNull('tanggal_expired')->where('status_expired', 'hampir_expired')->count();
    $totalAman          = BarangMasuk::whereNotNull('tanggal_expired')->where('status_expired', 'aman')->count();
    $totalTercatat      = $totalExpired + $totalHampirExpired + $totalAman;

    return compact('totalExpired', 'totalHampirExpired', 'totalAman', 'totalTercatat');
  }

  public function getExpiredChart(): array {
    return [
      'labels' => ['Expired', 'Hampir Expired', 'Aman'],
      'values' => [
        BarangMasuk::whereNotNull('tanggal_expired')->where('status_expired', 'expired')->count(),
        BarangMasuk::whereNotNull('tanggal_expired')->where('status_expired', 'hampir_expired')->count(),
        BarangMasuk::whereNotNull('tanggal_expired')->where('status_expired', 'aman')->count(),
      ],
    ];
  }

  public function cetakPdf() {
    $data = BarangMasuk::with(['barang', 'supplier'])
      ->whereNotNull('tanggal_expired')
      ->when($this->filterStatus, fn($q) => $q->where('status_expired', $this->filterStatus))
      ->orderBy('tanggal_expired')->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.barang-expired', [
      'data'          => $data, 'dicetak_oleh' => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'portrait');
    return response()->stream(fn() => print($pdf->output()), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline']);
  }

  public function exportExcel() {
    return (new \App\Exports\BarangExpiredExport($this->filterStatus))->download();
  }

  public function render() {
    return view('components.admin.laporan.barang-expired', [
      'ringkasan'    => $this->getRingkasan(),
      'expiredChart' => $this->getExpiredChart(),
      'tabelData'    => BarangMasuk::with(['barang', 'supplier'])->whereNotNull('tanggal_expired')
        ->when($this->filterStatus, fn($q) => $q->where('status_expired', $this->filterStatus))
        ->when($this->search, fn($q) => $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%')))
        ->orderBy('tanggal_expired')->paginate(10),
    ]);
  }
}