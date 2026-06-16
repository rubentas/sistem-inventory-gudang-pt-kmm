<?php
namespace App\Livewire\Admin;

use App\Models\Sales;
use App\Models\Wilayah;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class DataSales extends Component {
  use WithPagination;

  public string $search = '';

  // Form
  public int | null $id_sales  = null;
  public string $nama_sales    = '';
  public string $no_hp         = '';
  public string $wilayah_tugas = '';
  public string $status        = 'Aktif';
  public string $keterangan    = '';

  public bool $isEdit = false;

  protected $rules = [
    'nama_sales'    => 'required|string|max:100',
    'no_hp'         => 'required|string|max:20',
    'wilayah_tugas' => 'required|string|max:100',
    'status'        => 'required|in:Aktif,Non-Aktif',
    'keterangan'    => 'nullable|string|max:255',
  ];

  protected $messages = [
    'nama_sales.required'    => 'Nama sales wajib diisi.',
    'no_hp.required'         => 'No. HP wajib diisi.',
    'wilayah_tugas.required' => 'Wilayah tugas wajib diisi.',
  ];

  public function updatedSearch(): void {$this->resetPage();}

  public function resetForm(): void {
    $this->reset(['id_sales', 'nama_sales', 'no_hp', 'wilayah_tugas', 'status', 'keterangan', 'isEdit']);
    $this->status = 'Aktif';
    $this->resetErrorBag();
  }

  public function openAddModal(): void {$this->resetForm();
    $this->dispatch('openModal');}

  public function edit(int $id): void {
    $s                   = Sales::findOrFail($id);
    $this->id_sales      = $s->id_sales;
    $this->nama_sales    = $s->nama_sales;
    $this->no_hp         = $s->no_hp;
    $this->wilayah_tugas = $s->wilayah_tugas;
    $this->status        = $s->status;
    $this->keterangan    = $s->keterangan ?? '';
    $this->isEdit        = true;
    $this->dispatch('openModal');
  }

  public function simpan(): void {
    $this->validate();

    $data = [
      'nama_sales'    => $this->nama_sales,
      'no_hp'         => $this->no_hp,
      'wilayah_tugas' => $this->wilayah_tugas,
      'status'        => $this->status,
      'keterangan'    => $this->keterangan ?: null,
    ];

    if ($this->isEdit) {
      Sales::findOrFail($this->id_sales)->update($data);
      $message = 'Data sales berhasil diperbarui.';
    } else {
      Sales::create($data);
      $message = 'Data sales berhasil ditambahkan.';
    }

    $this->resetForm();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: $message);
  }

  public function update(): void {$this->simpan();}

  public function hapus(int $id): void {
    Sales::findOrFail($id)->delete();
    $this->dispatch('dataSaved', type: 'success', title: 'Berhasil!', message: 'Data sales berhasil dihapus.');
  }

  public function getStats(): array {
    return [
      'total' => Sales::count(),
      'aktif' => Sales::where('status', 'Aktif')->count(),
    ];
  }

  public function render() {
    $sales = Sales::when($this->search, fn($q) => $q->where('nama_sales', 'like', '%' . $this->search . '%')
        ->orWhere('kode_sales', 'like', '%' . $this->search . '%'))
      ->orderBy('kode_sales')
      ->paginate(10);

    $wilayahs = Wilayah::orderBy('nama_wilayah')->get();

    return view('components.admin.data-sales', [
      'sales'    => $sales,
      'stats'    => $this->getStats(),
      'wilayahs' => $wilayahs,
    ]);
  }
}