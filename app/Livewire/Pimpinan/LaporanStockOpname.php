<?php
namespace App\Livewire\Pimpinan;

use App\Models\StockOpname;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanStockOpname extends Component {
  use WithPagination;

  public string $search       = '';
  public string $tanggalAwal  = '';
  public string $tanggalAkhir = '';

  public function mount(): void {
    $this->tanggalAwal  = now()->subMonths(3)->format('Y-m-d');
    $this->tanggalAkhir = now()->format('Y-m-d');
  }

  public function updatingSearch(): void {
    $this->resetPage();
  }

  public function resetFilters(): void {
    $this->search       = '';
    $this->tanggalAwal  = now()->subMonths(3)->format('Y-m-d');
    $this->tanggalAkhir = now()->format('Y-m-d');
    $this->resetPage();
  }

  public function render() {
    $query = StockOpname::with(['barang', 'user'])
      ->when($this->search, function ($q) {
        $q->whereHas('barang', function ($b) {
          $b->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
        });
      })
      ->when($this->tanggalAwal, fn($q) => $q->whereDate('tanggal_opname', '>=', $this->tanggalAwal))
      ->when($this->tanggalAkhir, fn($q) => $q->whereDate('tanggal_opname', '<=', $this->tanggalAkhir))
      ->orderByDesc('tanggal_opname');

    // Clone buat total
    $totalQuery   = clone $query;
    $totalSelisih = $totalQuery->sum('selisih');
    $totalData    = $totalQuery->count();

    // Paginate
    $stockOpnames = $query->paginate(10);

    return view('components.pimpinan.laporan-stock-opname', compact('stockOpnames', 'totalSelisih', 'totalData'))
      ->layout('layouts.pimpinan');
  }
}
