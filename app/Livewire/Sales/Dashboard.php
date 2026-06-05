<?php
namespace App\Livewire\Sales;

use App\Models\OrderSales;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sales')]
class Dashboard extends Component {
  public function getStats(): array {
    $userId = Auth::id();
    return [
      'total'    => OrderSales::where('id_user', $userId)->count(),
      'pending'  => OrderSales::where('id_user', $userId)->where('status', 'pending')->count(),
      'diproses' => OrderSales::where('id_user', $userId)->where('status', 'diproses')->count(),
      'selesai'  => OrderSales::where('id_user', $userId)->where('status', 'selesai')->count(),
    ];
  }

  public function render() {
    $userId = Auth::id();

    $orderTerbaru = OrderSales::with(['barang', 'wilayah'])
      ->where('id_user', $userId)
      ->orderByDesc('tanggal_order')
      ->limit(5)
      ->get();

    return view('components.sales.dashboard', [
      'stats'        => $this->getStats(),
      'orderTerbaru' => $orderTerbaru,
    ]);
  }
}
