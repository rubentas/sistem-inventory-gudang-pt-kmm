<?php
namespace App\Livewire\Pimpinan;

use App\Models\Wilayah;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanWilayah extends Component {
  use WithPagination;

  public string $search = '';

  public function updatingSearch(): void {
    $this->resetPage();
  }

  public function resetFilters(): void {
    $this->search = '';
    $this->resetPage();
  }

  public function render() {
    $wilayahs = Wilayah::with('sales')
      ->when($this->search, function ($q) {
        $q->where('nama_wilayah', 'like', '%' . $this->search . '%');
      })
      ->orderBy('nama_wilayah')
      ->paginate(10);

    $totalWilayah = Wilayah::count();
    $totalToko    = Wilayah::count();

    return view('components.pimpinan.laporan-wilayah', compact('wilayahs', 'totalWilayah', 'totalToko'))
      ->layout('layouts.pimpinan');
  }
}
