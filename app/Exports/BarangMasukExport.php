<?php
namespace App\Exports;

use App\Models\BarangMasuk;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class BarangMasukExport {
  protected $tanggalAwal;
  protected $tanggalAkhir;

  public function __construct($tanggalAwal, $tanggalAkhir) {
    $this->tanggalAwal  = $tanggalAwal;
    $this->tanggalAkhir = $tanggalAkhir;
  }

  public function download() {
    $data = BarangMasuk::with(['barang', 'supplier', 'user'])
      ->whereBetween('tanggal_masuk', [$this->tanggalAwal, $this->tanggalAkhir])
      ->orderByDesc('tanggal_masuk')
      ->get();

    $fileName = 'laporan-barang-masuk-' . $this->tanggalAwal . '-sd-' . $this->tanggalAkhir . '.xlsx';

    $writer = new Writer();
    $writer->openToBrowser($fileName);

    // Header
    $headerRow = Row::fromValues([
      'NO', 'TANGGAL', 'NO. NOTA', 'SURAT JALAN',
      'KODE BARANG', 'NAMA BARANG', 'JUMLAH',
      'SUPPLIER', 'SUMBER', 'INPUT OLEH', 'KETERANGAN',
    ]);
    $writer->addRow($headerRow);

    // Data
    $no = 1;
    foreach ($data as $row) {
      $writer->addRow(Row::fromValues([
        $no++,
        $row->tanggal_masuk->format('d/m/Y'),
        $row->no_nota,
        $row->no_surat_jalan ?? '-',
        $row->barang->kode_barang ?? '-',
        $row->barang->nama_barang ?? '-',
        $row->jumlah,
        $row->supplier->nama_supplier ?? '-',
        $row->sumber,
        $row->user->nama ?? 'System',
        $row->keterangan ?? '-',
      ]));
    }

    $writer->close();
    exit;
  }
}
