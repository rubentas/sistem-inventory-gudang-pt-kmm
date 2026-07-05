<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturPenjualan extends Model {
  protected $primaryKey = 'id_retur';

  protected $fillable = [
    'no_retur',
    'id_order',
    'id_user',
    'tanggal_retur',
    'status',
  ];

  protected $casts = [
    'tanggal_retur' => 'date',
  ];

  public function order() {
    return $this->belongsTo(OrderSales::class, 'id_order', 'id_order');
  }

  public function user() {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }

  public function detailRetur() {
    return $this->hasMany(DetailReturPenjualan::class, 'id_retur', 'id_retur');
  }
}