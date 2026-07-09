<?php
namespace App\Livewire\Sales;

use App\Models\DetailReturPenjualan;
use App\Models\OrderSales;
use App\Models\ReturPenjualan;
use App\Models\Sales;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sales')]
class ReturBarang extends Component {
  public ?int $id_order        = null;
  public string $tanggal_retur = '';
  public array $detail         = [];
  public array $orderList      = [];
  public ?string $nama_toko    = null;
  public ?string $no_invoice   = null;

  public function mount(): void {
    $this->tanggal_retur = now()->format('Y-m-d');
  }

  public function updatedIdOrder($value): void {
    if (! $value) {
      return;
    }

    $sales = Sales::where('id_user', Auth::id())->first();
    if (! $sales) {
      return;
    }

    $order = OrderSales::with('barang')
      ->where('id_sales', $sales->id_sales)
      ->find($value);

    if (! $order) {
      return;
    }

    $this->nama_toko  = $order->nama_toko ?: '(tidak diisi)';
    $this->no_invoice = $order->no_invoice;
    $this->detail     = [[
      'id_barang'      => $order->id_barang,
      'nama_barang'    => $order->barang->nama_barang ?? '',
      'jumlah_order'   => $order->jumlah,
      'jumlah_retur'   => 0,
      'harga_satuan'   => ($order->harga_satuan > 0 ? $order->harga_satuan : ($order->barang->harga_jual_default ?? 0)),
      'subtotal_retur' => 0,
      'alasan'         => '',
      'keterangan'     => '',
    ]];
  }

  public function updatedDetail($value, $key): void {
    $parts = explode('.', $key);
    $index = $parts[0] ?? 0;
    $field = $parts[1] ?? '';

    if (($field === 'jumlah_retur' || $field === 'harga_satuan') && isset($this->detail[$index])) {
      $jml                                    = (int) ($this->detail[$index]['jumlah_retur'] ?? 0);
      $hrg                                    = (float) ($this->detail[$index]['harga_satuan'] ?? 0);
      $this->detail[$index]['subtotal_retur'] = $jml * $hrg;
    }
  }

  public function generateNoRetur(): string {
    $prefix = 'RET/' . now()->format('Ymd') . '/';
    $last   = ReturPenjualan::where('no_retur', 'like', $prefix . '%')->latest('id_retur')->first();
    $num    = $last ? (int) substr($last->no_retur, -5) + 1 : 1;
    return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
  }

  public function ajukan(): void {
    $this->validate([
      'id_order'              => 'required|exists:order_sales,id_order',
      'tanggal_retur'         => 'required|date',
      'detail'                => 'required|array|min:1',
      'detail.*.jumlah_retur' => 'required|integer|min:1',
      'detail.*.alasan'       => 'required|string',
    ], [
      'detail.*.jumlah_retur.min' => 'Jumlah retur minimal 1.',
      'detail.*.alasan.required'  => 'Alasan retur wajib diisi.',
    ]);

    $retur = ReturPenjualan::create([
      'no_retur'      => $this->generateNoRetur(),
      'id_order'      => $this->id_order,
      'id_user'       => Auth::id(),
      'tanggal_retur' => $this->tanggal_retur,
      'status'        => 'Pengajuan',
    ]);

    foreach ($this->detail as $d) {
      DetailReturPenjualan::create([
        'id_retur'       => $retur->id_retur,
        'id_barang'      => $d['id_barang'],
        'jumlah_retur'   => $d['jumlah_retur'],
        'harga_satuan'   => $d['harga_satuan'] ?? 0,
        'subtotal_retur' => ($d['jumlah_retur'] ?? 0) * ($d['harga_satuan'] ?? 0),
        'alasan'         => $d['alasan'],
        'kondisi_barang' => 'Bagus',
        'tujuan'         => 'Stok Utama',
        'keterangan'     => $d['keterangan'] ?? null,
      ]);
    }

    $this->reset(['id_order', 'detail', 'nama_toko', 'no_invoice']);
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Pengajuan retur berhasil dikirim.');
  }

  public function render() {
    $sales = Sales::where('id_user', Auth::id())->first();
    if ($sales) {
      $this->orderList = OrderSales::where('id_sales', $sales->id_sales)
        ->whereIn('status', ['selesai', 'diproses'])
        ->with('barang')
        ->orderByDesc('tanggal_order')
        ->get()
        ->toArray();
    }

    $pengajuan = ReturPenjualan::with('detailRetur.barang')
      ->where('id_user', Auth::id())
      ->orderByDesc('created_at')
      ->get();

    return view('components.sales.retur-barang', [
      'pengajuan' => $pengajuan,
    ]);
  }
}
