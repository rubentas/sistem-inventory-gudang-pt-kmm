<?php
namespace App\Models;

use App\Models\BarangKeluar;
use App\Models\OrderSales;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model {
  protected $primaryKey = 'id_wilayah';

  protected $fillable = [
    'nama_wilayah',
    'id_user',
    'keterangan',
  ];

  public function sales() {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }

  public function orderSales() {
    return $this->hasMany(OrderSales::class, 'id_wilayah', 'id_wilayah');
  }

  public function barangKeluar() {
    return $this->hasMany(BarangKeluar::class, 'id_wilayah', 'id_wilayah');
  }
}
