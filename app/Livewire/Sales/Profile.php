<?php
namespace App\Livewire\Sales;

use App\Models\Sales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.sales')]
class Profile extends Component {
  use WithFileUploads;

  public $nama;
  public $email;
  public $no_telp;
  public $nik;
  public $alamat;
  public $foto_ktp;
  public $surat_kerja;
  public $foto_profil;

  // File existing
  public $existing_ktp;
  public $existing_surat;
  public $existing_foto;

  public function mount() {
    $user                 = Auth::user();
    $this->nama           = $user->nama;
    $this->email          = $user->email;
    $this->no_telp        = $user->no_telp;
    $this->nik            = $user->nik;
    $this->alamat         = $user->alamat;
    $this->existing_ktp   = $user->foto_ktp;
    $this->existing_surat = $user->surat_kerja;
    $this->existing_foto  = $user->foto_profil;
  }

  public function updateProfile() {
    $user = Auth::user();

    $this->validate(
    [
      'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
    ],
    [
      'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
    ]
);
    $data = [
      'nama'    => $this->nama,
      'email'   => $this->email,
      'no_telp' => $this->no_telp,
      'nik'     => $this->nik,
      'alamat'  => $this->alamat,
    ];

    // Upload KTP
    if ($this->foto_ktp) {
      if ($user->foto_ktp) {
        Storage::disk('public')->delete($user->foto_ktp);
      }
      $data['foto_ktp'] = $this->foto_ktp->store('dokumen/ktp', 'public');
    }

    // Upload Surat Kerja
    if ($this->surat_kerja) {
      if ($user->surat_kerja) {
        Storage::disk('public')->delete($user->surat_kerja);
      }
      $data['surat_kerja'] = $this->surat_kerja->store('dokumen/surat', 'public');
    }

    // Upload Foto Profil
    if ($this->foto_profil) {
      if ($user->foto_profil) {
        Storage::disk('public')->delete($user->foto_profil);
      }
      $data['foto_profil'] = $this->foto_profil->store('dokumen/foto', 'public');
    }

    DB::beginTransaction();
    try {
      // Update User
      $user->update($data);

      // Update Sales (sinkron nama & no_hp)
      $sales = Sales::where('id_user', $user->id_user)->first();
      if ($sales) {
        $sales->update([
          'nama_sales' => $this->nama,
          'no_hp'      => $this->no_telp,
        ]);
      }

      DB::commit();

      // Refresh data
      $this->existing_ktp   = $user->fresh()->foto_ktp;
      $this->existing_surat = $user->fresh()->surat_kerja;
      $this->existing_foto  = $user->fresh()->foto_profil;

      $this->reset(['foto_ktp', 'surat_kerja', 'foto_profil']);

      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Profile berhasil diperbarui.');
    } catch (\Throwable $e) {
      DB::rollBack();
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Error: ' . $e->getMessage());
    }
  }

  public function render() {
    return view('components.sales.profile');
  }
}