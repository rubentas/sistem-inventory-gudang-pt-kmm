<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\OrderSales as OrderSalesModel;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Auth;
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
  public int | null $id_order     = null;
  public int | string $id_barang  = '';
  public int | string $id_wilayah = '';
  public int | string $jumlah     = '';
  public string $tanggal_order    = '';
  public string $status           = 'pending';
  public string $keterangan       = '';

  // UI state
  public bool $isEdit = false;

  protected $rules = [
    'id_barang'     => 'required|exists:barangs,id_barang',
    'id_wilayah'    => 'required|exists:wilayahs,id_wilayah',
    'jumlah'        => 'required|integer|min:1',
    'tanggal_order' => 'required|date',
    'status'        => 'required|in:pending,diproses,selesai',
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
    $this->reset([
      'id_order', 'id_barang', 'id_wilayah', 'jumlah',
      'tanggal_order', 'status', 'keterangan', 'isEdit',
    ]);
    $this->tanggal_order = now()->format('Y-m-d');
    $this->status        = 'pending';
    $this->resetErrorBag();
  }

  public function openAddModal(): void {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function edit(int $id): void {
    $order               = OrderSalesModel::findOrFail($id);
    $this->id_order      = $order->id_order;
    $this->id_barang     = $order->id_barang;
    $this->id_wilayah    = $order->id_wilayah;
    $this->jumlah        = $order->jumlah;
    $this->tanggal_order = $order->tanggal_order->format('Y-m-d');
    $this->status        = $order->status;
    $this->keterangan    = $order->keterangan ?? '';
    $this->isEdit        = true;
    $this->resetErrorBag();
    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $this->validate();

    $data = [
      'id_barang'     => $this->id_barang,
      'id_user'       => Auth::id(),
      'id_wilayah'    => $this->id_wilayah,
      'jumlah'        => $this->jumlah,
      'tanggal_order' => $this->tanggal_order,
      'status'        => $this->status,
      'keterangan'    => $this->keterangan,
    ];

    if ($this->isEdit) {
      $order = OrderSalesModel::findOrFail($this->id_order);
      if ($order->status !== 'selesai' && $this->status === 'selesai') {
        $stok = \App\Models\Stok::where('id_barang', $this->id_barang)->first();
        if (! $stok || $stok->jumlah_stok < $this->jumlah) {
          $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Stok tidak mencukupi untuk menyelesaikan order ini.');
          return;
        }
      }
      $order->update($data);
      $message = 'Order sales berhasil diperbarui.';
    } else {
      OrderSalesModel::create($data);
      $message = 'Order sales berhasil ditambahkan.';
    }

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: $message);
  }

  public function update(): void {$this->simpan();}

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