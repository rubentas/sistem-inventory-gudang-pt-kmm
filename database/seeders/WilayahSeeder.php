<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder {
    public function run(): void {
        $sales1 = User::where('username', 'andi_sales')->first();
        $sales2 = User::where('username', 'sari_sales')->first();

        $wilayahs = [
            [
                'nama_wilayah' => 'Wilayah A - Tanjung Kota',
                'jumlah_toko'  => 20,
                'id_user'      => $sales1 ? $sales1->id_user : null,
                'keterangan'   => 'Area Tanjung Kota dan sekitarnya',
            ],
            [
                'nama_wilayah' => 'Wilayah B - Tanjung Utara',
                'jumlah_toko'  => 18,
                'id_user'      => $sales2 ? $sales2->id_user : null,
                'keterangan'   => 'Area Tanjung Utara dan sekitarnya',
            ],
            [
                'nama_wilayah' => 'Wilayah C - Tanjung Selatan',
                'jumlah_toko'  => 25,
                'id_user'      => $sales1 ? $sales1->id_user : null,
                'keterangan'   => 'Area Tanjung Selatan dan sekitarnya',
            ],
            [
                'nama_wilayah' => 'Wilayah D - Tanjung Timur',
                'jumlah_toko'  => 15,
                'id_user'      => $sales2 ? $sales2->id_user : null,
                'keterangan'   => 'Area Tanjung Timur dan sekitarnya',
            ],
            [
                'nama_wilayah' => 'Wilayah E - Tanjung Barat',
                'jumlah_toko'  => 22,
                'id_user'      => $sales1 ? $sales1->id_user : null,
                'keterangan'   => 'Area Tanjung Barat dan sekitarnya',
            ],
        ];

        foreach ($wilayahs as $wilayah) {
            Wilayah::create($wilayah);
        }
    }
}
