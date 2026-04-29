<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('wilayahs', function (Blueprint $table) {
            $table->id('id_wilayah');
            $table->string('nama_wilayah', 100);
            $table->integer('jumlah_toko')->default(0);
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('wilayahs');
    }
};
