<?php
namespace App\Livewire\KepalaGudang;

use App\Models\Barang;
use App\Models\StockOpname as StockOpnameModel;
use App\Models\Stok;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.kepala-gudang')]
class StockOpname extends Component {
  use WithPagination;

  // Form
  public int | string $id_barang  = '';
  public int $stok_sistem         = 0;
  public int | string $stok_fisik = '';
  public int $selisih             = 0;
  public string $tanggal_opname   = '';
  public string $keterangan       = '';

  // UI
  public string $search = '';

  protected $rules = [
    'id_barang'      => 'required|exists:barangs,id_barang',
    'stok_fisik'     => 'required|integer|min:0',
    'tanggal_opname' => 'required|date',
    'keterangan'     => 'nullable|string|max:255',
  ];

  protected $messages = [
    'id_barang.required'      => 'Pilih barang terlebih dahulu.',
    'stok_fisik.required'     => 'Stok fisik wajib diisi.',
    'tanggal_opname.required' => 'Tanggal opname wajib diisi.',
  ];

  public function mount(): void {
    $this->tanggal_opname = now()->format('Y-m-d');
  }

  public function updatedSearch(): void {$this->resetPage();}

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

  public function updatedStokFisik(): void {$this->hitungSelisih();}

  public function hitungSelisih(): void {
    $this->selisih = (int) $this->stok_fisik - (int) $this->stok_sistem;
  }

  public function resetForm(): void {
    $this->reset(['id_barang', 'stok_sistem', 'stok_fisik', 'selisih', 'keterangan']);
    $this->tanggal_opname = now()->format('Y-m-d');
    $this->resetErrorBag();
  }

  public function openAddModal(): void {
    $this->resetForm();
    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $this->validate();

    StockOpnameModel::create([
      'id_barang'      => $this->id_barang,
      'id_user'        => Auth::id(),
      'stok_sistem'    => $this->stok_sistem,
      'stok_fisik'     => $this->stok_fisik,
      'selisih'        => $this->selisih,
      'tanggal_opname' => $this->tanggal_opname,
      'keterangan'     => $this->keterangan,
    ]);

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data stock opname berhasil disimpan.');
  }

  public function hapus(int $id): void {
    StockOpnameModel::findOrFail($id)->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data stock opname berhasil dihapus.');
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

    return view('components.kepala-gudang.stock-opname', compact('opnames', 'barangs'));
  }

  public function cetakPdf() {
    $data = StockOpnameModel::with(['barang', 'user'])
      ->orderByDesc('tanggal_opname')
      ->get();

    $totalSelisih = $data->sum('selisih');

    $pdf = Pdf::loadView('laporan.stock-opname', [
      'data'              => $data,
      'tanggal_awal'      => '-',
      'tanggal_akhir'     => '-',
      'total_selisih'     => $totalSelisih,
      'rata_rata_selisih' => $data->count() > 0 ? round($totalSelisih / $data->count(), 2) : 0,
      'dicetak_oleh'      => Auth::user()->nama ?? 'System',
      'tanggal_cetak'     => Carbon::now()->translatedFormat('d F Y'),
    ])->setPaper('a4', 'landscape');

    return response()->stream(
      fn() => print($pdf->output()),
      200,
      [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="laporan-stock-opname.pdf"',
      ]
    );
  }
}