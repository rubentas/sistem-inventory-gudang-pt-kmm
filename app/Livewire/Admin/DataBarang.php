<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use Livewire\Component;
use Livewire\WithPagination;

class DataBarang extends Component {
    use WithPagination;

    public $id_barang          = null;
    public string $kode_barang = '';
    public string $nama_barang = '';
    public string $kategori    = '';
    public string $satuan      = 'Pcs';
    public $stok_minimum       = 0;
    public string $keterangan  = '';

    public string $search = '';
    public bool $showForm = false;
    public bool $isEdit   = false;

    protected $rules = [
        'kode_barang'  => 'required|string|max:50',
        'nama_barang'  => 'required|string|max:255',
        'kategori'     => 'required|string|max:100',
        'satuan'       => 'required|string|max:20',
        'stok_minimum' => 'required|integer|min:0',
        'keterangan'   => 'nullable|string',
    ];

    public function bukaTambah(): void {
        $this->reset(['id_barang', 'kode_barang', 'nama_barang', 'kategori', 'satuan', 'stok_minimum', 'keterangan']);
        $this->satuan   = 'Pcs';
        $this->isEdit   = false;
        $this->showForm = true;
    }

    public function bukaEdit(int $id): void {
        $barang             = Barang::findOrFail($id);
        $this->id_barang    = $barang->id_barang;
        $this->kode_barang  = $barang->kode_barang;
        $this->nama_barang  = $barang->nama_barang;
        $this->kategori     = $barang->kategori;
        $this->satuan       = $barang->satuan;
        $this->stok_minimum = $barang->stok_minimum;
        $this->keterangan   = $barang->keterangan ?? '';
        $this->isEdit       = true;
        $this->showForm     = true;
    }

    public function batal(): void {
        $this->reset(['id_barang', 'kode_barang', 'nama_barang', 'kategori', 'satuan', 'stok_minimum', 'keterangan']);
        $this->showForm = false;
    }

    public function simpan(): void {
        $rules = $this->rules;

        if ($this->isEdit) {
            // Validasi unique exclude current ID
            $rules['kode_barang'] = 'required|string|max:50|unique:barangs,kode_barang,' . $this->id_barang . ',id_barang';
        } else {
            // Validasi unique untuk baru
            $rules['kode_barang'] = 'required|string|max:50|unique:barangs,kode_barang';
        }

        $this->validate($rules);

        $data = [
            'kode_barang'  => $this->kode_barang,
            'nama_barang'  => $this->nama_barang,
            'kategori'     => $this->kategori,
            'satuan'       => $this->satuan,
            'stok_minimum' => $this->stok_minimum,
            'keterangan'   => $this->keterangan,
        ];

        if ($this->isEdit) {
            Barang::findOrFail($this->id_barang)->update($data);
            session()->flash('success', 'Data barang berhasil diperbarui.');
        } else {
            Barang::create($data);
            session()->flash('success', 'Data barang berhasil ditambahkan.');
        }

        $this->batal();
    }

    public function hapus(int $id): void {
        $barang = Barang::findOrFail($id);

        // Cek apakah barang sudah punya transaksi masuk atau keluar
        if ($barang->barangMasuk()->count() > 0 || $barang->barangKeluar()->count() > 0) {
            session()->flash('error', 'Barang tidak bisa dihapus karena sudah memiliki transaksi.');
            return;
        }

        $barang->delete();
        session()->flash('success', 'Data barang berhasil dihapus.');
    }

    public function render() {
        $barangs = Barang::with('stok')
            ->when($this->search, function ($q) {
                $q->where('nama_barang', 'like', '%' . $this->search . '%')
                    ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
            })
            ->orderBy('kode_barang')
            ->paginate(15);

        return view('components.admin.data-barang', compact('barangs'))
            ->layout('layouts.admin');
    }
}