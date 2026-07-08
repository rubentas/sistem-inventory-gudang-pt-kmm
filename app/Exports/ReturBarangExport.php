<?php
namespace App\Exports;

use App\Models\ReturPenjualan;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class ReturBarangExport {
  protected $tanggalAwal;
  protected $tanggalAkhir;

  public function __construct($tanggalAwal, $tanggalAkhir) {
    $this->tanggalAwal  = $tanggalAwal;
    $this->tanggalAkhir = $tanggalAkhir;
  }

  public function download() {
    $data = ReturPenjualan::with(['detailRetur.barang', 'order'])
      ->whereBetween('tanggal_retur', [$this->tanggalAwal, $this->tanggalAkhir])
      ->orderByDesc('tanggal_retur')
      ->get();

    $fileName = 'laporan-retur-barang-' . $this->tanggalAwal . '-sd-' . $this->tanggalAkhir . '.xlsx';

    $writer = new Writer();
    $writer->openToBrowser($fileName);

    $writer->addRow(Row::fromValues([
      'NO', 'NO RETUR', 'ORDER', 'TANGGAL', 'BARANG', 'JUMLAH RETUR', 'KONDISI', 'ALASAN', 'KETERANGAN', 'STATUS',
    ]));

    $no = 1;
    foreach ($data as $r) {
      $detail = $r->detailRetur->first();
      $writer->addRow(Row::fromValues([
        $no++,
        $r->no_retur,
        $r->order->no_invoice ?? $r->id_order,
        $r->tanggal_retur->format('d/m/Y'),
        $detail->barang->nama_barang ?? '-',
        $detail->jumlah_retur ?? 0,
        $detail->kondisi_barang ?? '-',
        $detail->alasan ?? '-',
        $detail->keterangan ?? '-',
        $r->status,
      ]));
    }

    $writer->close();
    exit;
  }
}