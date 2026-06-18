<?php
namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\OrderSales;
use App\Models\Stok;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder {
  public function run(): void {
    // === BERSIHIN SEMUA DATA DULU ===
    DB::statement('SET FOREIGN_KEY_CHECKS=0');

    BarangKeluar::truncate();
    BarangMasuk::truncate();
    OrderSales::truncate();
    Stok::truncate();
    Barang::truncate();

    DB::statement('SET FOREIGN_KEY_CHECKS=1');

    /*
         | Data diambil dari Saldo Stok Gudang Tanjung per 17-03-2026 (PDF).
         | Satuan  : Dos (satuan jual per karton ke toko)
         | Harga   : harga jual per Dos (bukan per Pcs)
         | Stok Min: 10 Dos (kebijakan depo — bikin RO kalau sisa ≤ 10 karton)
         | Stok    : dikonversi dari Pcs ÷ isi per Dos
         |
         | Konversi stok Pcs → Dos:
         |   French Fries  40 pcs/dos  → 9.920 ÷ 40  = 248 Dos
         |   TAIKO         60 pcs/dos  → 4.800 ÷ 60  =  80 Dos
         |   KETAGI        40 pcs/dos  → 5.160 ÷ 40  = 129 Dos
         |   STT MIE       100 pcs/dos → 26.000 ÷ 100= 260 Dos
         |   ULEG TERASI   100 pcs/dos → 52.100 ÷ 100= 521 Dos
         |   TWISKO        60 pcs/dos  → 15.822 ÷ 60  = 263 Dos
         |   HOT BALL      256 pcs/dos → 27.472 ÷ 256 = 107 Dos
         |   HOT-HOT PAK   50 pcs/dos  → 49.085 ÷ 50  =  39 Dos (dibulatkan)
         |   SUN KARA      24 pcs/dos  → 6.976 ÷ 24   = 290 Dos
         |   GO POTATO     200 pcs/dos → 19.600 ÷ 200 =  98 Dos
         |   GO RIO COKLAT 200 pcs/dos → 24.200 ÷ 200 = 121 Dos
         |   FINNA BUMBU   120 pcs/dos → 12.220 ÷ 120 = 101 Dos
         |   ICHITAN MTea  24 pcs/dos  → 1.637 ÷ 24   =  68 Dos
         |   ICHITAN Brown 24 pcs/dos  → 1.969 ÷ 24   =  82 Dos
         |   HI-LO WHITE   150 pcs/dos → 29.290 ÷ 150 = 195 Dos
         |   PENDEKAR BIRU 400 pcs/dos → 31.925 ÷ 400 =  25 Dos (dibulatkan)
         |   GRESH JERUK   12 pcs/dos  → 1.164 ÷ 12   =  97 Dos
         |   GRESH MANGGA  12 pcs/dos  → 1.151 ÷ 12   =  95 Dos
         |   ULEG GEPREK   100 pcs/dos → 12.730 ÷ 100 = 127 Dos
         |   KUSUKA BBQ    10 pcs/dos  → 4 Dos (stok kecil, diambil dari PDF)
        */

    $barangs = [
      // ── SNACK ────────────────────────────────────────────────────────────
      [
        'kode_barang'        => 'A281008S',
        'nama_barang'        => 'FRENCH FRIES NEW : 40 @2000',
        'kategori'           => 'Snack',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 75500,
        'stok_awal'          => 248,
      ],
      [
        'kode_barang'        => 'A185021S',
        'nama_barang'        => 'IWM TAIKO 28 GR @2000 SP PGG : 6X10',
        'kategori'           => 'Snack',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 106000,
        'stok_awal'          => 80,
      ],
      [
        'kode_barang'        => 'A281017S',
        'nama_barang'        => 'KETAGI RASA AYAM GORENG CBP@2000 : 40',
        'kategori'           => 'Snack',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 77500,
        'stok_awal'          => 129,
      ],
      [
        'kode_barang'        => 'A281003S',
        'nama_barang'        => 'CBP 1000 TWISKO : 60',
        'kategori'           => 'Snack',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 109000,
        'stok_awal'          => 263,
      ],
      [
        'kode_barang'        => 'A131001S',
        'nama_barang'        => 'HOT BALL MANGGA (HBM) 16X16',
        'kategori'           => 'Snack',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 112000,
        'stok_awal'          => 107,
      ],
      [
        'kode_barang'        => 'A131002S',
        'nama_barang'        => 'HOT-HOT PAK 50X25',
        'kategori'           => 'Snack',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 84000,
        'stok_awal'          => 39,
      ],
      [
        'kode_barang'        => 'A131003S',
        'nama_barang'        => 'PENDEKAR BIRU PAK 50X25',
        'kategori'           => 'Snack',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 82500,
        'stok_awal'          => 25,
      ],
      [
        'kode_barang'        => 'A283055S',
        'nama_barang'        => 'GO POTATO 10X20',
        'kategori'           => 'Snack',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 85000,
        'stok_awal'          => 98,
      ],
      [
        'kode_barang'        => 'A283056S',
        'nama_barang'        => 'GO RIO RIO COKLAT 10X20',
        'kategori'           => 'Snack',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 85000,
        'stok_awal'          => 121,
      ],
      [
        'kode_barang'        => 'A851002S',
        'nama_barang'        => 'KUSUKA KEJU BAKAR 180 GR : 10',
        'kategori'           => 'Snack',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 158000,
        'stok_awal'          => 15,
      ],

      // ── MIE ──────────────────────────────────────────────────────────────
      [
        'kode_barang'        => 'A282017S',
        'nama_barang'        => 'STT MIE GOR (HIJAU) @1000 : 5X20',
        'kategori'           => 'Mie',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 84000,
        'stok_awal'          => 260,
      ],
      [
        'kode_barang'        => 'A282022S',
        'nama_barang'        => 'MIE GEMEZ NEW AYAM GORENG : 4X20',
        'kategori'           => 'Mie',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 69000,
        'stok_awal'          => 63,
      ],

      // ── SAMBAL & SAUS ────────────────────────────────────────────────────
      [
        'kode_barang'        => 'A902701S',
        'nama_barang'        => 'ULEG SAMBEL TERASI 18G : 10X10',
        'kategori'           => 'Sambal & Saus',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 81000,
        'stok_awal'          => 521,
      ],
      [
        'kode_barang'        => 'A902731S',
        'nama_barang'        => 'ULEG SAMBEL GEPREK 18G : 10X10',
        'kategori'           => 'Sambal & Saus',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 81000,
        'stok_awal'          => 127,
      ],

      // ── BUMBU & REMPAH ───────────────────────────────────────────────────
      [
        'kode_barang'        => 'A902861S',
        'nama_barang'        => 'FINNA TP BUMBU CRISPY 12X10',
        'kategori'           => 'Bumbu & Rempah',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 101000,
        'stok_awal'          => 101,
      ],

      // ── MINUMAN RTD ──────────────────────────────────────────────────────
      [
        'kode_barang'        => 'A702001S',
        'nama_barang'        => 'ICHITAN THAI MILK TEA 24X310 ML',
        'kategori'           => 'Minuman RTD',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 204000,
        'stok_awal'          => 68,
      ],
      [
        'kode_barang'        => 'A702005S',
        'nama_barang'        => 'ICHITAN BROWN SUGAR MILK 24X310ML',
        'kategori'           => 'Minuman RTD',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 204000,
        'stok_awal'          => 82,
      ],
      [
        'kode_barang'        => 'A177001S',
        'nama_barang'        => 'GRESH JERUK NIPIS 510 ML : 12',
        'kategori'           => 'Minuman RTD',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 98000,
        'stok_awal'          => 81,
      ],
      [
        'kode_barang'        => 'A179051S',
        'nama_barang'        => 'SUN KARA 110 ML : 24',
        'kategori'           => 'Minuman RTD',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 65000,
        'stok_awal'          => 290,
      ],

      // ── SUSU & NUTRISI ───────────────────────────────────────────────────
      [
        'kode_barang'        => 'N105081S',
        'nama_barang'        => 'HI LO DRINK WHITE CHOCOLAT PLS',
        'kategori'           => 'Susu & Nutrisi',
        'satuan'             => 'Dos',
        'stok_minimum'       => 10,
        'harga_jual_default' => 165000,
        'stok_awal'          => 195,
      ],
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
