<?php
namespace App\Livewire\Admin;

use App\Models\OrderSales;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Invoice extends Component {
  use WithPagination;

  public string $search       = '';
  public string $filterStatus = '';

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

  public function cetakPdf(int $id) {
    $order = OrderSales::with(['barang', 'wilayah', 'sales'])->findOrFail($id);
    $pdf   = Pdf::loadView('laporan.invoice', compact('order'))->setPaper('a4', 'portrait');
    return response()->streamDownload(
      fn() => print($pdf->output()),
      'invoice-' . str_replace('/', '-', $order->no_invoice) . '.pdf'
    );
  }

  public function getStats(): array {
    return [
      'total'   => OrderSales::whereNotNull('no_invoice')->count(),
      'lunas'   => OrderSales::whereNotNull('no_invoice')->where('status_pembayaran', 'lunas')->count(),
      'dicicil' => OrderSales::whereNotNull('no_invoice')->where('status_pembayaran', 'dicicil')->count(),
    ];
  }

  public function render() {
    $invoices = OrderSales::with(['barang', 'wilayah', 'sales'])
      ->whereNotNull('no_invoice')
      ->when($this->search, function ($q) {
        $q->where('no_invoice', 'like', '%' . $this->search . '%')
          ->orWhere('nama_toko', 'like', '%' . $this->search . '%');
      })
      ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
      ->orderByDesc('id_order')
      ->paginate(10);

    $pendingInvoices = OrderSales::whereNull('no_invoice')->count();

    return view('components.admin.invoice', [
      'invoices'        => $invoices,
      'pendingInvoices' => $pendingInvoices,
      'stats'           => $this->getStats(),
      'filterStatus'    => $this->filterStatus,
    ]);
  }

  public function lunasi(int $id): void {
    $order = OrderSales::findOrFail($id);
    $order->update(['status_pembayaran' => 'lunas']);
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Pembayaran berhasil dilunasi.');
  }
}
