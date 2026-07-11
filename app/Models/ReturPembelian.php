<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturPembelian extends Model {
  protected $primaryKey = 'id_retur_pembelian';

  protected $fillable = [
    'no_retur',
    'id_supplier',
    'id_barang',
    'id_user',
    'jumlah',
    'tujuan',
    'keterangan',
    'tanggal_retur',
  ];

  protected $casts = [
    'tanggal_retur' => 'date',
  ];

  public function supplier() {
    return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
  }

  public function barang() {
    return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
  }

  public function user() {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }
}