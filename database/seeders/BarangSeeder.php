<?php
namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Stok;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder {
    public function run(): void {
        $barangs = [
            ['kode_barang' => 'A281008S', 'nama_barang' => 'FRENCH FRIES NEW : 40 @2000', 'kategori' => 'Snack', 'satuan' => 'Pcs', 'stok_minimum' => 100, 'stok_awal' => 9920],
            ['kode_barang' => 'A185021S', 'nama_barang' => 'IWM TAIKO 28 GR @2000 SP PGG : 6X10', 'kategori' => 'Snack', 'satuan' => 'Pcs', 'stok_minimum' => 50, 'stok_awal' => 4800],
            ['kode_barang' => 'A281017S', 'nama_barang' => 'KETAGI RASA AYAM GORENG CBP@2000 : 40', 'kategori' => 'Snack', 'satuan' => 'Pcs', 'stok_minimum' => 50, 'stok_awal' => 5160],
            ['kode_barang' => 'A282017S', 'nama_barang' => 'STT MIE GOR (HIJAU) @1000 : 5X20', 'kategori' => 'Mie', 'satuan' => 'Pcs', 'stok_minimum' => 100, 'stok_awal' => 26000],
            ['kode_barang' => 'A902701S', 'nama_barang' => 'ULEG SAMBEL TERASI 18G : 10X10', 'kategori' => 'Bumbu', 'satuan' => 'Pcs', 'stok_minimum' => 100, 'stok_awal' => 52100],
            ['kode_barang' => 'A281003S', 'nama_barang' => 'CBP 1000 TWISKO : 60', 'kategori' => 'Snack', 'satuan' => 'Pcs', 'stok_minimum' => 100, 'stok_awal' => 15822],
            ['kode_barang' => 'A131001S', 'nama_barang' => 'HOT BALL MANGGA (HBM) 16X16', 'kategori' => 'Snack', 'satuan' => 'Pcs', 'stok_minimum' => 100, 'stok_awal' => 27472],
            ['kode_barang' => 'A131002S', 'nama_barang' => 'HOT-HOT PAK 50X25', 'kategori' => 'Snack', 'satuan' => 'Pcs', 'stok_minimum' => 100, 'stok_awal' => 49085],
            ['kode_barang' => 'A179051S', 'nama_barang' => 'SUN KARA 110 ML : 24', 'kategori' => 'Minuman', 'satuan' => 'Pcs', 'stok_minimum' => 50, 'stok_awal' => 6976],
            ['kode_barang' => 'A283055S', 'nama_barang' => 'GO POTATO 10X20', 'kategori' => 'Snack', 'satuan' => 'Pcs', 'stok_minimum' => 100, 'stok_awal' => 19600],
            ['kode_barang' => 'A283056S', 'nama_barang' => 'GO RIO RIO COKLAT 10X20', 'kategori' => 'Snack', 'satuan' => 'Pcs', 'stok_minimum' => 100, 'stok_awal' => 24200],
            ['kode_barang' => 'A902861S', 'nama_barang' => 'FINNA TP BUMBU CRISPY 12X10', 'kategori' => 'Bumbu', 'satuan' => 'Pcs', 'stok_minimum' => 100, 'stok_awal' => 12220],
            ['kode_barang' => 'A702001S', 'nama_barang' => 'ICHITAN THAI MILK TEA 24X310 ML', 'kategori' => 'Minuman', 'satuan' => 'Pcs', 'stok_minimum' => 50, 'stok_awal' => 1637],
            ['kode_barang' => 'A702005S', 'nama_barang' => 'ICHITAN BROWN SUGAR MILK 24X310ML', 'kategori' => 'Minuman', 'satuan' => 'Pcs', 'stok_minimum' => 50, 'stok_awal' => 1969],
            ['kode_barang' => 'N105081S', 'nama_barang' => 'HI LO DRINK WHITE CHOCOLAT PLS', 'kategori' => 'Susu', 'satuan' => 'Pcs', 'stok_minimum' => 100, 'stok_awal' => 29290],
        ];

        foreach ($barangs as $data) {
            $stokAwal = $data['stok_awal'];
            unset($data['stok_awal']);

            $barang = Barang::create($data);

            Stok::create([
                'id_barang'    => $barang->id_barang,
                'jumlah_stok'  => $stokAwal,
                'stok_minimum' => $barang->stok_minimum,
                'updated_at'   => now(),
            ]);
        }
    }
}
