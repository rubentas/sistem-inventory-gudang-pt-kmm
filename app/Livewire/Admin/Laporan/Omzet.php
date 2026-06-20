<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\OrderSales;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Omzet extends Component {
  public string $tahun;

  public function mount(): void {
    $this->tahun = now()->year;
  }

  public function getOmzetPerBulan(): array {
    $data = OrderSales::whereYear('tanggal_order', $this->tahun)
      ->selectRaw('MONTH(tanggal_order) as bulan, SUM(subtotal) as total')
      ->groupBy('bulan')
      ->orderBy('bulan')
      ->get();

    $bulan  = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    $values = array_fill(0, 12, 0);
    foreach ($data as $d) {
      $values[$d->bulan - 1] = (int) $d->total;
    }

    return ['labels' => $bulan, 'values' => $values];
  }

  public function getRingkasan(): array {
    $totalOmzet     = OrderSales::whereYear('tanggal_order', $this->tahun)->sum('subtotal');
    $totalOrder     = OrderSales::whereYear('tanggal_order', $this->tahun)->count();
    $rataOmzet      = $totalOrder > 0 ? round($totalOmzet / $totalOrder) : 0;
    $bulanTertinggi = OrderSales::whereYear('tanggal_order', $this->tahun)
      ->selectRaw('MONTH(tanggal_order) as bulan, SUM(subtotal) as total')
      ->groupBy('bulan')->orderByDesc('total')->first();

    return [
      'total_omzet'     => $totalOmzet,
      'total_order'     => $totalOrder,
      'rata_omzet'      => $rataOmzet,
      'bulan_tertinggi' => $bulanTertinggi ? Carbon::createFromDate($this->tahun, $bulanTertinggi->bulan, 1)->translatedFormat('F') : '-',
      'nilai_tertinggi' => $bulanTertinggi?->total ?? 0,
    ];
  }

  public function cetakPdf() {
    $ringkasan = $this->getRingkasan();
    $pdf       = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.omzet', [
      'bulan'         => null,
      'tahun'         => $this->tahun,
      'omzet'         => $ringkasan['total_omzet'],
      'totalOrder'    => $ringkasan['total_order'],
      'totalTerjual'  => OrderSales::whereYear('tanggal_order', $this->tahun)->sum('jumlah'),
      'periode'       => $this->tahun,
      'dicetak_oleh'  => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'portrait');
    return response()->stream(fn() => print($pdf->output()), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline']);
  }

  public function exportExcel() {
    return (new \App\Exports\OmzetExport($this->tahun))->download();
  }

  public function render() {
    return view('components.admin.laporan.omzet', [
      'omzetPerBulan' => $this->getOmzetPerBulan(),
      'ringkasan'     => $this->getRingkasan(),
    ]);
  }
}
