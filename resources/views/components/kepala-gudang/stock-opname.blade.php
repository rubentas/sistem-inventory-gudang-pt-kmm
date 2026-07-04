<div x-data="stockOpnameManager()" x-init="init()" class="space-y-5">
{{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Stock Opname</h1>
          <p class="text-sm text-gray-400 mt-0.5">Pengecekan stok fisik setiap bulan</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <button wire:click="cetakPdf"
          class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-md">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>PDF
        </button>
        <button wire:click="openAddModal"
          class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-md">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
          </svg>Tambah Opname
        </button>
      </div>
    </div>
  </div>

  {{-- ALERT SOP --}}
  <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
      <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
    </div>
    <div>
      <p class="text-sm font-semibold text-emerald-800">SOP Perusahaan</p>
      <p class="text-xs text-emerald-600 mt-0.5">Stock opname wajib dilakukan setiap akhir bulan sesuai SOP perusahaan.
      </p>
    </div>
  </div>

  {{-- SEARCH --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div
        class="flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-emerald-400 transition">
        <div class="pl-3.5 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg></div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang…"
          class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none text-gray-900">
      </div>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[800px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Tanggal</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Barang</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Stok Sistem</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Stok Fisik</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Selisih</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Input Oleh</th>
            <th class="px-5 py-4 text-center w-24 text-xs font-bold text-gray-400 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($opnames as $opname)
            <tr class="hover:bg-emerald-50/30 transition">
              <td class="px-5 py-4 text-sm">{{ $opname->tanggal_opname->format('d/m/Y') }}</td>
              <td class="px-5 py-4 text-sm font-semibold">{{ $opname->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-sm">{{ number_format($opname->stok_sistem) }}</td>
              <td class="px-5 py-4 text-sm">{{ number_format($opname->stok_fisik) }}</td>
              <td class="px-5 py-4"><span
                  class="text-sm font-bold {{ $opname->selisih != 0 ? 'text-red-600' : 'text-gray-600' }}">{{ number_format($opname->selisih) }}</span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-500">{{ $opname->user->nama ?? '-' }}</td>
              <td class="px-5 py-4 text-center">
                <button @click="confirmDelete({{ $opname->id_opname }})"
                  class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition"><svg
                    class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg></button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-20 text-center text-gray-400">Belum ada data stock opname.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($opnames->hasPages())
      <div class="px-5 py-3 border-t border-gray-100">{{ $opnames->links() }}</div>
    @endif
  </div>

  {{-- MODAL --}}
  <template x-teleport="body">
    <div x-show="modalOpen" x-cloak x-transition class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div @click="modalOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-md z-40"></div>
      <div @click.stop class="relative z-50 w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-emerald-600 px-6 py-5">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 text-white">
              <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"><svg class="w-5 h-5"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg></div>
              <div>
                <h2 class="text-lg font-bold">Tambah Stock Opname</h2>
                <p class="text-emerald-100 text-xs">Lengkapi field wajib <span class="text-white">*</span></p>
              </div>
            </div>
            <button @click="modalOpen = false"
              class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition"><svg
                class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
              </svg></button>
          </div>
        </div>
        <div class="px-6 py-5 space-y-4">
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Barang <span
                class="text-red-500">*</span></label>
            <select wire:model.live="id_barang"
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition outline-none">
              <option value="">-- Pilih Barang --</option>
              @foreach ($barangs as $barang)
                <option value="{{ $barang->id_barang }}">{{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                </option>
              @endforeach
            </select>
            @error('id_barang')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Stok Sistem</label><input type="text"
                value="{{ number_format($stok_sistem) }}" readonly disabled
                class="w-full rounded-xl border-2 border-gray-200 bg-gray-100 px-4 py-3 text-sm font-medium text-gray-500 outline-none">
            </div>
            <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Stok Fisik <span
                  class="text-red-500">*</span></label><input type="number" wire:model.live="stok_fisik"
                placeholder="Hasil hitung fisik"
                class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition outline-none">
              @error('stok_fisik')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
              @enderror
            </div>
          </div>
          <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Selisih</label><input type="text"
              value="{{ number_format($selisih) }}" readonly disabled
              class="w-full rounded-xl border-2 {{ $selisih != 0 ? 'border-red-200 bg-red-50 text-red-600' : 'border-gray-200 bg-gray-100 text-gray-500' }} px-4 py-3 text-sm font-bold outline-none">
          </div>
          <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Tanggal Opname <span
                class="text-red-500">*</span></label><input type="date" wire:model="tanggal_opname"
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition outline-none">
            @error('tanggal_opname')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Keterangan <span
                class="text-gray-400 font-normal">(opsional)</span></label>
            <textarea wire:model="keterangan" rows="3" placeholder="Catatan tambahan…"
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition outline-none resize-none"></textarea>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-3">
          <p class="text-xs text-gray-400"><span class="text-red-500">*</span> Wajib diisi</p>
          <div class="flex items-center gap-2">
            <button @click="modalOpen = false"
              class="px-5 py-2.5 rounded-xl bg-white border-2 border-gray-200 hover:bg-gray-50 text-sm font-bold text-gray-700 transition">Batal</button>
            <button @click="$wire.simpan()"
              class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold transition shadow-lg shadow-emerald-600/25 flex items-center gap-2"><svg
                class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>Simpan Data</button>
          </div>
        </div>
      </div>
    </div>
  </template>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function stockOpnameManager() {
    return {
      modalOpen: false,

      init() {
        window.addEventListener('dataSaved', (e) => {
          this.modalOpen = false;
          Swal.fire({
            title: e.detail.title || 'Berhasil!',
            text: e.detail.message || 'Data berhasil disimpan.',
            icon: e.detail.type || 'success',
            confirmButtonColor: '#3B82F6',
            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl text-sm font-bold px-5 py-2.5' },
            toast: false, position: 'center', showConfirmButton: true,
          });
        });

        window.addEventListener('openModal', () => {
          this.modalOpen = true;
        });
      },

      confirmDelete(id) {
        Swal.fire({
          title: 'Hapus data ini?',
          text: 'Tindakan ini tidak dapat dibatalkan.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#EF4444',
          cancelButtonColor: '#94A3B8',
          confirmButtonText: 'Ya, Hapus',
          cancelButtonText: 'Batal',
          customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl text-sm font-bold px-5', cancelButton: 'rounded-xl text-sm font-bold px-5' },
        }).then((result) => {
          if (result.isConfirmed) { this.$wire.call('hapus', id); }
        });
      }
    };
  }
</script>
