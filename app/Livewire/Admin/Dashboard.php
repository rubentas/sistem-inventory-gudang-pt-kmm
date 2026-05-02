<?php
namespace App\Livewire\Admin;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\OrderSales;
use App\Models\Supplier;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component {
    public function render() {
        $totalMasukHariIni  = BarangMasuk::whereDate('tanggal_masuk', Carbon::today())->sum('jumlah');
        $totalKeluarHariIni = BarangKeluar::whereDate('tanggal_keluar', Carbon::today())->sum('jumlah');
        $totalOrderHariIni  = OrderSales::whereDate('tanggal_order', Carbon::today())->count();
        $totalSupplier      = Supplier::count();

        $orderTerbaru = OrderSales::with(['barang', 'wilayah', 'user'])
            ->orderByDesc('tanggal_order')
            ->limit(5)
            ->get();

        $barangMasukTerbaru = BarangMasuk::with(['barang', 'supplier'])
            ->orderByDesc('tanggal_masuk')
            ->limit(5)
            ->get();

        return view('components.admin.dashboard', compact(
            'totalMasukHariIni',
            'totalKeluarHariIni',
            'totalOrderHariIni',
            'totalSupplier',
            'orderTerbaru',
            'barangMasukTerbaru'
        ))->layout('layouts.admin');
    }
}