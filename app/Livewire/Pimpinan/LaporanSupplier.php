<?php
namespace App\Livewire\Pimpinan;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanSupplier extends Component {
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
        $suppliers = Supplier::when($this->search, function ($q) {
            $q->where('kode_supplier', 'like', '%' . $this->search . '%')
                ->orWhere('nama_supplier', 'like', '%' . $this->search . '%');
        })
            ->orderBy('kode_supplier')
            ->paginate(10);

        $totalSupplier = Supplier::count();

        return view('components.pimpinan.laporan-supplier', compact('suppliers', 'totalSupplier'))
            ->layout('layouts.pimpinan');
    }
}
