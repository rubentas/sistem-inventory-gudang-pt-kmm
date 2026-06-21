<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id_user
 * @property string $nama
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $no_telp
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class User extends Authenticatable {
  use Notifiable;

  protected $primaryKey = 'id_user';

  protected $fillable = [
    'nama',
    'username',
    'password',
    'email',
    'no_telp',
    'nik',
    'alamat',
    'foto_ktp',
    'surat_kerja',
    'foto_profil',
    'role',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected function casts(): array {
    return [
      'password' => 'hashed',
    ];
  }

  // Relasi: User bisa jadi penanggung jawab wilayah
  public function wilayah() {
    return $this->hasMany(Wilayah::class, 'id_user', 'id_user');
  }

  // Helper: cek role
  public function isKepalaGudang(): bool {
    return $this->role === 'kepala_gudang';
  }

  public function isAdminFakturis(): bool {
    return $this->role === 'admin_fakturis';
  }

  public function isSales(): bool {
    return $this->role === 'sales';
  }

  public function isPimpinan(): bool {
    return $this->role === 'pimpinan';
  }
}
