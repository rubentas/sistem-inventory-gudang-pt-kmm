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
    /**
     * Catat aktivitas cetak laporan ke database
     */
    private function catatLaporan(string $jenis, ?string $awal = null, ?string $akhir = null): void {
        Laporan::create([
            'id_user'       => Auth::user()->id_user,
            'jenis_laporan' => $jenis,
            'tanggal_awal'  => $awal,
            'tanggal_akhir' => $akhir,
            'tanggal_cetak' => Carbon::today(),
        ]);
    }

    /**
     * Format tanggal Indonesia
     */
    private function formatTanggalIndonesia($tanggal): string {
        if (! $tanggal) {
            return '-';
        }
        return Carbon::parse($tanggal)->isoFormat('D MMMM Y');
    }

    /**
     * Laporan Barang Masuk PDF
     */
    public function barangMasukPdf(Request $request) {
        $awal  = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $akhir = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));

        $data = BarangMasuk::with(['barang', 'supplier', 'user'])
            ->whereBetween('tanggal_masuk', [$awal, $akhir])
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        $this->catatLaporan('barang_masuk', $awal, $akhir);

        $totalJumlah = $data->sum('jumlah');

        $pdf = Pdf::loadView('laporan.barang-masuk', [
            'data'          => $data,
            'tanggal_awal'  => $this->formatTanggalIndonesia($awal),
            'tanggal_akhir' => $this->formatTanggalIndonesia($akhir),
            'total_jumlah'  => $totalJumlah,
            'dicetak_oleh'  => Auth::user()->nama,
            'tanggal_cetak' => $this->formatTanggalIndonesia(Carbon::now()),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-barang-masuk-' . $awal . '-sd-' . $akhir . '.pdf');
    }

    /**
     * Laporan Barang Keluar PDF
     */
    public function barangKeluarPdf(Request $request) {
        $awal  = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $akhir = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));

        $data = BarangKeluar::with(['barang', 'wilayah', 'user'])
            ->whereBetween('tanggal_keluar', [$awal, $akhir])
            ->orderBy('tanggal_keluar', 'desc')
            ->get();

        $this->catatLaporan('barang_keluar', $awal, $akhir);

        $totalJumlah = $data->sum('jumlah');

        $pdf = Pdf::loadView('laporan.barang-keluar', [
            'data'          => $data,
            'tanggal_awal'  => $this->formatTanggalIndonesia($awal),
            'tanggal_akhir' => $this->formatTanggalIndonesia($akhir),
            'total_jumlah'  => $totalJumlah,
            'dicetak_oleh'  => Auth::user()->nama,
            'tanggal_cetak' => $this->formatTanggalIndonesia(Carbon::now()),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-barang-keluar-' . $awal . '-sd-' . $akhir . '.pdf');
    }

    /**
     * Laporan Stok Barang PDF
     */
    public function stokPdf() {
        $data = Stok::with('barang')
            ->orderBy('id_barang')
            ->get();

        $this->catatLaporan('stok_barang');

        $totalStok   = $data->sum('jumlah_stok');
        $stokMenipis = $data->where('status', 'Menipis')->count();

        $pdf = Pdf::loadView('laporan.stok-barang', [
            'data'          => $data,
            'total_stok'    => $totalStok,
            'stok_menipis'  => $stokMenipis,
            'dicetak_oleh'  => Auth::user()->nama,
            'tanggal_cetak' => $this->formatTanggalIndonesia(Carbon::now()),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-stok-barang-' . Carbon::today()->format('Y-m-d') . '.pdf');
    }

    /**
     * Laporan Stock Opname PDF
     */
    public function stockOpnamePdf(Request $request) {
        $awal  = $request->input('tanggal_awal', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $akhir = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));

        $data = StockOpname::with(['barang', 'user'])
            ->whereBetween('tanggal_opname', [$awal, $akhir])
            ->orderBy('tanggal_opname', 'desc')
            ->get();

        $this->catatLaporan('stock_opname', $awal, $akhir);

        $totalSelisih    = $data->sum('selisih');
        $rataRataSelisih = $data->count() > 0 ? round($totalSelisih / $data->count(), 2) : 0;

        $pdf = Pdf::loadView('laporan.stock-opname', [
            'data'              => $data,
            'tanggal_awal'      => $this->formatTanggalIndonesia($awal),
            'tanggal_akhir'     => $this->formatTanggalIndonesia($akhir),
            'total_selisih'     => $totalSelisih,
            'rata_rata_selisih' => $rataRataSelisih,
            'dicetak_oleh'      => Auth::user()->nama,
            'tanggal_cetak'     => $this->formatTanggalIndonesia(Carbon::now()),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-stock-opname-' . $awal . '-sd-' . $akhir . '.pdf');
    }

    /**
     * Laporan Order Sales PDF
     */
    public function orderSalesPdf(Request $request) {
        $awal   = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $akhir  = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));
        $status = $request->input('status');

        $data = OrderSales::with(['barang', 'wilayah', 'user'])
            ->whereBetween('tanggal_order', [$awal, $akhir])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('tanggal_order', 'desc')
            ->get();

        $this->catatLaporan('order_sales', $awal, $akhir);

        $totalJumlah   = $data->sum('jumlah');
        $totalPending  = $data->where('status', 'pending')->count();
        $totalDiproses = $data->where('status', 'diproses')->count();
        $totalSelesai  = $data->where('status', 'selesai')->count();

        $pdf = Pdf::loadView('laporan.order-sales', [
            'data'           => $data,
            'tanggal_awal'   => $this->formatTanggalIndonesia($awal),
            'tanggal_akhir'  => $this->formatTanggalIndonesia($akhir),
            'total_jumlah'   => $totalJumlah,
            'total_pending'  => $totalPending,
            'total_diproses' => $totalDiproses,
            'total_selesai'  => $totalSelesai,
            'dicetak_oleh'   => Auth::user()->nama,
            'tanggal_cetak'  => $this->formatTanggalIndonesia(Carbon::now()),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-order-sales-' . $awal . '-sd-' . $akhir . '.pdf');
    }

    /**
     * Laporan Supplier PDF
     */
    public function supplierPdf() {
        $data = Supplier::orderBy('kode_supplier')->get();

        $this->catatLaporan('supplier');

        $pdf = Pdf::loadView('laporan.supplier', [
            'data'           => $data,
            'total_supplier' => $data->count(),
            'dicetak_oleh'   => Auth::user()->nama,
            'tanggal_cetak'  => $this->formatTanggalIndonesia(Carbon::now()),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-supplier-' . Carbon::today()->format('Y-m-d') . '.pdf');
    }

    /**
     * Laporan Wilayah PDF
     */
    public function wilayahPdf() {
        $data = Wilayah::with('sales')->orderBy('nama_wilayah')->get();

        $this->catatLaporan('wilayah');

        $totalToko = $data->sum('jumlah_toko');

        $pdf = Pdf::loadView('laporan.wilayah', [
            'data'          => $data,
            'total_toko'    => $totalToko,
            'dicetak_oleh'  => Auth::user()->nama,
            'tanggal_cetak' => $this->formatTanggalIndonesia(Carbon::now()),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-wilayah-' . Carbon::today()->format('Y-m-d') . '.pdf');
    }

    /**
     * Laporan Inventory Keseluruhan PDF
     */
    public function inventoryPdf(Request $request) {
        $awal  = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $akhir = $request->input('tanggal_akhir', Carbon::today()->format('Y-m-d'));

        $stoks = Stok::with('barang')->get()->map(function ($stok) use ($awal, $akhir) {
            $totalMasuk = BarangMasuk::where('id_barang', $stok->id_barang)
                ->whereBetween('tanggal_masuk', [$awal, $akhir])
                ->sum('jumlah');

            $totalKeluar = BarangKeluar::where('id_barang', $stok->id_barang)
                ->whereBetween('tanggal_keluar', [$awal, $akhir])
                ->sum('jumlah');

            $stok->total_masuk  = $totalMasuk;
            $stok->total_keluar = $totalKeluar;
            return $stok;
        });

        $totalMasukKeseluruhan  = $stoks->sum('total_masuk');
        $totalKeluarKeseluruhan = $stoks->sum('total_keluar');
        $totalStokAkhir         = $stoks->sum('jumlah_stok');

        $this->catatLaporan('inventory', $awal, $akhir);

        $pdf = Pdf::loadView('laporan.inventory', [
            'stoks'                    => $stoks,
            'tanggal_awal'             => $this->formatTanggalIndonesia($awal),
            'tanggal_akhir'            => $this->formatTanggalIndonesia($akhir),
            'total_masuk_keseluruhan'  => $totalMasukKeseluruhan,
            'total_keluar_keseluruhan' => $totalKeluarKeseluruhan,
            'total_stok_akhir'         => $totalStokAkhir,
            'dicetak_oleh'             => Auth::user()->nama,
            'tanggal_cetak'            => $this->formatTanggalIndonesia(Carbon::now()),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-inventory-' . $awal . '-sd-' . $akhir . '.pdf');
    }
}
