<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('sales', function (Blueprint $table) {
      $table->id('id_sales');
      $table->string('kode_sales', 10)->unique();
      $table->string('nama_sales', 100);
      $table->string('no_hp', 20);
      $table->string('wilayah_tugas', 100);
      $table->enum('status', ['Aktif', 'Non-Aktif'])->default('Aktif');
      $table->string('keterangan', 255)->nullable();
      $table->timestamps();
    });
  }

  public function down(): void {
    Schema::dropIfExists('sales');
  }
};