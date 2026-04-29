<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        User::create([
            'nama'     => 'Ahmad Pimpinan',
            'username' => 'pimpinan',
            'password' => Hash::make('password'),
            'email'    => 'pimpinan@kmm.com',
            'no_telp'  => '081234567890',
            'role'     => 'pimpinan',
        ]);

        User::create([
            'nama'     => 'Eka Youllyati',
            'username' => 'eka_admin',
            'password' => Hash::make('password'),
            'email'    => 'eka@kmm.com',
            'no_telp'  => '081234567891',
            'role'     => 'admin_fakturis',
        ]);

        User::create([
            'nama'     => 'Budi Kepala Gudang',
            'username' => 'budi_kg',
            'password' => Hash::make('password'),
            'email'    => 'budi@kmm.com',
            'no_telp'  => '081234567892',
            'role'     => 'kepala_gudang',
        ]);

        User::create([
            'nama'     => 'Andi Sales',
            'username' => 'andi_sales',
            'password' => Hash::make('password'),
            'email'    => 'andi@kmm.com',
            'no_telp'  => '081234567893',
            'role'     => 'sales',
        ]);

        User::create([
            'nama'     => 'Sari Sales',
            'username' => 'sari_sales',
            'password' => Hash::make('password'),
            'email'    => 'sari@kmm.com',
            'no_telp'  => '081234567894',
            'role'     => 'sales',
        ]);
    }
}