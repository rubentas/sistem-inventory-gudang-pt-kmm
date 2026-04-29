<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_sales', function (Blueprint $table) {
            $table->id('id_order');
            $table->unsignedBigInteger('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('id_wilayah')->nullable();
            $table->foreign('id_wilayah')->references('id_wilayah')->on('wilayahs')->onDelete('set null');
            $table->integer('jumlah');
            $table->date('tanggal_order');
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('order_sales');
    }
};
