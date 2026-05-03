<?php
namespace App\Livewire\Pimpinan;

use App\Models\BarangMasuk;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanBarangMasuk extends Component {
    use WithPagination;

    public string $search       = '';
    public string $filterSumber = '';
    public string $tanggalAwal  = '';
    public string $tanggalAkhir = '';

    public function updatingSearch(): void {
        $this->resetPage();
    }

    public function resetFilters(): void {
        $this->search       = '';
        $this->filterSumber = '';
        $this->tanggalAwal  = '';
        $this->tanggalAkhir = '';
        $this->resetPage();
    }

    public function render() {
        $query = BarangMasuk::with(['barang', 'supplier', 'user'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('barang', function ($b) {
                        $b->where('nama_barang', 'like', '%' . $this->search . '%')
                            ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                    })->orWhere('no_nota', 'like', '%' . $this->search . '%')
                        ->orWhere('no_surat_jalan', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterSumber, fn($q) => $q->where('sumber', $this->filterSumber))
            ->when($this->tanggalAwal, fn($q) => $q->whereDate('tanggal_masuk', '>=', $this->tanggalAwal))
            ->when($this->tanggalAkhir, fn($q) => $q->whereDate('tanggal_masuk', '<=', $this->tanggalAkhir))
            ->orderByDesc('tanggal_masuk');

        $barangMasuk = $query->paginate(10);

        $sumberList = BarangMasuk::select('sumber')->distinct()->pluck('sumber');

        $totalJumlah = $query->sum('jumlah');

        return view('components.pimpinan.laporan-barang-masuk', compact('barangMasuk', 'sumberList', 'totalJumlah'))
            ->layout('layouts.pimpinan');
    }
}
