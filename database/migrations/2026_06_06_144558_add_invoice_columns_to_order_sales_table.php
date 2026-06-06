<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('order_sales', function (Blueprint $table) {
      $table->string('no_invoice', 50)->nullable()->unique()->after('id_order');
      $table->decimal('harga_satuan', 12, 2)->default(0)->after('jumlah');
      $table->decimal('potongan', 12, 2)->default(0)->after('harga_satuan');
      $table->decimal('total_harga', 12, 2)->default(0)->after('potongan');
      $table->string('nama_toko', 255)->nullable()->after('total_harga');
    });
  }

  public function down(): void {
    Schema::table('order_sales', function (Blueprint $table) {
      $table->dropColumn(['no_invoice', 'harga_satuan', 'potongan', 'total_harga', 'nama_toko']);
    });
  }
};