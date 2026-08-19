<?php
namespace App\Http\Controllers;

use App\Exports\BarangExpiredExport;
use App\Exports\BarangKeluarExport;
use App\Exports\BarangMasukExport;
use App\Exports\BarangTerlarisExport;
use App\Exports\DataBarangExport;
use App\Exports\InventoryExport;
use App\Exports\OmzetExport;
use App\Exports\OrderSalesExport;
use App\Exports\StokBarangExport;
use App\Exports\SupplierExport;
use App\Exports\WilayahExport;
use Illuminate\Http\Request;

class ExportController extends Controller {
  public function barangMasukExcel(Request $request) {
    $awal  = $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d'));
    $akhir = $request->input('tanggal_akhir', now()->format('Y-m-d'));

    return (new BarangMasukExport($awal, $akhir))->download();
  }

  public function barangKeluarExcel(Request $request) {
    $awal    = $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d'));
    $akhir   = $request->input('tanggal_akhir', now()->format('Y-m-d'));
    $wilayah = $request->input('id_wilayah', '');

    return (new BarangKeluarExport($awal, $akhir, $wilayah))->download();
  }

  public function stokBarangExcel(Request $request) {
  return (new StokBarangExport(
    urldecode($request->input('kategori', '')),
    urldecode($request->input('status', '')),
    urldecode($request->input('search', ''))
  ))->download();
}

  public function orderSalesExcel(Request $request) {
    return (new OrderSalesExport(
      $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')),
      $request->input('tanggal_akhir', now()->format('Y-m-d')),
      $request->input('status', '')
    ))->download();
  }

  public function omzetExcel(Request $request) {
    return (new OmzetExport($request->input('tahun', now()->year)))->download();
  }

  public function barangTerlarisExcel(Request $request) {
    return (new BarangTerlarisExport(
      $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')),
      $request->input('tanggal_akhir', now()->format('Y-m-d')),
      $request->input('kategori', ''),
      $request->input('limit', 10)
    ))->download();
  }

  public function barangExpiredExcel(Request $request) {
    return (new BarangExpiredExport($request->input('status', '')))->download();
  }

  public function dataBarangExcel(Request $request) {
    return (new DataBarangExport(
      $request->input('search', ''),
      $request->input('filterKategori', ''),
      $request->input('filterStok', '')
    ))->download();
  }

  public function supplierExcel() {
    return (new SupplierExport)->download();
  }

  public function wilayahExcel() {
    return (new WilayahExport)->download();
  }

  public function inventoryExcel(Request $request) {
    return (new InventoryExport($request->tanggal_awal, $request->tanggal_akhir))->download();
  }
  public function returBarangExcel(Request $request) {
    return (new \App\Exports\ReturBarangExport(
      $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')),
      $request->input('tanggal_akhir', now()->format('Y-m-d'))
    ))->download();
  }

  public function returPembelianExcel(Request $request) {
    return (new \App\Exports\ReturPembelianExport(
      $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')),
      $request->input('tanggal_akhir', now()->format('Y-m-d'))
    ))->download();
  }
}