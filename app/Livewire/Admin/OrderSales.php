<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\OrderSales as OrderSalesModel;
use App\Models\Wilayah;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class OrderSales extends Component {
  use WithPagination;

  // Filter
  public string $search       = '';
  public string $filterStatus = '';

  // Form
  public $id_order             = null;
  public $id_barang            = '';
  public $id_wilayah           = '';
  public $jumlah               = '';
  public $harga_jual           = '';
  public $subtotal             = '';
  public string $tanggal_order = '';
  public string $status        = 'pending';
  public string $keterangan    = '';

  // UI state
  public bool $isEdit = false;

  protected $rules = [
    'id_barang'     => 'required|exists:barangs,id_barang',
    'id_wilayah'    => 'required|exists:wilayahs,id_wilayah',
    'jumlah'        => 'required|integer|min:1',
    'harga_jual'    => 'required|numeric|min:0',
    'tanggal_order' => 'required|date',
    'status'        => 'required|in:pending,diproses,selesai',
    'keterangan'    => 'nullable|string',
  ];

  protected $messages = [
    'id_barang.required'     => 'Pilih barang terlebih dahulu.',
    'id_wilayah.required'    => 'Pilih wilayah terlebih dahulu.',
    'jumlah.required'        => 'Jumlah wajib diisi.',
    'jumlah.min'             => 'Jumlah minimal 1.',
    'harga_jual.required'    => 'Harga jual wajib diisi.',
    'harga_jual.min'         => 'Harga jual minimal 0.',
    'tanggal_order.required' => 'Tanggal order wajib diisi.',
  ];

  public function mount(): void {
    $this->tanggal_order = now()->format('Y-m-d');
  }

  public function updatedJumlah(): void {
    $this->hitungSubtotal();
  }

  public function updatedHargaJual(): void {
    $this->hitungSubtotal();
  }

  public function updatedIdBarang(): void {
    if ($this->id_barang && ! $this->isEdit) {
      $barang = Barang::find($this->id_barang);
      if ($barang && $barang->harga_jual_default > 0) {
        $this->harga_jual = $barang->harga_jual_default;
        $this->hitungSubtotal();
      }
    }
  }

  public function hitungSubtotal(): void {
    $jumlah         = (int) $this->jumlah;
    $harga          = (int) $this->harga_jual;
    $this->subtotal = $jumlah * $harga;
  }

  public function updatedSearch(): void {
    $this->resetPage();
  }

  public function updatedFilterStatus(): void {
    $this->resetPage();
  }

  public function resetFilters(): void {
    $this->search       = '';
    $this->filterStatus = '';
    $this->resetPage();
  }

  public function resetForm(): void {
    $this->reset([
      'id_order', 'id_barang', 'id_wilayah', 'jumlah', 'harga_jual', 'subtotal',
      'tanggal_order', 'status', 'keterangan', 'isEdit',
    ]);
    $this->tanggal_order = now()->format('Y-m-d');
    $this->status        = 'pending';
    $this->resetErrorBag();
  }

  public function edit(int $id): void {
    $order               = OrderSalesModel::findOrFail($id);
    $this->id_order      = $order->id_order;
    $this->id_barang     = $order->id_barang;
    $this->id_wilayah    = $order->id_wilayah;
    $this->jumlah        = $order->jumlah;
    $this->harga_jual    = $order->harga_jual;
    $this->subtotal      = $order->subtotal;
    $this->tanggal_order = $order->tanggal_order->format('Y-m-d');
    $this->status        = $order->status;
    $this->keterangan    = $order->keterangan ?? '';
    $this->isEdit        = true;
    $this->resetErrorBag();
    $this->dispatch('openModal');
  }

  public function update(): void {
    $this->validate();

    $order = OrderSalesModel::findOrFail($this->id_order);

    // Cek stok jika status diubah menjadi selesai
    if ($order->status !== 'selesai' && $this->status === 'selesai') {
      $stok = \App\Models\Stok::where('id_barang', $this->id_barang)->first();
      if (! $stok || $stok->jumlah_stok < $this->jumlah) {
        $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Stok tidak mencukupi untuk menyelesaikan order ini.');
        return;
      }
    }

    $order->update([
      'id_barang'     => $this->id_barang,
      'id_wilayah'    => $this->id_wilayah,
      'jumlah'        => $this->jumlah,
      'harga_jual'    => $this->harga_jual,
      'subtotal'      => $this->subtotal,
      'tanggal_order' => $this->tanggal_order,
      'status'        => $this->status,
      'keterangan'    => $this->keterangan,
    ]);

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Order sales berhasil diperbarui.');
  }

  public function hapus(int $id): void {
    $order = OrderSalesModel::findOrFail($id);
    if ($order->barangKeluar) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Order tidak bisa dihapus karena sudah diproses menjadi barang keluar.');
      return;
    }
    $order->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Order sales berhasil dihapus.');
  }

  public function getStats(): array {
    return [
      'total'   => OrderSalesModel::count(),
      'pending' => OrderSalesModel::where('status', 'pending')->count(),
      'selesai' => OrderSalesModel::where('status', 'selesai')->count(),
    ];
  }

  public function render() {
    $orders = OrderSalesModel::with(['barang', 'wilayah', 'user'])
      ->when($this->search, function ($q) {
        $q->whereHas('barang', function ($b) {
          $b->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
        })->orWhereHas('wilayah', function ($w) {
          $w->where('nama_wilayah', 'like', '%' . $this->search . '%');
        });
      })
      ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
      ->orderByDesc('tanggal_order')
      ->paginate(10);

    $barangs  = Barang::orderBy('nama_barang')->get();
    $wilayahs = Wilayah::orderBy('nama_wilayah')->get();

    return view('components.admin.order-sales', [
      'orders'       => $orders,
      'barangs'      => $barangs,
      'wilayahs'     => $wilayahs,
      'stats'        => $this->getStats(),
      'filterStatus' => $this->filterStatus,
    ]);
  }
}