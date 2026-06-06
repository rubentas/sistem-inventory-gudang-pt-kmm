<?php
namespace App\Livewire\Pimpinan;

use App\Models\BarangMasuk;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanBarangExpired extends Component {
  use WithPagination;

  public $search       = '';
  public $filterStatus = '';

  public function render() {
    $expired = BarangMasuk::with(['barang', 'supplier'])
      ->whereNotNull('tanggal_expired')
      ->when($this->search, function ($query) {
        $query->whereHas('barang', function ($q) {
          $q->where('nama_barang', 'like', '%' . $this->search . '%')
            ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
        });
      })
      ->when($this->filterStatus, fn($q) => $q->where('status_expired', $this->filterStatus))
      ->orderBy('tanggal_expired', 'asc')
      ->paginate(10);

    return view('components.pimpinan.laporan-barang-expired', compact('expired'))
      ->layout('layouts.pimpinan');
  }
}