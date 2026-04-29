<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('kode_barang', 50)->unique();
            $table->string('nama_barang', 255);
            $table->string('kategori', 100)->nullable();
            $table->string('satuan', 20)->default('Pcs');
            $table->integer('stok_minimum')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('barangs');
    }
};
