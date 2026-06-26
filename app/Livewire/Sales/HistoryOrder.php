<?php
namespace App\Livewire\Sales;

use App\Models\OrderSales;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.sales')]
class HistoryOrder extends Component {
  use WithPagination;

  public string $search       = '';
  public string $filterStatus = '';

  public function updatedSearch(): void {$this->resetPage();}
  public function updatedFilterStatus(): void {$this->resetPage();}

  public function resetFilters(): void {
    $this->search       = '';
    $this->filterStatus = '';
    $this->resetPage();
  }

  public function render() {
    $orders = OrderSales::with(['barang', 'wilayah'])
      ->where('id_user', Auth::id())
      ->when($this->search, fn($q) => $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%')))
      ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
      ->orderByDesc('tanggal_order')
      ->paginate(10);

    return view('components.sales.history-order', [
      'orders' => $orders,
    ]);
  }
}
