<div x-data="dataSalesManager()" x-init="init()" class="space-y-5">

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
          <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Data Sales</h1>
            <p class="text-sm text-gray-400 mt-0.5">Master Data Management</p>
          </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
          <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm">
            <div class="flex items-center gap-2.5">
              <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Aktif</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['aktif'] }}</p>
              </div>
            </div>
            <div class="w-px h-10 bg-gray-200"></div>
            <div class="flex items-center gap-2.5">
              <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                </svg>
              </div>
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p>
              </div>
            </div>
          </div>
          <button wire:click="openAddModal"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_4px_12px_rgba(37,99,235,0.25)]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Sales
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- SEARCH --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div
        class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-blue-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 transition">
        <div class="pl-3.5 shrink-0 text-gray-400">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode atau nama sales…"
          class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
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
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Sales</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">No.
                HP</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Wilayah</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status</span></th>
            <th class="px-5 py-4 text-center"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dokumen</span></th>
            <th class="px-5 py-4 text-center w-32"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($sales as $index => $s)
            @php $user = $users[$s->nama_sales] ?? null; @endphp
            <tr class="hover:bg-blue-50/30 transition">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $sales->firstItem() + $index }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-violet-50 text-violet-700 border border-violet-100">{{ $s->kode_sales }}</span>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $s->nama_sales }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $s->no_hp }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $s->wilayah_tugas }}</td>
              <td class="px-5 py-4">
                @if ($s->status == 'Aktif')
                  <span
                    class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold">Aktif</span>
                @else
                  <span
                    class="px-2.5 py-1 bg-red-50 text-red-700 border border-red-100 rounded-lg text-xs font-semibold">Non-Aktif</span>
                @endif
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center justify-center gap-2">
                  @if ($user?->foto_ktp)
                    <a href="{{ Storage::url($user->foto_ktp) }}" target="_blank"
                      class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition"
                      title="KTP">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5" />
                      </svg>
                    </a>
                  @else
                    <span class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-300"
                      title="Belum upload KTP">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5" />
                      </svg>
                    </span>
                  @endif
                  @if ($user?->surat_kerja)
                    <a href="{{ Storage::url($user->surat_kerja) }}" target="_blank"
                      class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-100 transition"
                      title="Surat Kerja">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                    </a>
                  @else
                    <span class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-300"
                      title="Belum upload Surat Kerja">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                    </span>
                  @endif
                </div>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center justify-center gap-0.5">
                  <button wire:click="edit({{ $s->id_sales }})"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition"
                    title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button @click="confirmDelete({{ $s->id_sales }})"
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
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Belum Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada data sales.</p>
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
    @if ($sales->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $sales->links() }}</div>
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
                <h2 class="text-lg font-bold" x-text="$wire.isEdit ? 'Edit Sales' : 'Tambah Sales'"></h2>
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
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Nama Lengkap <span
                class="text-red-500">*</span></label>
            <input type="text" wire:model="nama_sales" placeholder="Masukkan nama sales"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
            @error('nama_sales')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">No. HP / WhatsApp <span
                class="text-red-500">*</span></label>
            <input type="text" wire:model="no_hp" placeholder="0812-3456-7890"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
            @error('no_hp')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Wilayah Tugas <span
                class="text-red-500">*</span></label>
            <select wire:model="wilayah_tugas"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
              <option value="">-- Pilih Wilayah --</option>
              @foreach ($wilayahs as $w)
                <option value="{{ $w->nama_wilayah }}">{{ $w->nama_wilayah }}</option>
              @endforeach
            </select>
            @error('wilayah_tugas')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Status <span
                class="text-red-500">*</span></label>
            <select wire:model="status"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
              <option value="Aktif">Aktif</option>
              <option value="Non-Aktif">Non-Aktif</option>
            </select>
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
  function dataSalesManager() {
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
