<?php
namespace App\Models;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Eloquent\Model;

class OrderSales extends Model {
  protected $primaryKey = 'id_order';

  protected $fillable = [
    'id_barang',
    'id_user',
    'id_wilayah',
    'nama_toko',
    'jumlah',
    'harga_jual',
    'subtotal',
    'tanggal_order',
    'status',
    'keterangan',
    'no_invoice',
  ];

  protected $casts = [
    'tanggal_order' => 'date',
  ];

  public function barang() {
    return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
  }

  public function user() {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }

  public function wilayah() {
    return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
  }

  public function barangKeluar() {
    return $this->hasOne(BarangKeluar::class, 'id_order', 'id_order');
  }
}
