<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('retur_penjualans', function (Blueprint $table) {
      $table->string('status', 50)->default('Pengajuan')->change();
    });
  }

  public function down(): void {
    Schema::table('retur_penjualans', function (Blueprint $table) {
      $table->enum('status', ['Menunggu', 'Pengajuan', 'Cek Gudang', 'Cek Kasir', 'Disetujui', 'Selesai', 'Ditolak'])->default('Pengajuan')->change();
    });
  }
};
