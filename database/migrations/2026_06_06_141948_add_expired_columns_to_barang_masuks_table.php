<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('barang_masuks', function (Blueprint $table) {
      $table->date('tanggal_expired')->nullable()->after('keterangan');
      $table->enum('status_expired', ['aman', 'hampir_expired', 'expired'])->default('aman')->after('tanggal_expired');
    });
  }

  public function down(): void {
    Schema::table('barang_masuks', function (Blueprint $table) {
      $table->dropColumn(['tanggal_expired', 'status_expired']);
    });
  }
};