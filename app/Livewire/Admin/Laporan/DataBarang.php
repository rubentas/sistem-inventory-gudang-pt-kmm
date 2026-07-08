<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\Barang;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class DataBarang extends Component {
  use WithPagination;

  public string $search         = '';
  public string $filterKategori = '';
  public string $filterStok     = '';

  public function updated($property): void {
    if (in_array($property, ['search', 'filterKategori', 'filterStok'])) {
      $this->resetPage();
    }
  }

  public function resetFilters(): void {
    $this->search         = '';
    $this->filterKategori = '';
    $this->filterStok     = '';
    $this->resetPage();
  }

  public function getStats(): array {
    return [
      'total'    => Barang::count(),
      'kategori' => Barang::distinct('kategori')->count('kategori'),
      'habis'    => Barang::whereHas('stok', fn($q) => $q->where('jumlah_stok', '<=', 0))->count(),
      'menipis'  => Barang::whereHas('stok', fn($q) => $q
          ->where('jumlah_stok', '>', 0)
          ->whereColumn('jumlah_stok', '<=', 'stok_minimum'))->count(),
      'aman'     => Barang::whereHas('stok', fn($q) => $q->whereColumn('jumlah_stok', '>', 'stok_minimum'))->count(),
    ];
  }

  public function getKategoriList() {
    return Barang::distinct()->pluck('kategori')->filter()->values();
  }

  public function getChartKategori(): array {
    $data = Barang::selectRaw('kategori, count(*) as total')
      ->groupBy('kategori')
      ->orderByDesc('total')
      ->get();

    return [
      'labels' => $data->pluck('kategori')->toArray(),
      'values' => $data->pluck('total')->toArray(),
    ];
  }

  public function exportExcel() {
    return redirect()->route('admin.laporan.data-barang.excel', [
      'search'         => $this->search,
      'filterKategori' => $this->filterKategori,
      'filterStok'     => $this->filterStok,
    ]);
  }
  public function render() {
    $barangs = Barang::with('stok')
      ->when($this->search, fn($q) => $q->where(function ($q) {
        $q->where('nama_barang', 'like', '%' . $this->search . '%')
          ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
      }))
      ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
      ->when($this->filterStok === 'habis', fn($q) => $q->whereHas('stok', fn($s) => $s->where('jumlah_stok', '<=', 0)))
      ->when($this->filterStok === 'menipis', fn($q) => $q->whereHas('stok', fn($s) => $s
          ->where('jumlah_stok', '>', 0)
          ->whereColumn('jumlah_stok', '<=', 'stok_minimum')))
      ->when($this->filterStok === 'aman', fn($q) => $q->whereHas('stok', fn($s) => $s->whereColumn('jumlah_stok', '>', 'stok_minimum')))
      ->orderBy('kode_barang')
      ->paginate(15);

    return view('components.admin.laporan.data-barang', [
      'barangs'        => $barangs,
      'stats'          => $this->getStats(),
      'chartKategori'  => $this->getChartKategori(),
      'kategoriList'   => $this->getKategoriList(),
      'filterKategori' => $this->filterKategori,
      'filterStok'     => $this->filterStok,
      'search'         => $this->search,
    ]);
  }
}