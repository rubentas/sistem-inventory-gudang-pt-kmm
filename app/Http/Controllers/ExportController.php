<?php
namespace App\Http\Controllers;

use App\Exports\BarangKeluarExport;
use Illuminate\Http\Request;

class ExportController extends Controller {
  public function barangKeluarExcel(Request $request) {
    $awal    = $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d'));
    $akhir   = $request->input('tanggal_akhir', now()->format('Y-m-d'));
    $wilayah = $request->input('id_wilayah', '');

    return (new BarangKeluarExport($awal, $akhir, $wilayah))->download();
  }
}