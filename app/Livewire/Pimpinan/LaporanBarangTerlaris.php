<?php
namespace App\Livewire\Pimpinan;

use App\Models\BarangKeluar;
use Livewire\Component;

class LaporanBarangTerlaris extends Component {
  public $bulan = '';
  public $tahun = '';

  public function mount() {
    $this->bulan = date('m');
    $this->tahun = date('Y');
  }

  public function render() {
    $query = BarangKeluar::with('barang')
      ->selectRaw('id_barang, sum(jumlah) as total_terjual')
      ->whereMonth('tanggal_keluar', $this->bulan)
      ->whereYear('tanggal_keluar', $this->tahun)
      ->groupBy('id_barang')
      ->orderByDesc('total_terjual')
      ->limit(5);

    $terlaris = $query->get();

    return view('components.pimpinan.laporan-barang-terlaris', compact('terlaris'))
      ->layout('layouts.pimpinan');
  }
}