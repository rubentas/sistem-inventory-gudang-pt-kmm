<?php
namespace App\Livewire\Pimpinan;

use App\Models\OrderSales;
use Livewire\Component;

class LaporanOmzet extends Component {
  public $bulan = '';
  public $tahun = '';

  public function mount() {
    $this->bulan = date('m');
    $this->tahun = date('Y');
  }

  public function getOmzet() {
    return OrderSales::whereMonth('tanggal_order', $this->bulan)
      ->whereYear('tanggal_order', $this->tahun)
      ->sum('subtotal');
  }

  public function getTotalOrder() {
    return OrderSales::whereMonth('tanggal_order', $this->bulan)
      ->whereYear('tanggal_order', $this->tahun)
      ->count();
  }

  public function getTotalTerjual() {
    return OrderSales::whereMonth('tanggal_order', $this->bulan)
      ->whereYear('tanggal_order', $this->tahun)
      ->sum('jumlah');
  }

  public function render() {
    $omzet        = $this->getOmzet();
    $totalOrder   = $this->getTotalOrder();
    $totalTerjual = $this->getTotalTerjual();

    return view('components.pimpinan.laporan-omzet', compact('omzet', 'totalOrder', 'totalTerjual'))
      ->layout('layouts.pimpinan');
  }
}