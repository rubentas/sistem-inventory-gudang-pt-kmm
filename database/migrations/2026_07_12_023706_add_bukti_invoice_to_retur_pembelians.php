<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('retur_pembelians', function (Blueprint $table) {
      $table->string('bukti_invoice')->nullable()->after('keterangan');
      $table->string('nama_file_asli')->nullable()->after('bukti_invoice');
    });
  }

  public function down(): void {
    Schema::table('retur_pembelians', function (Blueprint $table) {
      $table->dropColumn(['bukti_invoice', 'nama_file_asli']);
    });
  }
};