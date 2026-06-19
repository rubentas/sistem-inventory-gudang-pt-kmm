<?php
namespace App\Http\Controllers;

use App\Exports\BarangMasukExport;
use Illuminate\Http\Request;

class ExportController extends Controller {
  public function barangMasukExcel(Request $request) {
    $awal  = $request->input('tanggal_awal', now()->startOfMonth()->format('Y-m-d'));
    $akhir = $request->input('tanggal_akhir', now()->format('Y-m-d'));

    return (new BarangMasukExport($awal, $akhir))->download();
  }
}
