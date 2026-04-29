<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model {
    protected $primaryKey = 'id_opname';

    protected $fillable = [
        'id_barang',
        'id_user',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'tanggal_opname',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_opname' => 'date',
    ];

    public function barang() {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
