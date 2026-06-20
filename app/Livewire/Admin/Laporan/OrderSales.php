<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\OrderSales as OrderSalesModel;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class OrderSales extends Component {
  use WithPagination;

  public string $filterType   = 'month';
  public string $filterStatus = '';
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
      $this->tanggalAwal = $this->tanggalAkhir = now()->format('Y-m-d');
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

  protected function query() {
    return OrderSalesModel::with(['barang', 'wilayah', 'sales'])
      ->whereBetween('tanggal_order', [$this->tanggalAwal, $this->tanggalAkhir])
      ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus));
  }

  public function getRingkasan(): array {
    $total    = (clone $this->query())->count();
    $pending  = (clone $this->query())->where('status', 'pending')->count();
    $diproses = (clone $this->query())->where('status', 'diproses')->count();
    $selesai  = (clone $this->query())->where('status', 'selesai')->count();

    return compact('total', 'pending', 'diproses', 'selesai');
  }

  public function getStatusChart(): array {
    return [
      'labels' => ['Pending', 'Diproses', 'Selesai'],
      'values' => [
        (clone $this->query())->where('status', 'pending')->count(),
        (clone $this->query())->where('status', 'diproses')->count(),
        (clone $this->query())->where('status', 'selesai')->count(),
      ],
    ];
  }

  public function getPerBulan(): array {
    $tahun = now()->year;
    $data  = OrderSalesModel::whereYear('tanggal_order', $tahun)
      ->selectRaw('MONTH(tanggal_order) as bulan, COUNT(*) as total')
      ->groupBy('bulan')->orderBy('bulan')->get();

    $values = array_fill(0, 12, 0);
    foreach ($data as $d) {
      $values[$d->bulan - 1] = $d->total;
    }

    return [
      'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
      'values' => $values,
    ];
  }

  public function cetakPdf() {
    $data = $this->query()->orderByDesc('tanggal_order')->get();
    $pdf  = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.order-sales', [
      'data'           => $data, 'tanggal_awal'                                        => Carbon::parse($this->tanggalAwal)->translatedFormat('d F Y'),
      'tanggal_akhir'  => Carbon::parse($this->tanggalAkhir)->translatedFormat('d F Y'),
      'total_jumlah'   => $data->sum('jumlah'), 'total_pending'                        => $data->where('status', 'pending')->count(),
      'total_diproses' => $data->where('status', 'diproses')->count(), 'total_selesai' => $data->where('status', 'selesai')->count(),
      'dicetak_oleh'   => auth()->user()->nama ?? 'System', 'tanggal_cetak'            => now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'landscape');
    return response()->stream(fn() => print($pdf->output()), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline']);
  }

  public function exportExcel() {
    return (new \App\Exports\OrderSalesExport($this->tanggalAwal, $this->tanggalAkhir, $this->filterStatus))->download();
  }

  public function render() {
    return view('components.admin.laporan.order-sales', [
      'ringkasan'   => $this->getRingkasan(),
      'statusChart' => $this->getStatusChart(),
      'perBulan'    => $this->getPerBulan(),
      'tabelData'   => $this->query()->orderByDesc('tanggal_order')->paginate(10),
    ]);
  }
}
