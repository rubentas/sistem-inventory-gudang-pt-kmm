<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('retur_pembelians', function (Blueprint $table) {
      $table->id('id_retur_pembelian');
      $table->string('no_retur')->unique();
      $table->unsignedBigInteger('id_supplier');
      $table->unsignedBigInteger('id_barang');
      $table->unsignedBigInteger('id_user');
      $table->integer('jumlah');
      $table->enum('tujuan', ['Gudang Banjarmasin', 'Supplier'])->default('Supplier');
      $table->string('keterangan')->nullable();
      $table->date('tanggal_retur');
      $table->timestamps();

      $table->foreign('id_supplier')->references('id_supplier')->on('suppliers')->onDelete('cascade');
      $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
      $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
    });
  }

  public function down(): void {
    Schema::dropIfExists('retur_pembelians');
  }
};