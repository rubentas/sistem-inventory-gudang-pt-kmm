<?php
namespace App\Livewire\Sales;

use App\Models\Barang;
use App\Models\OrderSales as OrderSalesModel;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrderSales extends Component {
    use WithPagination;

    // Form fields
    public $id_barang            = '';
    public $id_wilayah           = '';
    public $jumlah               = '';
    public string $tanggal_order = '';
    public string $keterangan    = '';

    // UI state
    public string $search       = '';
    public string $filterStatus = '';
    public bool $showForm       = false;
    public $editId              = null;

    protected $rules = [
        'id_barang'     => 'required|exists:barangs,id_barang',
        'id_wilayah'    => 'required|exists:wilayahs,id_wilayah',
        'jumlah'        => 'required|integer|min:1',
        'tanggal_order' => 'required|date',
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
        $this->reset(['id_barang', 'id_wilayah', 'jumlah', 'tanggal_order', 'keterangan', 'editId']);
        $this->tanggal_order = now()->format('Y-m-d');
        $this->showForm      = true;
    }

    public function batal(): void {
        $this->reset(['id_barang', 'id_wilayah', 'jumlah', 'tanggal_order', 'keterangan', 'editId']);
        $this->showForm = false;
    }

    public function simpan(): void {
        $this->validate();

        // Cek stok apakah mencukupi
        $stok = \App\Models\Stok::where('id_barang', $this->id_barang)->first();
        if (! $stok || $stok->jumlah_stok < $this->jumlah) {
            session()->flash('error', 'Stok tidak mencukupi! Stok saat ini: ' . number_format($stok ? $stok->jumlah_stok : 0));
            return;
        }

        $data = [
            'id_barang'     => $this->id_barang,
            'id_user'       => Auth::user()->id_user,
            'id_wilayah'    => $this->id_wilayah,
            'jumlah'        => $this->jumlah,
            'tanggal_order' => $this->tanggal_order,
            'status'        => 'pending',
            'keterangan'    => $this->keterangan,
        ];

        if ($this->editId) {
            $order = OrderSalesModel::findOrFail($this->editId);
            // Tidak boleh edit jumlah dan barang jika sudah diproses
            if ($order->status !== 'pending') {
                session()->flash('error', 'Order yang sudah diproses tidak bisa diubah.');
                return;
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

        // Tidak boleh hapus jika sudah diproses
        if ($order->status !== 'pending') {
            session()->flash('error', 'Order yang sudah diproses tidak bisa dihapus.');
            return;
        }

        $order->delete();
        session()->flash('success', 'Data order sales berhasil dihapus.');
    }

    public function render() {
        $userId = Auth::user()->id_user;

        $orders = OrderSalesModel::with(['barang', 'wilayah'])
            ->where('id_user', $userId)
            ->when($this->search, function ($q) {
                $q->whereHas('barang', function ($b) {
                    $b->where('nama_barang', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderByDesc('tanggal_order')
            ->paginate(10);

        $barangs  = Barang::orderBy('nama_barang')->get();
        $wilayahs = Wilayah::where('id_user', $userId)->orWhereNull('id_user')->orderBy('nama_wilayah')->get();

        return view('components.sales.order-sales', compact('orders', 'barangs', 'wilayahs'))
            ->layout('layouts.sales');
    }
}
