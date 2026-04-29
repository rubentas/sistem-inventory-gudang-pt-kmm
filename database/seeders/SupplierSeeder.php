<?php
namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder {
    public function run(): void {
        $suppliers = [
            [
                'kode_supplier' => 'SUP001',
                'nama_supplier' => 'PT. Nutrifood Indonesia',
                'alamat'        => 'Banjarmasin, Kalimantan Selatan',
                'no_telp'       => '0511-123456',
                'email'         => 'nutrifood@mail.com',
                'keterangan'    => 'Supplier Hi-Lo, L-Men, Tropicana Slim',
            ],
            [
                'kode_supplier' => 'SUP002',
                'nama_supplier' => 'PT. Orang Tua',
                'alamat'        => 'Banjarmasin, Kalimantan Selatan',
                'no_telp'       => '0511-234567',
                'email'         => 'orangtua@mail.com',
                'keterangan'    => 'Supplier minuman dan produk konsumsi',
            ],
            [
                'kode_supplier' => 'SUP003',
                'nama_supplier' => 'PT. Sekar Laut',
                'alamat'        => 'Banjarmasin, Kalimantan Selatan',
                'no_telp'       => '0511-345678',
                'email'         => 'sekarlaut@mail.com',
                'keterangan'    => 'Supplier kerupuk dan produk laut',
            ],
            [
                'kode_supplier' => 'SUP004',
                'nama_supplier' => 'KMM Pusat Banjarmasin',
                'alamat'        => 'Banjarmasin, Kalimantan Selatan',
                'no_telp'       => '0511-456789',
                'email'         => 'kmm.pusat@mail.com',
                'keterangan'    => 'Dropping dari KMM Pusat Banjarmasin',
            ],
            [
                'kode_supplier' => 'SUP005',
                'nama_supplier' => 'Gudang Barabai',
                'alamat'        => 'Barabai, Hulu Sungai Tengah, Kalimantan Selatan',
                'no_telp'       => '0517-123456',
                'email'         => 'gudang.barabai@mail.com',
                'keterangan'    => 'Dropping dari Gudang Barabai',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
