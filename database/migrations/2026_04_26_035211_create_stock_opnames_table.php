<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id('id_opname');
            $table->unsignedBigInteger('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->integer('stok_sistem');
            $table->integer('stok_fisik');
            $table->integer('selisih');
            $table->date('tanggal_opname');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_opnames');
    }
};
