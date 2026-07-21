<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Wilayah as WilayahModel;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Wilayah extends Component
{
  use WithPagination;

  // Filter
  public string $search = '';

  // Form
  public int | null $id_wilayah = null;
  public string $nama_wilayah   = '';
  public int | string $id_user  = '';
  public string $keterangan     = '';

  // UI state
  public bool $isEdit = false;

  protected $rules = [
    'nama_wilayah' => 'required|string|max:100',
    'id_user'      => 'nullable|exists:users,id_user',
    'keterangan'   => 'nullable|string',
  ];

  protected $messages = [
    'nama_wilayah.required' => 'Nama wilayah wajib diisi.',
  ];

  public function updatedSearch(): void
  {
    $this->resetPage();
  }

  public function resetFilters(): void
  {
    $this->search = '';
    $this->resetPage();
  }

  public function resetForm(): void
  {
    $this->reset(['id_wilayah', 'nama_wilayah', 'id_user', 'keterangan', 'isEdit']);
    $this->resetErrorBag();
  }

  public function openAddModal(): void
  {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function edit(int $id): void
  {
    $wilayah            = WilayahModel::findOrFail($id);
    $this->id_wilayah   = $wilayah->id_wilayah;
    $this->nama_wilayah = $wilayah->nama_wilayah;
    $this->id_user      = $wilayah->id_user ?? '';
    $this->keterangan   = $wilayah->keterangan ?? '';
    $this->isEdit       = true;
    $this->resetErrorBag();
    $this->dispatch('openModal');
  }

  public function simpan(): void
  {
    $this->validate();

    $data = [
      'nama_wilayah' => $this->nama_wilayah,
      'id_user'      => $this->id_user ?: null,
      'keterangan'   => $this->keterangan ?: null,
    ];

    if ($this->isEdit) {
      WilayahModel::findOrFail($this->id_wilayah)->update($data);
      $message = 'Data wilayah berhasil diperbarui.';
    } else {
      WilayahModel::create($data);
      $message = 'Data wilayah berhasil ditambahkan.';
    }

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: $message);
  }

  public function update(): void
  {
    $this->simpan();
  }

  public function hapus(int $id): void
  {
    $wilayah = WilayahModel::findOrFail($id);
    if ($wilayah->orderSales()->count() > 0 || $wilayah->barangKeluar()->count() > 0) {
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Wilayah tidak bisa dihapus karena sudah memiliki transaksi.');
      return;
    }
    $wilayah->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data wilayah berhasil dihapus.');
  }

  public function getStats(): array
  {
    return [
      'totalWilayah'       => WilayahModel::count(),
      'totalBarangKeluar'  => WilayahModel::withSum('barangKeluar', 'jumlah')->get()->sum('barang_keluar_sum_jumlah'),
    ];
  }

  public function render()
  {
    $wilayahs = WilayahModel::with('sales')
      ->withSum('barangKeluar', 'jumlah')
      ->when($this->search, function ($q) {
        $q->where('nama_wilayah', 'like', '%' . $this->search . '%');
      })
      ->orderBy('nama_wilayah')
      ->paginate(10);

    $sales = User::where('role', 'sales')->orderBy('nama')->get();

    return view('components.admin.wilayah', [
      'wilayahs' => $wilayahs,
      'sales'    => $sales,
      'stats'    => $this->getStats(),
    ]);
  }
}
