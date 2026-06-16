<?php
namespace App\Models;

use App\Models\BarangMasuk;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {
  protected $primaryKey = 'id_supplier';

  protected $fillable = [
    'kode_supplier',
    'nama_supplier',
    'alamat',
    'no_telp',
    'email',
    'no_rekening',
    'keterangan',
  ];

  public function barangMasuk() {
    return $this->hasMany(BarangMasuk::class, 'id_supplier', 'id_supplier');
  }
}
