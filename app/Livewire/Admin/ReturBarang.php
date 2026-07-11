<?php
namespace App\Livewire\Admin;

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
  public bool $isEdit   = false;
  public bool $editMode = false;

  // Tolak
  public bool $tolakMode              = false;
  public string $alasan_tolak         = '';
  public string $alasan_tolak_lainnya = '';

  public function mount(): void {
    $this->tanggal_retur = now()->format('Y-m-d');
  }

  public function updatedSearch(): void {$this->resetPage();}
  public function updatedFilterStatus(): void {$this->resetPage();}

  public function resetFilters(): void {
    $this->search       = '';
    $this->filterStatus = '';
    $this->resetPage();
  }

  public function getSisaRetur($id_order, $excludeReturId = null): int {
    $order = OrderSales::find($id_order);
    if (! $order) {
      return 0;
    }

    $totalRetur = DetailReturPenjualan::whereHas('retur', function ($q) use ($id_order, $excludeReturId) {
      $q->where('id_order', $id_order)->where('status', '!=', 'Ditolak');
      if ($excludeReturId) {
        $q->where('id_retur', '!=', $excludeReturId);
      }

    })->sum('jumlah_retur');

    return max(0, $order->jumlah - $totalRetur);
  }

  public function updatedIdOrder($value): void {
    if ($value) {
      $order           = OrderSales::with('sales', 'barang')->find($value);
      $this->nama_toko = $order->nama_toko ?? '-';
      $this->id_sales  = $order->id_sales;
      $this->detail    = [[
        'id_barang'      => $order->id_barang,
        'nama_barang'    => $order->barang->nama_barang ?? '',
        'jumlah_order'   => $order->jumlah,
        'jumlah_retur'   => 0,
        'sisa_retur'     => $this->getSisaRetur($value),
        'harga_satuan'   => ($order->harga_satuan > 0 ? $order->harga_satuan : ($order->barang->harga_jual_default ?? 0)),
        'subtotal_retur' => 0,
        'alasan'         => '',
        'kondisi_barang' => 'Bagus',
        'tujuan'         => 'Stok Utama',
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

  public function editRetur(int $id): void {
    $retur = ReturPenjualan::with('detailRetur.barang', 'order.sales')->findOrFail($id);

    if (in_array($retur->status, ['Selesai', 'Ditolak'])) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Retur sudah selesai/ditolak, tidak bisa diedit.');
      return;
    }

    $this->id_retur  = $retur->id_retur;
    $this->id_order  = $retur->id_order;
    $this->nama_toko = $retur->order->nama_toko ?? '-';
    $this->id_sales  = $retur->order->id_sales;
    $this->editMode  = true;

    $this->detail = $retur->detailRetur->map(function ($d) use ($retur) {
      return [
        'id_detail_retur' => $d->id_detail_retur,
        'id_barang'       => $d->id_barang,
        'nama_barang'     => $d->barang->nama_barang ?? '',
        'jumlah_order'    => $retur->order->jumlah,
        'jumlah_retur'    => $d->jumlah_retur,
        'sisa_retur'      => $this->getSisaRetur($retur->id_order, $retur->id_retur) + $d->jumlah_retur,
        'harga_satuan'    => $d->harga_satuan,
        'subtotal_retur'  => $d->subtotal_retur,
        'alasan'          => $d->alasan,
        'kondisi_barang'  => $d->kondisi_barang,
        'tujuan'          => $d->tujuan ?? 'Stok Utama',
        'keterangan'      => $d->keterangan ?? '',
      ];
    })->toArray();

    $this->dispatch('openModal');
  }

  public function generateNoRetur(): string {
    $prefix = 'RET/' . now()->format('Ymd') . '/';
    $last   = ReturPenjualan::where('no_retur', 'like', $prefix . '%')->latest('id_retur')->first();
    $num    = $last ? (int) substr($last->no_retur, -5) + 1 : 1;
    return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
  }

  public function simpan(): void {
    if ($this->editMode) {$this->updateRetur();return;}

    $order = OrderSales::find($this->id_order);
    if ($order) {
      foreach ($this->detail as $index => $d) {
        $sisa = $this->getSisaRetur($this->id_order);
        if (($d['jumlah_retur'] ?? 0) > $sisa) {
          $this->addError("detail.{$index}.jumlah_retur", "Jumlah retur maksimal {$sisa} (order {$order->jumlah} - total retur sebelumnya).");
          return;
        }
      }
    }

    $this->validate([
      'id_order'                => 'required|exists:order_sales,id_order',
      'tanggal_retur'           => 'required|date',
      'detail'                  => 'required|array|min:1',
      'detail.*.id_barang'      => 'required|exists:barangs,id_barang',
      'detail.*.jumlah_retur'   => 'required|integer|min:1',
      'detail.*.alasan'         => 'required|string',
      'detail.*.kondisi_barang' => 'required|in:Bagus,Rusak',
      'detail.*.tujuan'         => 'required|in:Stok Utama,Dimusnahkan,Gudang Pusat,Supplier',
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
          'kondisi_barang' => $d['kondisi_barang'],
          'tujuan'         => $d['tujuan'] ?? 'Stok Utama',
          'keterangan'     => $d['keterangan'] ?? null,
        ]);
      }

      DB::commit();
      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Retur penjualan berhasil disimpan.');
      $this->resetForm();
    } catch (\Throwable $e) {
      DB::rollBack();
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }

  public function updateRetur(): void {
    $this->validate([
      'detail'                  => 'required|array|min:1',
      'detail.*.jumlah_retur'   => 'required|integer|min:1',
      'detail.*.alasan'         => 'required|string',
      'detail.*.kondisi_barang' => 'required|in:Bagus,Rusak',
      'detail.*.tujuan'         => 'required|in:Stok Utama,Dimusnahkan,Gudang Pusat,Supplier',
    ], [
      'detail.*.jumlah_retur.min' => 'Jumlah retur minimal 1.',
      'detail.*.alasan.required'  => 'Alasan retur wajib diisi.',
    ]);

    DB::beginTransaction();
    try {
      foreach ($this->detail as $d) {
        DetailReturPenjualan::where('id_detail_retur', $d['id_detail_retur'] ?? 0)
          ->update([
            'jumlah_retur'   => $d['jumlah_retur'],
            'harga_satuan'   => $d['harga_satuan'],
            'subtotal_retur' => ($d['jumlah_retur'] ?? 0) * ($d['harga_satuan'] ?? 0),
            'alasan'         => $d['alasan'],
            'kondisi_barang' => $d['kondisi_barang'],
            'tujuan'         => $d['tujuan'],
            'keterangan'     => $d['keterangan'] ?? null,
          ]);
      }
      DB::commit();
      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Retur penjualan berhasil diperbarui.');
      $this->resetForm();
    } catch (\Throwable $e) {
      DB::rollBack();
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }

  public function approve(int $id): void {
    $retur = ReturPenjualan::with('detailRetur')->findOrFail($id);

    $nextStatus = match ($retur->status) {
      'Pengajuan'  => 'Cek Gudang',
      'Cek Gudang' => 'Cek Kasir',
      'Cek Kasir'  => 'Disetujui',
      'Disetujui'  => 'Selesai',
      default      => null,
    };

    if (! $nextStatus) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Retur sudah selesai.');
      return;
    }

    if ($nextStatus === 'Selesai') {
      foreach ($retur->detailRetur as $d) {
        $stok = Stok::where('id_barang', $d->id_barang)->first();
        if ($stok) {
          if ($d->tujuan === 'Stok Utama' && $d->kondisi_barang === 'Bagus') {
            $stok->jumlah_stok += $d->jumlah_retur;
          } elseif ($d->tujuan === 'Stok Utama' && $d->kondisi_barang === 'Rusak') {
            $stok->stok_rusak += $d->jumlah_retur;
          }
          $stok->save();
        }
      }
    }

    $retur->status = $nextStatus;
    $retur->save();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Status retur: ' . $nextStatus);
  }

  public function konfirmasiTolak(int $id): void {
    $retur = ReturPenjualan::findOrFail($id);
    if (in_array($retur->status, ['Selesai', 'Ditolak'])) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Retur sudah selesai/ditolak.');
      return;
    }
    $this->id_retur             = $id;
    $this->tolakMode            = true;
    $this->alasan_tolak         = '';
    $this->alasan_tolak_lainnya = '';
    $this->dispatch('openTolakModal');
  }

  public function tolak(): void {
    $this->validate([
      'alasan_tolak'         => 'required|string',
      'alasan_tolak_lainnya' => 'nullable|string|max:255',
    ], [
      'alasan_tolak.required' => 'Alasan penolakan wajib dipilih.',
    ]);

    $retur       = ReturPenjualan::findOrFail($this->id_retur);
    $alasanFinal = $this->alasan_tolak;
    if ($this->alasan_tolak === 'Lainnya' && $this->alasan_tolak_lainnya) {
      $alasanFinal = $this->alasan_tolak_lainnya;
    }

    $retur->status           = 'Ditolak';
    $retur->keterangan_tolak = $alasanFinal;
    $retur->save();

    $this->tolakMode            = false;
    $this->id_retur             = null;
    $this->alasan_tolak         = '';
    $this->alasan_tolak_lainnya = '';

    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Retur berhasil ditolak.');
    $this->dispatch('closeTolakModal');
  }

  public function resetForm(): void {
    $this->reset(['id_retur', 'id_order', 'detail', 'nama_toko', 'id_sales', 'isEdit', 'editMode', 'tolakMode', 'alasan_tolak', 'alasan_tolak_lainnya']);
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

    return view('components.admin.retur-barang', ['returs' => $returs]);
  }

  public function cetakPdf() {
    return redirect()->route('admin.retur-barang.pdf', [
      'search'       => $this->search,
      'filterStatus' => $this->filterStatus,
    ]);
  }
}
