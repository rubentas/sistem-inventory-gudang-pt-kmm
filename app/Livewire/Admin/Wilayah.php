<?php
namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Wilayah as WilayahModel;
use Livewire\Component;
use Livewire\WithPagination;

class Wilayah extends Component {
    use WithPagination;

    // Form fields
    public $id_wilayah          = null;
    public string $nama_wilayah = '';
    public int $jumlah_toko     = 0;
    public $id_user             = '';
    public string $keterangan   = '';

    // UI state
    public string $search = '';
    public bool $showForm = false;
    public bool $isEdit   = false;

    protected $rules = [
        'nama_wilayah' => 'required|string|max:100',
        'jumlah_toko'  => 'required|integer|min:0',
        'id_user'      => 'nullable|exists:users,id_user',
        'keterangan'   => 'nullable|string',
    ];

    public function bukaTambah(): void {
        $this->reset(['id_wilayah', 'nama_wilayah', 'jumlah_toko', 'id_user', 'keterangan']);
        $this->isEdit   = false;
        $this->showForm = true;
    }

    public function bukaEdit(int $id): void {
        $wilayah            = WilayahModel::findOrFail($id);
        $this->id_wilayah   = $wilayah->id_wilayah;
        $this->nama_wilayah = $wilayah->nama_wilayah;
        $this->jumlah_toko  = $wilayah->jumlah_toko;
        $this->id_user      = $wilayah->id_user;
        $this->keterangan   = $wilayah->keterangan ?? '';
        $this->isEdit       = true;
        $this->showForm     = true;
    }

    public function batal(): void {
        $this->reset(['id_wilayah', 'nama_wilayah', 'jumlah_toko', 'id_user', 'keterangan']);
        $this->showForm = false;
    }

    public function simpan(): void {
        $this->validate();

        $data = [
            'nama_wilayah' => $this->nama_wilayah,
            'jumlah_toko'  => $this->jumlah_toko,
            'id_user'      => $this->id_user ?: null,
            'keterangan'   => $this->keterangan ?: null,
        ];

        if ($this->isEdit) {
            WilayahModel::findOrFail($this->id_wilayah)->update($data);
            session()->flash('success', 'Data wilayah berhasil diperbarui.');
        } else {
            WilayahModel::create($data);
            session()->flash('success', 'Data wilayah berhasil ditambahkan.');
        }

        $this->batal();
    }

    public function hapus(int $id): void {
        $wilayah = WilayahModel::findOrFail($id);

        // Cek apakah wilayah sudah punya transaksi order sales atau barang keluar
        if ($wilayah->orderSales()->count() > 0 || $wilayah->barangKeluar()->count() > 0) {
            session()->flash('error', 'Wilayah tidak bisa dihapus karena sudah memiliki transaksi.');
            return;
        }

        $wilayah->delete();
        session()->flash('success', 'Data wilayah berhasil dihapus.');
    }

    public function render() {
        $wilayahs = WilayahModel::with('sales')
            ->when($this->search, function ($q) {
                $q->where('nama_wilayah', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nama_wilayah')
            ->paginate(10);

        $sales = User::where('role', 'sales')->orderBy('nama')->get();

        return view('components.admin.wilayah', compact('wilayahs', 'sales'))
            ->layout('layouts.admin');
    }
}