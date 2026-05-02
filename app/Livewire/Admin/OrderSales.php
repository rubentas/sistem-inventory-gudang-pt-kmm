<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\OrderSales as OrderSalesModel;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrderSales extends Component {
    use WithPagination;

    // Form fields
    public $id_order             = null;
    public $id_barang            = '';
    public $id_wilayah           = '';
    public $jumlah               = '';
    public string $tanggal_order = '';
    public string $status        = 'pending';
    public string $keterangan    = '';

    // UI state
    public string $search       = '';
    public string $filterStatus = '';
    public bool $showForm       = false;
    public bool $isEdit         = false;

    protected $rules = [
        'id_barang'     => 'required|exists:barangs,id_barang',
        'id_wilayah'    => 'required|exists:wilayahs,id_wilayah',
        'jumlah'        => 'required|integer|min:1',
        'tanggal_order' => 'required|date',
        'status'        => 'required|in:pending,diproses,selesai',
        'keterangan'    => 'nullable|string',
    ];

    protected $messages = [
        'id_barang.required'     => 'Pilih barang terlebih dahulu.',
        'id_wilayah.required'    => 'Pilih wilayah terlebih dahulu.',
        'jumlah.required'        => 'Jumlah wajib diisi.',
        'jumlah.min'             => 'Jumlah minimal 1.',
        'tanggal_order.required' => 'Tanggal order wajib diisi.',
    ];

    public function mount() {
        $this->tanggal_order = now()->format('Y-m-d');
    }

    public function bukaTambah(): void {
        $this->reset(['id_order', 'id_barang', 'id_wilayah', 'jumlah', 'tanggal_order', 'status', 'keterangan']);
        $this->tanggal_order = now()->format('Y-m-d');
        $this->status        = 'pending';
        $this->isEdit        = false;
        $this->showForm      = true;
    }

    public function bukaEdit(int $id): void {
        $order               = OrderSalesModel::findOrFail($id);
        $this->id_order      = $order->id_order;
        $this->id_barang     = $order->id_barang;
        $this->id_wilayah    = $order->id_wilayah;
        $this->jumlah        = $order->jumlah;
        $this->tanggal_order = $order->tanggal_order->format('Y-m-d');
        $this->status        = $order->status;
        $this->keterangan    = $order->keterangan ?? '';
        $this->isEdit        = true;
        $this->showForm      = true;
    }

    public function batal(): void {
        $this->reset(['id_order', 'id_barang', 'id_wilayah', 'jumlah', 'tanggal_order', 'status', 'keterangan']);
        $this->showForm = false;
    }

    public function simpan(): void {
        $this->validate();

        $data = [
            'id_barang'     => $this->id_barang,
            'id_user'       => Auth::user()->id_user,
            'id_wilayah'    => $this->id_wilayah,
            'jumlah'        => $this->jumlah,
            'tanggal_order' => $this->tanggal_order,
            'status'        => $this->status,
            'keterangan'    => $this->keterangan,
        ];

        if ($this->isEdit) {
            $order = OrderSalesModel::findOrFail($this->id_order);

            // Jika status diubah dari pending/selesai, cek stok dulu
            if ($order->status !== 'selesai' && $this->status === 'selesai') {
                // Cek stok cukup tidak
                $stok = \App\Models\Stok::where('id_barang', $this->id_barang)->first();
                if (! $stok || $stok->jumlah_stok < $this->jumlah) {
                    session()->flash('error', 'Stok tidak mencukupi untuk menyelesaikan order ini.');
                    return;
                }
            }

            $order->update($data);
            session()->flash('success', 'Data order sales berhasil diperbarui.');
        } else {
            OrderSalesModel::create($data);
            session()->flash('success', 'Data order sales berhasil disimpan.');
        }

        $this->batal();
    }

    public function hapus(int $id): void {
        $order = OrderSalesModel::findOrFail($id);

        // Cek apakah order sudah memiliki barang keluar
        if ($order->barangKeluar) {
            session()->flash('error', 'Order tidak bisa dihapus karena sudah diproses menjadi barang keluar.');
            return;
        }

        $order->delete();
        session()->flash('success', 'Data order sales berhasil dihapus.');
    }

    public function render() {
        $orders = OrderSalesModel::with(['barang', 'wilayah', 'user'])
            ->when($this->search, function ($q) {
                $q->whereHas('barang', function ($b) {
                    $b->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                })->orWhereHas('wilayah', function ($w) {
                    $w->where('nama_wilayah', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('tanggal_order')
            ->paginate(10);

        $barangs  = Barang::orderBy('nama_barang')->get();
        $wilayahs = Wilayah::orderBy('nama_wilayah')->get();

        return view('components.admin.order-sales', compact('orders', 'barangs', 'wilayahs'))
            ->layout('layouts.admin');
    }
}