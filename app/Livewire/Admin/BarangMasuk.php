<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\BarangMasuk as BarangMasukModel;
use App\Models\Stok;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class BarangMasuk extends Component {
  use WithPagination;

  // Filter fields
  public string $search         = '';
  public string $filterDate     = '';
  public string $filterSupplier = '';
  public string $filterType     = ''; // 'today', 'week', 'month', 'custom'

  // Form fields
  public int | string $id_barang   = '';
  public int | string $id_supplier = '';
  public string $no_nota           = '';
  public string $no_surat_jalan    = '';
  public int | string $jumlah      = '';
  public string $tanggal_masuk     = '';
  public string $sumber            = '';
  public string $keterangan        = '';
  public string $satuan_display    = '';

  // UI state
  public int | null $editId = null;
  public bool $isEdit       = false;

  protected $rules = [
    'id_barang'      => 'required|exists:barangs,id_barang',
    'id_supplier'    => 'nullable|exists:suppliers,id_supplier',
    'no_nota'        => 'required|string|max:100',
    'no_surat_jalan' => 'nullable|string|max:100',
    'jumlah'         => 'required|integer|min:1',
    'tanggal_masuk'  => 'required|date',
    'sumber'         => 'required|string|max:100',
    'keterangan'     => 'nullable|string',
  ];

  protected $messages = [
    'id_barang.required'     => 'Pilih barang terlebih dahulu.',
    'no_nota.required'       => 'No. Nota wajib diisi.',
    'jumlah.required'        => 'Jumlah wajib diisi.',
    'jumlah.min'             => 'Jumlah minimal 1.',
    'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
    'sumber.required'        => 'Sumber barang wajib dipilih.',
  ];

  public function mount(): void {
    $this->tanggal_masuk = now()->format('Y-m-d');
  }

  public function updatedSearch(): void {
    $this->resetPage();
  }

  public function updatedFilterDate(): void {
    $this->resetPage();
  }

  public function updatedFilterSupplier(): void {
    $this->resetPage();
  }

  public function setFilter(string $type): void {
    $this->filterType = $type;

    switch ($type) {
    case 'today':
      $this->filterDate = now()->format('Y-m-d');
      break;

    case 'week':
      // Set filterDate ke hari ini untuk trigger filter,
      // logic 7 hari ditangani di query
      $this->filterDate = now()->format('Y-m-d');
      break;

    case 'month':
      $this->filterDate = now()->startOfMonth()->format('Y-m-d');
      break;

    default:
      $this->filterType = 'custom';
      break;
    }

    $this->resetPage();
  }

  public function resetFilters(): void {
    $this->search         = '';
    $this->filterDate     = '';
    $this->filterSupplier = '';
    $this->filterType     = '';
    $this->resetPage();
  }

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
      'satuan_display',
    ]);
    $this->tanggal_masuk = now()->format('Y-m-d');
    $this->resetErrorBag();
  }

  public function updatedIdBarang($value): void {
    if ($value) {
      $barang               = Barang::find($value);
      $this->satuan_display = $barang->satuan ?? 'Pcs';
    } else {
      $this->satuan_display = '';
    }
  }

  public function updatedSumber($value): void {
    if ($value !== 'Supplier') {
      $this->id_supplier = '';
    }
  }

  public function openAddModal(): void {
    $this->resetForm();
    $this->isEdit = false;
    $this->dispatch('openModal');
  }

  public function edit(int $id): void {
    $item = BarangMasukModel::findOrFail($id);

    $this->editId         = $item->id_masuk;
    $this->isEdit         = true;
    $this->id_barang      = $item->id_barang;
    $this->id_supplier    = $item->id_supplier ?? '';
    $this->no_nota        = $item->no_nota;
    $this->no_surat_jalan = $item->no_surat_jalan ?? '';
    $this->jumlah         = $item->jumlah;
    $this->tanggal_masuk  = $item->tanggal_masuk->format('Y-m-d');
    $this->sumber         = $item->sumber;
    $this->keterangan     = $item->keterangan ?? '';
    $this->satuan_display = $item->barang->satuan ?? '';

    $this->dispatch('openEditModal');
  }

  public function simpan(): void {
    $rules = $this->rules;

    if ($this->sumber === 'Supplier') {
      $rules['id_supplier'] = 'required|exists:suppliers,id_supplier';
    }

    $this->validate($rules);

    BarangMasukModel::create([
      'id_barang'      => $this->id_barang,
      'id_supplier'    => $this->sumber === 'Supplier' ? $this->id_supplier : null,
      'id_user'        => Auth::id(),
      'no_nota'        => $this->no_nota,
      'no_surat_jalan' => $this->no_surat_jalan,
      'jumlah'         => (int) $this->jumlah,
      'tanggal_masuk'  => $this->tanggal_masuk,
      'sumber'         => $this->sumber,
      'keterangan'     => $this->keterangan,
    ]);

    // Update stok
    $this->syncStok($this->id_barang, (int) $this->jumlah, 'increment');

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data barang masuk berhasil disimpan. Stok telah diperbarui.');
  }

  public function update(): void {
    $rules = $this->rules;

    if ($this->sumber === 'Supplier') {
      $rules['id_supplier'] = 'required|exists:suppliers,id_supplier';
    }

    $this->validate($rules);

    $item   = BarangMasukModel::findOrFail($this->editId);
    $oldQty = (int) $item->jumlah;

    // Rollback stok lama, apply stok baru
    $this->syncStok($item->id_barang, $oldQty, 'decrement');
    $this->syncStok($this->id_barang, (int) $this->jumlah, 'increment');

    $item->update([
      'id_barang'      => $this->id_barang,
      'id_supplier'    => $this->sumber === 'Supplier' ? $this->id_supplier : null,
      'no_nota'        => $this->no_nota,
      'no_surat_jalan' => $this->no_surat_jalan,
      'jumlah'         => (int) $this->jumlah,
      'tanggal_masuk'  => $this->tanggal_masuk,
      'sumber'         => $this->sumber,
      'keterangan'     => $this->keterangan,
    ]);

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data barang masuk berhasil diperbarui.');
  }

  public function hapus(int $id): void {
    $item = BarangMasukModel::findOrFail($id);

    // Kurangi stok
    $this->syncStok($item->id_barang, (int) $item->jumlah, 'decrement');

    $item->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data barang masuk berhasil dihapus.');
  }

  /**
   * Helper untuk sync stok: increment atau decrement
   */
  private function syncStok(int $idBarang, int $jumlah, string $action = 'increment'): void {
    $stok = Stok::where('id_barang', $idBarang)->first();

    if ($stok) {
      if ($action === 'increment') {
        $stok->increment('jumlah_stok', $jumlah);
      } else {
        $stok->decrement('jumlah_stok', $jumlah);
      }
      $stok->updated_at = now();
      $stok->save();
    } elseif ($action === 'increment') {
      // Buat record stok baru kalau belum ada
      $barang = Barang::find($idBarang);
      Stok::create([
        'id_barang'    => $idBarang,
        'jumlah_stok'  => $jumlah,
        'stok_minimum' => $barang?->stok_minimum ?? 0,
        'updated_at'   => now(),
      ]);
    }
  }

  public function getStats(): array {
    return [
      'totalItems' => BarangMasukModel::count(),
      'thisMonth'  => BarangMasukModel::whereMonth('tanggal_masuk', now()->month)
        ->whereYear('tanggal_masuk', now()->year)
        ->count(),
    ];
  }

  public function render() {
    $barangMasuk = BarangMasukModel::with(['barang', 'supplier', 'user'])
      ->when($this->search, function ($query) {
        $query->where(function ($sub) {
          $sub->whereHas('barang', function ($q) {
            $q->where('nama_barang', 'like', '%' . $this->search . '%')
              ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
          })
            ->orWhereHas('supplier', function ($q) {
              $q->where('nama_supplier', 'like', '%' . $this->search . '%');
            })
            ->orWhere('no_nota', 'like', '%' . $this->search . '%');
        });
      })
      ->when($this->filterDate, function ($query) {
        $date = Carbon::parse($this->filterDate);

        switch ($this->filterType) {
        case 'week':
          // 7 hari terakhir (hari ini + 6 hari ke belakang)
          return $query->whereBetween('tanggal_masuk', [
            now()->subDays(6)->startOfDay(),
            now()->endOfDay(),
          ]);

        case 'month':
          // 1 bulan penuh berdasarkan filterDate
          return $query->whereBetween('tanggal_masuk', [
            $date->copy()->startOfMonth()->startOfDay(),
            $date->copy()->endOfMonth()->endOfDay(),
          ]);

        case 'custom':
          // Dari input date picker, cek apakah tanggal 1
          if ($date->day === 1 && request()->has('manual_month')) {
            return $query->whereBetween('tanggal_masuk', [
              $date->copy()->startOfMonth()->startOfDay(),
              $date->copy()->endOfMonth()->endOfDay(),
            ]);
          }
          // Fallback: 1 hari spesifik
          return $query->whereDate('tanggal_masuk', $this->filterDate);

        case 'today':
        default:
          // 1 hari spesifik
          return $query->whereDate('tanggal_masuk', $this->filterDate);
        }
      })
      ->when($this->filterSupplier, function ($query) {
        $query->where('id_supplier', $this->filterSupplier);
      })
      ->orderByDesc('tanggal_masuk')
      ->orderByDesc('id_masuk')
      ->paginate(10);

    $barangs    = Barang::orderBy('nama_barang')->get();
    $suppliers  = Supplier::orderBy('nama_supplier')->get();
    $sumberList = ['KMM Pusat Banjarmasin', 'Gudang Barabai', 'Supplier'];

    return view('components.admin.barang-masuk', [
      'barangMasuk'    => $barangMasuk,
      'barangs'        => $barangs,
      'suppliers'      => $suppliers,
      'sumberList'     => $sumberList,
      'stats'          => $this->getStats(),
      'filterDate'     => $this->filterDate,
      'filterSupplier' => $this->filterSupplier,
      'filterType'     => $this->filterType,
    ]);
  }
}