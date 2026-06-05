<?php
namespace App\Livewire\Sales;

use App\Models\Barang;
use App\Models\OrderSales as OrderSalesModel;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.sales')]
class OrderSales extends Component {
  use WithPagination;

  // Form
  public int | string $id_barang  = '';
  public int | string $id_wilayah = '';
  public int | string $jumlah     = '';
  public string $tanggal_order    = '';
  public string $keterangan       = '';

  // Filter
  public string $search       = '';
  public string $filterStatus = '';

  // UI
  public bool $isEdit       = false;
  public int | null $editId = null;

  protected $rules = [
    'id_barang'     => 'required|exists:barangs,id_barang',
    'id_wilayah'    => 'required|exists:wilayahs,id_wilayah',
    'jumlah'        => 'required|integer|min:1',
    'tanggal_order' => 'required|date',
    'keterangan'    => 'nullable|string',
  ];

  protected $messages = [
    'id_barang.required'     => 'Pilih barang terlebih dahulu.',
    'id_wilayah.required'    => 'Pilih wilayah terlebih dahulu.',
    'jumlah.required'        => 'Jumlah wajib diisi.',
    'jumlah.min'             => 'Jumlah minimal 1.',
    'tanggal_order.required' => 'Tanggal order wajib diisi.',
  ];

  public function mount(): void {
    $this->tanggal_order = now()->format('Y-m-d');
  }

  public function updatedSearch(): void {$this->resetPage();}
  public function updatedFilterStatus(): void {$this->resetPage();}

  public function resetFilters(): void {
    $this->search       = '';
    $this->filterStatus = '';
    $this->resetPage();
  }

  public function resetForm(): void {
    $this->reset(['id_barang', 'id_wilayah', 'jumlah', 'tanggal_order', 'keterangan', 'editId', 'isEdit']);
    $this->tanggal_order = now()->format('Y-m-d');
    $this->resetErrorBag();
  }

  public function openAddModal(): void {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function edit(int $id): void {
    $order = OrderSalesModel::findOrFail($id);
    if ($order->status !== 'pending') {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Order yang sudah diproses tidak bisa diubah.');
      return;
    }
    $this->editId        = $order->id_order;
    $this->id_barang     = $order->id_barang;
    $this->id_wilayah    = $order->id_wilayah;
    $this->jumlah        = $order->jumlah;
    $this->tanggal_order = $order->tanggal_order->format('Y-m-d');
    $this->keterangan    = $order->keterangan ?? '';
    $this->isEdit        = true;
    $this->resetErrorBag();
    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $this->validate();

    $stok = \App\Models\Stok::where('id_barang', $this->id_barang)->first();
    if (! $stok || $stok->jumlah_stok < $this->jumlah) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Stok tidak mencukupi! Stok saat ini: ' . number_format($stok ? $stok->jumlah_stok : 0));
      return;
    }

    $data = [
      'id_barang'     => $this->id_barang,
      'id_user'       => Auth::id(),
      'id_wilayah'    => $this->id_wilayah,
      'jumlah'        => $this->jumlah,
      'tanggal_order' => $this->tanggal_order,
      'status'        => 'pending',
      'keterangan'    => $this->keterangan,
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

  public function update(): void {$this->simpan();}

  public function hapus(int $id): void {
    $order = OrderSalesModel::findOrFail($id);
    if ($order->status !== 'pending') {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Order yang sudah diproses tidak bisa dihapus.');
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

    $orders = OrderSalesModel::with(['barang', 'wilayah'])
      ->where('id_user', $userId)
      ->when($this->search, function ($q) {
        $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%'));
      })
      ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
      ->orderByDesc('tanggal_order')
      ->paginate(10);

    $barangs  = Barang::orderBy('nama_barang')->get();
    $wilayahs = Wilayah::where('id_user', $userId)->orWhereNull('id_user')->orderBy('nama_wilayah')->get();

    return view('components.sales.order-sales', [
      'orders'       => $orders,
      'barangs'      => $barangs,
      'wilayahs'     => $wilayahs,
      'stats'        => $this->getStats(),
      'filterStatus' => $this->filterStatus,
    ]);
  }
}
