<?php

namespace App\Livewire\Admin;

use App\Models\Supplier as SupplierModel;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Supplier extends Component
{
  use WithPagination;

  // Filter
  public string $search = '';

  // Form
  public int|null $id_supplier = null;
  public string $kode_supplier = '';
  public string $nama_supplier = '';
  public string $nama_pemilik = '';
  public string $alamat = '';
  public string $no_telp = '';
  public string $email = '';
  public string $no_rekening = '';
  public string $keterangan = '';

  // UI state
  public bool $isEdit = false;

  protected $rules = [
    'kode_supplier' => 'required|string|max:50',
    'nama_supplier' => 'required|string|max:255',
    'nama_pemilik'  => 'nullable|string|max:255',
    'alamat'        => 'nullable|string',
    'no_telp'       => 'nullable|string|max:20',
    'email'         => 'nullable|email|max:255',
    'no_rekening'   => 'nullable|string|max:50',
    'keterangan'    => 'nullable|string',
  ];

  protected $messages = [
    'kode_supplier.required' => 'Kode supplier wajib diisi.',
    'kode_supplier.unique'   => 'Kode supplier sudah digunakan.',
    'nama_supplier.required' => 'Nama supplier wajib diisi.',
    'email.email'            => 'Format email tidak valid.',
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
    $this->reset([
      'id_supplier',
      'kode_supplier',
      'nama_supplier',
      'nama_pemilik',
      'alamat',
      'no_telp',
      'email',
      'no_rekening',
      'keterangan',
      'isEdit',
    ]);

    $this->resetErrorBag();
  }

  public function openAddModal(): void
  {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function edit(int $id): void
  {
    $supplier = SupplierModel::findOrFail($id);

    $this->id_supplier   = $supplier->id_supplier;
    $this->kode_supplier = $supplier->kode_supplier;
    $this->nama_supplier = $supplier->nama_supplier;
    $this->nama_pemilik  = $supplier->nama_pemilik ?? '';
    $this->alamat        = $supplier->alamat ?? '';
    $this->no_telp       = $supplier->no_telp ?? '';
    $this->email         = $supplier->email ?? '';
    $this->no_rekening   = $supplier->no_rekening ?? '';
    $this->keterangan    = $supplier->keterangan ?? '';

    $this->isEdit = true;

    $this->resetErrorBag();
    $this->dispatch('openModal');
  }

  public function simpan(): void
  {
    $rules = $this->rules;

    if ($this->isEdit) {
      $rules['kode_supplier'] =
        'required|string|max:50|unique:suppliers,kode_supplier,' .
        $this->id_supplier .
        ',id_supplier';
    } else {
      $rules['kode_supplier'] =
        'required|string|max:50|unique:suppliers,kode_supplier';
    }

    $this->validate($rules);

    $data = [
      'kode_supplier' => $this->kode_supplier,
      'nama_supplier' => $this->nama_supplier,
      'nama_pemilik'  => $this->nama_pemilik ?: null,
      'alamat'        => $this->alamat ?: null,
      'no_telp'       => $this->no_telp ?: null,
      'email'         => $this->email ?: null,
      'no_rekening'   => $this->no_rekening ?: null,
      'keterangan'    => $this->keterangan ?: null,
    ];

    if ($this->isEdit) {
      SupplierModel::findOrFail($this->id_supplier)->update($data);
      $message = 'Data supplier berhasil diperbarui.';
    } else {
      SupplierModel::create($data);
      $message = 'Data supplier berhasil ditambahkan.';
    }

    $this->resetForm();

    $this->dispatch(
      'dataSaved',
      type: 'success',
      title: 'Berhasil!',
      message: $message
    );
  }

  public function update(): void
  {
    $this->simpan();
  }

  public function hapus(int $id): void
  {
    $supplier = SupplierModel::findOrFail($id);

    if ($supplier->barangMasuk()->count() > 0) {
      $this->dispatch(
        'dataSaved',
        type: 'error',
        title: 'Gagal!',
        message: 'Supplier tidak bisa dihapus karena sudah memiliki transaksi.'
      );

      return;
    }

    $supplier->delete();

    $this->dispatch(
      'dataSaved',
      type: 'success',
      title: 'Berhasil!',
      message: 'Data supplier berhasil dihapus.'
    );
  }

  public function getStats(): array
  {
    return [
      'totalItems' => SupplierModel::count(),
    ];
  }

  public function render()
  {
    $suppliers = SupplierModel::when($this->search, function ($q) {
      $q->where('kode_supplier', 'like', '%' . $this->search . '%')
        ->orWhere('nama_supplier', 'like', '%' . $this->search . '%')
        ->orWhere('nama_pemilik', 'like', '%' . $this->search . '%');
    })
      ->orderBy('kode_supplier')
      ->paginate(10);

    return view('components.admin.supplier', [
      'suppliers' => $suppliers,
      'stats' => $this->getStats(),
    ]);
  }
}
