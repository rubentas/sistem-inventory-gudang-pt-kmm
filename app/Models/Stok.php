<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stok extends Model {
    protected $primaryKey = 'id_stok';
    public $timestamps    = false;

    protected $fillable = [
        'id_barang',
        'jumlah_stok',
        'stok_minimum',
        'updated_at',
    ];

    protected $dates = ['updated_at'];

    public function barang() {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    // Accessor: status stok (Aman / Menipis)
    public function getStatusAttribute(): string {
        return $this->jumlah_stok <= $this->stok_minimum ? 'Menipis' : 'Aman';
    }
}
