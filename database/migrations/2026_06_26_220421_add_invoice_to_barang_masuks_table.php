<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('barang_masuks', function (Blueprint $table) {
      $table->string('no_invoice')->nullable()->after('keterangan');
      $table->string('bukti_pembayaran')->nullable()->after('no_invoice');
    });
  }

  public function down(): void {
    Schema::table('barang_masuks', function (Blueprint $table) {
      $table->dropColumn(['no_invoice', 'bukti_pembayaran']);
    });
  }
};
