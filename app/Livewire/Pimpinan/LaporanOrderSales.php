<?php

namespace App\Livewire\Pimpinan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OrderSales;

class LaporanOrderSales extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public string $tanggalAwal = '';
    public string $tanggalAkhir = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->tanggalAwal = '';
        $this->tanggalAkhir = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = OrderSales::with(['barang', 'wilayah', 'user'])
            ->when($this->search, function ($q) {
                $q->whereHas('barang', function ($b) {
                    $b->where('nama_barang', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                })->orWhereHas('wilayah', function ($w) {
                    $w->where('nama_wilayah', 'like', '%' . $this->search . '%');
                })->orWhereHas('user', function ($u) {
                    $u->where('nama', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->tanggalAwal, fn($q) => $q->whereDate('tanggal_order', '>=', $this->tanggalAwal))
            ->when($this->tanggalAkhir, fn($q) => $q->whereDate('tanggal_order', '<=', $this->tanggalAkhir))
            ->orderByDesc('tanggal_order');

        $orders = $query->paginate(10);

        $totalJumlah = $query->sum('jumlah');
        $totalPending = (clone $query)->where('status', 'pending')->sum('jumlah');
        $totalDiproses = (clone $query)->where('status', 'diproses')->sum('jumlah');
        $totalSelesai = (clone $query)->where('status', 'selesai')->sum('jumlah');

        return view('components.pimpinan.laporan-order-sales', compact('orders', 'totalJumlah', 'totalPending', 'totalDiproses', 'totalSelesai'))
            ->layout('layouts.pimpinan');
    }
}
