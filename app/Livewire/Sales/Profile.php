<?php
namespace App\Livewire\Sales;

use Illuminate\Support\Facades\Auth;
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

    $user->update($data);

    // Refresh data yang ditampilkan setelah update
    $this->existing_ktp   = $user->fresh()->foto_ktp;
    $this->existing_surat = $user->fresh()->surat_kerja;
    $this->existing_foto  = $user->fresh()->foto_profil;

    // Reset file input biar kosong
    $this->reset(['foto_ktp', 'surat_kerja', 'foto_profil']);

    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Profile berhasil diperbarui.');
  }

  public function render() {
    return view('components.sales.profile');
  }
}
