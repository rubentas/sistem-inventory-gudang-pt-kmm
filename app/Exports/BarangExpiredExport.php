<?php
namespace App\Exports;

use App\Models\BarangMasuk;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class BarangExpiredExport {
  protected $status;
  public function __construct($status) {$this->status = $status;}

  public function download() {
    $data = BarangMasuk::with(['barang', 'supplier'])->whereNotNull('tanggal_expired')
      ->when($this->status, fn($q) => $q->where('status_expired', $this->status))
      ->orderBy('tanggal_expired')->get();

    $writer = new Writer();
    $writer->openToBrowser('laporan-barang-expired.xlsx');
    $writer->addRow(Row::fromValues(['NO', 'BARANG', 'TANGGAL MASUK', 'TANGGAL EXPIRED', 'STATUS', 'SUPPLIER']));
    $no = 1;
    foreach ($data as $d) {
      $writer->addRow(Row::fromValues([$no++, $d->barang->nama_barang ?? '-', $d->tanggal_masuk->format('d/m/Y'), $d->tanggal_expired->format('d/m/Y'), ucwords(str_replace('_', ' ', $d->status_expired)), $d->supplier->nama_supplier ?? '-']));
    }
    $writer->close();exit;
  }
}