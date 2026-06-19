<?php
namespace App\Exports;

use App\Models\BarangKeluar;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class BarangKeluarExport {
  protected $tanggalAwal;
  protected $tanggalAkhir;
  protected $filterWilayah;

  public function __construct($tanggalAwal, $tanggalAkhir, $filterWilayah = '') {
    $this->tanggalAwal   = $tanggalAwal;
    $this->tanggalAkhir  = $tanggalAkhir;
    $this->filterWilayah = $filterWilayah;
  }

  public function download() {
    $data = BarangKeluar::with(['barang', 'wilayah', 'user'])
      ->whereBetween('tanggal_keluar', [$this->tanggalAwal, $this->tanggalAkhir])
      ->when($this->filterWilayah, fn($q) => $q->where('id_wilayah', $this->filterWilayah))
      ->orderByDesc('tanggal_keluar')
      ->get();

    $fileName = 'laporan-barang-keluar-' . $this->tanggalAwal . '-sd-' . $this->tanggalAkhir . '.xlsx';

    $writer = new Writer();
    $writer->openToBrowser($fileName);

    $writer->addRow(Row::fromValues([
      'NO', 'TANGGAL', 'BARANG', 'JUMLAH', 'WILAYAH', 'INPUT OLEH', 'KETERANGAN',
    ]));

    $no = 1;
    foreach ($data as $row) {
      $writer->addRow(Row::fromValues([
        $no++,
        $row->tanggal_keluar->format('d/m/Y'),
        $row->barang->nama_barang ?? '-',
        $row->jumlah,
        $row->wilayah->nama_wilayah ?? '-',
        $row->user->nama ?? 'System',
        $row->keterangan ?? '-',
      ]));
    }

    $writer->close();
    exit;
  }
}