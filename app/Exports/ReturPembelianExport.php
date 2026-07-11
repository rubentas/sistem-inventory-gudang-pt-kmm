<?php
namespace App\Exports;

use App\Models\ReturPembelian;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class ReturPembelianExport {
  protected $tanggalAwal;
  protected $tanggalAkhir;

  public function __construct($tanggalAwal, $tanggalAkhir) {
    $this->tanggalAwal  = $tanggalAwal;
    $this->tanggalAkhir = $tanggalAkhir;
  }

  public function download() {
    $data = ReturPembelian::with(['supplier', 'barang', 'user'])
      ->whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->orderByDesc('tanggal_retur')
      ->get();

    $fileName = 'laporan-retur-pembelian-' . $this->tanggalAwal . '-sd-' . $this->tanggalAkhir . '.xlsx';

    $writer = new Writer();
    $writer->openToBrowser($fileName);
    $writer->addRow(Row::fromValues(['NO', 'NO RETUR', 'SUPPLIER', 'BARANG', 'JUMLAH', 'TUJUAN', 'TANGGAL', 'DIINPUT OLEH']));

    $no = 1;
    foreach ($data as $r) {
      $writer->addRow(Row::fromValues([
        $no++,
        $r->no_retur,
        $r->supplier->nama_supplier ?? '-',
        $r->barang->nama_barang ?? '-',
        $r->jumlah,
        $r->tujuan,
        $r->tanggal_retur->format('d/m/Y'),
        $r->user->nama ?? 'Admin',
      ]));
    }

    $writer->close();
    exit;
  }
}