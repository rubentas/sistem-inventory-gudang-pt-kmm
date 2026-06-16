<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class DataBarang extends Component {
  use WithPagination;

  // Filter
  public string $search         = '';
  public string $filterKategori = '';
  public string $filterStok     = '';

  // Form
  public int | null $id_barang            = null;
  public string $kode_barang              = '';
  public string $nama_barang              = '';
  public string $kategori                 = '';
  public string $satuan                   = 'Pcs';
  public int $stok_minimum                = 0;
  public int | string $harga_jual_default = '';
  public string $keterangan               = '';

  // UI state
  public bool $isEdit = false;

  protected $rules = [
    'kode_barang'        => 'required|string|max:50',
    'nama_barang'        => 'required|string|max:255',
    'kategori'           => 'required|string|max:100',
    'satuan'             => 'required|string|max:20',
    'stok_minimum'       => 'required|integer|min:0',
    'harga_jual_default' => 'nullable|integer|min:0',
    'keterangan'         => 'nullable|string',
  ];

  protected $messages = [
    'kode_barang.required'  => 'Kode barang wajib diisi.',
    'kode_barang.unique'    => 'Kode barang sudah digunakan.',
    'nama_barang.required'  => 'Nama barang wajib diisi.',
    'kategori.required'     => 'Kategori wajib dipilih.',
    'satuan.required'       => 'Satuan wajib dipilih.',
    'stok_minimum.required' => 'Stok minimum wajib diisi.',
    'stok_minimum.min'      => 'Stok minimum minimal 0.',
  ];

  public function updatedSearch(): void {$this->resetPage();}
  public function updatedFilterKategori(): void {$this->resetPage();}
  public function updatedFilterStok(): void {$this->resetPage();}

  public function resetFilters(): void {
    $this->search         = '';
    $this->filterKategori = '';
    $this->filterStok     = '';
    $this->resetPage();
  }

  public function resetForm(): void {
    $this->reset([
      'id_barang', 'kode_barang', 'nama_barang', 'kategori',
      'satuan', 'stok_minimum', 'harga_jual_default', 'keterangan', 'isEdit',
    ]);
    $this->satuan       = 'Pcs';
    $this->stok_minimum = 0;
    $this->resetErrorBag();
  }

  public function openAddModal(): void {$this->resetForm();
    $this->dispatch('openModal');}

  public function edit(int $id): void {
    $barang                   = Barang::findOrFail($id);
    $this->id_barang          = $barang->id_barang;
    $this->kode_barang        = $barang->kode_barang;
    $this->nama_barang        = $barang->nama_barang;
    $this->kategori           = $barang->kategori ?? '';
    $this->satuan             = $barang->satuan;
    $this->stok_minimum       = $barang->stok_minimum;
    $this->harga_jual_default = $barang->harga_jual_default ?? '';
    $this->keterangan         = $barang->keterangan ?? '';
    $this->isEdit             = true;
    $this->resetErrorBag();
    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $rules = $this->rules;
    if ($this->isEdit) {
      $rules['kode_barang'] = 'required|string|max:50|unique:barangs,kode_barang,' . $this->id_barang . ',id_barang';
    } else {
      $rules['kode_barang'] = 'required|string|max:50|unique:barangs,kode_barang';
    }
    $this->validate($rules);

    $data = [
      'kode_barang'        => $this->kode_barang,
      'nama_barang'        => $this->nama_barang,
      'kategori'           => $this->kategori,
      'satuan'             => $this->satuan,
      'stok_minimum'       => $this->stok_minimum,
      'harga_jual_default' => $this->harga_jual_default ?: null,
      'keterangan'         => $this->keterangan,
    ];

    if ($this->isEdit) {
      Barang::findOrFail($this->id_barang)->update($data);
      $message = 'Data barang berhasil diperbarui.';
    } else {
      Barang::create($data);
      $message = 'Data barang berhasil ditambahkan.';
    }

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: $message);
  }

  public function update(): void {$this->simpan();}

  public function hapus(int $id): void {
    $barang = Barang::findOrFail($id);
    if ($barang->barangMasuk()->count() > 0 || $barang->barangKeluar()->count() > 0) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Barang tidak bisa dihapus karena sudah memiliki transaksi.');
      return;
    }
    $barang->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data barang berhasil dihapus.');
  }

  public function getStats(): array {
    return [
      'totalItems' => Barang::count(),
      'kategori'   => Barang::distinct('kategori')->count('kategori'),
      'menipis'    => Barang::whereHas('stok', fn($q) => $q->whereColumn('jumlah_stok', '<=', 'stok_minimum'))->count(),
      'aman'       => Barang::whereHas('stok', fn($q) => $q->whereColumn('jumlah_stok', '>', 'stok_minimum'))->count(),
    ];
  }

  public function exportPdf() {
    $barangs = Barang::with('stok')
      ->when($this->search, fn($q) => $q->where('nama_barang', 'like', '%' . $this->search . '%')
          ->orWhere('kode_barang', 'like', '%' . $this->search . '%'))
      ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
      ->when($this->filterStok === 'menipis', fn($q) => $q->whereHas('stok', fn($s) => $s->whereColumn('jumlah_stok', '<=', 'stok_minimum')))
      ->when($this->filterStok === 'aman', fn($q) => $q->whereHas('stok', fn($s) => $s->whereColumn('jumlah_stok', '>', 'stok_minimum')))
      ->orderBy('kode_barang')
      ->get();

    $filterLabel = match ($this->filterStok) {
      'menipis' => 'Stok Menipis',
      'aman'    => 'Stok Aman',
      default   => 'Semua Stok'
    };

    $pdf = Pdf::loadView('laporan.data-barang', [
      'data'          => $barangs,
      'dicetak_oleh'  => auth()->user()->nama,
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
      'filter_label'  => $filterLabel,
    ])->setPaper('a4', 'landscape');

    return response()->streamDownload(
      fn() => print($pdf->output()),
      'data-barang-' . ($this->filterStok ?: 'semua') . '-' . now()->format('Ymd') . '.pdf'
    );
  }

  public function render() {
    $barangs = Barang::with('stok')
      ->when($this->search, fn($q) => $q->where('nama_barang', 'like', '%' . $this->search . '%')
          ->orWhere('kode_barang', 'like', '%' . $this->search . '%'))
      ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
      ->when($this->filterStok === 'menipis', fn($q) => $q->whereHas('stok', fn($s) => $s->whereColumn('jumlah_stok', '<=', 'stok_minimum')))
      ->when($this->filterStok === 'aman', fn($q) => $q->whereHas('stok', fn($s) => $s->whereColumn('jumlah_stok', '>', 'stok_minimum')))
      ->orderBy('kode_barang')
      ->paginate(15);

    $kategoriList = Barang::distinct()->pluck('kategori')->filter()->values();

    return view('components.admin.data-barang', [
      'barangs'        => $barangs,
      'kategoriList'   => $kategoriList,
      'stats'          => $this->getStats(),
      'filterKategori' => $this->filterKategori,
      'filterStok'     => $this->filterStok,
    ]);
  }
}