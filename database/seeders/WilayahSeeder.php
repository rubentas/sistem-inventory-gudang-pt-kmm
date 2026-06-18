<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder {
  public function run(): void {
    // Matiin foreign key check
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    Wilayah::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    // Ambil akun sales yang sudah di-seed oleh UserSeeder
    $sales1 = User::where('username', 'andi_sales')->first();
    $sales2 = User::where('username', 'sari_sales')->first();

    $wilayahs = [
      [
        'nama_wilayah' => 'Tanjung Kota',
        'jumlah_toko'  => 25,
        'id_user'      => $sales1?->id_user,
        'keterangan'   => 'Area pusat Kota Tanjung dan pasar utama',
      ],
      [
        'nama_wilayah' => 'Murung Pudak',
        'jumlah_toko'  => 18,
        'id_user'      => $sales2?->id_user,
        'keterangan'   => 'Area Kecamatan Murung Pudak dan sekitarnya',
      ],
      [
        'nama_wilayah' => 'Kelua & Pugaan',
        'jumlah_toko'  => 20,
        'id_user'      => $sales1?->id_user,
        'keterangan'   => 'Area Kecamatan Kelua dan Pugaan',
      ],
      [
        'nama_wilayah' => 'Muara Uya & Jaro',
        'jumlah_toko'  => 15,
        'id_user'      => $sales2?->id_user,
        'keterangan'   => 'Area Kecamatan Muara Uya dan Jaro',
      ],
      [
        'nama_wilayah' => 'Haruai & Bintang Ara',
        'jumlah_toko'  => 17,
        'id_user'      => $sales1?->id_user,
        'keterangan'   => 'Area Kecamatan Haruai dan Bintang Ara',
      ],
    ];

    foreach ($wilayahs as $wilayah) {
      Wilayah::create($wilayah);
    }
  }
}
