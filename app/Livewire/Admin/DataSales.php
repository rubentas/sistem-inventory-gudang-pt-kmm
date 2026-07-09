<?php
namespace App\Livewire\Admin;

use App\Models\Sales;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class DataSales extends Component {
  use WithPagination;

  public string $search = '';

  // Form Sales
  public ?int $id_sales        = null;
  public string $nama_sales    = '';
  public string $no_hp         = '';
  public string $wilayah_tugas = '';
  public string $status        = 'Aktif';
  public string $keterangan    = '';

  // Form User (terkait)
  public ?int $id_user    = null;
  public string $username = '';
  public string $password = '';

  public bool $isEdit = false;

  protected function rules() {
    $rules = [
      'nama_sales'    => 'required|string|max:100',
      'no_hp'         => 'required|string|max:20',
      'wilayah_tugas' => 'required|string|max:100',
      'status'        => 'required|in:Aktif,Non-Aktif',
      'keterangan'    => 'nullable|string|max:255',
      'username'      => 'required|string|max:50|unique:users,username' . ($this->id_user ? ',' . $this->id_user . ',id_user' : ''),
      'password'      => $this->isEdit ? 'nullable|min:6' : 'required|min:6',
    ];

    return $rules;
  }

  protected $messages = [
    'nama_sales.required'    => 'Nama sales wajib diisi.',
    'no_hp.required'         => 'No. HP wajib diisi.',
    'wilayah_tugas.required' => 'Wilayah tugas wajib diisi.',
    'username.required'      => 'Username wajib diisi.',
    'username.unique'        => 'Username sudah digunakan.',
    'password.required'      => 'Password wajib diisi.',
    'password.min'           => 'Password minimal 6 karakter.',
  ];

  public function updatedSearch(): void {
    $this->resetPage();
  }

  public function resetForm(): void {
    $this->reset(['id_sales', 'id_user', 'nama_sales', 'no_hp', 'wilayah_tugas', 'status', 'keterangan', 'username', 'password', 'isEdit']);
    $this->status = 'Aktif';
    $this->resetErrorBag();
  }

  public function openAddModal(): void {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function edit(int $id): void {
    $s = Sales::with('user')->findOrFail($id);

    $this->id_sales      = $s->id_sales;
    $this->id_user       = $s->id_user;
    $this->nama_sales    = $s->nama_sales;
    $this->no_hp         = $s->no_hp;
    $this->wilayah_tugas = $s->wilayah_tugas;
    $this->status        = $s->status;
    $this->keterangan    = $s->keterangan ?? '';
    $this->username      = $s->user->username ?? '';
    $this->password      = '';
    $this->isEdit        = true;

    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $this->validate();

    DB::beginTransaction();
    try {
      // 1. Simpan/Update User
      $userData = [
        'nama'     => $this->nama_sales,
        'username' => $this->username,
        'role'     => 'sales',
      ];

      if ($this->password) {
        $userData['password'] = Hash::make($this->password);
      }

      if ($this->isEdit && $this->id_user) {
        $user = User::findOrFail($this->id_user);
        $user->update($userData);
      } else {
        $user          = User::create($userData);
        $this->id_user = $user->id_user;
      }

      // 2. Simpan/Update Sales
      $salesData = [
        'nama_sales'    => $this->nama_sales,
        'no_hp'         => $this->no_hp,
        'wilayah_tugas' => $this->wilayah_tugas,
        'status'        => $this->status,
        'keterangan'    => $this->keterangan ?: null,
        'id_user'       => $this->id_user,
      ];

      if ($this->isEdit) {
        Sales::findOrFail($this->id_sales)->update($salesData);
        $message = 'Data sales berhasil diperbarui.';
      } else {
        Sales::create($salesData);
        $message = 'Data sales berhasil ditambahkan.';
      }

      DB::commit();

      $this->resetForm();
      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: $message);
    } catch (\Throwable $e) {
      DB::rollBack();
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }

  public function update(): void {
    $this->simpan();
  }

  public function hapus(int $id): void {
    $sales = Sales::findOrFail($id);

    DB::beginTransaction();
    try {
      // Nonaktifkan user terkait, jangan hapus
      if ($sales->id_user) {
        $user = User::find($sales->id_user);
        if ($user) {
          $user->update(['role' => 'sales', 'status' => 'nonaktif']); // opsional
        }
      }

      $sales->delete();

      DB::commit();
      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data sales berhasil dihapus.');
    } catch (\Throwable $e) {
      DB::rollBack();
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Error: ' . $e->getMessage());
    }
  }

  public function getStats(): array {
    return [
      'total' => Sales::count(),
      'aktif' => Sales::where('status', 'Aktif')->count(),
    ];
  }

  public function render() {
    $sales = Sales::with('user')
      ->when($this->search, fn($q) => $q->where('nama_sales', 'like', '%' . $this->search . '%')
          ->orWhere('kode_sales', 'like', '%' . $this->search . '%'))
      ->orderBy('kode_sales')
      ->paginate(10);

    $wilayahs = Wilayah::orderBy('nama_wilayah')->get();

    return view('components.admin.data-sales', [
      'sales'    => $sales,
      'stats'    => $this->getStats(),
      'wilayahs' => $wilayahs,
    ]);
  }
}