<?php
namespace App\Livewire\KepalaGudang;

use App\Models\Barang;
use App\Models\StockOpname as StockOpnameModel;
use App\Models\Stok;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class StockOpname extends Component {
    use WithPagination;

    // Form fields
    public $id_barang             = '';
    public int $stok_sistem       = 0;
    public $stok_fisik            = '';
    public int $selisih           = 0;
    public string $tanggal_opname = '';
    public string $keterangan     = '';

    // UI state
    public string $search = '';
    public bool $showForm = false;
    public $editId        = null;

    protected $rules = [
        'id_barang'      => 'required|exists:barangs,id_barang',
        'stok_fisik'     => 'required|integer|min:0',
        'tanggal_opname' => 'required|date',
        'keterangan'     => 'nullable|string|max:255',
    ];

    protected $messages = [
        'id_barang.required'      => 'Pilih barang terlebih dahulu.',
        'stok_fisik.required'     => 'Stok fisik wajib diisi.',
        'stok_fisik.integer'      => 'Stok fisik harus berupa angka.',
        'tanggal_opname.required' => 'Tanggal opname wajib diisi.',
    ];

    public function mount() {
        $this->tanggal_opname = now()->format('Y-m-d');
    }

    // Saat barang dipilih, otomatis isi stok sistem dari database
    public function updatedIdBarang($value): void {
        if ($value) {
            $stok              = Stok::where('id_barang', $value)->first();
            $this->stok_sistem = $stok ? $stok->jumlah_stok : 0;
            $this->hitungSelisih();
        } else {
            $this->stok_sistem = 0;
            $this->selisih     = 0;
        }
    }

    // Saat stok fisik diubah, hitung selisih otomatis
    public function updatedStokFisik(): void {
        $this->hitungSelisih();
    }

    public function hitungSelisih(): void {
        $this->selisih = (int) $this->stok_fisik - (int) $this->stok_sistem;
    }

    public function bukaTambah(): void {
        $this->reset(['id_barang', 'stok_sistem', 'stok_fisik', 'selisih', 'keterangan', 'editId']);
        $this->tanggal_opname = now()->format('Y-m-d');
        $this->showForm       = true;
    }

    public function batal(): void {
        $this->reset(['id_barang', 'stok_sistem', 'stok_fisik', 'selisih', 'tanggal_opname', 'keterangan', 'editId']);
        $this->showForm = false;
    }

    public function simpan(): void {
        $this->validate();

        StockOpnameModel::create([
            'id_barang'      => $this->id_barang,
            'id_user'        => Auth::user()->id_user,
            'stok_sistem'    => $this->stok_sistem,
            'stok_fisik'     => $this->stok_fisik,
            'selisih'        => $this->selisih,
            'tanggal_opname' => $this->tanggal_opname,
            'keterangan'     => $this->keterangan,
        ]);

        $this->batal();
        session()->flash('success', 'Data stock opname berhasil disimpan.');
    }

    public function hapus(int $id): void {
        StockOpnameModel::findOrFail($id)->delete();
        session()->flash('success', 'Data stock opname berhasil dihapus.');
    }

    public function render() {
        $opnames = StockOpnameModel::with(['barang', 'user'])
            ->when($this->search, fn($q) =>
                $q->whereHas('barang', fn($b) =>
                    $b->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%')
                )
            )
            ->orderByDesc('tanggal_opname')
            ->paginate(10);

        $barangs = Barang::orderBy('nama_barang')->get();

        return view('components.kepala-gudang.stock-opname', compact('opnames', 'barangs'))
            ->layout('layouts.kepala-gudang');
    }
}