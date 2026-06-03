<?php
namespace App\Livewire\Admin;

use App\Models\Stok;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class StokBarang extends Component {
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

  public function getStats(): array {
    return [
      'total'   => Stok::sum('jumlah_stok'),
      'menipis' => Stok::whereColumn('jumlah_stok', '<=', 'stok_minimum')->count(),
      'aman'    => Stok::whereColumn('jumlah_stok', '>', 'stok_minimum')->count(),
    ];
  }

  public function render() {
    $stoks = Stok::with('barang')
      ->when($this->search, function ($q) {
        $q->whereHas('barang', function ($b) {
          $b->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
        });
      })
      ->when($this->filterStatus === 'menipis', fn($q) => $q->whereColumn('jumlah_stok', '<=', 'stok_minimum'))
      ->when($this->filterStatus === 'aman', fn($q) => $q->whereColumn('jumlah_stok', '>', 'stok_minimum'))
      ->orderBy('id_barang')
      ->paginate(15);

    return view('components.admin.stok-barang', [
      'stoks'        => $stoks,
      'stats'        => $this->getStats(),
      'filterStatus' => $this->filterStatus,
    ]);
  }
}