# Aplikasi Inventory dan Penjualan Barang Gudang Berbasis Web pada PT. Kuda Mas Mandiri Tanjung Tabalong

> Proyek Skripsi / Tugas Akhir — Aplikasi Berbasis Web untuk Manajemen Inventory dan Penjualan Barang Gudang

## 📖 Deskripsi Singkat

Aplikasi Inventory dan Penjualan Barang Gudang Berbasis Web ini merupakan sistem informasi yang dikembangkan untuk membantu **PT. Kuda Mas Mandiri Tanjung Tabalong** dalam mengelola proses pencatatan stok barang, transaksi penjualan, dan pelaporan secara terkomputerisasi. Sistem ini dibangun untuk menggantikan proses pencatatan manual yang sebelumnya rentan terhadap kesalahan input, keterlambatan pelaporan, dan kesulitan dalam memantau ketersediaan stok gudang secara real-time.

Aplikasi ini dirancang dengan pendekatan berbasis peran (*role-based access control*) yang melibatkan empat jenis pengguna, yaitu **Admin Fakturis**, **Sales**, **Kepala Gudang**, dan **Pimpinan**, dengan hak akses dan fungsi yang disesuaikan dengan tanggung jawab masing-masing. Melalui sistem ini, proses pencatatan barang masuk dan keluar, pembuatan faktur penjualan, pemantauan stok gudang, hingga pengambilan keputusan oleh pimpinan dapat dilakukan secara lebih cepat, terintegrasi, dan akurat.

## ✨ Fitur Utama

Fitur pada aplikasi ini dibagi berdasarkan peran (*role*) pengguna sebagai berikut.

### 🧾 Admin Fakturis
- Mengelola data master barang (tambah, ubah, hapus, dan lihat detail stok)
- Mengelola data pelanggan dan data supplier
- Mencatat transaksi penjualan barang
- Membuat dan mencetak faktur penjualan (invoice) dalam format PDF
- Melihat dan mengelola riwayat serta status transaksi
- Mengelola retur penjualan (apabila diperlukan)

### 💼 Sales
- Melihat katalog barang beserta ketersediaan stok secara real-time
- Membuat pesanan atau penawaran (*quotation*) untuk pelanggan
- Memantau status pesanan yang telah diajukan
- Melihat riwayat transaksi penjualan berdasarkan sales yang bersangkutan
- Melihat dashboard pencapaian target penjualan pribadi

### 📦 Kepala Gudang
- Mengelola pencatatan barang masuk dari supplier
- Mengelola dan memverifikasi pengeluaran barang untuk pengiriman
- Memantau stok barang dengan notifikasi stok minimum (*low stock alert*)
- Melakukan penyesuaian stok fisik (*stok opname*)
- Melihat dan mencetak laporan mutasi stok gudang

### 🧑‍💼 Pimpinan
- Melihat dashboard ringkasan data (total barang masuk, barang keluar, jumlah barang aktif, dan total supplier)
- Mengakses seluruh laporan (stok, penjualan, omzet, barang expired, stok kritis, barang terlaris, dan per wilayah distribusi)
- Mengekspor laporan dalam format PDF untuk keperluan evaluasi dan pengambilan keputusan

> **Catatan:** Daftar fitur di atas merupakan rancangan umum berdasarkan kebutuhan standar sistem inventory dan penjualan. Sesuaikan kembali dengan fitur yang benar-benar diimplementasikan pada aplikasi Anda.

## 🛠️ Teknologi yang Digunakan

| Teknologi | Fungsi dalam Aplikasi |
|---|---|
| **Laravel 13** | Framework PHP utama untuk membangun logika backend dan struktur aplikasi (MVC) |
| **Livewire 4** | Membangun komponen antarmuka yang dinamis dan reaktif tanpa banyak menulis JavaScript |
| **Tailwind CSS** | Framework CSS *utility-first* untuk mempercepat dan merapikan proses styling antarmuka |
| **MySQL** | Sistem manajemen basis data relasional untuk penyimpanan seluruh data aplikasi |
| **DomPDF** | Library untuk menghasilkan dokumen laporan dan faktur penjualan dalam format PDF |
| **Alpine.js** | Library JavaScript ringan untuk menambahkan interaktivitas pada sisi client |

## 💻 Spesifikasi Sistem Minimum

Berikut adalah kebutuhan minimum yang harus dipenuhi untuk menjalankan aplikasi ini:

| Kebutuhan | Spesifikasi Minimum |
|---|---|
| PHP | 8.3 atau lebih tinggi |
| MySQL | 8.0 atau lebih tinggi |
| Composer | Versi terbaru (2.x) |
| Node.js & NPM | Node.js 18.x atau lebih tinggi |
| Web Server | Laravel Herd atau Laragon (lingkungan pengembangan lokal) |
| Browser | Google Chrome, Mozilla Firefox, atau Microsoft Edge versi terbaru |

## 🚀 Cara Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah berikut secara berurutan untuk menjalankan aplikasi pada lingkungan lokal (*localhost*).

**1. Clone repository**
```bash
git clone https://github.com/username/nama-repository.git
cd nama-repository
```

**2. Install dependency PHP melalui Composer**
```bash
composer install
```

**3. Salin file environment**
```bash
cp .env.example .env
```

**4. Generate application key**
```bash
php artisan key:generate
```

**5. Konfigurasi koneksi database**

Buka file `.env`, lalu sesuaikan konfigurasi berikut dengan lingkungan lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kuda_mas_mandiri
DB_USERNAME=root
DB_PASSWORD=
```

**6. Buat database**

Buat database baru sesuai dengan nama pada `DB_DATABASE`, misalnya melalui phpMyAdmin, atau melalui terminal:
```bash
mysql -u root -p -e "CREATE DATABASE kuda_mas_mandiri"
```

**7. Jalankan migrasi dan seeder**
```bash
php artisan migrate --seed
```

**8. Install dependency Node.js**
```bash
npm install
```

**9. Build asset frontend**

Untuk build produksi:
```bash
npm run build
```
Atau untuk mode pengembangan (*hot reload*):
```bash
npm run dev
```

**10. Jalankan server lokal**
```bash
php artisan serve
```

**11. Akses aplikasi**

Buka browser dan kunjungi alamat berikut:
```
http://127.0.0.1:8000
```

## 🔑 Akun Default untuk Login

Setelah proses seeding berhasil dijalankan, gunakan akun berikut untuk login sesuai dengan peran masing-masing:

| Role | Username | Password |
|---|---|---|
| Admin Fakturis | admin | password123 |
| Sales | andi_sales | password123 |
| Kepala Gudang | kepalagudang | password123 |
| Pimpinan | pimpinan | password123 |

> **Catatan:** Akun di atas merupakan akun contoh hasil dari proses *seeding*. Sesuaikan dengan data pada file `UserSeeder.php` atau `DatabaseSeeder.php` pada proyek Anda, dan pastikan mengganti seluruh password default sebelum aplikasi digunakan pada lingkungan produksi.

## 📁 Struktur Folder / Modul Utama

Berikut adalah gambaran singkat struktur folder utama pada proyek ini:

```
kuda-mas-mandiri/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controller utama aplikasi
│   │   └── Livewire/        # Komponen Livewire per modul (Barang, Transaksi, Laporan, dll.)
│   ├── Models/               # Model data (Barang, Transaksi, User, Role, dll.)
│   └── Providers/
├── database/
│   ├── migrations/           # Skema/struktur tabel database
│   └── seeders/               # Data awal (akun default, data master)
├── resources/
│   ├── views/                 # Tampilan Blade dan komponen Livewire
│   ├── css/                   # Konfigurasi Tailwind CSS
│   └── js/                    # Konfigurasi Alpine.js
├── routes/
│   └── web.php                 # Definisi routing aplikasi
├── public/                     # Asset publik dan entry point aplikasi
└── storage/
    └── app/                     # Penyimpanan file, termasuk hasil cetak PDF
```

## 📊 Daftar Laporan yang Tersedia

Aplikasi ini menyediakan beberapa jenis laporan yang dapat dicetak dalam format PDF menggunakan DomPDF, di antaranya:

- Laporan Data Barang
- Laporan Supplier
- Laporan Wilayah Distribusi
- Laporan Barang Masuk
- Laporan Barang Keluar
- Laporan Stok Barang
- Laporan Inventory
- Laporan Order Sales
- Laporan Omzet (Penjualan)
- Laporan Barang Terlaris
- Laporan Barang Expired
- Laporan Retur Penjualan
- Laporan Retur Pembelian
- Laporan Stock Opname
- Laporan Invoice

## 📝 Catatan untuk Pengguna

- Aplikasi ini dikembangkan sebagai bagian dari tugas akhir/skripsi dan masih terbuka untuk pengembangan lebih lanjut sesuai kebutuhan.
- Disarankan untuk melakukan backup database secara berkala guna menghindari kehilangan data.
- Pastikan konfigurasi pada file `.env` sudah sesuai sebelum menjalankan aplikasi, terutama pada bagian koneksi database.
- Untuk lingkungan produksi, disarankan mengganti seluruh password default dan menonaktifkan mode debug dengan mengatur `APP_DEBUG=false`.
- Jika terjadi kendala pada saat instalasi, pastikan seluruh dependency (PHP, Composer, Node.js, dan MySQL) telah terpasang dengan versi yang sesuai dengan spesifikasi minimum.

## 🖼️ Screenshot

Berikut adalah tampilan antarmuka dari aplikasi ini:

| Halaman | Preview |
|---|---|
| Halaman Login | *![Halaman Login](docs/screenshots/login.png)* |
| Dashboard Admin Fakturis | *![Dashboard Admin](docs/screenshots/login.png)* |
| Dashboard Sales | *![Dashboard Sales](docs/screenshots/login.png)* |
| Dashboard Kepala Gudang | *![Dashboard Kepala Gudang](docs/screenshots/login.png)* |
| Dashboard Pimpinan | *![Dashboard Pimpinan](docs/screenshots/login.png)* |
| Contoh Faktur Penjualan (PDF) | *![Faktur Penjualan](docs/screenshots/login.png)* |

> Simpan gambar screenshot pada folder `docs/screenshots/`, kemudian tautkan menggunakan format berikut:

```markdown
![Nama Halaman](docs/screenshots/nama-file.png)
```

## 👨‍🎓 Kontributor / Identitas Mahasiswa

| Keterangan | Detail |
|---|---|
| Nama | Ruben Tri Ardian Saputra |
| NPM | 2210010485 |
| Program Studi | S1 Teknik Informatika |
| Fakultas | Teknologi Informasi |
| Universitas | Universitas Islam Kalimantan Muhammad Arsyad Al Banjari Banjarmasin |
| Dosen Pembimbing I | Nadiya Hijriana, ST., M.Kom |
| Dosen Pembimbing II | Rahmadi Agus, M.Kom., Ph.D |
| Tahun Akademik | 2025/2026 |
