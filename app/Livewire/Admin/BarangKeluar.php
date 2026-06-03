<?php
namespace App\Livewire\Admin;

use App\Models\BarangKeluar as BarangKeluarModel;
use App\Models\OrderSales;
use App\Models\Stok;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class BarangKeluar extends Component {
  use WithPagination;

  // Filter
  public string $search        = '';
  public string $filterDate    = '';
  public string $filterWilayah = '';

  // Form
  public int | string $id_order   = '';
  public int | string $id_barang  = '';
  public int | string $id_wilayah = '';
  public int | string $jumlah     = '';
  public string $tanggal_keluar   = '';
  public string $keterangan       = '';

  // Display
  public string $nama_barang_display  = '';
  public string $satuan_display       = '';
  public string $order_status_display = '';

  // UI state
  public bool $isEdit       = false;
  public int | null $editId = null;

  protected $rules = [
    'id_order'       => 'required|exists:order_sales,id_order',
    'id_barang'      => 'required|exists:barangs,id_barang',
    'id_wilayah'     => 'required|exists:wilayahs,id_wilayah',
    'jumlah'         => 'required|integer|min:1',
    'tanggal_keluar' => 'required|date',
    'keterangan'     => 'nullable|string',
  ];

  protected $messages = [
    'id_order.required'       => 'Pilih order sales terlebih dahulu.',
    'jumlah.required'         => 'Jumlah wajib diisi.',
    'jumlah.min'              => 'Jumlah minimal 1.',
    'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
  ];

  public function mount(): void {
    $this->tanggal_keluar = now()->format('Y-m-d');
  }

  public function updatedSearch(): void {$this->resetPage();}
  public function updatedFilterDate(): void {$this->resetPage();}
  public function updatedFilterWilayah(): void {$this->resetPage();}

  public function resetFilters(): void {
    $this->search        = '';
    $this->filterDate    = '';
    $this->filterWilayah = '';
    $this->resetPage();
  }

  public function resetForm(): void {
    $this->reset([
      'id_order', 'id_barang', 'id_wilayah', 'jumlah',
      'keterangan', 'nama_barang_display', 'satuan_display',
      'order_status_display', 'editId', 'isEdit',
    ]);
    $this->tanggal_keluar = now()->format('Y-m-d');
    $this->resetErrorBag();
  }

  public function updatedIdOrder($value): void {
    if ($value) {
      $order = OrderSales::with(['barang', 'wilayah'])->find($value);
      if ($order) {
        $this->id_barang            = $order->id_barang;
        $this->id_wilayah           = $order->id_wilayah;
        $this->jumlah               = $order->jumlah;
        $this->nama_barang_display  = $order->barang->nama_barang ?? '';
        $this->satuan_display       = $order->barang->satuan ?? '';
        $this->order_status_display = $order->status;
      }
    } else {
      $this->reset(['id_barang', 'id_wilayah', 'jumlah', 'nama_barang_display', 'satuan_display', 'order_status_display']);
    }
  }

  public function openAddModal(): void {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $this->validate();

    $stok = Stok::where('id_barang', $this->id_barang)->first();
    if (! $stok || $stok->jumlah_stok < $this->jumlah) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Stok tidak mencukupi!');
      return;
    }

    BarangKeluarModel::create([
      'id_barang'      => $this->id_barang,
      'id_order'       => $this->id_order,
      'id_user'        => Auth::id(),
      'id_wilayah'     => $this->id_wilayah,
      'jumlah'         => $this->jumlah,
      'tanggal_keluar' => $this->tanggal_keluar,
      'keterangan'     => $this->keterangan,
    ]);

    $stok->decrement('jumlah_stok', (int) $this->jumlah);
    $stok->updated_at = now();
    $stok->save();

    if ($this->id_order) {
      OrderSales::find($this->id_order)?->update(['status' => 'selesai']);
    }

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Barang keluar berhasil disimpan. Stok telah diperbarui.');
  }

  public function hapus(int $id): void {
    $bk = BarangKeluarModel::findOrFail($id);

    $stok = Stok::where('id_barang', $bk->id_barang)->first();
    if ($stok) {
      $stok->increment('jumlah_stok', (int) $bk->jumlah);
      $stok->updated_at = now();
      $stok->save();
    }

    if ($bk->id_order) {
      OrderSales::find($bk->id_order)?->update(['status' => 'diproses']);
    }

    $bk->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data barang keluar berhasil dihapus.');
  }

  public function getStats(): array {
    return [
      'totalItems' => BarangKeluarModel::count(),
      'thisMonth'  => BarangKeluarModel::whereMonth('tanggal_keluar', now()->month)->count(),
    ];
  }

  public function render() {
    $barangKeluar = BarangKeluarModel::with(['barang', 'order', 'user', 'wilayah'])
      ->when($this->search, function ($q) {
        $q->whereHas('barang', function ($b) {
          $b->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
        })->orWhereHas('wilayah', function ($w) {
          $w->where('nama_wilayah', 'like', '%' . $this->search . '%');
        });
      })
      ->when($this->filterDate, fn($q) => $q->whereDate('tanggal_keluar', $this->filterDate))
      ->when($this->filterWilayah, fn($q) => $q->where('id_wilayah', $this->filterWilayah))
      ->orderByDesc('tanggal_keluar')
      ->paginate(10);

    $orders = OrderSales::with(['barang', 'wilayah'])
      ->where('status', '!=', 'selesai')
      ->orderByDesc('tanggal_order')
      ->get();

    $wilayahs = \App\Models\Wilayah::orderBy('nama_wilayah')->get();

    return view('components.admin.barang-keluar', [
      'barangKeluar'  => $barangKeluar,
      'orders'        => $orders,
      'wilayahs'      => $wilayahs,
      'stats'         => $this->getStats(),
      'filterDate'    => $this->filterDate,
      'filterWilayah' => $this->filterWilayah,
    ]);
  }
}