<?php
namespace App\Livewire\Sales;

use App\Models\OrderSales;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component {
    public function render() {
        $userId = Auth::user()->id_user;

        $totalOrderHariIni = OrderSales::where('id_user', $userId)
            ->whereDate('tanggal_order', Carbon::today())
            ->count();

        $totalPending  = OrderSales::where('id_user', $userId)->where('status', 'pending')->count();
        $totalDiproses = OrderSales::where('id_user', $userId)->where('status', 'diproses')->count();
        $totalSelesai  = OrderSales::where('id_user', $userId)->where('status', 'selesai')->count();

        $orderTerbaru = OrderSales::with(['barang', 'wilayah'])
            ->where('id_user', $userId)
            ->orderByDesc('tanggal_order')
            ->limit(5)
            ->get();

        return view('components.sales.dashboard', compact(
            'totalOrderHariIni',
            'totalPending',
            'totalDiproses',
            'totalSelesai',
            'orderTerbaru'
        ))->layout('layouts.sales');
    }
}
