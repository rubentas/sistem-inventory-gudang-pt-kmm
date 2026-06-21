<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('users', function (Blueprint $table) {
      $table->string('nik')->nullable()->after('no_telp');
      $table->text('alamat')->nullable()->after('nik');
      $table->string('foto_ktp')->nullable()->after('alamat');
      $table->string('surat_kerja')->nullable()->after('foto_ktp');
      $table->string('foto_profil')->nullable()->after('surat_kerja');
    });
  }

  public function down(): void {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn(['nik', 'alamat', 'foto_ktp', 'surat_kerja', 'foto_profil']);
    });
  }
};