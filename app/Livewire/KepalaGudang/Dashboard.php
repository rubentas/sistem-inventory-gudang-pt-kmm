<?php
namespace App\Livewire\KepalaGudang;

use App\Models\BarangMasuk;
use App\Models\StockOpname;
use App\Models\Stok;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component {
  public function render() {
    $totalMasukHariIni = BarangMasuk::whereDate('tanggal_masuk', Carbon::today())->sum('jumlah');
    $totalJenisBarang  = Stok::count();
    $stokMenipis       = Stok::whereColumn('jumlah_stok', '<=', 'stok_minimum')->count();
    $opnameTerakhir    = StockOpname::latest('tanggal_opname')->first();

    $barangMasukTerbaru = BarangMasuk::with(['barang', 'supplier'])
      ->orderByDesc('tanggal_masuk')
      ->limit(5)
      ->get();

    $stokMenipisList = Stok::with('barang')
      ->whereColumn('jumlah_stok', '<=', 'stok_minimum')
      ->limit(5)
      ->get();

    return view('components.kepala-gudang.dashboard', compact(
      'totalMasukHariIni',
      'totalJenisBarang',
      'stokMenipis',
      'opnameTerakhir',
      'barangMasukTerbaru',
      'stokMenipisList'
    ))->layout('layouts.kepala-gudang');
  }
}