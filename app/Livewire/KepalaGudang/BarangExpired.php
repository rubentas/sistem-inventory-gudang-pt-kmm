<?php
namespace App\Livewire\KepalaGudang;

use App\Models\BarangMasuk;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class BarangExpired extends Component {
  use WithPagination;

  public $search          = '';
  public $id_barang_masuk = '';
  public $tanggal_expired = '';
  public $status_expired  = '';

  protected $rules = [
    'id_barang_masuk' => 'required|exists:barang_masuks,id_masuk',
    'tanggal_expired' => 'required|date|after_or_equal:today',
  ];

  protected $messages = [
    'id_barang_masuk.required'       => 'Pilih barang masuk terlebih dahulu',
    'tanggal_expired.required'       => 'Tanggal expired wajib diisi',
    'tanggal_expired.after_or_equal' => 'Tanggal expired tidak boleh kurang dari hari ini',
  ];

  public function updatedIdBarangMasuk($value) {
    if ($value) {
      $barang               = BarangMasuk::find($value);
      $this->status_expired = $barang->status_expired ?? 'aman';
    }
  }

  public function simpan() {
    $this->validate();

    $barangMasuk = BarangMasuk::find($this->id_barang_masuk);

    // Hitung status expired
    $today       = Carbon::today();
    $expiredDate = Carbon::parse($this->tanggal_expired);
    $diffDays    = $today->diffInDays($expiredDate, false);

    if ($diffDays < 0) {
      $status = 'expired';
    } elseif ($diffDays <= 30) {
      $status = 'hampir_expired';
    } else {
      $status = 'aman';
    }

    $barangMasuk->update([
      'tanggal_expired' => $this->tanggal_expired,
      'status_expired'  => $status,
    ]);

    $this->reset(['id_barang_masuk', 'tanggal_expired', 'status_expired']);
    session()->flash('success', 'Data expired berhasil disimpan');

    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Tanggal expired berhasil dicatat');
  }

  public function render() {
    $barangMasuk = BarangMasuk::with(['barang', 'supplier'])
      ->whereNull('tanggal_expired')
      ->when($this->search, function ($query) {
        $query->whereHas('barang', function ($q) {
          $q->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
        });
      })
      ->orderBy('tanggal_masuk', 'desc')
      ->paginate(10);

    $sudahExpired = BarangMasuk::with(['barang'])
      ->whereNotNull('tanggal_expired')
      ->where('status_expired', 'expired')
      ->count();

    $hampirExpired = BarangMasuk::with(['barang'])
      ->whereNotNull('tanggal_expired')
      ->where('status_expired', 'hampir_expired')
      ->count();

    return view('components.kepala-gudang.barang-expired', compact('barangMasuk', 'sudahExpired', 'hampirExpired'))
      ->layout('layouts.kepala-gudang');
  }
}