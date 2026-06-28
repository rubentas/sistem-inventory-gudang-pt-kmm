<?php
namespace App\Livewire\Admin\Laporan;

use App\Models\Stok;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class StokBarang extends Component {
  use WithPagination;

  public string $filterKategori = '';
  public string $filterStatus   = '';
  public string $search         = '';

  protected function query() {
    return Stok::with('barang')
      ->when($this->filterKategori, function ($q) {
        $q->whereHas('barang', fn($b) => $b->where('kategori', $this->filterKategori));
      })
      ->when($this->filterStatus === 'menipis', function ($q) {
        $q->whereColumn('jumlah_stok', '<=', 'stok_minimum');
      })
      ->when($this->filterStatus === 'aman', function ($q) {
        $q->whereColumn('jumlah_stok', '>', 'stok_minimum');
      })
      ->when($this->search, function ($q) {
        $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%'));
      });
  }

  public function getRingkasan(): array {
    $totalStok   = Stok::sum('jumlah_stok');
    $totalBarang = Stok::count();
    $stokMenipis = Stok::whereColumn('jumlah_stok', '<=', 'stok_minimum')->count();
    $stokNormal  = $totalBarang - $stokMenipis;

    return [
      'total_stok'   => $totalStok,
      'total_barang' => $totalBarang,
      'stok_menipis' => $stokMenipis,
      'stok_normal'  => $stokNormal,
    ];
  }

  public function getDataPerKategori(): array {
    $data = $this->query()
      ->get()
      ->groupBy(fn($s) => $s->barang->kategori ?? 'Lainnya');

    return [
      'labels' => $data->keys()->toArray(),
      'values' => $data->map(fn($g) => $g->sum('jumlah_stok'))->values()->toArray(),
    ];
  }

  public function getTopStok(): array {
    $data = $this->query()
      ->orderByDesc('jumlah_stok')
      ->limit(10)
      ->get();

    return [
      'labels' => $data->pluck('barang.nama_barang')->map(fn($n) => strlen($n) > 25 ? substr($n, 0, 25) . '...' : $n)->toArray(),
      'values' => $data->pluck('jumlah_stok')->toArray(),
    ];
  }

  public function getKategoriList(): array {
    return \App\Models\Barang::distinct()->pluck('kategori')->toArray();
  }

  public function cetakPdf() {
    $data = $this->query()->orderBy('jumlah_stok', 'asc')->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.stok-barang', [
      'data'          => $data,
      'total_stok'    => $data->sum('jumlah_stok'),
      'stok_menipis'  => $data->where('status', 'Menipis')->count(),
      'dicetak_oleh'  => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'landscape');

    return response()->stream(
      fn() => print($pdf->output()),
      200,
      ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="laporan-stok-barang.pdf"']
    );
  }

  public function exportExcel() {
    return (new \App\Exports\StokBarangExport($this->filterKategori, $this->filterStatus, $this->search))->download();
  }

  public function render() {
    return view('components.admin.laporan.stok-barang', [
      'ringkasan'    => $this->getRingkasan(),
      'perKategori'  => $this->getDataPerKategori(),
      'topStok'      => $this->getTopStok(),
      'tabelStok'    => $this->query()->orderBy('jumlah_stok', 'asc')->paginate(15),
      'kategoriList' => $this->getKategoriList(),
    ]);
  }
}