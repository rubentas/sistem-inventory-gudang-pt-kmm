<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('id_supplier');
            $table->string('kode_supplier', 50)->unique();
            $table->string('nama_supplier', 255);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('suppliers');
    }
};
