<?php
namespace App\Livewire\Pimpinan;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component {
    public function render() {
        $totalMasukBulanIni = BarangMasuk::whereMonth('tanggal_masuk', Carbon::now()->month)
            ->whereYear('tanggal_masuk', Carbon::now()->year)
            ->sum('jumlah');

        $totalKeluarBulanIni = BarangKeluar::whereMonth('tanggal_keluar', Carbon::now()->month)
            ->whereYear('tanggal_keluar', Carbon::now()->year)
            ->sum('jumlah');

        $totalJenisBarang = Barang::count();
        $totalSupplier    = Supplier::count();

        $barangMasukTerbaru = BarangMasuk::with(['barang', 'supplier'])
            ->orderByDesc('tanggal_masuk')
            ->limit(5)
            ->get();

        $orderTerbaru = \App\Models\OrderSales::with(['barang', 'wilayah'])
            ->orderByDesc('tanggal_order')
            ->limit(5)
            ->get();

        return view('components.pimpinan.dashboard', compact(
            'totalMasukBulanIni',
            'totalKeluarBulanIni',
            'totalJenisBarang',
            'totalSupplier',
            'barangMasukTerbaru',
            'orderTerbaru'
        ))->layout('layouts.pimpinan');
    }
}
