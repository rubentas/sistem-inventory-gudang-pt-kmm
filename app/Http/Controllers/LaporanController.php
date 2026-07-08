<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Laporan;
use App\Models\OrderSales;
use App\Models\ReturPenjualan;
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

  // ========== BARANG MASUK ==========
  public function barangMasukPdf(Request $request) {
    $awal       = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $akhir      = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));
    $idSupplier = $request->input('id_supplier');
    $sumber     = $request->input('sumber', '');

    $query = BarangMasuk::with(['barang', 'supplier', 'user'])
      ->whereBetween('tanggal_masuk', [$awal, $akhir]);

    if ($idSupplier) {
      $query->where('id_supplier', $idSupplier);
    }

    if ($sumber) {
      $query->where('sumber', $sumber);
    }

    $data = $query->orderByDesc('tanggal_masuk')->orderByDesc('id_masuk')->get();

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

  // ========== BARANG KELUAR ==========
  public function barangKeluarPdf(Request $request) {
    $awal    = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $akhir   = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));
    $wilayah = $request->input('id_wilayah', '');

    $query = BarangKeluar::with(['barang', 'wilayah', 'user'])
      ->whereBetween('tanggal_keluar', [$awal, $akhir]);

    if ($wilayah) {
      $query->where('id_wilayah', $wilayah);
    }

    $data = $query->orderByDesc('tanggal_keluar')->get();

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

  // ========== STOK BARANG ==========
  public function stokPdf(Request $request) {
    $kategori = $request->input('kategori', '');
    $status   = $request->input('status', '');
    $search   = $request->input('search', '');

    $data = Stok::with('barang')
      ->when($kategori, fn($q) => $q->whereHas('barang', fn($b) => $b->where('kategori', $kategori)))
      ->when($status === 'menipis', fn($q) => $q->whereColumn('jumlah_stok', '<=', 'stok_minimum'))
      ->when($status === 'aman', fn($q) => $q->whereColumn('jumlah_stok', '>', 'stok_minimum'))
      ->when($search, fn($q) => $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $search . '%')))
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

  // ========== STOCK OPNAME ==========
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

  // ========== ORDER SALES ==========
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

  // ========== SUPPLIER ==========
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

  // ========== WILAYAH ==========
  public function wilayahPdf() {
    $data = Wilayah::with('sales')->orderBy('nama_wilayah')->get();

    $this->catatLaporan('wilayah');

    $pdf = Pdf::loadView('laporan.wilayah', [
      'data'          => $data,
      'dicetak_oleh'  => Auth::user()->nama,
      'tanggal_cetak' => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('laporan-wilayah-' . Carbon::today()->format('Y-m-d') . '.pdf');
  }

  // ========== INVENTORY ==========
  public function inventoryPdf(Request $request) {
    $awal  = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
    $akhir = $request->input('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));

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
      'dicetak_oleh'             => Auth::user()->nama ?? 'System',
      'tanggal_cetak'            => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('laporan-inventory-' . $awal . '-sd-' . $akhir . '.pdf');
  }

  // ========== BARANG TERLARIS ==========
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

  // ========== BARANG EXPIRED ==========
  public function barangExpiredPdf(Request $request) {
    $data = BarangMasuk::with(['barang', 'supplier'])
      ->whereNotNull('tanggal_expired')
      ->when($request->status, fn($q) => $q->where('status_expired', $request->status))
      ->orderBy('tanggal_expired')
      ->get();

    $pdf = Pdf::loadView('laporan.barang-expired', [
      'data'          => $data,
      'dicetak_oleh'  => Auth::user()->nama,
      'tanggal_cetak' => $this->formatTanggal(Carbon::now()),
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('laporan-barang-expired.pdf');
  }

  // ========== OMZET ==========
  public function omzetPdf(Request $request) {
    $bulan = $request->input('bulan', date('m'));
    $tahun = $request->input('tahun', date('Y'));

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

  // ========== DATA BARANG ==========
  public function dataBarangPdf(Request $request) {
    set_time_limit(120);

    $data = Barang::with('stok')
      ->when($request->search, fn($q) => $q->where(function ($q) use ($request) {
        $q->where('nama_barang', 'like', '%' . $request->search . '%')
          ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
      }))
      ->when($request->filterKategori, fn($q) => $q->where('kategori', $request->filterKategori))
      ->when($request->filterStok === 'habis', fn($q) => $q->whereHas('stok', fn($s) => $s->where('jumlah_stok', '<=', 0)))
      ->when($request->filterStok === 'menipis', fn($q) => $q->whereHas('stok', fn($s) => $s
          ->where('jumlah_stok', '>', 0)
          ->whereColumn('jumlah_stok', '<=', 'stok_minimum')))
      ->when($request->filterStok === 'aman', fn($q) => $q->whereHas('stok', fn($s) => $s->whereColumn('jumlah_stok', '>', 'stok_minimum')))
      ->orderBy('kode_barang')
      ->get();

    $filterLabel = match ($request->filterStok) {
      'habis'   => 'Stok Habis',
      'menipis' => 'Stok Menipis',
      'aman'    => 'Stok Aman',
      default   => 'Semua Stok',
    };

    $pdf = Pdf::loadView('laporan.data-barang', [
      'data'          => $data,
      'dicetak_oleh'  => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
      'filter_label'  => $filterLabel,
    ])->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-data-barang-' . now()->format('Ymd') . '.pdf');
  }

  // ========== RETUR BARANG ==========
  public function returBarangPdf(Request $request) {
    set_time_limit(120);

    $data = ReturPenjualan::with(['detailRetur.barang', 'order'])
      ->whereBetween('tanggal_retur', [$request->tanggal_awal, $request->tanggal_akhir])
      ->orderByDesc('tanggal_retur')
      ->get();

    $pdf = Pdf::loadView('laporan.retur-barang', [
      'data'          => $data,
      'dicetak_oleh'  => auth()->user()->nama ?? 'System',
      'tanggal_cetak' => now()->translatedFormat('d F Y'),
      'total_retur'   => $data->count(),
    ])->setPaper('a4', 'landscape');

    return $pdf->stream('laporan-retur-barang-' . $request->tanggal_awal . '-sd-' . $request->tanggal_akhir . '.pdf');
  }
}