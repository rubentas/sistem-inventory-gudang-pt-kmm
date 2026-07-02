<?php
namespace App\Livewire\Pimpinan;

use App\Models\OrderSales;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanOrderSales extends Component {
  use WithPagination;

  public string $search       = '';
  public string $filterStatus = '';
  public string $tanggalAwal  = '';
  public string $tanggalAkhir = '';

  public function mount(): void {
    $this->tanggalAwal  = now()->startOfMonth()->format('Y-m-d');
    $this->tanggalAkhir = now()->endOfMonth()->format('Y-m-d');
  }

  public function updatingSearch(): void {
    $this->resetPage();
  }

  public function resetFilters(): void {
    $this->search       = '';
    $this->filterStatus = '';
    $this->tanggalAwal  = now()->startOfMonth()->format('Y-m-d');
    $this->tanggalAkhir = now()->endOfMonth()->format('Y-m-d');
    $this->resetPage();
  }

  public function render() {
    $query = OrderSales::with(['barang', 'wilayah', 'sales'])
      ->when($this->search, function ($q) {
        $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%'))
          ->orWhereHas('wilayah', fn($w) => $w->where('nama_wilayah', 'like', '%' . $this->search . '%'))
          ->orWhereHas('sales', fn($s) => $s->where('nama_sales', 'like', '%' . $this->search . '%'));
      })
      ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
      ->when($this->tanggalAwal, fn($q) => $q->whereDate('tanggal_order', '>=', $this->tanggalAwal))
      ->when($this->tanggalAkhir, fn($q) => $q->whereDate('tanggal_order', '<=', $this->tanggalAkhir))
      ->orderByDesc('tanggal_order');

    // Clone sebelum paginate
    $totalJumlah   = (clone $query)->sum('jumlah');
    $totalPending  = (clone $query)->where('status', 'pending')->count();
    $totalDiproses = (clone $query)->where('status', 'diproses')->count();
    $totalSelesai  = (clone $query)->where('status', 'selesai')->count();

    $orders = $query->paginate(10);

    return view('components.pimpinan.laporan-order-sales', compact('orders', 'totalJumlah', 'totalPending', 'totalDiproses', 'totalSelesai'))
      ->layout('layouts.pimpinan');
  }
}