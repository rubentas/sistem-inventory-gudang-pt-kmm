<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('barang_masuks', function (Blueprint $table) {
            $table->id('id_masuk');
            $table->unsignedBigInteger('id_barang');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
            $table->unsignedBigInteger('id_supplier')->nullable();
            $table->foreign('id_supplier')->references('id_supplier')->on('suppliers')->onDelete('set null');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->string('no_nota', 100);
            $table->string('no_surat_jalan', 100);
            $table->integer('jumlah');
            $table->date('tanggal_masuk');
            $table->string('sumber', 100);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('barang_masuks');
    }
};
