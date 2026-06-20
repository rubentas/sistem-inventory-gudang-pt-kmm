<?php
namespace App\Exports;

use App\Models\OrderSales;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class OmzetExport {
  protected $tahun;
  public function __construct($tahun) {$this->tahun = $tahun;}

  public function download() {
    $data = OrderSales::whereYear('tanggal_order', $this->tahun)
      ->selectRaw('MONTH(tanggal_order) as bulan, COUNT(*) as total_order, SUM(jumlah) as total_qty, SUM(subtotal) as total_omzet')
      ->groupBy('bulan')->orderBy('bulan')->get();

    $writer = new Writer();
    $writer->openToBrowser('laporan-omzet-' . $this->tahun . '.xlsx');
    $writer->addRow(Row::fromValues(['BULAN', 'TOTAL ORDER', 'TOTAL QTY', 'TOTAL OMZET']));
    $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

    foreach ($data as $d) {
      $writer->addRow(Row::fromValues([$bulan[$d->bulan - 1], $d->total_order, $d->total_qty, 'Rp ' . number_format($d->total_omzet)]));
    }
    $writer->close();exit;
  }
}
