<div x-data="supplierManager()" x-init="init()" class="space-y-5">

  {{-- TOAST --}}
  <div x-show="toast.show" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-8 scale-95"
    x-transition:enter-end="opacity-100 translate-x-0 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
    x-transition:leave-end="opacity-0 translate-x-8 scale-95" class="fixed bottom-5 right-5 z-[200]">
    <div :class="toast.type === 'success' ? 'border-l-[3px] border-emerald-500' : 'border-l-[3px] border-red-500'"
      class="flex items-start gap-3 w-80 bg-white rounded-2xl shadow-[0_8px_32px_rgba(0,0,0,0.12)] border border-gray-200 px-4 py-3.5">
      <div :class="toast.type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'"
        class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0">
        <svg x-show="toast.type === 'success'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
        <svg x-show="toast.type === 'error'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-bold text-gray-900" x-text="toast.title"></p>
        <p class="text-xs text-gray-500 mt-0.5" x-text="toast.message"></p>
      </div>
      <button @click="toast.show = false" class="text-gray-300 hover:text-gray-500 transition shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Data Supplier</h1>
            <p class="text-sm text-gray-400 mt-0.5">Mengelola master data supplier/pemasok</p>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <div class="flex items-center gap-2.5 bg-white border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
              <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
              </svg>
            </div>
            <div>
              <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</p>
              <p class="text-xl font-bold text-gray-900">{{ $stats['totalItems'] }}</p>
            </div>
          </div>

          <a href="{{ route('laporan.supplier.pdf') }}" target="_blank"
            class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            PDF
          </a>

          <button wire:click="openAddModal"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_4px_12px_rgba(37,99,235,0.25)]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Supplier
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- SEARCH --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div class="flex items-center gap-2.5">
        <div
          class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-blue-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 transition">
          <div class="pl-3.5 shrink-0 text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode atau nama supplier…"
            class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
        </div>
        @if ($search)
          <button wire:click="resetFilters"
            class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2.5 rounded-lg bg-red-50 border border-red-200 hover:bg-red-100 text-xs font-semibold text-red-600 transition">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Reset
          </button>
        @endif
      </div>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[800px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left w-12"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">#</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kode</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Supplier</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">No.
                Telepon</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Email</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">No.
                Rekening</span></th>
            <th class="px-5 py-4 text-center w-32"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($suppliers as $index => $supplier)
            <tr class="hover:bg-blue-50/30 transition">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $suppliers->firstItem() + $index }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-violet-50 text-violet-700 border border-violet-100">{{ $supplier->kode_supplier }}</span>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $supplier->nama_supplier }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $supplier->alamat ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $supplier->no_telp ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $supplier->email ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $supplier->no_rekening ?? '-' }}</td>
              <td class="px-5 py-4">
                <div class="flex items-center justify-center gap-0.5">
                  <button wire:click="edit({{ $supplier->id_supplier }})"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition"
                    title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button @click="confirmDelete({{ $supplier->id_supplier }})"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                    title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Belum Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada data supplier yang tercatat.</p>
                  </div>
                  <button wire:click="openAddModal"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_4px_12px_rgba(37,99,235,0.25)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Sekarang
                  </button>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($suppliers->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $suppliers->links() }}</div>
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
              <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                <svg x-show="!$wire.isEdit" class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <svg x-show="$wire.isEdit" class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </div>
              <div class="text-white">
                <h2 class="text-lg font-bold" x-text="$wire.isEdit ? 'Edit Supplier' : 'Tambah Supplier'"></h2>
                <p class="text-blue-100 text-xs">Lengkapi field wajib <span class="text-white">*</span></p>
              </div>
            </div>
            <button @click="modalOpen = false"
              class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <div class="px-6 py-5 space-y-4 overflow-y-auto" style="max-height: calc(100vh - 250px);">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">Kode Supplier <span
                  class="text-red-500">*</span></label>
              <input type="text" wire:model="kode_supplier" placeholder="SUP001"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
              @error('kode_supplier')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">Nama Supplier <span
                  class="text-red-500">*</span></label>
              <input type="text" wire:model="nama_supplier" placeholder="Nama lengkap supplier"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
              @error('nama_supplier')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
              @enderror
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">No. Telepon</label>
              <input type="text" wire:model="no_telp" placeholder="0511-123456"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">Email</label>
              <input type="email" wire:model="email" placeholder="supplier@email.com"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
            </div>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">No. Rekening</label>
            <input type="text" wire:model="no_rekening" placeholder="Nomor rekening"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Alamat</label>
            <textarea wire:model="alamat" rows="2" placeholder="Alamat lengkap supplier"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none resize-none"></textarea>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Keterangan <span
                class="text-gray-400 font-normal">(opsional)</span></label>
            <textarea wire:model="keterangan" rows="2" placeholder="Catatan tambahan…"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none resize-none"></textarea>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-3">
          <p class="text-xs text-gray-400"><span class="text-red-500">*</span> Wajib diisi</p>
          <div class="flex items-center gap-2">
            <button @click="modalOpen = false"
              class="px-5 py-2.5 rounded-xl bg-white border-2 border-gray-200 hover:bg-gray-50 text-sm font-bold text-gray-700 transition">Batal</button>
            <button @click="$wire.isEdit ? $wire.update() : $wire.simpan()"
              class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-lg shadow-blue-600/25 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span x-text="$wire.isEdit ? 'Simpan Perubahan' : 'Simpan Data'"></span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function supplierManager() {
    return {
      modalOpen: false,
      toast: {
        show: false,
        type: 'success',
        title: '',
        message: ''
      },
      init() {
        window.addEventListener('dataSaved', (e) => {
          this.modalOpen = false;
          this.showToast(e.detail.type, e.detail.title, e.detail.message);
        });
        window.addEventListener('openModal', () => {
          this.modalOpen = true;
        });
      },
      showToast(type, title, message) {
        this.toast = {
          show: true,
          type,
          title,
          message
        };
        setTimeout(() => this.toast.show = false, 4000);
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
          customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl text-sm font-bold px-5',
            cancelButton: 'rounded-xl text-sm font-bold px-5'
          },
        }).then((result) => {
          if (result.isConfirmed) {
            this.$wire.call('hapus', id);
          }
        });
      }
    };
  }
</script>
