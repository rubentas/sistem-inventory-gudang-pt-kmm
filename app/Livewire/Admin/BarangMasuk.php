<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\BarangMasuk as BarangMasukModel;
use App\Models\Stok;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class BarangMasuk extends Component {
  use WithPagination;

  // Filter
  public string $search         = '';
  public string $filterDate     = '';
  public string $filterSupplier = '';

  // Form
  public int | string $id_barang   = '';
  public int | string $id_supplier = '';
  public string $no_nota           = '';
  public string $no_surat_jalan    = '';
  public int | string $jumlah      = '';
  public string $tanggal_masuk     = '';
  public string $sumber            = '';
  public string $keterangan        = '';

  // UI state
  public int | null $editId = null;

  // Property untuk mengecek apakah sedang edit
  public bool $isEdit = false;

  protected $rules = [
    'id_barang'      => 'required|exists:barangs,id_barang',
    'id_supplier'    => 'required|exists:suppliers,id_supplier',
    'no_nota'        => 'required|string|max:100',
    'no_surat_jalan' => 'nullable|string|max:100',
    'jumlah'         => 'required|integer|min:1',
    'tanggal_masuk'  => 'required|date',
    'sumber'         => 'required|string|max:100',
    'keterangan'     => 'nullable|string',
  ];

  protected $messages = [
    'id_barang.required'     => 'Pilih barang terlebih dahulu.',
    'id_supplier.required'   => 'Pilih supplier terlebih dahulu.',
    'no_nota.required'       => 'No. Nota wajib diisi.',
    'jumlah.required'        => 'Jumlah wajib diisi.',
    'jumlah.min'             => 'Jumlah minimal 1.',
    'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
    'sumber.required'        => 'Sumber barang wajib dipilih.',
  ];

  public function mount(): void {
    $this->tanggal_masuk = now()->format('Y-m-d');
  }

  // Reset pagination saat filter berubah
  public function updatedSearch(): void {
    $this->resetPage();
  }

  public function updatedFilterDate(): void {
    $this->resetPage();
  }

  public function updatedFilterSupplier(): void {
    $this->resetPage();
  }

  // Reset semua filter
  public function resetFilters(): void {
    $this->search         = '';
    $this->filterDate     = '';
    $this->filterSupplier = '';
    $this->resetPage();
  }

  // Reset form ke default
  public function resetForm(): void {
    $this->reset([
      'id_barang',
      'id_supplier',
      'no_nota',
      'no_surat_jalan',
      'jumlah',
      'sumber',
      'keterangan',
      'editId',
      'isEdit',
    ]);
    $this->tanggal_masuk = now()->format('Y-m-d');
    $this->resetErrorBag();
  }

  // Simpan data baru
  public function simpan(): void {
    try {
      $validated = $this->validate();

      BarangMasukModel::create([
        'id_barang'      => $this->id_barang,
        'id_supplier'    => $this->id_supplier,
        'id_user'        => Auth::id(),
        'no_nota'        => $this->no_nota,
        'no_surat_jalan' => $this->no_surat_jalan,
        'jumlah'         => (int) $this->jumlah,
        'tanggal_masuk'  => $this->tanggal_masuk,
        'sumber'         => $this->sumber,
        'keterangan'     => $this->keterangan,
      ]);

      // Update stok
      $stok = Stok::where('id_barang', $this->id_barang)->first();
      if ($stok) {
        $stok->increment('jumlah_stok', (int) $this->jumlah);
        $stok->updated_at = now();
        $stok->save();
      } else {
        $barang = Barang::find($this->id_barang);
        Stok::create([
          'id_barang'    => $this->id_barang,
          'jumlah_stok'  => (int) $this->jumlah,
          'stok_minimum' => $barang?->stok_minimum ?? 0,
          'updated_at'   => now(),
        ]);
      }

      $this->resetForm();
      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data barang masuk berhasil disimpan. Stok telah diperbarui.');
    } catch (\Exception $e) {
      Log::error('Error simpan barang masuk: ' . $e->getMessage());
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }

  // Ambil data untuk edit
  public function edit(int $id): void {
    $item = BarangMasukModel::findOrFail($id);

    $this->editId         = $item->id_masuk;
    $this->isEdit         = true;
    $this->id_barang      = $item->id_barang;
    $this->id_supplier    = $item->id_supplier;
    $this->no_nota        = $item->no_nota;
    $this->no_surat_jalan = $item->no_surat_jalan;
    $this->jumlah         = $item->jumlah;
    $this->tanggal_masuk  = $item->tanggal_masuk->format('Y-m-d');
    $this->sumber         = $item->sumber;
    $this->keterangan     = $item->keterangan ?? '';

    $this->dispatch('openEditModal');
  }

  // Update data
  public function update(): void {
    try {
      $this->validate();

      $item = BarangMasukModel::findOrFail($this->editId);

      // Update stok: kurangi stok lama, tambah stok baru
      $stok = Stok::where('id_barang', $this->id_barang)->first();
      if ($stok) {
        $stok->decrement('jumlah_stok', (int) $item->jumlah);
        $stok->increment('jumlah_stok', (int) $this->jumlah);
        $stok->updated_at = now();
        $stok->save();
      }

      $item->update([
        'id_barang'      => $this->id_barang,
        'id_supplier'    => $this->id_supplier,
        'no_nota'        => $this->no_nota,
        'no_surat_jalan' => $this->no_surat_jalan,
        'jumlah'         => (int) $this->jumlah,
        'tanggal_masuk'  => $this->tanggal_masuk,
        'sumber'         => $this->sumber,
        'keterangan'     => $this->keterangan,
      ]);

      $this->resetForm();
      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data barang masuk berhasil diperbarui.');
    } catch (\Exception $e) {
      Log::error('Error update barang masuk: ' . $e->getMessage());
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }

  // Hapus data
  public function hapus(int $id): void {
    try {
      $item = BarangMasukModel::findOrFail($id);

      // Kurangi stok
      $stok = Stok::where('id_barang', $item->id_barang)->first();
      if ($stok) {
        $stok->decrement('jumlah_stok', (int) $item->jumlah);
        $stok->updated_at = now();
        $stok->save();
      }

      $item->delete();
      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data barang masuk berhasil dihapus.');
    } catch (\Exception $e) {
      Log::error('Error hapus barang masuk: ' . $e->getMessage());
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }

  // Statistik untuk header
  public function getStats(): array {
    return [
      'totalItems' => BarangMasukModel::count(),
      'thisMonth'  => BarangMasukModel::whereMonth('tanggal_masuk', now()->month)
        ->whereYear('tanggal_masuk', now()->year)
        ->count(),
    ];
  }
  public function updatedIdSupplier($value) {
    if ($value && ! $this->isEdit) {
      $supplier = Supplier::find($value);
      if ($supplier) {
        $this->sumber = $supplier->nama_supplier;
      }
    }
  }

  // Render view
  public function render() {
    $barangMasuk = BarangMasukModel::with(['barang', 'supplier', 'user'])
      ->when($this->search, function ($query) {
        $query->where(function ($sub) {
          $sub->whereHas('barang', function ($q) {
            $q->where('nama_barang', 'like', '%' . $this->search . '%')
              ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
          })->orWhereHas('supplier', function ($q) {
            $q->where('nama_supplier', 'like', '%' . $this->search . '%');
          })->orWhere('no_nota', 'like', '%' . $this->search . '%');
        });
      })
      ->when($this->filterDate, fn($q) => $q->whereDate('tanggal_masuk', $this->filterDate))
      ->when($this->filterSupplier, fn($q) => $q->where('id_supplier', $this->filterSupplier))
      ->orderByDesc('tanggal_masuk')
      ->paginate(10);

    $barangs    = Barang::orderBy('nama_barang')->get();
    $suppliers  = Supplier::orderBy('nama_supplier')->get();
    $sumberList = [
      'KMM Pusat Banjarmasin',
      'Gudang Barabai',
      'PT. Nutrifood',
      'PT. Orang Tua',
      'PT. Sekar Laut',
    ];

    return view('components.admin.barang-masuk', [
      'barangMasuk'    => $barangMasuk,
      'barangs'        => $barangs,
      'suppliers'      => $suppliers,
      'sumberList'     => $sumberList,
      'stats'          => $this->getStats(),
      'filterDate'     => $this->filterDate,
      'filterSupplier' => $this->filterSupplier,
    ]);
  }
}