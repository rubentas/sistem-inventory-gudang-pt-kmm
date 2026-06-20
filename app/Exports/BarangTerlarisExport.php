<?php
namespace App\Exports;

use App\Models\BarangKeluar;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class BarangTerlarisExport {
  protected $awal, $akhir, $kategori, $limit;
  public function __construct($awal, $akhir, $kategori, $limit) {
    $this->awal     = $awal;
    $this->akhir    = $akhir;
    $this->kategori = $kategori;
    $this->limit    = $limit;
  }

  public function download() {
    $data = BarangKeluar::with('barang')
      ->whereBetween('tanggal_keluar', [$this->awal, $this->akhir])
      ->when($this->kategori, fn($q) => $q->whereHas('barang', fn($b) => $b->where('kategori', $this->kategori)))
      ->selectRaw('id_barang, SUM(jumlah) as total_keluar')
      ->groupBy('id_barang')->orderByDesc('total_keluar')->limit($this->limit)->get();

    $writer = new Writer();
    $writer->openToBrowser('laporan-barang-terlaris.xlsx');
    $writer->addRow(Row::fromValues(['NO', 'KODE', 'NAMA BARANG', 'KATEGORI', 'TOTAL KELUAR']));
    $no = 1;
    foreach ($data as $d) {
      $writer->addRow(Row::fromValues([$no++, $d->barang->kode_barang ?? '-', $d->barang->nama_barang ?? '-', $d->barang->kategori ?? '-', $d->total_keluar]));
    }
    $writer->close();exit;
  }
}