<?php
namespace App\Livewire\Pimpinan;

use App\Models\OrderSales;
use Livewire\Component;

class LaporanBarangTerlaris extends Component {
  public $bulan = '';
  public $tahun = '';

  public function mount() {
    $this->bulan = date('m');
    $this->tahun = date('Y');
  }

  public function render() {
    $terlaris = OrderSales::with('barang')
      ->selectRaw('id_barang, sum(jumlah) as total_terjual, sum(subtotal) as total_omzet')
      ->whereMonth('tanggal_order', $this->bulan)
      ->whereYear('tanggal_order', $this->tahun)
      ->groupBy('id_barang')
      ->orderByDesc('total_terjual')
      ->get();

    return view('components.pimpinan.laporan-barang-terlaris', compact('terlaris'))
      ->layout('layouts.pimpinan');
  }
}