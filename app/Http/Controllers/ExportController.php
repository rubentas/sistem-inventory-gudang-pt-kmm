<?php
namespace App\Http\Controllers;

use App\Exports\BarangKeluarExport;
use App\Exports\BarangMasukExport;
use App\Exports\OrderSalesExport;
use App\Exports\StokBarangExport;
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
      $request->input('kategori', ''),
      $request->input('status', ''),
      $request->input('search', '')
    ))->download();
  }

  public function orderSalesExcel(Request $request) {
    return (new OrderSalesExport(
      $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d')),
      $request->input('tanggal_akhir', now()->format('Y-m-d')),
      $request->input('status', '')
    ))->download();
  }
}
