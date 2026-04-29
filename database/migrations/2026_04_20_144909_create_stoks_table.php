<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stoks', function (Blueprint $table) {
            $table->id('id_stok');
            $table->unsignedBigInteger('id_barang')->unique();
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
            $table->integer('jumlah_stok')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stoks');
    }
};
