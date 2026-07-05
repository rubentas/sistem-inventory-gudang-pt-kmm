<?php
namespace App\Exports;

use App\Models\Stok;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

class StokBarangExport {
  protected $filterKategori;
  protected $filterStatus;
  protected $search;

  public function __construct($filterKategori, $filterStatus, $search) {
    $this->filterKategori = $filterKategori;
    $this->filterStatus   = $filterStatus;
    $this->search         = $search;
  }

  public function download() {
    $data = Stok::with('barang')
      ->when($this->filterKategori, fn($q) => $q->whereHas('barang', fn($b) => $b->where('kategori', $this->filterKategori)))
      ->when($this->filterStatus === 'habis', fn($q) => $q->where('jumlah_stok', '<=', 0))
      ->when($this->filterStatus === 'menipis', fn($q) => $q->whereColumn('jumlah_stok', '>', 0)
                                                     ->whereColumn('jumlah_stok', '<=', 'stok_minimum'))
      ->when($this->filterStatus === 'aman', fn($q) => $q->whereColumn('jumlah_stok', '>', 'stok_minimum'))
      ->when($this->search, fn($q) => $q->whereHas('barang', fn($b) => $b->where('nama_barang', 'like', '%' . $this->search . '%')))
      ->orderBy('jumlah_stok', 'asc')
      ->get();

    $writer = new Writer();
    $writer->openToBrowser('laporan-stok-barang.xlsx');

    $writer->addRow(Row::fromValues(['NO', 'KODE', 'NAMA BARANG', 'KATEGORI', 'STOK', 'MINIMUM', 'STATUS']));

    $no = 1;
    foreach ($data as $s) {
      $writer->addRow(Row::fromValues([
        $no++,
        $s->barang->kode_barang ?? '-',
        $s->barang->nama_barang ?? '-',
        $s->barang->kategori ?? '-',
        $s->jumlah_stok,
        $s->stok_minimum,
        $s->status,
      ]));
    }

    $writer->close();
    exit;
  }
}