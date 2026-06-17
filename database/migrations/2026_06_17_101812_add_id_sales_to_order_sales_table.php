<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('order_sales', function (Blueprint $table) {
      $table->unsignedBigInteger('id_sales')->nullable()->after('id_user');
      $table->foreign('id_sales')->references('id_sales')->on('sales')->onDelete('set null');
    });
  }

  public function down(): void {
    Schema::table('order_sales', function (Blueprint $table) {
      $table->dropForeign(['id_sales']);
      $table->dropColumn('id_sales');
    });
  }
};
