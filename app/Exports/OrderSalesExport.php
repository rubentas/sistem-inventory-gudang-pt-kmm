<?php
namespace App\Exports;

use App\Models\OrderSales;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class OrderSalesExport {
  protected $awal;
  protected $akhir;
  protected $status;

  public function __construct($awal, $akhir, $status) {
    $this->awal   = $awal;
    $this->akhir  = $akhir;
    $this->status = $status;
  }

  public function download() {
    $data = OrderSales::with(['barang', 'wilayah', 'sales'])
      ->whereBetween('tanggal_order', [$this->awal, $this->akhir])
      ->when($this->status, fn($q) => $q->where('status', $this->status))
      ->orderByDesc('tanggal_order')
      ->get();

    $writer = new Writer();
    $writer->openToBrowser('laporan-order-sales.xlsx');

    $writer->addRow(Row::fromValues([
      'NO', 'TANGGAL', 'BARANG', 'JUMLAH', 'TOTAL', 'WILAYAH', 'SALES', 'STATUS',
    ]));

    $no = 1;
    foreach ($data as $d) {
      $writer->addRow(Row::fromValues([
        $no++,
        $d->tanggal_order->format('d/m/Y'),
        $d->barang->nama_barang ?? '-',
        $d->jumlah,
        'Rp ' . number_format($d->subtotal, 0, ',', '.'),
        $d->wilayah->nama_wilayah ?? '-',
        $d->sales->nama_sales ?? '-',
        ucfirst($d->status),
      ]));
    }

    $writer->close();
    exit;
  }
}
