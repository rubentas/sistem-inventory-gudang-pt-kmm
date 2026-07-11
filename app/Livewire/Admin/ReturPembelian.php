<?php
namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\ReturPembelian as ReturPembelianModel;
use App\Models\Stok;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ReturPembelian extends Component {
  use WithPagination;

  public string $search         = '';
  public string $filterSupplier = '';

  public ?int $id_supplier     = null;
  public ?int $id_barang       = null;
  public int $jumlah           = 0;
  public string $tujuan        = 'Supplier';
  public string $keterangan    = '';
  public string $tanggal_retur = '';

  public function mount(): void {
    $this->tanggal_retur = now()->format('Y-m-d');
  }

  public function updatedSearch(): void {$this->resetPage();}
  public function updatedFilterSupplier(): void {$this->resetPage();}

  public function resetForm(): void {
    $this->reset(['id_supplier', 'id_barang', 'jumlah', 'tujuan', 'keterangan']);
    $this->tanggal_retur = now()->format('Y-m-d');
    $this->tujuan        = 'Supplier';
    $this->resetErrorBag();
  }

  public function generateNoRetur(): string {
    $prefix = 'RET-PMB/' . now()->format('Ymd') . '/';
    $last   = ReturPembelianModel::where('no_retur', 'like', $prefix . '%')->latest('id_retur_pembelian')->first();
    $num    = $last ? (int) substr($last->no_retur, -5) + 1 : 1;
    return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
  }

  public function simpan(): void {
    $this->validate([
      'id_supplier'   => 'required|exists:suppliers,id_supplier',
      'id_barang'     => 'required|exists:barangs,id_barang',
      'jumlah'        => 'required|integer|min:1',
      'tujuan'        => 'required|in:Gudang Banjarmasin,Supplier',
      'tanggal_retur' => 'required|date',
      'keterangan'    => 'nullable|string|max:255',
    ], [
      'id_supplier.required' => 'Supplier wajib dipilih.',
      'id_barang.required'   => 'Barang wajib dipilih.',
      'jumlah.min'           => 'Jumlah minimal 1.',
    ]);

    DB::beginTransaction();
    try {
      ReturPembelianModel::create([
        'no_retur'      => $this->generateNoRetur(),
        'id_supplier'   => $this->id_supplier,
        'id_barang'     => $this->id_barang,
        'id_user'       => auth()->id(),
        'jumlah'        => $this->jumlah,
        'tujuan'        => $this->tujuan,
        'keterangan'    => $this->keterangan ?: null,
        'tanggal_retur' => $this->tanggal_retur,
      ]);

      $stok = Stok::where('id_barang', $this->id_barang)->first();
      if ($stok) {
        $stok->jumlah_stok -= $this->jumlah;
        $stok->save();
      }

      DB::commit();
      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Retur pembelian berhasil disimpan.');
      $this->resetForm();
    } catch (\Throwable $e) {
      DB::rollBack();
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }

  public function render() {
    $returs = ReturPembelianModel::with(['supplier', 'barang', 'user'])
      ->when($this->search, fn($q) => $q->where('no_retur', 'like', '%' . $this->search . '%'))
      ->when($this->filterSupplier, fn($q) => $q->where('id_supplier', $this->filterSupplier))
      ->orderByDesc('created_at')
      ->paginate(15);

    return view('components.admin.retur-pembelian', [
      'returs'         => $returs,
      'suppliers'      => Supplier::orderBy('nama_supplier')->get(),
      'barangs'        => Barang::orderBy('nama_barang')->get(),
      'filterSupplier' => $this->filterSupplier,
    ]);
  }
}