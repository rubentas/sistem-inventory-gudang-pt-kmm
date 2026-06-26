<?php
namespace App\Livewire\Admin;

use App\Models\BarangMasuk;
use App\Models\OrderSales;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Invoice extends Component {
  use WithPagination;

  public string $search      = '';
  public string $filterJenis = ''; // 'masuk', 'keluar'

  public function updatedSearch(): void {$this->resetPage();}
  public function updatedFilterJenis(): void {$this->resetPage();}

  public function cetakPdfMasuk(int $id) {
    $item = BarangMasuk::with(['barang', 'supplier', 'user'])->findOrFail($id);
    $pdf  = Pdf::loadView('laporan.invoice-masuk', compact('item'))->setPaper('a4', 'portrait');
    return response()->stream(
      fn() => print($pdf->output()),
      200,
      ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="' . $item->no_invoice . '.pdf"']
    );
  }

  public function cetakPdfKeluar(int $id) {
    $order = OrderSales::with(['barang', 'wilayah', 'sales'])->findOrFail($id);
    $pdf   = Pdf::loadView('laporan.invoice', compact('order'))->setPaper('a4', 'portrait');
    return response()->stream(
      fn() => print($pdf->output()),
      200,
      ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="' . $order->no_invoice . '.pdf"']
    );
  }

  public function getStats(): array {
    return [
      'total'        => OrderSales::whereNotNull('no_invoice')->count() + BarangMasuk::whereNotNull('no_invoice')->count(),
      'total_masuk'  => BarangMasuk::whereNotNull('no_invoice')->count(),
      'total_keluar' => OrderSales::whereNotNull('no_invoice')->count(),
    ];
  }

  public function render() {
    // Invoice Barang Masuk
    $masuk = BarangMasuk::with(['barang', 'supplier', 'user'])
      ->whereNotNull('no_invoice')
      ->when($this->search, fn($q) => $q->where('no_invoice', 'like', '%' . $this->search . '%'))
      ->orderByDesc('id_masuk')
      ->get()
      ->map(fn($item) => [
        'id'         => $item->id_masuk,
        'no_invoice' => $item->no_invoice,
        'tanggal'    => $item->tanggal_masuk,
        'toko'       => $item->supplier->nama_supplier ?? '—',
        'sales'      => $item->user->nama ?? '—',
        'barang'     => $item->barang->nama_barang ?? '—',
        'satuan'     => $item->barang->satuan ?? 'pcs',
        'jumlah'     => $item->jumlah,
        'total'      => 0,
        'jenis'      => 'masuk',
        'bukti'      => $item->bukti_pembayaran,
      ]);

    // Invoice Barang Keluar
    $keluar = OrderSales::with(['barang', 'wilayah', 'sales'])
      ->whereNotNull('no_invoice')
      ->when($this->search, fn($q) => $q->where('no_invoice', 'like', '%' . $this->search . '%'))
      ->orderByDesc('id_order')
      ->get()
      ->map(fn($item) => [
        'id'         => $item->id_order,
        'no_invoice' => $item->no_invoice,
        'tanggal'    => $item->tanggal_order,
        'toko'       => $item->nama_toko ?? '—',
        'sales'      => $item->sales->nama_sales ?? ($item->user->nama ?? '—'),
        'barang'     => $item->barang->nama_barang ?? '—',
        'satuan'     => $item->barang->satuan ?? 'pcs',
        'jumlah'     => $item->jumlah,
        'total'      => $item->subtotal,
        'jenis'      => 'keluar',
        'bukti'      => null,
      ]);

    // Gabung & filter
    $invoices = $masuk->concat($keluar)
      ->when($this->filterJenis, fn($c) => $c->where('jenis', $this->filterJenis))
      ->sortByDesc('tanggal')
      ->values();

    // Pagination manual
    $page      = request()->get('page', 1);
    $perPage   = 10;
    $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
      $invoices->forPage($page, $perPage),
      $invoices->count(),
      $perPage,
      $page,
      ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('components.admin.invoice', [
      'invoices' => $paginated,
      'stats'    => $this->getStats(),
    ]);
  }
}