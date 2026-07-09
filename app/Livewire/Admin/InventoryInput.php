<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\DetailReturPenjualan;
use App\Models\Inventory;
use App\Models\Stok;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class InventoryInput extends Component {
  use WithPagination;

  // Form
  public $id_barang;
  public $stok_awal     = 0;
  public $barang_masuk  = 0;
  public $barang_keluar = 0;
  public $stok_sistem   = 0;
  public $stok_fisik;
  public $selisih = 0;
  public $tanggal;
  public $keterangan;

  // UI
  public $search        = '';
  public $filterTanggal = '';

  protected $rules = [
    'id_barang'  => 'required|exists:barangs,id_barang',
    'stok_fisik' => 'required|integer|min:0',
    'tanggal'    => 'required|date',
    'keterangan' => 'nullable|string|max:255',
  ];

  public function mount(): void {
    $this->tanggal = now()->format('Y-m-d');
  }

  public function updatedIdBarang($value): void {
    if ($value) {
      $bulanIni = now()->month;
      $tahunIni = now()->year;

      $stok            = Stok::where('id_barang', $value)->first();
      $this->stok_awal = $stok?->jumlah_stok ?? 0;

      $this->barang_masuk = BarangMasuk::where('id_barang', $value)
        ->whereMonth('tanggal_masuk', $bulanIni)
        ->whereYear('tanggal_masuk', $tahunIni)
        ->sum('jumlah');

      // Tambah retur penjualan yang masuk stok
      $retur  = DetailReturPenjualan::where('id_barang', $value)
        ->whereHas('retur', fn($q) => $q->where('status', 'Selesai')
            ->whereMonth('tanggal_retur', $bulanIni)
            ->whereYear('tanggal_retur', $tahunIni))
        ->where('kondisi_barang', 'Bagus')
        ->where('tujuan', 'Stok Utama')
        ->sum('jumlah_retur');
      $this->barang_masuk += $retur;

      $this->barang_keluar = BarangKeluar::where('id_barang', $value)
        ->whereMonth('tanggal_keluar', $bulanIni)
        ->whereYear('tanggal_keluar', $tahunIni)
        ->sum('jumlah');

      $this->hitungSistem();
    }
  }

  public function updatedStokFisik(): void {
    $this->hitungSelisih();
  }

  public function hitungSistem(): void {
    $this->stok_sistem = $this->stok_awal + $this->barang_masuk - $this->barang_keluar;
    $this->hitungSelisih();
  }

  public function hitungSelisih(): void {
    $this->selisih = (int) $this->stok_fisik - (int) $this->stok_sistem;
  }

  public function resetForm(): void {
    $this->reset(['id_barang', 'stok_awal', 'barang_masuk', 'barang_keluar', 'stok_sistem', 'stok_fisik', 'selisih', 'keterangan']);
    $this->tanggal = now()->format('Y-m-d');
    $this->resetErrorBag();
  }

  public function openModal(): void {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $this->validate();

    Inventory::create([
      'id_barang'     => $this->id_barang,
      'id_user'       => Auth::id(),
      'stok_awal'     => $this->stok_awal,
      'barang_masuk'  => $this->barang_masuk,
      'barang_keluar' => $this->barang_keluar,
      'stok_sistem'   => $this->stok_sistem,
      'stok_fisik'    => $this->stok_fisik,
      'selisih'       => $this->selisih,
      'tanggal'       => $this->tanggal,
      'keterangan'    => $this->keterangan,
    ]);

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data inventory berhasil disimpan.');
  }

  public function hapus(int $id): void {
    Inventory::findOrFail($id)->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data inventory berhasil dihapus.');
  }

  public function render() {
    $inventories = Inventory::with(['barang', 'user'])
      ->when($this->search, fn($q) => $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%')))
      ->when($this->filterTanggal, fn($q) => $q->whereDate('tanggal', $this->filterTanggal))
      ->orderByDesc('tanggal')
      ->paginate(10);

    return view('components.admin.inventory-input', [
      'inventories' => $inventories,
      'barangs'     => Barang::orderBy('nama_barang')->get(),
    ]);
  }
}