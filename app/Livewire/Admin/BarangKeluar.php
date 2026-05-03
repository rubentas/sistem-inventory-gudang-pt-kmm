<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\BarangKeluar as BarangKeluarModel;
use App\Models\OrderSales;
use App\Models\Stok;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BarangKeluar extends Component {
    use WithPagination;

    // Form fields
    public $id_order              = '';
    public $id_barang             = '';
    public $id_wilayah            = '';
    public $jumlah                = '';
    public string $tanggal_keluar = '';
    public string $keterangan     = '';

    // Data dari order yang dipilih (otomatis terisi)
    public string $nama_barang_display  = '';
    public string $satuan_display       = '';
    public string $order_status_display = '';

    // UI state
    public string $search = '';
    public bool $showForm = false;
    public $editId        = null;

    protected $rules = [
        'id_order'       => 'required|exists:order_sales,id_order',
        'id_barang'      => 'required|exists:barangs,id_barang',
        'id_wilayah'     => 'required|exists:wilayahs,id_wilayah',
        'jumlah'         => 'required|integer|min:1',
        'tanggal_keluar' => 'required|date',
        'keterangan'     => 'nullable|string',
    ];

    protected $messages = [
        'id_order.required'       => 'Pilih order sales terlebih dahulu.',
        'jumlah.required'         => 'Jumlah wajib diisi.',
        'jumlah.min'              => 'Jumlah minimal 1.',
        'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
    ];

    public function mount() {
        $this->tanggal_keluar = now()->format('Y-m-d');
    }

    public function bukaTambah(): void {
        $this->reset(['id_order', 'id_barang', 'id_wilayah', 'jumlah', 'tanggal_keluar', 'keterangan', 'nama_barang_display', 'satuan_display', 'order_status_display', 'editId']);
        $this->tanggal_keluar = now()->format('Y-m-d');
        $this->showForm       = true;
    }

    public function batal(): void {
        $this->reset(['id_order', 'id_barang', 'id_wilayah', 'jumlah', 'tanggal_keluar', 'keterangan', 'nama_barang_display', 'satuan_display', 'order_status_display', 'editId']);
        $this->showForm = false;
    }

    // Saat order dipilih, otomatis isi data barang, wilayah, dan jumlah
    public function updatedIdOrder($value): void {
        if ($value) {
            $order = OrderSales::with(['barang', 'wilayah'])->find($value);
            if ($order) {
                $this->id_barang            = $order->id_barang;
                $this->id_wilayah           = $order->id_wilayah;
                $this->jumlah               = $order->jumlah;
                $this->nama_barang_display  = $order->barang->nama_barang ?? '';
                $this->satuan_display       = $order->barang->satuan ?? '';
                $this->order_status_display = $order->status;

                // Cek apakah stok mencukupi
                $stok = Stok::where('id_barang', $this->id_barang)->first();
                if ($stok && $stok->jumlah_stok < $this->jumlah) {
                    session()->flash('error', 'Stok tidak mencukupi! Stok saat ini: ' . number_format($stok->jumlah_stok));
                }
            }
        } else {
            $this->reset(['id_barang', 'id_wilayah', 'jumlah', 'nama_barang_display', 'satuan_display', 'order_status_display']);
        }
    }

    public function simpan(): void {
        $this->validate();

        // Cek stok sebelum menyimpan
        $stok = Stok::where('id_barang', $this->id_barang)->first();
        if (! $stok || $stok->jumlah_stok < $this->jumlah) {
            session()->flash('error', 'Stok tidak mencukupi! Stok saat ini: ' . number_format($stok ? $stok->jumlah_stok : 0));
            return;
        }

        // Simpan data barang keluar
        BarangKeluarModel::create([
            'id_barang'      => $this->id_barang,
            'id_order'       => $this->id_order,
            'id_user'        => Auth::user()->id_user,
            'id_wilayah'     => $this->id_wilayah,
            'jumlah'         => $this->jumlah,
            'tanggal_keluar' => $this->tanggal_keluar,
            'keterangan'     => $this->keterangan,
        ]);

        // LOGIKA BISNIS: Kurangi stok otomatis saat barang keluar
        $stok->decrement('jumlah_stok', (int) $this->jumlah);
        $stok->updated_at = now();
        $stok->save();

        // Update status order menjadi selesai
        if ($this->id_order) {
            OrderSales::find($this->id_order)?->update(['status' => 'selesai']);
        }

        $this->batal();
        session()->flash('success', 'Data barang keluar berhasil disimpan. Stok telah diperbarui.');
    }

    public function hapus(int $id): void {
        $bk = BarangKeluarModel::findOrFail($id);

        // LOGIKA BISNIS: Tambah kembali stok saat data barang keluar dihapus
        $stok = Stok::where('id_barang', $bk->id_barang)->first();
        if ($stok) {
            $stok->increment('jumlah_stok', $bk->jumlah);
            $stok->updated_at = now();
            $stok->save();
        }

        // Kembalikan status order menjadi diproses
        if ($bk->id_order) {
            OrderSales::find($bk->id_order)?->update(['status' => 'diproses']);
        }

        $bk->delete();
        session()->flash('success', 'Data barang keluar berhasil dihapus. Stok telah disesuaikan.');
    }

    public function render() {
        $barangKeluar = BarangKeluarModel::with(['barang', 'order', 'user', 'wilayah'])
            ->when($this->search, function ($q) {
                $q->whereHas('barang', function ($b) {
                    $b->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                })->orWhereHas('wilayah', function ($w) {
                    $w->where('nama_wilayah', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('tanggal_keluar')
            ->paginate(10);

        // Hanya tampilkan order yang statusnya belum selesai
        $orders = OrderSales::with(['barang', 'wilayah'])
            ->where('status', '!=', 'selesai')
            ->orderByDesc('tanggal_order')
            ->get();

        return view('components.admin.barang-keluar', compact('barangKeluar', 'orders'))
            ->layout('layouts.admin');
    }
}