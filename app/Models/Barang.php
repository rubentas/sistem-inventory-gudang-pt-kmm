<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model {
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'stok_minimum',
        'keterangan',
    ];

    public function stok() {
        return $this->hasOne(Stok::class, 'id_barang', 'id_barang');
    }

    public function barangMasuk() {
        return $this->hasMany(BarangMasuk::class, 'id_barang', 'id_barang');
    }

    public function barangKeluar() {
        return $this->hasMany(BarangKeluar::class, 'id_barang', 'id_barang');
    }

    public function orderSales() {
        return $this->hasMany(OrderSales::class, 'id_barang', 'id_barang');
    }

    public function stockOpname() {
        return $this->hasMany(StockOpname::class, 'id_barang', 'id_barang');
    }
}