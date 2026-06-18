<?php
namespace App\Livewire\Sales;

use App\Models\Barang;
use App\Models\OrderSales as OrderSalesModel;
use App\Models\Sales;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.sales')]
class OrderSales extends Component {
  use WithPagination;

  public $id_barang                = '';
  public $id_wilayah               = '';
  public $id_sales                 = '';
  public $nama_toko                = '';
  public $jumlah                   = '';
  public $harga_jual               = '';
  public $subtotal                 = '';
  public string $tanggal_order     = '';
  public string $keterangan        = '';
  public string $status_pembayaran = 'dicicil';

  public string $search       = '';
  public string $filterStatus = '';
  public bool $isEdit         = false;
  public $editId              = null;

  protected $rules = [
    'id_barang'         => 'required|exists:barangs,id_barang',
    'id_wilayah'        => 'required|exists:wilayahs,id_wilayah',
    'id_sales'          => 'nullable|exists:sales,id_sales',
    'nama_toko'         => 'nullable|string|max:255',
    'jumlah'            => 'required|integer|min:1',
    'harga_jual'        => 'required|numeric|min:0',
    'tanggal_order'     => 'required|date',
    'keterangan'        => 'nullable|string',
    'status_pembayaran' => 'required|in:lunas,dicicil',
  ];

  protected $messages = [
    'id_barang.required'     => 'Pilih barang terlebih dahulu.',
    'id_wilayah.required'    => 'Pilih wilayah terlebih dahulu.',
    'jumlah.required'        => 'Jumlah wajib diisi.',
    'jumlah.min'             => 'Jumlah minimal 1.',
    'harga_jual.required'    => 'Harga jual wajib diisi.',
    'tanggal_order.required' => 'Tanggal order wajib diisi.',
  ];

  public function mount(): void {
    $this->tanggal_order = now()->format('Y-m-d');
  }

  public function updatedSearch(): void {
    $this->resetPage();
  }

  public function updatedFilterStatus(): void {
    $this->resetPage();
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
    $this->subtotal = (int) $this->jumlah * (int) $this->harga_jual;
  }

  public function resetFilters(): void {
    $this->search       = '';
    $this->filterStatus = '';
    $this->resetPage();
  }

  public function resetForm(): void {
    $this->reset([
      'id_barang', 'id_wilayah', 'id_sales', 'nama_toko', 'jumlah',
      'harga_jual', 'subtotal', 'tanggal_order', 'keterangan', 'editId', 'isEdit',
    ]);
    $this->status_pembayaran = 'dicicil';
    $this->tanggal_order     = now()->format('Y-m-d');
    $this->resetErrorBag();
  }

  public function openAddModal(): void {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function edit(int $id): void {
    $order = OrderSalesModel::findOrFail($id);
    if ($order->status !== 'pending') {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Order sudah diproses, tidak bisa diubah.');
      return;
    }
    $this->editId            = $order->id_order;
    $this->id_barang         = $order->id_barang;
    $this->id_wilayah        = $order->id_wilayah;
    $this->id_sales          = $order->id_sales ?? '';
    $this->nama_toko         = $order->nama_toko ?? '';
    $this->jumlah            = $order->jumlah;
    $this->harga_jual        = $order->harga_jual ?? 0;
    $this->subtotal          = $order->subtotal ?? 0;
    $this->tanggal_order     = $order->tanggal_order->format('Y-m-d');
    $this->keterangan        = $order->keterangan ?? '';
    $this->status_pembayaran = $order->status_pembayaran ?? 'dicicil';
    $this->isEdit            = true;
    $this->resetErrorBag();
    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $this->validate();

    $stok = \App\Models\Stok::where('id_barang', $this->id_barang)->first();
    if (! $stok || $stok->jumlah_stok < $this->jumlah) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Stok tidak mencukupi!');
      return;
    }

    $data = [
      'id_barang'         => $this->id_barang,
      'id_user'           => Auth::id(),
      'id_sales'          => $this->id_sales ?: null,
      'id_wilayah'        => $this->id_wilayah,
      'nama_toko'         => $this->nama_toko,
      'jumlah'            => $this->jumlah,
      'harga_jual'        => $this->harga_jual ?: 0,
      'subtotal'          => $this->subtotal ?: 0,
      'tanggal_order'     => $this->tanggal_order,
      'status'            => 'pending',
      'status_pembayaran' => $this->status_pembayaran,
      'keterangan'        => $this->keterangan,
    ];

    if ($this->isEdit) {
      OrderSalesModel::findOrFail($this->editId)->update($data);
      $message = 'Order sales berhasil diperbarui.';
    } else {
      OrderSalesModel::create($data);
      $message = 'Order sales berhasil dibuat.';
    }

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: $message);
  }

  public function update(): void {
    $this->simpan();
  }

  public function hapus(int $id): void {
    $order = OrderSalesModel::findOrFail($id);
    if ($order->status !== 'pending') {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Order sudah diproses, tidak bisa dihapus.');
      return;
    }
    $order->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Order sales berhasil dihapus.');
  }

  public function getStats(): array {
    $userId = Auth::id();
    return [
      'total'   => OrderSalesModel::where('id_user', $userId)->count(),
      'pending' => OrderSalesModel::where('id_user', $userId)->where('status', 'pending')->count(),
    ];
  }

  public function render() {
    $userId = Auth::id();

    $orders = OrderSalesModel::with(['barang', 'wilayah', 'sales'])
      ->where('id_user', $userId)
      ->when($this->search, function ($q) {
        $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%'));
      })
      ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
      ->orderByDesc('tanggal_order')
      ->paginate(10);

    $barangs   = Barang::orderBy('nama_barang')->get();
    $wilayahs  = Wilayah::where('id_user', $userId)->orWhereNull('id_user')->orderBy('nama_wilayah')->get();
    $salesList = Sales::orderBy('nama_sales')->get();

    return view('components.sales.order-sales', [
      'orders'       => $orders,
      'barangs'      => $barangs,
      'wilayahs'     => $wilayahs,
      'salesList'    => $salesList,
      'stats'        => $this->getStats(),
      'filterStatus' => $this->filterStatus,
    ]);
  }
}
