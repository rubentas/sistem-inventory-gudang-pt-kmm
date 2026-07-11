<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// =============================================
// HALAMAN LOGIN
// =============================================
Route::get('/', \App\Livewire\Auth\Login::class)->name('login');
Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// =============================================
// KEPALA GUDANG
// =============================================
Route::middleware(['auth', 'role:kepala_gudang'])->prefix('kepala-gudang')->name('kg.')->group(function () {
  Route::get('/dashboard', \App\Livewire\KepalaGudang\Dashboard::class)->name('dashboard');
  Route::get('/barang-masuk', \App\Livewire\KepalaGudang\LaporanBarangMasuk::class)->name('barang-masuk');
  Route::get('/stock-opname', \App\Livewire\KepalaGudang\StockOpname::class)->name('stock-opname');
  Route::get('/stok-barang', \App\Livewire\KepalaGudang\StokBarang::class)->name('stok-barang');
  Route::get('/barang-expired', \App\Livewire\KepalaGudang\BarangExpired::class)->name('barang-expired');
});

// =============================================
// ADMIN FAKTURIS
// =============================================
Route::middleware(['auth', 'role:admin_fakturis'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
  Route::get('/barang-masuk', \App\Livewire\Admin\BarangMasuk::class)->name('barang-masuk');
  Route::get('/data-barang', \App\Livewire\Admin\DataBarang::class)->name('data-barang');
  Route::get('/supplier', \App\Livewire\Admin\Supplier::class)->name('supplier');
  Route::get('/barang-keluar', \App\Livewire\Admin\BarangKeluar::class)->name('barang-keluar');
  Route::get('/order-sales', \App\Livewire\Admin\OrderSales::class)->name('order-sales');
  Route::get('/wilayah', \App\Livewire\Admin\Wilayah::class)->name('wilayah');
  Route::get('/stok-barang', \App\Livewire\Admin\StokBarang::class)->name('stok-barang');
  Route::get('/invoice', \App\Livewire\Admin\Invoice::class)->name('invoice');
  Route::get('/data-sales', \App\Livewire\Admin\DataSales::class)->name('data-sales');
  Route::get('/retur-barang', \App\Livewire\Admin\ReturBarang::class)->name('retur-barang');
  Route::get('/laporan-barang-masuk', \App\Livewire\Admin\Laporan\BarangMasuk::class)->name('laporan.masuk');
  Route::get('/laporan-barang-keluar', \App\Livewire\Admin\Laporan\BarangKeluar::class)->name('laporan.keluar');
  Route::get('/laporan-stok', \App\Livewire\Admin\Laporan\StokBarang::class)->name('laporan.stok');
  Route::get('/laporan-order-sales', \App\Livewire\Admin\Laporan\OrderSales::class)->name('laporan.order');
  Route::get('/laporan-omzet', \App\Livewire\Admin\Laporan\Omzet::class)->name('laporan.omzet');
  Route::get('/laporan-barang-terlaris', \App\Livewire\Admin\Laporan\BarangTerlaris::class)->name('laporan.terlaris');
  Route::get('/laporan-barang-expired', \App\Livewire\Admin\Laporan\BarangExpired::class)->name('laporan.expired');
  Route::get('/laporan-supplier', \App\Livewire\Admin\Laporan\Supplier::class)->name('laporan.supplier');
  Route::get('/laporan-wilayah', \App\Livewire\Admin\Laporan\Wilayah::class)->name('laporan.wilayah');
  Route::get('/laporan-inventory', \App\Livewire\Admin\Laporan\LaporanInventory::class)->name('laporan.inventory');
  Route::get('/laporan-data-barang', \App\Livewire\Admin\Laporan\DataBarang::class)->name('laporan.data-barang');
  Route::get('/laporan-data-barang/pdf', [LaporanController::class, 'dataBarangPdf'])->name('laporan.data-barang.pdf');
  Route::get('/laporan-data-barang/excel', [ExportController::class, 'dataBarangExcel'])->name('laporan.data-barang.excel');
  Route::get('/manajemen-pengguna', \App\Livewire\Admin\ManajemenPengguna::class)->name('pengguna');
  Route::get('/inventory-input', \App\Livewire\Admin\InventoryInput::class)->name('inventory.input');
  Route::get('/retur-barang/pdf', [LaporanController::class, 'returBarangPdf'])->name('retur-barang.pdf');
  Route::get('/laporan-retur-barang', \App\Livewire\Admin\Laporan\ReturBarang::class)->name('laporan.retur-barang');
  Route::get('/laporan-retur-barang/pdf', [LaporanController::class, 'returBarangPdf'])->name('laporan.retur-barang.pdf');
  Route::get('/laporan-retur-barang/excel', [ExportController::class, 'returBarangExcel'])->name('laporan.retur-barang.excel');
  Route::get('/retur-pembelian', \App\Livewire\Admin\ReturPembelian::class)->name('retur-pembelian');
  Route::get('/retur-pembelian/pdf', [LaporanController::class, 'returPembelianPdf'])->name('retur-pembelian.pdf');
  Route::get('/laporan-retur-pembelian', \App\Livewire\Admin\Laporan\ReturPembelian::class)->name('laporan.retur-pembelian');
  Route::get('/laporan-retur-pembelian/pdf', [LaporanController::class, 'returPembelianPdf'])->name('laporan.retur-pembelian.pdf');
  Route::get('/laporan-retur-pembelian/excel', [ExportController::class, 'returPembelianExcel'])->name('laporan.retur-pembelian.excel');
});

// =============================================
// SALES
// =============================================
Route::middleware(['auth', 'role:sales'])->prefix('sales')->name('sales.')->group(function () {
  Route::get('/dashboard', \App\Livewire\Sales\Dashboard::class)->name('dashboard');
  Route::get('/order-sales', \App\Livewire\Sales\OrderSales::class)->name('order-sales');
  Route::get('/stok-barang', \App\Livewire\Sales\StokBarang::class)->name('stok-barang');
  Route::get('/profile', \App\Livewire\Sales\Profile::class)->name('profile');
  Route::get('/history-order', \App\Livewire\Sales\HistoryOrder::class)->name('history.order');
  Route::get('/retur-barang', \App\Livewire\Sales\ReturBarang::class)->name('retur-barang');
});

// =============================================
// PIMPINAN
// =============================================
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
  Route::get('/dashboard', \App\Livewire\Pimpinan\Dashboard::class)->name('dashboard');
  Route::get('/laporan-barang-masuk', \App\Livewire\Pimpinan\LaporanBarangMasuk::class)->name('lap-masuk');
  Route::get('/laporan-barang-keluar', \App\Livewire\Pimpinan\LaporanBarangKeluar::class)->name('lap-keluar');
  Route::get('/laporan-stok', \App\Livewire\Pimpinan\LaporanStok::class)->name('lap-stok');
  Route::get('/laporan-stock-opname', \App\Livewire\Pimpinan\LaporanStockOpname::class)->name('lap-opname');
  Route::get('/laporan-order-sales', \App\Livewire\Pimpinan\LaporanOrderSales::class)->name('lap-order');
  Route::get('/laporan-supplier', \App\Livewire\Pimpinan\LaporanSupplier::class)->name('lap-supplier');
  Route::get('/laporan-wilayah', \App\Livewire\Pimpinan\LaporanWilayah::class)->name('lap-wilayah');
  Route::get('/laporan-inventory', \App\Livewire\Pimpinan\LaporanInventory::class)->name('lap-inventory');
  Route::get('/laporan-stok-kritis', \App\Livewire\Pimpinan\LaporanStokKritis::class)->name('lap-stok-kritis');
  Route::get('/laporan-barang-terlaris', \App\Livewire\Pimpinan\LaporanBarangTerlaris::class)->name('lap-terlaris');
  Route::get('/laporan-barang-expired', \App\Livewire\Pimpinan\LaporanBarangExpired::class)->name('lap-expired');
  Route::get('/laporan-omzet', \App\Livewire\Pimpinan\LaporanOmzet::class)->name('lap-omzet');
});

// =============================================
// EXPORT PDF & EXCEL
// =============================================
Route::middleware('auth')->prefix('laporan')->name('laporan.')->group(function () {
  Route::get('/barang-masuk/pdf', [LaporanController::class, 'barangMasukPdf'])->name('masuk.pdf');
  Route::get('/barang-masuk/excel', [ExportController::class, 'barangMasukExcel'])->name('masuk.excel');
  Route::get('/barang-keluar/pdf', [LaporanController::class, 'barangKeluarPdf'])->name('keluar.pdf');
  Route::get('/barang-keluar/excel', [ExportController::class, 'barangKeluarExcel'])->name('keluar.excel');
  Route::get('/stok/pdf', [LaporanController::class, 'stokPdf'])->name('stok.pdf');
  Route::get('/stok/excel', [ExportController::class, 'stokBarangExcel'])->name('stok.excel');
  Route::get('/stock-opname/pdf', [LaporanController::class, 'stockOpnamePdf'])->name('opname.pdf');
  Route::get('/order-sales/pdf', [LaporanController::class, 'orderSalesPdf'])->name('order.pdf');
  Route::get('/order-sales/excel', [ExportController::class, 'orderSalesExcel'])->name('order.excel');
  Route::get('/omzet/pdf', [LaporanController::class, 'omzetPdf'])->name('omzet.pdf');
  Route::get('/omzet/excel', [ExportController::class, 'omzetExcel'])->name('omzet.excel');
  Route::get('/barang-terlaris/pdf', [LaporanController::class, 'barangTerlarisPdf'])->name('terlaris.pdf');
  Route::get('/barang-terlaris/excel', [ExportController::class, 'barangTerlarisExcel'])->name('terlaris.excel');
  Route::get('/barang-expired/pdf', [LaporanController::class, 'barangExpiredPdf'])->name('expired.pdf');
  Route::get('/barang-expired/excel', [ExportController::class, 'barangExpiredExcel'])->name('expired.excel');
  Route::get('/supplier/pdf', [LaporanController::class, 'supplierPdf'])->name('supplier.pdf');
  Route::get('/supplier/excel', [ExportController::class, 'supplierExcel'])->name('supplier.excel');
  Route::get('/wilayah/pdf', [LaporanController::class, 'wilayahPdf'])->name('wilayah.pdf');
  Route::get('/wilayah/excel', [ExportController::class, 'wilayahExcel'])->name('wilayah.excel');
  Route::get('/inventory/pdf', [LaporanController::class, 'inventoryPdf'])->name('inventory.pdf');
  Route::get('/inventory/excel', [ExportController::class, 'inventoryExcel'])->name('inventory.excel');
  Route::get('/data-barang/pdf', [LaporanController::class, 'dataBarangPdf'])->name('data-barang.pdf');
});