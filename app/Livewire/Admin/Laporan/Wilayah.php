<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\BarangKeluar;
use App\Models\Wilayah as WilayahModel;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Wilayah extends Component {
  public function getRingkasan(): array {
    return [
      'total_wilayah' => WilayahModel::count(),
      'total_keluar'  => BarangKeluar::sum('jumlah'),
    ];
  }

  public function getPerWilayah(): array {
    $data = BarangKeluar::with('wilayah')
      ->selectRaw('id_wilayah, SUM(jumlah) as total')
      ->groupBy('id_wilayah')
      ->get();

    return [
      'labels' => $data->map(fn($d) => $d->wilayah->nama_wilayah ?? 'Tanpa Wilayah')->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  public function cetakPdf() {
    $data = WilayahModel::withSum('barangKeluar', 'jumlah')
      ->orderBy('nama_wilayah')
      ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.wilayah', [
      'data'          => $data,
      'dicetak_oleh'  => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'portrait');

    return response()->stream(
      fn() => print($pdf->output()),
      200,
      ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline']
    );
  }

  public function exportExcel() {
    return (new \App\Exports\WilayahExport)->download();
  }

  public function render() {
    return view('components.admin.laporan.wilayah', [
      'ringkasan'  => $this->getRingkasan(),
      'perWilayah' => $this->getPerWilayah(),
      'tabelData'  => WilayahModel::withSum('barangKeluar', 'jumlah')
        ->orderBy('nama_wilayah')
        ->get(),
    ]);
  }
}