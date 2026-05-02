<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\BarangMasuk as BarangMasukModel;
use App\Models\Stok;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BarangMasuk extends Component {
    use WithPagination;

    // Form fields
    public $id_barang             = '';
    public $id_supplier           = '';
    public string $no_nota        = '';
    public string $no_surat_jalan = '';
    public $jumlah                = '';
    public string $tanggal_masuk  = '';
    public string $sumber         = '';
    public string $keterangan     = '';

    // UI state
    public string $search = '';
    public bool $showForm = false;
    public $editId        = null;

    protected $rules = [
        'id_barang'      => 'required|exists:barangs,id_barang',
        'id_supplier'    => 'required|exists:suppliers,id_supplier',
        'no_nota'        => 'required|string|max:100',
        'no_surat_jalan' => 'required|string|max:100',
        'jumlah'         => 'required|integer|min:1',
        'tanggal_masuk'  => 'required|date',
        'sumber'         => 'required|string|max:100',
        'keterangan'     => 'nullable|string',
    ];

    protected $messages = [
        'id_barang.required'      => 'Pilih barang terlebih dahulu.',
        'id_supplier.required'    => 'Pilih supplier terlebih dahulu.',
        'no_nota.required'        => 'No. Nota wajib diisi.',
        'no_surat_jalan.required' => 'No. Surat Jalan wajib diisi.',
        'jumlah.required'         => 'Jumlah wajib diisi.',
        'jumlah.min'              => 'Jumlah minimal 1.',
        'tanggal_masuk.required'  => 'Tanggal masuk wajib diisi.',
        'sumber.required'         => 'Sumber barang wajib dipilih.',
    ];

    public function mount() {
        $this->tanggal_masuk = now()->format('Y-m-d');
    }

    public function bukaTambah(): void {
        $this->reset(['id_barang', 'id_supplier', 'no_nota', 'no_surat_jalan', 'jumlah', 'tanggal_masuk', 'sumber', 'keterangan', 'editId']);
        $this->tanggal_masuk = now()->format('Y-m-d');
        $this->showForm      = true;
    }

    public function batal(): void {
        $this->reset(['id_barang', 'id_supplier', 'no_nota', 'no_surat_jalan', 'jumlah', 'tanggal_masuk', 'sumber', 'keterangan', 'editId']);
        $this->showForm = false;
    }

    public function simpan(): void {
        $this->validate();

        // Simpan data barang masuk
        $barangMasuk = BarangMasukModel::create([
            'id_barang'      => $this->id_barang,
            'id_supplier'    => $this->id_supplier,
            'id_user'        => Auth::user()->id_user,
            'no_nota'        => $this->no_nota,
            'no_surat_jalan' => $this->no_surat_jalan,
            'jumlah'         => $this->jumlah,
            'tanggal_masuk'  => $this->tanggal_masuk,
            'sumber'         => $this->sumber,
            'keterangan'     => $this->keterangan,
        ]);

        // LOGIKA BISNIS: Update stok otomatis saat barang masuk
        $stok = Stok::where('id_barang', $this->id_barang)->first();
        if ($stok) {
            $stok->increment('jumlah_stok', (int) $this->jumlah);
            $stok->updated_at = now();
            $stok->save();
        } else {
            // Jika belum ada record stok, buat baru
            $barang = Barang::find($this->id_barang);
            Stok::create([
                'id_barang'    => $this->id_barang,
                'jumlah_stok'  => (int) $this->jumlah,
                'stok_minimum' => $barang ? $barang->stok_minimum : 0,
                'updated_at'   => now(),
            ]);
        }

        $this->batal();
        session()->flash('success', 'Data barang masuk berhasil disimpan. Stok telah diperbarui.');
    }

    public function hapus(int $id): void {
        $bm = BarangMasukModel::findOrFail($id);

        // LOGIKA BISNIS: Kurangi stok kembali saat data barang masuk dihapus
        $stok = Stok::where('id_barang', $bm->id_barang)->first();
        if ($stok) {
            $stok->decrement('jumlah_stok', $bm->jumlah);
            $stok->updated_at = now();
            $stok->save();
        }

        $bm->delete();
        session()->flash('success', 'Data barang masuk berhasil dihapus. Stok telah disesuaikan.');
    }

    public function render() {
        $barangMasuk = BarangMasukModel::with(['barang', 'supplier', 'user'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('barang', function ($b) {
                        $b->where('nama_barang', 'like', '%' . $this->search . '%')
                            ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                    })->orWhere('no_nota', 'like', '%' . $this->search . '%')
                        ->orWhere('no_surat_jalan', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('tanggal_masuk')
            ->paginate(10);

        $barangs   = Barang::orderBy('nama_barang')->get();
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        $sumberList = ['KMM Pusat Banjarmasin', 'Gudang Barabai', 'PT. Nutrifood', 'PT. Orang Tua', 'PT. Sekar Laut'];

        return view('components.admin.barang-masuk', compact('barangMasuk', 'barangs', 'suppliers', 'sumberList'))
            ->layout('layouts.admin');
    }
}