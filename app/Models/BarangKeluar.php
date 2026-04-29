<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderSales;
use App\Models\Barang;
use App\Models\User;
use App\Models\Wilayah;

class BarangKeluar extends Model {
    protected $primaryKey = 'id_keluar';

    protected $fillable = [
        'id_barang',
        'id_order',
        'id_user',
        'id_wilayah',
        'jumlah',
        'tanggal_keluar',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
    ];

    public function barang() {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function order() {
        return $this->belongsTo(OrderSales::class, 'id_order', 'id_order');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function wilayah() {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }
}
