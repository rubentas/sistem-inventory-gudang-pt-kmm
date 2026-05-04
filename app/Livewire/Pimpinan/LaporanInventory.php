<?php
namespace App\Livewire\Pimpinan;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Stok;
use Carbon\Carbon;
use Livewire\Component;

class LaporanInventory extends Component {
    public string $tanggalAwal  = '';
    public string $tanggalAkhir = '';

    public function mount() {
        $this->tanggalAwal  = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = Carbon::today()->format('Y-m-d');
    }

    public function resetFilters(): void {
        $this->tanggalAwal  = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->tanggalAkhir = Carbon::today()->format('Y-m-d');
    }

    public function render() {
        $stoks = Stok::with('barang')->get()->map(function ($stok) {
            $totalMasuk = BarangMasuk::where('id_barang', $stok->id_barang)
                ->whereBetween('tanggal_masuk', [$this->tanggalAwal, $this->tanggalAkhir])
                ->sum('jumlah');

            $totalKeluar = BarangKeluar::where('id_barang', $stok->id_barang)
                ->whereBetween('tanggal_keluar', [$this->tanggalAwal, $this->tanggalAkhir])
                ->sum('jumlah');

            $stok->total_masuk  = $totalMasuk;
            $stok->total_keluar = $totalKeluar;
            return $stok;
        });

        $totalStokAkhir         = $stoks->sum('jumlah_stok');
        $totalMasukKeseluruhan  = $stoks->sum('total_masuk');
        $totalKeluarKeseluruhan = $stoks->sum('total_keluar');

        return view('components.pimpinan.laporan-inventory', compact(
            'stoks',
            'totalStokAkhir',
            'totalMasukKeseluruhan',
            'totalKeluarKeseluruhan'
        ))->layout('layouts.pimpinan');
    }
}`
