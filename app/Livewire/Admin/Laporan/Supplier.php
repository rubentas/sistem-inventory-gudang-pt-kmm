<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\BarangMasuk;
use App\Models\Supplier as SupplierModel;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Supplier extends Component {
  public function getRingkasan(): array {
    return [
      'total_supplier' => SupplierModel::count(),
      'total_barang'   => BarangMasuk::count(),
    ];
  }

  public function getPerSupplier(): array {
    $data = BarangMasuk::with('supplier')
      ->selectRaw('id_supplier, SUM(jumlah) as total')
      ->groupBy('id_supplier')
      ->get();

    return [
      'labels' => $data->map(fn($d) => $d->supplier->nama_supplier ?? 'Tanpa Supplier')->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  public function cetakPdf() {
    $data = SupplierModel::withCount(['barangMasuk as total_masuk' => fn($q) => $q->selectRaw('SUM(jumlah)')])
      ->orderBy('nama_supplier')->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.supplier', [
      'data'         => $data, 'total_supplier'                           => $data->count(),
      'dicetak_oleh' => auth()->user()->nama ?? 'System', 'tanggal_cetak' => now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'portrait');
    return response()->stream(fn() => print($pdf->output()), 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline']);
  }

  public function exportExcel() {
    return (new \App\Exports\SupplierExport)->download();
  }

  public function render() {
    return view('components.admin.laporan.supplier', [
      'ringkasan'   => $this->getRingkasan(),
      'perSupplier' => $this->getPerSupplier(),
      'tabelData'   => SupplierModel::withCount(['barangMasuk as total_masuk' => fn($q) => $q->selectRaw('SUM(jumlah)')])->orderBy('nama_supplier')->get(),
    ]);
  }
}
