<?php
namespace App\Exports;

use App\Models\Inventory;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class InventoryExport {
  protected $awal, $akhir;
  public function __construct($awal, $akhir) {$this->awal = $awal;
    $this->akhir                           = $akhir;}

  public function download() {
    $data   = Inventory::with('barang')->whereBetween('tanggal', [$this->awal, $this->akhir])->orderByDesc('tanggal')->get();
    $writer = new Writer();
    $writer->openToBrowser('laporan-inventory.xlsx');
    $writer->addRow(Row::fromValues(['NO', 'TANGGAL', 'BARANG', 'STOK AWAL', 'MASUK', 'KELUAR', 'SISTEM', 'FISIK', 'SELISIH']));
    $no = 1;
    foreach ($data as $d) {
      $writer->addRow(Row::fromValues([$no++, $d->tanggal->format('d/m/Y'), $d->barang->nama_barang ?? '-', $d->stok_awal, $d->barang_masuk, $d->barang_keluar, $d->stok_sistem, $d->stok_fisik, $d->selisih]));
    }
    $writer->close();exit;
  }
}