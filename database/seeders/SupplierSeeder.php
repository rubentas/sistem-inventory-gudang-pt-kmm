<?php
namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder {
  public function run(): void {
    // Matiin foreign key check
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    Supplier::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    $suppliers = [
      [
        'kode_supplier' => 'SUP001',
        'nama_supplier' => 'PT. Nutrifood Indonesia',
        'alamat'        => 'Jl. Raya Bogor KM 27, Ciracas, Jakarta Timur',
        'no_telp'       => '021-8710343',
        'email'         => 'info@nutrifood.co.id',
        'no_rekening'   => '1380012345678',
        'keterangan'    => 'Supplier Hi-Lo, L-Men, Tropicana Slim',
      ],
      [
        'kode_supplier' => 'SUP002',
        'nama_supplier' => 'PT. Orang Tua Group',
        'alamat'        => 'Jl. Palmerah Barat No. 29, Jakarta Barat',
        'no_telp'       => '021-5482888',
        'email'         => 'ot@otgroup.co.id',
        'no_rekening'   => '0081234567890',
        'keterangan'    => 'Supplier minuman dan produk konsumsi (Gresh, Teh Gelas, dll)',
      ],
      [
        'kode_supplier' => 'SUP003',
        'nama_supplier' => 'PT. Sekar Laut Tbk',
        'alamat'        => 'Jl. Jenggolo No. 2, Sidoarjo, Jawa Timur',
        'no_telp'       => '031-8921481',
        'email'         => 'info@sekarlaut.com',
        'no_rekening'   => '0097654321001',
        'keterangan'    => 'Supplier Finna — kerupuk, bumbu crispy, sambal uleg, saus',
      ],
      [
        'kode_supplier' => 'SUP004',
        'nama_supplier' => 'KMM Pusat Banjarmasin',
        'alamat'        => 'Banjarmasin, Kalimantan Selatan',
        'no_telp'       => '0511-4368899',
        'email'         => 'pusat.kmm@gmail.com',
        'no_rekening'   => '0341112223334',
        'keterangan'    => 'Dropping barang dari KMM Pusat Banjarmasin ke Depo Tanjung',
      ],
      [
        'kode_supplier' => 'SUP005',
        'nama_supplier' => 'Gudang Barabai',
        'alamat'        => 'Barabai, Hulu Sungai Tengah, Kalimantan Selatan',
        'no_telp'       => '0517-41234',
        'email'         => 'gudang.barabai@gmail.com',
        'no_rekening'   => '0345556667778',
        'keterangan'    => 'Dropping barang dari Gudang Barabai ke Depo Tanjung',
      ],
      [
        'kode_supplier' => 'SUP006',
        'nama_supplier' => 'PT. Indofood CBP Sukses Makmur',
        'alamat'        => 'Jl. Jend. Sudirman Kav. 76-78, Jakarta Selatan',
        'no_telp'       => '021-5795000',
        'email'         => 'info@indofood.com',
        'no_rekening'   => '0081100223344',
        'keterangan'    => 'Supplier produk CBP — Twisko, French Fries, Ketagi, Go Potato, dll',
      ],
    ];

    foreach ($suppliers as $supplier) {
      Supplier::create($supplier);
    }
  }
}
