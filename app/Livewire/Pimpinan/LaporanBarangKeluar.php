<?php
namespace App\Livewire\Pimpinan;

use App\Models\BarangKeluar;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanBarangKeluar extends Component {
    use WithPagination;

    public string $search        = '';
    public string $filterWilayah = '';
    public string $tanggalAwal   = '';
    public string $tanggalAkhir  = '';

    public function updatingSearch(): void {
        $this->resetPage();
    }

    public function resetFilters(): void {
        $this->search        = '';
        $this->filterWilayah = '';
        $this->tanggalAwal   = '';
        $this->tanggalAkhir  = '';
        $this->resetPage();
    }

    public function render() {
        $query = BarangKeluar::with(['barang', 'wilayah', 'user'])
            ->when($this->search, function ($q) {
                $q->whereHas('barang', function ($b) {
                    $b->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                })->orWhereHas('wilayah', function ($w) {
                    $w->where('nama_wilayah', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterWilayah, function ($q) {
                $q->whereHas('wilayah', function ($w) {
                    $w->where('id_wilayah', $this->filterWilayah);
                });
            })
            ->when($this->tanggalAwal, fn($q) => $q->whereDate('tanggal_keluar', '>=', $this->tanggalAwal))
            ->when($this->tanggalAkhir, fn($q) => $q->whereDate('tanggal_keluar', '<=', $this->tanggalAkhir))
            ->orderByDesc('tanggal_keluar');

        $barangKeluar = $query->paginate(10);

        $wilayahList = \App\Models\Wilayah::orderBy('nama_wilayah')->get();

        $totalJumlah = $query->sum('jumlah');

        return view('components.pimpinan.laporan-barang-keluar', compact('barangKeluar', 'wilayahList', 'totalJumlah'))
            ->layout('layouts.pimpinan');
    }
}
