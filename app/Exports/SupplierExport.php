<?php
namespace App\Exports;

use App\Models\Supplier;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class SupplierExport {
  public function download() {
    $data = Supplier::withCount(['barangMasuk as total_masuk' => fn($q) => $q->selectRaw('SUM(jumlah)')])->orderBy('nama_supplier')->get();

    $writer = new Writer();
    $writer->openToBrowser('laporan-supplier.xlsx');
    $writer->addRow(Row::fromValues(['NO', 'KODE', 'NAMA SUPPLIER', 'ALAMAT', 'TELP', 'TOTAL BARANG MASUK']));
    $no = 1;
    foreach ($data as $d) {
      $writer->addRow(Row::fromValues([$no++, $d->kode_supplier, $d->nama_supplier, $d->alamat, $d->no_telp, $d->total_masuk ?? 0]));
    }
    $writer->close();exit;
  }
}