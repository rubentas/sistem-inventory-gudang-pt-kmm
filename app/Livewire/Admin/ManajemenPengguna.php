<?php
namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ManajemenPengguna extends Component {
  use WithPagination;

  public int | null $id_user = null;
  public string $nama        = '';
  public string $username    = '';
  public string $password    = '';
  public string $email       = '';
  public string $no_telp     = '';
  public string $role        = '';

  public string $search = '';
  public bool $isEdit   = false;

  protected $rules = [
    'nama'     => 'required|string|max:255',
    'username' => 'required|string|max:100',
    'password' => 'required|string|min:4',
    'email'    => 'nullable|email|max:255',
    'no_telp'  => 'nullable|string|max:20',
    'role'     => 'required|in:kepala_gudang,admin_fakturis,sales,pimpinan',
  ];

  protected $messages = [
    'nama.required'     => 'Nama wajib diisi.',
    'username.required' => 'Username wajib diisi.',
    'username.unique'   => 'Username sudah digunakan.',
    'password.required' => 'Password wajib diisi.',
    'password.min'      => 'Password minimal 4 karakter.',
    'role.required'     => 'Role wajib dipilih.',
  ];

  public function updatedSearch(): void {$this->resetPage();}

  public function resetForm(): void {
    $this->reset(['id_user', 'nama', 'username', 'password', 'email', 'no_telp', 'role', 'isEdit']);
    $this->resetErrorBag();
  }

  public function openAddModal(): void {$this->resetForm();
    $this->dispatch('openModal');}

  public function edit(int $id): void {
    $user           = User::findOrFail($id);
    $this->id_user  = $user->id_user;
    $this->nama     = $user->nama;
    $this->username = $user->username;
    $this->password = '';
    $this->email    = $user->email ?? '';
    $this->no_telp  = $user->no_telp ?? '';
    $this->role     = $user->role;
    $this->isEdit   = true;
    $this->resetErrorBag();
    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $rules = $this->rules;
    if ($this->isEdit) {
      $rules['username'] = 'required|string|max:100|unique:users,username,' . $this->id_user . ',id_user';
      $rules['password'] = 'nullable|string|min:4';
    } else {
      $rules['username'] = 'required|string|max:100|unique:users,username';
    }
    $this->validate($rules);

    $data = [
      'nama'     => $this->nama,
      'username' => $this->username,
      'email'    => $this->email ?: null,
      'no_telp'  => $this->no_telp ?: null,
      'role'     => $this->role,
    ];
    if ($this->password) {
      $data['password'] = Hash::make($this->password);
    }

    if ($this->isEdit) {
      User::findOrFail($this->id_user)->update($data);
      $message = 'Data pengguna berhasil diperbarui.';
    } else {
      User::create($data);
      $message = 'Pengguna baru berhasil ditambahkan.';
    }
    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: $message);
  }

  public function update(): void {$this->simpan();}

  public function hapus(int $id): void {
    $user = User::findOrFail($id);
    if ($user->id_user === auth()->user()->id_user) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Anda tidak dapat menghapus akun sendiri.');
      return;
    }
    $user->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data pengguna berhasil dihapus.');
  }

  public function render() {
    $users = User::where('role', '!=', 'sales')
      ->when($this->search, fn($q) => $q->where('nama', 'like', '%' . $this->search . '%')->orWhere('username', 'like', '%' . $this->search . '%'))
      ->orderBy('role')->paginate(10);

    return view('components.admin.manajemen-pengguna', compact('users'));
  }
}
