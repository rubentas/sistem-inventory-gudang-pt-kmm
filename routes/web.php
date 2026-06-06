<?php

use App\Http\Controllers\AuthController;
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
});

// =============================================
// SALES
// =============================================
Route::middleware(['auth', 'role:sales'])->prefix('sales')->name('sales.')->group(function () {
  Route::get('/dashboard', \App\Livewire\Sales\Dashboard::class)->name('dashboard');
  Route::get('/order-sales', \App\Livewire\Sales\OrderSales::class)->name('order-sales');
  Route::get('/stok-barang', \App\Livewire\Sales\StokBarang::class)->name('stok-barang');
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
  // Route::get('/laporan-barang-terlaris', \App\Livewire\Pimpinan\LaporanBarangTerlaris::class)->name('lap-terlaris'); // BELUM DIBUAT
  // Route::get('/laporan-barang-expired', \App\Livewire\Pimpinan\LaporanBarangExpired::class)->name('lap-expired'); // BELUM DIBUAT
  Route::get('/manajemen-pengguna', \App\Livewire\Pimpinan\ManajemenPengguna::class)->name('pengguna');
});

// =============================================
// EXPORT PDF
// =============================================
Route::middleware('auth')->prefix('laporan')->name('laporan.')->group(function () {
  Route::get('/barang-masuk/pdf', [LaporanController::class, 'barangMasukPdf'])->name('masuk.pdf');
  Route::get('/barang-keluar/pdf', [LaporanController::class, 'barangKeluarPdf'])->name('keluar.pdf');
  Route::get('/stok/pdf', [LaporanController::class, 'stokPdf'])->name('stok.pdf');
  Route::get('/stock-opname/pdf', [LaporanController::class, 'stockOpnamePdf'])->name('opname.pdf');
  Route::get('/order-sales/pdf', [LaporanController::class, 'orderSalesPdf'])->name('order.pdf');
  Route::get('/supplier/pdf', [LaporanController::class, 'supplierPdf'])->name('supplier.pdf');
  Route::get('/wilayah/pdf', [LaporanController::class, 'wilayahPdf'])->name('wilayah.pdf');
  Route::get('/inventory/pdf', [LaporanController::class, 'inventoryPdf'])->name('inventory.pdf');
});