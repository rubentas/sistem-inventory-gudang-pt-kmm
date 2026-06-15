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

  // Form (cuma status & keterangan)
  public $id_order          = null;
  public string $status     = 'pending';
  public string $keterangan = '';

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
    $this->reset(['id_order', 'status', 'keterangan']);
    $this->status = 'pending';
    $this->resetErrorBag();
  }

  public function edit(int $id): void {
    $order            = OrderSalesModel::findOrFail($id);
    $this->id_order   = $order->id_order;
    $this->status     = $order->status;
    $this->keterangan = $order->keterangan ?? '';
    $this->resetErrorBag();
    $this->dispatch('openModal');
  }

  public function update(): void {
    $this->validate([
      'status' => 'required|in:pending,diproses,selesai',
    ]);

    $order = OrderSalesModel::findOrFail($this->id_order);

    // JANGAN biarkan admin ubah status jadi "selesai" langsung
    if ($this->status === 'selesai') {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Status "Selesai" hanya bisa diubah melalui proses Barang Keluar.');
      return;
    }

    $order->update([
      'status'     => $this->status,
      'keterangan' => $this->keterangan,
    ]);

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Status order berhasil diperbarui.');
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