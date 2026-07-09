<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailReturPenjualan extends Model {
  protected $primaryKey = 'id_detail_retur';

  protected $fillable = [
    'id_retur',
    'id_barang',
    'jumlah_retur',
    'harga_satuan',
    'subtotal_retur',
    'alasan',
    'kondisi_barang',
    'tujuan',
    'keterangan',
  ];

  public function retur() {
    return $this->belongsTo(ReturPenjualan::class, 'id_retur', 'id_retur');
  }

  public function barang() {
    return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
  }
}
