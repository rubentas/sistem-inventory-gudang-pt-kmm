<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model {
    protected $primaryKey = 'id_masuk';

    protected $fillable = [
        'id_barang',
        'id_supplier',
        'id_user',
        'no_nota',
        'no_surat_jalan',
        'jumlah',
        'tanggal_masuk',
        'sumber',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function barang() {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
