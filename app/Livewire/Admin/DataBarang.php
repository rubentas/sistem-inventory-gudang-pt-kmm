<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class DataBarang extends Component {
  use WithPagination;

  // Filter
  public string $search         = '';
  public string $filterKategori = '';

  // Form
  public int | null $id_barang = null;
  public string $kode_barang   = '';
  public string $nama_barang   = '';
  public string $kategori      = '';
  public string $satuan        = 'Pcs';
  public int $stok_minimum     = 0;
  public string $keterangan    = '';

  // UI state
  public bool $isEdit = false;

  protected $rules = [
    'kode_barang'  => 'required|string|max:50',
    'nama_barang'  => 'required|string|max:255',
    'kategori'     => 'required|string|max:100',
    'satuan'       => 'required|string|max:20',
    'stok_minimum' => 'required|integer|min:0',
    'keterangan'   => 'nullable|string',
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

  public function resetFilters(): void {
    $this->search         = '';
    $this->filterKategori = '';
    $this->resetPage();
  }

  public function resetForm(): void {
    $this->reset([
      'id_barang', 'kode_barang', 'nama_barang', 'kategori',
      'satuan', 'stok_minimum', 'keterangan', 'isEdit',
    ]);
    $this->satuan       = 'Pcs';
    $this->stok_minimum = 0;
    $this->resetErrorBag();
  }

  public function openAddModal(): void {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function edit(int $id): void {
    $barang             = Barang::findOrFail($id);
    $this->id_barang    = $barang->id_barang;
    $this->kode_barang  = $barang->kode_barang;
    $this->nama_barang  = $barang->nama_barang;
    $this->kategori     = $barang->kategori ?? '';
    $this->satuan       = $barang->satuan;
    $this->stok_minimum = $barang->stok_minimum;
    $this->keterangan   = $barang->keterangan ?? '';
    $this->isEdit       = true;
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
      'kode_barang'  => $this->kode_barang,
      'nama_barang'  => $this->nama_barang,
      'kategori'     => $this->kategori,
      'satuan'       => $this->satuan,
      'stok_minimum' => $this->stok_minimum,
      'keterangan'   => $this->keterangan,
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
    ];
  }

  public function render() {
    $barangs = Barang::with('stok')
      ->when($this->search, function ($q) {
        $q->where('nama_barang', 'like', '%' . $this->search . '%')
          ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
      })
      ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
      ->orderBy('kode_barang')
      ->paginate(15);

    $kategoriList = Barang::distinct()->pluck('kategori')->filter()->values();

    return view('components.admin.data-barang', [
      'barangs'        => $barangs,
      'kategoriList'   => $kategoriList,
      'stats'          => $this->getStats(),
      'filterKategori' => $this->filterKategori,
    ]);
  }
}