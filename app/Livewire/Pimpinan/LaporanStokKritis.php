<?php
namespace App\Livewire\Pimpinan;

use App\Models\Stok;
use Livewire\Component;

class LaporanStokKritis extends Component {
  public function render() {
    $stokKritis = Stok::with('barang')
      ->whereColumn('jumlah_stok', '<=', 'stok_minimum')
      ->orderByRaw('jumlah_stok / stok_minimum ASC')
      ->limit(5)
      ->get();

    $totalStokKritis = Stok::whereColumn('jumlah_stok', '<=', 'stok_minimum')->count();

    return view('components.pimpinan.laporan-stok-kritis', compact('stokKritis', 'totalStokKritis'))
      ->layout('layouts.pimpinan');
  }
}