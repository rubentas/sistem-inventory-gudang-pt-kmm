<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model {
  protected $table      = 'sales';
  protected $primaryKey = 'id_sales';

  protected $fillable = [
    'kode_sales',
    'nama_sales',
    'no_hp',
    'wilayah_tugas',
    'status',
    'keterangan',
    'id_user',
  ];

  public static function boot() {
    parent::boot();
    static::creating(function ($model) {
      $last              = static::latest('id_sales')->first();
      $num               = $last ? (int) substr($last->kode_sales, 3) + 1 : 1;
      $model->kode_sales = 'SLS' . str_pad($num, 3, '0', STR_PAD_LEFT);
    });
  }

  public function user() {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
  }

  public function wilayah() {
    return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
  }
}