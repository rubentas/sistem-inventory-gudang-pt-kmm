<?php
namespace App\Exports;

use App\Models\Wilayah;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class WilayahExport {
  public function download() {
    $data = Wilayah::withCount(['barangKeluar as total_keluar' => fn($q) => $q->selectRaw('SUM(jumlah)')])->orderBy('nama_wilayah')->get();

    $writer = new Writer();
    $writer->openToBrowser('laporan-wilayah.xlsx');
    $writer->addRow(Row::fromValues(['NO', 'NAMA WILAYAH', 'TOTAL BARANG KELUAR']));
    $no = 1;
    foreach ($data as $d) {
      $writer->addRow(Row::fromValues([$no++, $d->nama_wilayah, $d->total_keluar ?? 0]));
    }
    $writer->close();exit;
  }
}