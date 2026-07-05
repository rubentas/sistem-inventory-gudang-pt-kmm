<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    // Header Retur
    Schema::create('retur_penjualans', function (Blueprint $table) {
      $table->id('id_retur');
      $table->string('no_retur')->unique();
      $table->unsignedBigInteger('id_order');
      $table->unsignedBigInteger('id_user');
      $table->date('tanggal_retur');
      $table->enum('status', ['Menunggu', 'Selesai'])->default('Menunggu');
      $table->timestamps();

      $table->foreign('id_order')->references('id_order')->on('order_sales')->onDelete('cascade');
      $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
    });

    // Detail Retur
    Schema::create('detail_retur_penjualans', function (Blueprint $table) {
      $table->id('id_detail_retur');
      $table->unsignedBigInteger('id_retur');
      $table->unsignedBigInteger('id_barang');
      $table->integer('jumlah_retur');
      $table->decimal('harga_satuan', 12, 2)->default(0);
      $table->decimal('subtotal_retur', 12, 2)->default(0);
      $table->string('alasan')->nullable();
      $table->enum('kondisi_barang', ['Bagus', 'Rusak'])->default('Bagus');
      $table->text('keterangan')->nullable();
      $table->timestamps();

      $table->foreign('id_retur')->references('id_retur')->on('retur_penjualans')->onDelete('cascade');
      $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
    });
  }

  public function down(): void {
    Schema::dropIfExists('detail_retur_penjualans');
    Schema::dropIfExists('retur_penjualans');
  }
};