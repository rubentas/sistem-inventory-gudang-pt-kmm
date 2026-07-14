<?php

namespace App\Livewire\Admin;

use App\Models\Barang;
use App\Models\ReturPembelian as ReturPembelianModel;
use App\Models\Stok;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class ReturPembelian extends Component
{
  use WithPagination, WithFileUploads;

  public string $search         = '';
  public string $filterSupplier = '';

  public ?int $id_retur_pembelian = null;
  public ?int $id_supplier        = null;
  public ?int $id_barang          = null;
  public int $jumlah              = 0;
  public string $tujuan           = 'Supplier';
  public string $keterangan       = '';
  public string $no_invoice       = '';
  public string $tanggal_retur    = '';

  public $bukti_invoice = null;
  public bool $editMode = false;

  public function mount(): void
  {
    $this->tanggal_retur = now()->format('Y-m-d');
  }

  public function updatedSearch(): void
  {
    $this->resetPage();
  }
  public function updatedFilterSupplier(): void
  {
    $this->resetPage();
  }

  public function resetForm(): void
  {
    $this->reset(['id_retur_pembelian', 'id_supplier', 'id_barang', 'jumlah', 'tujuan', 'keterangan', 'no_invoice', 'bukti_invoice', 'editMode']);
    $this->tanggal_retur = now()->format('Y-m-d');
    $this->tujuan        = 'Supplier';
    $this->resetErrorBag();
  }

  public function generateNoRetur(): string
  {
    $prefix = 'RET-PMB/' . now()->format('Ymd') . '/';
    $last   = ReturPembelianModel::where('no_retur', 'like', $prefix . '%')->latest('id_retur_pembelian')->first();
    $num    = $last ? (int) substr($last->no_retur, -5) + 1 : 1;
    return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
  }

  public function edit(int $id): void
  {
    $retur                        = ReturPembelianModel::findOrFail($id);
    $this->id_retur_pembelian     = $retur->id_retur_pembelian;
    $this->id_supplier            = $retur->id_supplier;
    $this->id_barang              = $retur->id_barang;
    $this->jumlah                 = $retur->jumlah;
    $this->tujuan                 = $retur->tujuan;
    $this->keterangan             = $retur->keterangan ?? '';
    $this->no_invoice             = $retur->no_invoice ?? '';
    $this->tanggal_retur          = $retur->tanggal_retur->format('Y-m-d');
    $this->editMode               = true;
    $this->dispatch('open-modal');
  }

  public function simpan(): void
  {
    if ($this->editMode) {
      $this->update();
      return;
    }

    $this->validate([
      'id_supplier'   => 'required|exists:suppliers,id_supplier',
      'id_barang'     => 'required|exists:barangs,id_barang',
      'jumlah'        => 'required|integer|min:1',
      'tujuan'        => 'required|in:Gudang Banjarmasin,Supplier',
      'tanggal_retur' => 'required|date',
      'keterangan'    => 'nullable|string|max:255',
      'no_invoice'    => 'required|string|max:100',
      'bukti_invoice' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ], [
      'id_supplier.required'   => 'Supplier wajib dipilih.',
      'id_barang.required'     => 'Barang wajib dipilih.',
      'jumlah.min'             => 'Jumlah minimal 1.',
      'no_invoice.required'    => 'No Invoice wajib diisi.',
      'bukti_invoice.required' => 'Bukti invoice wajib diupload.',
    ]);

    DB::beginTransaction();
    try {
      $retur = ReturPembelianModel::create([
        'no_retur'      => $this->generateNoRetur(),
        'no_invoice'    => $this->no_invoice ?: null,
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

      // Upload file setelah transaksi berhasil
      if ($this->bukti_invoice) {
        $file     = $this->bukti_invoice;
        $filename = 'RET-PMB-' . now()->format('Ymd') . '-' . $retur->id_retur_pembelian . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('bukti-retur', $filename, 'public');

        $retur->update([
          'bukti_invoice'  => $path,
          'nama_file_asli' => $file->getClientOriginalName(),
        ]);
      }

      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Retur pembelian berhasil disimpan.');
      $this->resetForm();
    } catch (\Throwable $e) {
      DB::rollBack();
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }

  public function update(): void
  {
    $this->validate([
      'id_supplier'   => 'required|exists:suppliers,id_supplier',
      'id_barang'     => 'required|exists:barangs,id_barang',
      'jumlah'        => 'required|integer|min:1',
      'tujuan'        => 'required|in:Gudang Banjarmasin,Supplier',
      'tanggal_retur' => 'required|date',
      'keterangan'    => 'nullable|string|max:255',
      'no_invoice'    => 'required|string|max:100',
      'bukti_invoice' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ], [
      'id_supplier.required' => 'Supplier wajib dipilih.',
      'id_barang.required'   => 'Barang wajib dipilih.',
      'jumlah.min'           => 'Jumlah minimal 1.',
      'no_invoice.required'  => 'No Invoice wajib diisi.',
    ]);

    DB::beginTransaction();
    try {
      $retur    = ReturPembelianModel::findOrFail($this->id_retur_pembelian);
      $oldJumlah = $retur->jumlah;
      $oldBarang = $retur->id_barang;

      // Balikin stok lama
      if ($oldBarang) {
        $stokLama = Stok::where('id_barang', $oldBarang)->first();
        if ($stokLama) {
          $stokLama->jumlah_stok += $oldJumlah;
          $stokLama->save();
        }
      }

      // Update data retur
      $retur->update([
        'no_invoice'    => $this->no_invoice,
        'id_supplier'   => $this->id_supplier,
        'id_barang'     => $this->id_barang,
        'jumlah'        => $this->jumlah,
        'tujuan'        => $this->tujuan,
        'keterangan'    => $this->keterangan ?: null,
        'tanggal_retur' => $this->tanggal_retur,
      ]);

      // Kurangi stok baru
      $stokBaru = Stok::where('id_barang', $this->id_barang)->first();
      if ($stokBaru) {
        $stokBaru->jumlah_stok -= $this->jumlah;
        $stokBaru->save();
      }

      DB::commit();

      // Upload file baru jika ada
      if ($this->bukti_invoice) {
        if ($retur->bukti_invoice) {
          Storage::disk('public')->delete($retur->bukti_invoice);
        }

        $file     = $this->bukti_invoice;
        $filename = 'RET-PMB-' . now()->format('Ymd') . '-' . $retur->id_retur_pembelian . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('bukti-retur', $filename, 'public');

        $retur->update([
          'bukti_invoice'  => $path,
          'nama_file_asli' => $file->getClientOriginalName(),
        ]);
      }

      $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Retur pembelian berhasil diperbarui.');
      $this->resetForm();
    } catch (\Throwable $e) {
      DB::rollBack();
      $this->dispatch('dataSaved', type: 'error', title: 'Gagal!', message: 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }

  public function render()
  {
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
