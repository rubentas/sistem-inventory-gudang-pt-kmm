<div x-data="{ modalOpen: false }" @open-modal.window="modalOpen = true" @close-modal.window="modalOpen = false"
  class="space-y-5">
  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Retur Pembelian</h1>
            <p class="text-sm text-gray-400 mt-0.5">Retur barang ke supplier / gudang pusat</p>
          </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
          <a href="{{ route('admin.retur-pembelian.pdf', ['search' => $search, 'filterSupplier' => $filterSupplier]) }}"
            target="_blank"
            class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>PDF
          </a>
          <button @click="$wire.resetForm(); modalOpen = true"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_4px_12px_rgba(37,99,235,0.25)]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>Tambah Retur
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div class="flex flex-wrap items-center gap-2.5">
        <div
          class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-blue-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 transition min-w-[180px]">
          <div class="pl-3.5 shrink-0 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg></div>
          <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari no retur..."
            class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
        </div>
        <select wire:model.live="filterSupplier"
          class="h-11 px-4 border-2 border-gray-200 rounded-xl text-sm font-semibold bg-white text-gray-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
          <option value="">Semua Supplier</option>
          @foreach ($suppliers as $s)
            <option value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
          @endforeach
        </select>
        @if ($search || $filterSupplier)
          <button wire:click="$set('search', ''); $set('filterSupplier', '')"
            class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2.5 rounded-lg bg-red-50 border border-red-200 hover:bg-red-100 text-xs font-semibold text-red-600 transition">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>Reset
          </button>
        @endif
      </div>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[1000px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-4 py-4 text-left w-12"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">#</span></th>
            <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No Retur</th>
            <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No Invoice</th>
            <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Supplier</th>
            <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Barang</th>
            <th class="px-4 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
            <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tujuan</th>
            <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
            <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Diinput Oleh</th>
            <th class="px-4 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Bukti</th>
            <th class="px-4 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
            <th class="px-4 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse ($returs as $index => $r)
            <tr class="hover:bg-blue-50/30 transition">
              <td class="px-4 py-4 text-xs font-semibold text-gray-300">{{ $returs->firstItem() + $index }}</td>
              <td class="px-4 py-4 text-sm font-mono font-semibold text-blue-600">{{ $r->no_retur }}</td>
              <td class="px-4 py-4 text-sm font-mono text-gray-700">{{ $r->no_invoice ?? '-' }}</td>
              <td class="px-4 py-4 text-sm text-gray-800">{{ $r->supplier->nama_supplier ?? '-' }}</td>
              <td class="px-4 py-4 text-sm font-bold text-gray-900">{{ $r->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-4 text-sm text-right font-bold text-gray-700">{{ $r->jumlah }}</td>
              <td class="px-4 py-4 text-sm text-gray-600">{{ $r->tujuan }}</td>
              <td class="px-4 py-4 text-xs text-gray-500">{{ $r->keterangan ?? '-' }}</td>
              <td class="px-4 py-4 text-xs text-gray-600">{{ $r->user->nama ?? 'Admin' }}</td>
              <td class="px-4 py-4 text-center">
                @if ($r->bukti_invoice)
                  <a href="{{ Storage::url($r->bukti_invoice) }}" target="_blank"
                    class="text-blue-600 hover:text-blue-700 text-xs font-semibold">Lihat</a>
                @else
                  <span class="text-xs text-gray-400">Belum ada</span>
                @endif
              </td>
              <td class="px-4 py-4 text-sm text-gray-600">{{ $r->tanggal_retur->format('d/m/Y') }}</td>
              <td class="px-4 py-4 text-center">
                <button onclick="alert('Tombol edit diklik untuk ID: {{ $r->id_retur_pembelian }}'); @this.call('edit', {{ $r->id_retur_pembelian }})" type="button"
                  class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="12" class="px-6 py-20 text-center">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center"><svg
                      class="w-9 h-9 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg></div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Belum Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada data retur pembelian.</p>
                  </div>
                  <button @click="$wire.resetForm(); modalOpen = true"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_4px_12px_rgba(37,99,235,0.25)]"><svg
                      class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>Tambah Sekarang</button>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($returs->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $returs->links() }}</div>
    @endif
  </div>

  {{-- MODAL --}}
  <template x-teleport="body">
    <div x-show="modalOpen" x-cloak x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0" class="fixed inset-0 z-[100] flex items-center justify-center p-4"
      @keydown.escape.window="modalOpen = false">
      <div @click="modalOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-md z-40"></div>
      <div @click.stop class="relative z-50 w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-blue-600 px-6 py-5">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"><svg
                  class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg></div>
              <div class="text-white">
                <h2 class="text-lg font-bold">{{ $editMode ? 'Edit' : 'Form' }} Retur Pembelian</h2>
                <p class="text-blue-100 text-xs">Lengkapi field wajib <span class="text-white">*</span></p>
              </div>
            </div>
            <button @click="modalOpen = false"
              class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition"><svg
                class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
              </svg></button>
          </div>
        </div>
        <div class="px-6 py-5 space-y-4 overflow-y-auto" style="max-height: calc(100vh - 250px);">
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Supplier <span
                class="text-red-500">*</span></label>
            <select wire:model.live="id_supplier"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
              <option value="">-- Pilih Supplier --</option>
              @foreach ($suppliers as $s)
                <option value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
              @endforeach
            </select>
            @error('id_supplier')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Barang <span
                class="text-red-500">*</span></label>
            <select wire:model.live="id_barang"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
              <option value="">-- Pilih Barang --</option>
              @foreach ($barangs as $b)
                <option value="{{ $b->id_barang }}">{{ $b->kode_barang }} - {{ $b->nama_barang }}</option>
              @endforeach
            </select>
            @error('id_barang')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">Jumlah <span
                  class="text-red-500">*</span></label>
              <input type="number" wire:model.live="jumlah" min="1"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
              @error('jumlah')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">Tujuan <span
                  class="text-red-500">*</span></label>
              <select wire:model.live="tujuan"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
                <option value="Supplier">Supplier</option>
                <option value="Gudang Banjarmasin">Gudang Banjarmasin</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Tanggal <span
                class="text-red-500">*</span></label>
            <input type="date" wire:model="tanggal_retur"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">No Invoice <span
                class="text-red-500">*</span></label>
            <input type="text" wire:model="no_invoice" placeholder="Nomor invoice supplier..."
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
            @error('no_invoice')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Keterangan <span
                class="text-gray-400 font-normal">(opsional)</span></label>
            <textarea wire:model="keterangan" rows="2" placeholder="Catatan..."
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none resize-none"></textarea>
          </div>
          <div>
            <label class="text-xs font-bold text-gray-500 mb-1 block">Upload Bukti Invoice 
              @if (!$editMode)
                <span class="text-red-500">*</span>
              @else
                <span class="text-gray-400 font-normal">(opsional - kosongkan jika tidak diganti)</span>
              @endif
            </label>
            <input type="file" wire:model="bukti_invoice" accept=".jpg,.jpeg,.png,.pdf"
              class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
            <p class="text-xs text-gray-400 mt-1">Maksimal 2MB (JPG, PNG, PDF)</p>
            @error('bukti_invoice')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-3">
          <p class="text-xs text-gray-400"><span class="text-red-500">*</span> Wajib diisi</p>
          <div class="flex items-center gap-2">
            <button @click="modalOpen = false"
              class="px-5 py-2.5 rounded-xl bg-white border-2 border-gray-200 hover:bg-gray-50 text-sm font-bold text-gray-700 transition">Batal</button>
            <button wire:click="simpan"
              class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-lg shadow-blue-600/25 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>{{ $editMode ? 'Simpan Perubahan' : 'Simpan Data' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('livewire:initialized', () => {
    window.addEventListener('dataSaved', (e) => {
      window.dispatchEvent(new CustomEvent('close-modal'));
      Swal.fire({
        title: e.detail.title || 'Berhasil!',
        text: e.detail.message || 'Data berhasil disimpan.',
        icon: e.detail.type || 'success',
        confirmButtonColor: '#2563EB',
        customClass: {
          popup: 'rounded-2xl',
          confirmButton: 'rounded-xl text-sm font-bold px-5 py-2.5'
        },
        toast: false,
        position: 'center',
        showConfirmButton: true
      });
    });
  });
</script>
