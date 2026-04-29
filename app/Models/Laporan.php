<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model {
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_user',
        'jenis_laporan',
        'tanggal_awal',
        'tanggal_akhir',
        'tanggal_cetak',
    ];

    protected $casts = [
        'tanggal_awal'  => 'date',
        'tanggal_akhir' => 'date',
        'tanggal_cetak' => 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
