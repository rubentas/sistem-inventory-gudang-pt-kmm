<?php
namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Laporan;
use App\Models\OrderSales;
use App\Models\StockOpname;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\Wilayah;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller {
  private function catatLaporan(string $jenis, ?string $awal = null, ?string $akhir = null): void {
    Laporan::create([
      'id_user'       => Auth::id(),
      'jenis_laporan' => $jenis,
      'tanggal_awal'  => $awal,
      'tanggal_akhir' => $akhir,
      'tanggal_cetak' => Carbon::today(),
    ]);
  }

  private function formatTanggal($tanggal): string {
    if (! $tanggal) {
      return '-';
    }

    return Carbon::parse($tanggal)->translatedFormat('d F Y');
  }

  // Barang Masuk
  public function barangMasukPdf(Request $request) {
    $awal       = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $akhir      = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));
    $idSupplier = $request->input('id_supplier');

    $query = BarangMasuk::with(['barang', 'supplier', 'user'])
      ->whereBetween('tanggal_masuk', [$awal, $akhir]);

    // Filter supplier kalau ada
    if ($idSupplier) {
      $query->where('id_supplier', $idSupplier);
    }

    $data = $query->orderByDesc('tanggal_masuk')
      ->orderByDesc('id_masuk')
      ->get();

    // Ambil nama supplier untuk judul
    $namaSupplier = null;
    if ($idSupplier) {
      $supplier     = Supplier::find($idSupplier);
      $namaSupplier = $supplier?->nama_supplier;
    }

    $this->catatLaporan('barang_masuk', $awal, $akhir);

    $pdf = Pdf::loadView('laporan.barang-masuk', [
      'data'          => $data,
      'tanggal_awal'  => $this->formatTanggal($awal),
      'tanggal_akhir' => $this->formatTanggal($akhir),
      'total_jumlah'  => $data->sum('jumlah'),
      'dicetak_oleh'  => Auth::user()->nama ?? 'System',
      'tanggal_cetak' => $this->formatTanggal(Carbon::now()),
      'nama_supplier' => $namaSupplier,
    ])->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-barang-masuk-' . $awal . '-sd-' . $akhir . '.pdf');
  }

  // Barang Keluar
  public function barangKeluarPdf(Request $request) {
    $awal  = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $akhir = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));

    $data = BarangKeluar::with(['barang', 'wilayah', 'user'])
      ->whereBetween('tanggal_keluar', [$awal, $akhir])
      ->orderByDesc('tanggal_keluar')
      ->get();

    $this->catatLaporan('barang_keluar', $awal, $akhir);

    $pdf = Pdf::loadView('laporan.barang-keluar', [
      'data'          => $data,
      'tanggal_awal'  => $this->formatTanggal($awal),
      'tanggal_akhir' => $this->formatTanggal($akhir),
      'total_jumlah'  => $data->sum('jumlah'),
      'dicetak_oleh'  => Auth::user()->nama,
      'tanggal_cetak' => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-barang-keluar-' . $awal . '-sd-' . $akhir . '.pdf');
  }

  // Stok Barang
  public function stokPdf(Request $request) {
    $kategori = $request->input('kategori', '');
    $status   = $request->input('status', '');
    $search   = $request->input('search', '');

    $data = Stok::with('barang')
      ->when($kategori, function ($q) use ($kategori) {
        $q->whereHas('barang', fn($b) => $b->where('kategori', $kategori));
      })
      ->when($status === 'menipis', function ($q) {
        $q->whereColumn('jumlah_stok', '<=', 'stok_minimum');
      })
      ->when($status === 'aman', function ($q) {
        $q->whereColumn('jumlah_stok', '>', 'stok_minimum');
      })
      ->when($search, function ($q) use ($search) {
        $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $search . '%'));
      })
      ->orderBy('jumlah_stok', 'asc')
      ->get();

    $this->catatLaporan('stok_barang');

    $pdf = Pdf::loadView('laporan.stok-barang', [
      'data'          => $data,
      'total_stok'    => $data->sum('jumlah_stok'),
      'stok_menipis'  => $data->where('status', 'Menipis')->count(),
      'dicetak_oleh'  => Auth::user()->nama,
      'tanggal_cetak' => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-stok-barang-' . Carbon::today()->format('Y-m-d') . '.pdf');
  }

  // Stock Opname
  public function stockOpnamePdf(Request $request) {
    $awal  = $request->input('tanggal_awal', Carbon::now()->subMonths(3)->format('Y-m-d'));
    $akhir = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));

    $data = StockOpname::with(['barang', 'user'])
      ->whereBetween('tanggal_opname', [$awal, $akhir])
      ->orderByDesc('tanggal_opname')
      ->get();

    $this->catatLaporan('stock_opname', $awal, $akhir);

    $totalSelisih = $data->sum('selisih');

    $pdf = Pdf::loadView('laporan.stock-opname', [
      'data'              => $data,
      'tanggal_awal'      => $this->formatTanggal($awal),
      'tanggal_akhir'     => $this->formatTanggal($akhir),
      'total_selisih'     => $totalSelisih,
      'rata_rata_selisih' => $data->count() > 0 ? round($totalSelisih / $data->count(), 2) : 0,
      'dicetak_oleh'      => Auth::user()->nama,
      'tanggal_cetak'     => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-stock-opname-' . $awal . '-sd-' . $akhir . '.pdf');
  }

  // Order Sales
  public function orderSalesPdf(Request $request) {
    $awal   = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $akhir  = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));
    $status = $request->input('status');

    $data = OrderSales::with(['barang', 'wilayah', 'user'])
      ->whereBetween('tanggal_order', [$awal, $akhir])
      ->when($status, fn($q) => $q->where('status', $status))
      ->orderByDesc('tanggal_order')
      ->get();

    $this->catatLaporan('order_sales', $awal, $akhir);

    $pdf = Pdf::loadView('laporan.order-sales', [
      'data'           => $data,
      'tanggal_awal'   => $this->formatTanggal($awal),
      'tanggal_akhir'  => $this->formatTanggal($akhir),
      'total_jumlah'   => $data->sum('jumlah'),
      'total_pending'  => $data->where('status', 'pending')->count(),
      'total_diproses' => $data->where('status', 'diproses')->count(),
      'total_selesai'  => $data->where('status', 'selesai')->count(),
      'dicetak_oleh'   => Auth::user()->nama,
      'tanggal_cetak'  => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-order-sales-' . $awal . '-sd-' . $akhir . '.pdf');
  }

  // Supplier
  public function supplierPdf() {
    $data = Supplier::orderBy('kode_supplier')->get();

    $this->catatLaporan('supplier');

    $pdf = Pdf::loadView('laporan.supplier', [
      'data'           => $data,
      'total_supplier' => $data->count(),
      'dicetak_oleh'   => Auth::user()->nama,
      'tanggal_cetak'  => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('laporan-supplier-' . Carbon::today()->format('Y-m-d') . '.pdf');
  }

  // Wilayah
  public function wilayahPdf() {
    $data = Wilayah::with('sales')->orderBy('nama_wilayah')->get();

    $this->catatLaporan('wilayah');

    $pdf = Pdf::loadView('laporan.wilayah', [
      'data'          => $data,
      'total_toko'    => $data->sum('jumlah_toko'),
      'dicetak_oleh'  => Auth::user()->nama,
      'tanggal_cetak' => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('laporan-wilayah-' . Carbon::today()->format('Y-m-d') . '.pdf');
  }

  // Inventory
  public function inventoryPdf(Request $request) {
    $awal  = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $akhir = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));

    $stoks = Stok::with('barang')->get()->map(function ($stok) use ($awal, $akhir) {
      $stok->total_masuk = BarangMasuk::where('id_barang', $stok->id_barang)
        ->whereBetween('tanggal_masuk', [$awal, $akhir])->sum('jumlah');
      $stok->total_keluar = BarangKeluar::where('id_barang', $stok->id_barang)
        ->whereBetween('tanggal_keluar', [$awal, $akhir])->sum('jumlah');
      return $stok;
    });

    $this->catatLaporan('inventory', $awal, $akhir);

    $pdf = Pdf::loadView('laporan.inventory', [
      'stoks'                    => $stoks,
      'tanggal_awal'             => $this->formatTanggal($awal),
      'tanggal_akhir'            => $this->formatTanggal($akhir),
      'total_masuk_keseluruhan'  => $stoks->sum('total_masuk'),
      'total_keluar_keseluruhan' => $stoks->sum('total_keluar'),
      'total_stok_akhir'         => $stoks->sum('jumlah_stok'),
      'dicetak_oleh'             => Auth::user()->nama,
      'tanggal_cetak'            => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-inventory-' . $awal . '-sd-' . $akhir . '.pdf');
  }

  // Data barang
  public function dataBarangPdf() {
    $data = \App\Models\Barang::with('stok')->orderBy('kode_barang')->get();

    $this->catatLaporan('data_barang');

    $pdf = Pdf::loadView('laporan.data-barang', [
      'data'          => $data,
      'dicetak_oleh'  => Auth::user()->nama,
      'tanggal_cetak' => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'landscape');

    return $pdf->stream('data-barang-' . Carbon::today()->format('Y-m-d') . '.pdf');
  }

  public function omzetPdf(Request $request) {
    $bulan = $request->get('bulan', date('m'));
    $tahun = $request->get('tahun', date('Y'));

    $startDate = $tahun . '-' . $bulan . '-01';
    $endDate   = date('Y-m-t', strtotime($startDate));

    $omzet        = OrderSales::whereBetween('tanggal_order', [$startDate, $endDate])->sum('subtotal');
    $totalOrder   = OrderSales::whereBetween('tanggal_order', [$startDate, $endDate])->count();
    $totalTerjual = OrderSales::whereBetween('tanggal_order', [$startDate, $endDate])->sum('jumlah');

    $data = [
      'bulan'         => $bulan,
      'tahun'         => $tahun,
      'omzet'         => $omzet,
      'totalOrder'    => $totalOrder,
      'totalTerjual'  => $totalTerjual,
      'periode'       => Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y'),
      'dicetak_oleh'  => Auth::user()->nama,
      'tanggal_cetak' => Carbon::now()->isoFormat('D MMMM Y'),
    ];

    $pdf = Pdf::loadView('laporan.omzet', $data)->setPaper('a4', 'portrait');
    return $pdf->stream('laporan-omzet-' . $tahun . '-' . $bulan . '.pdf');
  }

  public function barangTerlarisPdf(Request $request) {
    $awal     = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $akhir    = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));
    $kategori = $request->input('kategori', '');
    $limit    = $request->input('limit', 10);

    $data = BarangKeluar::with('barang')
      ->whereBetween('tanggal_keluar', [$awal, $akhir])
      ->when($kategori, fn($q) => $q->whereHas('barang', fn($b) => $b->where('kategori', $kategori)))
      ->selectRaw('id_barang, SUM(jumlah) as total_keluar')
      ->groupBy('id_barang')
      ->orderByDesc('total_keluar')
      ->limit($limit)
      ->get();

    $this->catatLaporan('barang_terlaris', $awal, $akhir);

    $pdf = Pdf::loadView('laporan.barang-terlaris', [
      'data'          => $data,
      'tanggal_awal'  => $this->formatTanggal($awal),
      'tanggal_akhir' => $this->formatTanggal($akhir),
      'total_keluar'  => $data->sum('total_keluar'),
      'dicetak_oleh'  => Auth::user()->nama,
      'tanggal_cetak' => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('laporan-barang-terlaris-' . $awal . '.pdf');
  }
}