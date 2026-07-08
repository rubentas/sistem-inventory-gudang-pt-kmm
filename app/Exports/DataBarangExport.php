<?php
namespace App\Exports;

use App\Models\Barang;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class DataBarangExport {
  protected $search;
  protected $filterKategori;
  protected $filterStok;

  public function __construct($search = '', $filterKategori = '', $filterStok = '') {
    $this->search         = $search;
    $this->filterKategori = $filterKategori;
    $this->filterStok     = $filterStok;
  }

  public function download() {
    $data = Barang::with('stok')
      ->when($this->search, fn($q) => $q->where(function ($q) {
        $q->where('nama_barang', 'like', '%' . $this->search . '%')
          ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
      }))
      ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
      ->when($this->filterStok === 'habis', fn($q) => $q->whereHas('stok', fn($s) => $s->where('jumlah_stok', '<=', 0)))
      ->when($this->filterStok === 'menipis', fn($q) => $q->whereHas('stok', fn($s) => $s
          ->where('jumlah_stok', '>', 0)
          ->whereColumn('jumlah_stok', '<=', 'stok_minimum')))
      ->when($this->filterStok === 'aman', fn($q) => $q->whereHas('stok', fn($s) => $s->whereColumn('jumlah_stok', '>', 'stok_minimum')))
      ->orderBy('kode_barang')
      ->get();

    $writer = new Writer();
    $writer->openToBrowser('laporan-data-barang-' . now()->format('Ymd') . '.xlsx');
    $writer->addRow(Row::fromValues(['KODE', 'NAMA BARANG', 'KATEGORI', 'SATUAN', 'HARGA JUAL', 'STOK MIN', 'STOK', 'STATUS']));

    foreach ($data as $b) {
      $stok   = $b->stok->jumlah_stok ?? 0;
      $status = 'Aman';
      if ($stok <= 0) {
        $status = 'Habis';
      } elseif ($stok <= $b->stok_minimum) {
        $status = 'Menipis';
      }

      $writer->addRow(Row::fromValues([
        $b->kode_barang,
        $b->nama_barang,
        $b->kategori,
        $b->satuan,
        'Rp ' . number_format($b->harga_jual_default ?? 0, 0, ',', '.'),
        $b->stok_minimum,
        $stok,
        $status,
      ]));
    }

    $writer->close();
    exit;
  }
}