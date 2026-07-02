<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('inventories', function (Blueprint $table) {
      $table->id('id_inventory');
      $table->foreignId('id_barang')->constrained('barangs', 'id_barang')->onDelete('cascade');
      $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->onDelete('set null');
      $table->integer('stok_awal');
      $table->integer('barang_masuk');
      $table->integer('barang_keluar');
      $table->integer('stok_sistem');
      $table->integer('stok_fisik');
      $table->integer('selisih');
      $table->date('tanggal');
      $table->text('keterangan')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void {
    Schema::dropIfExists('inventories');
  }
};