<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\DetailReturPenjualan;
use App\Models\OrderSales;
use App\Models\ReturPenjualan;
use App\Models\Stok;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ReturBarang extends Component {
  use WithPagination;

  // Filter
  public string $search       = '';
  public string $filterStatus = '';

  // Form Header
  public ?int $id_retur        = null;
  public string $no_retur      = '';
  public ?int $id_order        = null;
  public string $tanggal_retur = '';
  public string $status        = 'Menunggu';

  // Form Detail
  public array $detail = [];

  // Dropdown data
  public array $orderList   = [];
  public ?string $nama_toko = null;
  public ?int $id_sales     = null;

  // UI
  public bool $isEdit = false;

  public function mount(): void {
    $this->tanggal_retur = now()->format('Y-m-d');
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

  public function updatedIdOrder($value): void {
    if ($value) {
      $order           = OrderSales::with('sales', 'barang')->find($value);
      $this->nama_toko = $order->nama_toko ?? '-';
      $this->id_sales  = $order->id_sales;
      // Pre-fill detail dengan barang dari order yang dipilih (1 order = 1 barang)
      $this->detail = [[
        'id_barang'      => $order->id_barang,
        'nama_barang'    => $order->barang->nama_barang ?? '',
        'jumlah_order'   => $order->jumlah,
        'jumlah_retur'   => 0,
        'harga_satuan'   => $order->harga_satuan ?? $order->harga_jual ?? 0,
        'subtotal_retur' => 0,
        'alasan'         => '',
        'kondisi_barang' => 'Bagus',
        'keterangan'     => '',
      ]];
    } else {
      $this->nama_toko = null;
      $this->id_sales  = null;
      $this->detail    = [];
    }
  }

  public function updatedDetail($value, $key): void {
    $parts = explode('.', $key);
    $index = $parts[0] ?? 0;
    $field = $parts[1] ?? '';

    if ($field === 'jumlah_retur' || $field === 'harga_satuan') {
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

  public function simpan(): void {
    $this->validate([
      'id_order'                => 'required|exists:order_sales,id_order',
      'tanggal_retur'           => 'required|date',
      'detail'                  => 'required|array|min:1',
      'detail.*.id_barang'      => 'required|exists:barangs,id_barang',
      'detail.*.jumlah_retur'   => 'required|integer|min:1',
      'detail.*.alasan'         => 'required|string',
      'detail.*.kondisi_barang' => 'required|in:Bagus,Rusak',
    ], [
      'id_order.required'         => 'Order asal wajib dipilih.',
      'detail.required'           => 'Minimal 1 barang diretur.',
      'detail.*.jumlah_retur.min' => 'Jumlah retur minimal 1.',
      'detail.*.alasan.required'  => 'Alasan retur wajib diisi.',
    ]);

    DB::beginTransaction();
    try {
      $retur = ReturPenjualan::create([
        'no_retur'      => $this->generateNoRetur(),
        'id_order'      => $this->id_order,
        'id_user'       => auth()->id(),
        'tanggal_retur' => $this->tanggal_retur,
        'status'        => 'Menunggu',
      ]);

      foreach ($this->detail as $d) {
        DetailReturPenjualan::create([
          'id_retur'       => $retur->id_retur,
          'id_barang'      => $d['id_barang'],
          'jumlah_retur'   => $d['jumlah_retur'],
          'harga_satuan'   => $d['harga_satuan'] ?? 0,
          'subtotal_retur' => ($d['jumlah_retur'] ?? 0) * ($d['harga_satuan'] ?? 0),
          'alasan'         => $d['alasan'],
          'kondisi_barang' => $d['kondisi_barang'],
          'keterangan'     => $d['keterangan'] ?? null,
        ]);
      }

      DB::commit();

      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Retur barang berhasil disimpan. Status: Menunggu.');
      $this->resetForm();
    } catch (\Throwable $e) {
      DB::rollBack();
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }

  public function approve(int $id): void {
    $retur = ReturPenjualan::with('detailRetur')->findOrFail($id);

    if ($retur->status !== 'Menunggu') {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Retur sudah diproses sebelumnya.');
      return;
    }

    DB::beginTransaction();
    try {
      foreach ($retur->detailRetur as $d) {
        $stok = Stok::where('id_barang', $d->id_barang)->first();

        if ($stok) {
          if ($d->kondisi_barang === 'Bagus') {
            $stok->jumlah_stok += $d->jumlah_retur;
          } else {
            $stok->stok_rusak += $d->jumlah_retur;
          }
          $stok->save();
        }
      }

      $retur->status = 'Selesai';
      $retur->save();

      DB::commit();

      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Retur disetujui & stok diperbarui.');
    } catch (\Throwable $e) {
      DB::rollBack();
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Error: ' . $e->getMessage());
    }
  }

  public function resetForm(): void {
    $this->reset(['id_retur', 'id_order', 'detail', 'nama_toko', 'id_sales', 'isEdit']);
    $this->tanggal_retur = now()->format('Y-m-d');
    $this->status        = 'Menunggu';
    $this->resetErrorBag();
  }

  public function render() {
    $this->orderList = OrderSales::whereIn('status', ['selesai', 'diproses'])
      ->with('barang')
      ->orderByDesc('tanggal_order')
      ->limit(200)
      ->get()
      ->toArray();

    $returs = ReturPenjualan::with(['detailRetur.barang', 'order', 'user'])
      ->when($this->search, fn($q) => $q->where('no_retur', 'like', '%' . $this->search . '%'))
      ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
      ->orderByDesc('created_at')
      ->paginate(15);

    return view('components.admin.retur-barang', [
      'returs' => $returs,
    ]);
  }

  public function cetakPdf() {
    return redirect()->route('admin.retur-barang.pdf', [
      'search'       => $this->search,
      'filterStatus' => $this->filterStatus,
    ]);
  }
}
