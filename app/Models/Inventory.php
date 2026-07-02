<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model {
  protected $primaryKey = 'id_inventory';

  protected $fillable = [
    'id_barang',
    'id_user',
    'stok_awal',
    'barang_masuk',
    'barang_keluar',
    'stok_sistem',
    'stok_fisik',
    'selisih',
    'tanggal',
    'keterangan',
  ];

  protected $casts = [
    'tanggal' => 'date',
  ];

  public function barang() {
    return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
  }

  public function user() {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }
}