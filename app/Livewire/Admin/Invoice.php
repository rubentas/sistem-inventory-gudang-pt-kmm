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

  public string $search = '';

  public function updatedSearch(): void {
    $this->resetPage();
  }

  public function cetakPdf(int $id) {
    $order = OrderSales::with(['barang', 'wilayah', 'sales'])->findOrFail($id);
    $pdf   = Pdf::loadView('laporan.invoice', compact('order'))->setPaper('a4', 'portrait');
    return response()->stream(
      fn() => print($pdf->output()),
      200,
      ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="invoice-' . $order->no_invoice . '.pdf"']
    );
  }

  public function getStats(): array {
    return [
      'total' => OrderSales::whereNotNull('no_invoice')->count(),
    ];
  }

  public function render() {
    $invoices = OrderSales::with(['barang', 'wilayah', 'sales'])
      ->whereNotNull('no_invoice')
      ->when($this->search, function ($q) {
        $q->where('no_invoice', 'like', '%' . $this->search . '%')
          ->orWhere('nama_toko', 'like', '%' . $this->search . '%');
      })
      ->orderByDesc('id_order')
      ->paginate(10);

    return view('components.admin.invoice', [
      'invoices' => $invoices,
      'stats'    => $this->getStats(),
    ]);
  }
}
