<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BarangMasuk;

class Supplier extends Model {
    protected $primaryKey = 'id_supplier';

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat',
        'no_telp',
        'email',
        'keterangan',
    ];

    public function barangMasuk() {
        return $this->hasMany(BarangMasuk::class, 'id_supplier', 'id_supplier');
    }
}