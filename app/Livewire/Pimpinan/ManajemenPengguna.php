<?php
namespace App\Livewire\Pimpinan;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class ManajemenPengguna extends Component {
    use WithPagination;

    // Form fields
    public $id_user         = null;
    public string $nama     = '';
    public string $username = '';
    public string $password = '';
    public string $email    = '';
    public string $no_telp  = '';
    public string $role     = '';

    // UI state
    public string $search = '';
    public bool $showForm = false;
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

    public function bukaTambah(): void {
        $this->reset(['id_user', 'nama', 'username', 'password', 'email', 'no_telp', 'role']);
        $this->isEdit   = false;
        $this->showForm = true;
    }

    public function bukaEdit(int $id): void {
        $user           = User::findOrFail($id);
        $this->id_user  = $user->id_user;
        $this->nama     = $user->nama;
        $this->username = $user->username;
        $this->password = '';
        $this->email    = $user->email ?? '';
        $this->no_telp  = $user->no_telp ?? '';
        $this->role     = $user->role;
        $this->isEdit   = true;
        $this->showForm = true;
    }

    public function batal(): void {
        $this->reset(['id_user', 'nama', 'username', 'password', 'email', 'no_telp', 'role']);
        $this->showForm = false;
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
            session()->flash('success', 'Data pengguna berhasil diperbarui.');
        } else {
            User::create($data);
            session()->flash('success', 'Pengguna baru berhasil ditambahkan.');
        }

        $this->batal();
    }

    public function hapus(int $id): void {
        $user = User::findOrFail($id);

        // Cegah menghapus diri sendiri
        if ($user->id_user === auth()->user()->id_user) {
            session()->flash('error', 'Anda tidak dapat menghapus akun sendiri.');
            return;
        }

        $user->delete();
        session()->flash('success', 'Data pengguna berhasil dihapus.');
    }

    public function render() {
        $users = User::when($this->search, function ($q) {
            $q->where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('username', 'like', '%' . $this->search . '%');
        })
            ->orderBy('role')
            ->paginate(10);

        return view('components.pimpinan.manajemen-pengguna', compact('users'))
            ->layout('layouts.pimpinan');
    }
}
