<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama', 255);
            $table->string('username', 100)->unique();
            $table->string('password', 255);
            $table->string('email', 255)->unique()->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->enum('role', ['kepala_gudang', 'admin_fakturis', 'sales', 'pimpinan']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
