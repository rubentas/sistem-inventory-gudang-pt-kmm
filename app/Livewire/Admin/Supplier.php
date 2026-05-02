<?php
namespace App\Livewire\Admin;

use App\Models\Supplier as SupplierModel;
use Livewire\Component;
use Livewire\WithPagination;

class Supplier extends Component {
    use WithPagination;

    // Form fields
    public $id_supplier          = null;
    public string $kode_supplier = '';
    public string $nama_supplier = '';
    public string $alamat        = '';
    public string $no_telp       = '';
    public string $email         = '';
    public string $keterangan    = '';

    // UI state
    public string $search = '';
    public bool $showForm = false;
    public bool $isEdit   = false;

    protected $rules = [
        'kode_supplier' => 'required|string|max:50',
        'nama_supplier' => 'required|string|max:255',
        'alamat'        => 'nullable|string',
        'no_telp'       => 'nullable|string|max:20',
        'email'         => 'nullable|email|max:255',
        'keterangan'    => 'nullable|string',
    ];

    public function bukaTambah(): void {
        $this->reset(['id_supplier', 'kode_supplier', 'nama_supplier', 'alamat', 'no_telp', 'email', 'keterangan']);
        $this->isEdit   = false;
        $this->showForm = true;
    }

    public function bukaEdit(int $id): void {
        $supplier            = SupplierModel::findOrFail($id);
        $this->id_supplier   = $supplier->id_supplier;
        $this->kode_supplier = $supplier->kode_supplier;
        $this->nama_supplier = $supplier->nama_supplier;
        $this->alamat        = $supplier->alamat ?? '';
        $this->no_telp       = $supplier->no_telp ?? '';
        $this->email         = $supplier->email ?? '';
        $this->keterangan    = $supplier->keterangan ?? '';
        $this->isEdit        = true;
        $this->showForm      = true;
    }

    public function batal(): void {
        $this->reset(['id_supplier', 'kode_supplier', 'nama_supplier', 'alamat', 'no_telp', 'email', 'keterangan']);
        $this->showForm = false;
    }

    public function simpan(): void {
        $rules = $this->rules;

        if ($this->isEdit) {
            $rules['kode_supplier'] = 'required|string|max:50|unique:suppliers,kode_supplier,' . $this->id_supplier . ',id_supplier';
        } else {
            $rules['kode_supplier'] = 'required|string|max:50|unique:suppliers,kode_supplier';
        }

        $this->validate($rules);

        $data = [
            'kode_supplier' => $this->kode_supplier,
            'nama_supplier' => $this->nama_supplier,
            'alamat'        => $this->alamat ?: null,
            'no_telp'       => $this->no_telp ?: null,
            'email'         => $this->email ?: null,
            'keterangan'    => $this->keterangan ?: null,
        ];

        if ($this->isEdit) {
            SupplierModel::findOrFail($this->id_supplier)->update($data);
            session()->flash('success', 'Data supplier berhasil diperbarui.');
        } else {
            SupplierModel::create($data);
            session()->flash('success', 'Data supplier berhasil ditambahkan.');
        }

        $this->batal();
    }

    public function hapus(int $id): void {
        $supplier = SupplierModel::findOrFail($id);

        // Cek apakah supplier sudah punya transaksi barang masuk
        if ($supplier->barangMasuk()->count() > 0) {
            session()->flash('error', 'Supplier tidak bisa dihapus karena sudah memiliki transaksi barang masuk.');
            return;
        }

        $supplier->delete();
        session()->flash('success', 'Data supplier berhasil dihapus.');
    }

    public function render() {
        $suppliers = SupplierModel::when($this->search, function ($q) {
            $q->where('kode_supplier', 'like', '%' . $this->search . '%')
                ->orWhere('nama_supplier', 'like', '%' . $this->search . '%');
        })
            ->orderBy('kode_supplier')
            ->paginate(10);

        return view('components.admin.supplier', compact('suppliers'))
            ->layout('layouts.admin');
    }
}