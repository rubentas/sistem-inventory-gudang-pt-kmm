<?php
namespace App\Livewire\Pimpinan;

use App\Models\Stok;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanStok extends Component {
    use WithPagination;

    public string $search       = '';
    public string $filterStatus = '';

    public function updatingSearch(): void {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void {
        $this->resetPage();
    }

    public function resetFilters(): void {
        $this->search       = '';
        $this->filterStatus = '';
        $this->resetPage();
    }

    public function render() {
        $stoks = Stok::with('barang')
            ->when($this->search, function ($q) {
                $q->whereHas('barang', function ($b) {
                    $b->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus === 'menipis', function ($q) {
                $q->whereColumn('jumlah_stok', '<=', 'stok_minimum');
            })
            ->when($this->filterStatus === 'aman', function ($q) {
                $q->whereColumn('jumlah_stok', '>', 'stok_minimum');
            })
            ->orderBy('id_barang')
            ->paginate(15);

        $totalStok    = Stok::sum('jumlah_stok');
        $totalMenipis = Stok::whereColumn('jumlah_stok', '<=', 'stok_minimum')->count();
        $totalAman    = Stok::whereColumn('jumlah_stok', '>', 'stok_minimum')->count();

        return view('components.pimpinan.laporan-stok', compact('stoks', 'totalStok', 'totalMenipis', 'totalAman'))
            ->layout('layouts.pimpinan');
    }
}
