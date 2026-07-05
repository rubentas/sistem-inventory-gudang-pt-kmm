<?php
namespace App\Livewire\KepalaGudang;

use App\Models\Stok;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;

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

  public function exportPdf() {
    $stoks = Stok::with('barang')
      ->when($this->search, function ($q) {
        $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_barang', 'like', '%' . $this->search . '%'));
      })
      ->when($this->filterStatus === 'habis', fn($q) => $q->where('jumlah_stok', '<=', 0))
      ->when($this->filterStatus === 'menipis', fn($q) => $q->whereColumn('jumlah_stok', '>', 0)
                                                              ->whereColumn('jumlah_stok', '<=', 'stok_minimum'))
      ->when($this->filterStatus === 'aman', fn($q) => $q->whereColumn('jumlah_stok', '>', 'stok_minimum'))
      ->orderBy('id_barang')
      ->get();

    $filterLabel = match ($this->filterStatus) {
      'habis'   => 'Stok Habis',
      'menipis' => 'Stok Menipis',
      'aman'    => 'Stok Aman',
      default   => 'Semua Stok'
    };

    $pdf = Pdf::loadView('laporan.stok-barang', [
      'data'          => $stoks,
      'total_stok'    => $stoks->sum('jumlah_stok'),
      'stok_menipis'  => $stoks->where('status', 'Menipis')->count(),
      'dicetak_oleh'  => auth()->user()->nama,
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
      'filter_label'  => $filterLabel,
    ])->setPaper('a4', 'landscape');

    return response()->streamDownload(
      fn() => print($pdf->output()),
      'laporan-stok-' . ($this->filterStatus ?: 'semua') . '-' . now()->format('Ymd') . '.pdf'
    );
  }

  public function render() {
    $stoks = Stok::with('barang')
      ->when($this->search, function ($q) {
        $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_barang', 'like', '%' . $this->search . '%'));
      })
      ->when($this->filterStatus === 'habis', fn($q) => $q->where('jumlah_stok', '<=', 0))
      ->when($this->filterStatus === 'menipis', fn($q) => $q->whereColumn('jumlah_stok', '>', 0)
          ->whereColumn('jumlah_stok', '<=', 'stok_minimum'))
      ->when($this->filterStatus === 'aman', fn($q) => $q->whereColumn('jumlah_stok', '>', 'stok_minimum'))
      ->orderBy('id_barang')
      ->paginate(15);

    $totalStok    = Stok::sum('jumlah_stok');
    $totalMenipis = Stok::whereColumn('jumlah_stok', '>', 0)
                       ->whereColumn('jumlah_stok', '<=', 'stok_minimum')
                       ->count();
    $totalAman    = Stok::whereColumn('jumlah_stok', '>', 'stok_minimum')->count();

    return view('components.kepala-gudang.stok-barang', compact('stoks', 'totalStok', 'totalMenipis', 'totalAman'))
      ->layout('layouts.kepala-gudang');
  }
}