<?php
namespace App\Livewire\Admin;

use App\Models\BarangKeluar as BarangKeluarModel;
use App\Models\OrderSales;
use App\Models\Stok;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class BarangKeluar extends Component {
  use WithPagination;

  // Filter
  public string $search        = '';
  public string $filterDate    = '';
  public string $filterWilayah = '';
  public string $filterType    = '';

  // Form
  public int | string $id_order   = '';
  public int | string $id_barang  = '';
  public int | string $id_wilayah = '';
  public int | string $jumlah     = '';
  public string $tanggal_keluar   = '';
  public string $keterangan       = '';

  // Display
  public string $nama_barang_display  = '';
  public string $satuan_display       = '';
  public string $order_status_display = '';
  public string $nama_sales_display = '';

  // UI state
  public bool $isEdit       = false;
  public int | null $editId = null;

  protected $rules = [
    'id_order'       => 'required|exists:order_sales,id_order',
    'id_barang'      => 'required|exists:barangs,id_barang',
    'id_wilayah'     => 'required|exists:wilayahs,id_wilayah',
    'jumlah'         => 'required|integer|min:1',
    'tanggal_keluar' => 'required|date',
    'keterangan'     => 'nullable|string',
  ];

  protected $messages = [
    'id_order.required'       => 'Pilih order sales terlebih dahulu.',
    'jumlah.required'         => 'Jumlah wajib diisi.',
    'jumlah.min'              => 'Jumlah minimal 1.',
    'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
  ];

  public function mount(): void {
    $this->tanggal_keluar = now()->format('Y-m-d');
  }

  public function updatedSearch(): void {
    $this->resetPage();
  }

  public function updatedFilterDate(): void {
    $this->resetPage();
  }

  public function updatedFilterWilayah(): void {
    $this->resetPage();
  }

  public function setFilter(string $type): void {
    $this->filterType = $type;

    switch ($type) {
    case 'today':
      $this->filterDate = now()->format('Y-m-d');
      break;
    case 'week':
      $this->filterDate = now()->format('Y-m-d');
      break;
    case 'month':
      $this->filterDate = now()->startOfMonth()->format('Y-m-d');
      break;
    default:
      $this->filterType = 'custom';
      break;
    }

    $this->resetPage();
  }

  public function resetFilters(): void {
    $this->search        = '';
    $this->filterDate    = '';
    $this->filterWilayah = '';
    $this->filterType    = '';
    $this->resetPage();
  }

  public function resetForm(): void
{
    $this->reset([
        'id_order',
        'id_barang',
        'id_wilayah',
        'jumlah',
        'keterangan',
        'nama_barang_display',
        'satuan_display',
        'order_status_display',
        'nama_sales_display',
        'editId',
        'isEdit',
    ]);
    $this->tanggal_keluar = now()->format('Y-m-d');
    $this->resetErrorBag();
}

  public function updatedIdOrder($value): void
{
    if ($value) {
        $order = OrderSales::with(['barang', 'wilayah', 'sales'])->find($value);
        if ($order) {
            $this->id_barang            = $order->id_barang;
            $this->id_wilayah           = $order->id_wilayah;
            $this->jumlah               = $order->jumlah;
            $this->nama_barang_display  = $order->barang->nama_barang ?? '';
            $this->satuan_display       = $order->barang->satuan ?? '';
            $this->order_status_display = $order->status;
            $this->nama_sales_display   = $order->sales->nama_sales ?? '—';
        }
    } else {
        $this->reset(['id_barang', 'id_wilayah', 'jumlah', 'nama_barang_display', 'satuan_display', 'order_status_display', 'nama_sales_display']);
    }
}
  public function openAddModal(): void {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $this->validate();

    $stok = Stok::where('id_barang', $this->id_barang)->first();
    if (! $stok || $stok->jumlah_stok < $this->jumlah) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Stok tidak mencukupi!');
      return;
    }

    BarangKeluarModel::create([
      'id_barang'      => $this->id_barang,
      'id_order'       => $this->id_order,
      'id_user'        => Auth::id(),
      'id_wilayah'     => $this->id_wilayah,
      'jumlah'         => $this->jumlah,
      'tanggal_keluar' => $this->tanggal_keluar,
      'keterangan'     => $this->keterangan,
    ]);

    $order = OrderSales::find($this->id_order);
    if ($order && ! $order->no_invoice) {
      $order->update([
        'no_invoice' => 'INV/' . date('Ymd') . '/' . str_pad($order->id_order, 5, '0', STR_PAD_LEFT),
      ]);
    }

    $stok->decrement('jumlah_stok', (int) $this->jumlah);
    $stok->updated_at = now();
    $stok->save();

    if ($this->id_order) {
      OrderSales::find($this->id_order)?->update(['status' => 'selesai']);
    }

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Barang keluar berhasil disimpan. Stok telah diperbarui.');
  }

  public function hapus(int $id): void {
    $bk = BarangKeluarModel::findOrFail($id);

    $stok = Stok::where('id_barang', $bk->id_barang)->first();
    if ($stok) {
      $stok->increment('jumlah_stok', (int) $bk->jumlah);
      $stok->updated_at = now();
      $stok->save();
    }

    if ($bk->id_order) {
      OrderSales::find($bk->id_order)?->update(['status' => 'diproses']);
    }

    $bk->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data barang keluar berhasil dihapus.');
  }

  public function getStats(): array {
    return [
      'totalItems' => BarangKeluarModel::count(),
      'thisMonth'  => BarangKeluarModel::whereMonth('tanggal_keluar', now()->month)
        ->whereYear('tanggal_keluar', now()->year)
        ->count(),
    ];
  }

  public function render() {
    $barangKeluar = BarangKeluarModel::with(['barang', 'order.sales', 'user', 'wilayah'])
      ->when($this->search, function ($query) {
        $query->where(function ($sub) {
          $sub->whereHas('barang', function ($q) {
            $q->where('nama_barang', 'like', '%' . $this->search . '%')
              ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
          })
            ->orWhereHas('wilayah', function ($q) {
              $q->where('nama_wilayah', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('order.sales', function ($q) {
              $q->where('nama_sales', 'like', '%' . $this->search . '%');
            });
        });
      })
      ->when($this->filterDate, function ($query) {
        $date = Carbon::parse($this->filterDate);

        switch ($this->filterType) {
        case 'week':
          return $query->whereBetween('tanggal_keluar', [
            now()->subDays(6)->startOfDay(),
            now()->endOfDay(),
          ]);
        case 'month':
          return $query->whereBetween('tanggal_keluar', [
            $date->copy()->startOfMonth()->startOfDay(),
            $date->copy()->endOfMonth()->endOfDay(),
          ]);
        case 'today':
        default:
          return $query->whereDate('tanggal_keluar', $this->filterDate);
        }
      })
      ->when($this->filterWilayah, function ($query) {
        $query->where('id_wilayah', $this->filterWilayah);
      })
      ->orderByDesc('tanggal_keluar')
      ->orderByDesc('id_keluar')
      ->paginate(10);

    $orders = OrderSales::with(['barang', 'wilayah', 'sales'])
      ->where('status', '!=', 'selesai')
      ->orderByDesc('tanggal_order')
      ->get();

    $wilayahs = \App\Models\Wilayah::orderBy('nama_wilayah')->get();

    return view('components.admin.barang-keluar', [
      'barangKeluar'  => $barangKeluar,
      'orders'        => $orders,
      'wilayahs'      => $wilayahs,
      'stats'         => $this->getStats(),
      'filterDate'    => $this->filterDate,
      'filterWilayah' => $this->filterWilayah,
      'filterType'    => $this->filterType,
    ]);
  }
}
