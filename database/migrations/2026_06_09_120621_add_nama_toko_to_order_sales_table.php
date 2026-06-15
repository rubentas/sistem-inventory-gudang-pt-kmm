<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('order_sales', function (Blueprint $table) {
      $table->string('nama_toko', 255)->nullable()->after('id_wilayah');
    });
  }

  public function down(): void {
    Schema::table('order_sales', function (Blueprint $table) {
      $table->dropColumn('nama_toko');
    });
  }
};