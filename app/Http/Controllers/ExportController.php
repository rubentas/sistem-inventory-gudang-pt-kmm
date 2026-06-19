<?php
namespace App\Http\Controllers;

use App\Exports\StokBarangExport;
use Illuminate\Http\Request;

class ExportController extends Controller {
  public function stokBarangExcel(Request $request) {
    return (new StokBarangExport(
      $request->input('kategori', ''),
      $request->input('status', ''),
      $request->input('search', '')
    ))->download();
  }
}