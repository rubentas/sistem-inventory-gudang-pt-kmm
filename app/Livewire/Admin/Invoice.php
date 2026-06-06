<?php
namespace App\Livewire\Admin;

use App\Models\OrderSales;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;

class Invoice extends Component {
  use WithPagination;

  public $search       = '';
  public $filterStatus = '';

  public function resetFilters() {
    $this->search       = '';
    $this->filterStatus = '';
    $this->resetPage();
  }

  public function generateInvoice($id) {
    $order = OrderSales::with(['barang', 'wilayah', 'user'])->findOrFail($id);

    // Generate nomor invoice otomatis
    if (! $order->no_invoice) {
      $order->no_invoice = 'INV/' . date('Ymd') . '/' . str_pad($order->id_order, 5, '0', STR_PAD_LEFT);
      $order->save();
    }

    $pdf = Pdf::loadView('laporan.invoice', compact('order'))->setPaper('a4', 'portrait');
    return $pdf->download('invoice-' . $order->no_invoice . '.pdf');
  }

  public function render() {
    $invoices = OrderSales::with(['barang', 'wilayah', 'user'])
      ->whereNotNull('no_invoice')
      ->when($this->search, function ($query) {
        $query->where('no_invoice', 'like', '%' . $this->search . '%')
          ->orWhere('nama_toko', 'like', '%' . $this->search . '%');
      })
      ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
      ->orderByDesc('id_order')
      ->paginate(10);

    $pendingInvoices = OrderSales::whereNull('no_invoice')->count();

    return view('components.admin.invoice', compact('invoices', 'pendingInvoices'))
      ->layout('layouts.admin');
  }
}