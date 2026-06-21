<?php
namespace App\Livewire\KepalaGudang;

use App\Models\BarangMasuk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

  public function edit($id) {
    $this->id_barang_masuk = $id;
    $this->dispatch('openModal');
  }

  public function simpan() {
    $this->validate();

    $expiredDate = Carbon::parse($this->tanggal_expired);
    $diffDays    = Carbon::today()->diffInDays($expiredDate, false);

    if ($diffDays < 0) {
      $status = 'expired';
    } elseif ($diffDays <= 30) {
      $status = 'hampir_expired';
    } else {
      $status = 'aman';
    }

    DB::table('barang_masuks')->where('id_masuk', $this->id_barang_masuk)->update([
      'tanggal_expired' => $this->tanggal_expired,
      'status_expired'  => $status,
    ]);

    $this->reset(['id_barang_masuk', 'tanggal_expired', 'status_expired']);
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Tanggal expired berhasil dicatat');
  }

  public function render() {
    // Tabel 1: Belum diinput
    $barangMasuk = BarangMasuk::with(['barang', 'supplier'])
      ->whereNull('tanggal_expired')
      ->when($this->search, function ($query) {
        $query->whereHas('barang', function ($q) {
          $q->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
        });
      })
      ->orderBy('tanggal_masuk', 'desc')
      ->paginate(10, ['*'], 'belumPage');

    // Tabel 2: Sudah diinput
    $sudahDinput = BarangMasuk::with(['barang', 'supplier'])
      ->whereNotNull('tanggal_expired')
      ->when($this->search, function ($query) {
        $query->whereHas('barang', function ($q) {
          $q->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
        });
      })
      ->orderBy('tanggal_expired', 'desc')
      ->paginate(10, ['*'], 'sudahPage');

    $sudahExpired = BarangMasuk::whereNotNull('tanggal_expired')
      ->where('status_expired', 'expired')->count();
    $hampirExpired = BarangMasuk::whereNotNull('tanggal_expired')
      ->where('status_expired', 'hampir_expired')->count();

    return view('components.kepala-gudang.barang-expired', compact(
      'barangMasuk', 'sudahDinput', 'sudahExpired', 'hampirExpired'
    ))->layout('layouts.kepala-gudang');
  }
}