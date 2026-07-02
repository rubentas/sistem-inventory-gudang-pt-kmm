<div x-data="inventoryManager()" x-init="init()" class="space-y-5">

  {{-- TOAST --}}
  <div x-show="toast.show" x-cloak x-transition class="fixed bottom-5 right-5 z-[200]">
    <div :class="toast.type === 'success' ? 'border-l-[3px] border-emerald-500' : 'border-l-[3px] border-red-500'"
      class="flex items-start gap-3 w-80 bg-white rounded-2xl shadow-lg border border-gray-200 px-4 py-3.5">
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
      <button @click="toast.show = false" class="text-gray-300 hover:text-gray-500 transition"><svg class="w-4 h-4"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg></button>
    </div>
  </div>

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-teal-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Inventory</h1>
          <p class="text-sm text-gray-400 mt-0.5">Catat stok fisik barang per periode</p>
        </div>
      </div>
      <button @click="openModal()"
        class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
        </svg>Tambah Inventory
      </button>
    </div>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5 flex items-center gap-3 flex-wrap">
      <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Filter:</span>
      <div
        class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-teal-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-teal-100 transition max-w-xs">
        <div class="pl-3.5 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg></div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang..."
          class="flex-1 h-10 px-3 text-sm bg-transparent focus:outline-none text-gray-900">
      </div>
      <input type="date" wire:model.live="filterTanggal"
        class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 font-semibold bg-white focus:border-teal-500 transition outline-none cursor-pointer">
      @if ($search || $filterTanggal)
        <button wire:click="$set('search', ''); $set('filterTanggal', '')"
          class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">Reset</button>
      @endif
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[900px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Tanggal</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Barang</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Stok Awal</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Masuk</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Keluar</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Sistem</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Fisik</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Selisih</th>
            <th class="px-5 py-4 text-center text-xs font-bold text-gray-400 uppercase w-24">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($inventories as $inv)
            <tr class="hover:bg-teal-50/30 transition">
              <td class="px-5 py-4 text-sm">{{ $inv->tanggal->format('d/m/Y') }}</td>
              <td class="px-5 py-4 text-sm font-semibold">{{ $inv->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-right">{{ number_format($inv->stok_awal) }}</td>
              <td class="px-5 py-4 text-sm text-right text-emerald-600">+{{ number_format($inv->barang_masuk) }}</td>
              <td class="px-5 py-4 text-sm text-right text-red-500">-{{ number_format($inv->barang_keluar) }}</td>
              <td class="px-5 py-4 text-sm text-right font-semibold">{{ number_format($inv->stok_sistem) }}</td>
              <td class="px-5 py-4 text-sm text-right font-bold">{{ number_format($inv->stok_fisik) }}</td>
              <td
                class="px-5 py-4 text-sm text-right font-bold {{ $inv->selisih != 0 ? 'text-red-600' : 'text-gray-600' }}">
                {{ $inv->selisih >= 0 ? '+' . $inv->selisih : $inv->selisih }}</td>
              <td class="px-5 py-4 text-center">
                <button @click="confirmDelete({{ $inv->id_inventory }})"
                  class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition"><svg
                    class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg></button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-6 py-20 text-center text-gray-400">Belum ada data inventory</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($inventories->hasPages())
      <div class="px-5 py-3 border-t border-gray-100">{{ $inventories->links() }}</div>
    @endif
  </div>

  {{-- MODAL --}}
  <template x-teleport="body">
    <div x-show="modalOpen" x-cloak x-transition class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div @click="modalOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-md z-40"></div>
      <div @click.stop class="relative z-50 w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-teal-600 px-6 py-5">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 text-white">
              <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"><svg class="w-5 h-5"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg></div>
              <div>
                <h2 class="text-lg font-bold">Tambah Inventory</h2>
                <p class="text-teal-100 text-xs">Catat stok fisik barang</p>
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
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-100 transition outline-none">
              <option value="">-- Pilih Barang --</option>
              @foreach ($barangs as $b)
                <option value="{{ $b->id_barang }}">{{ $b->kode_barang }} - {{ $b->nama_barang }}</option>
              @endforeach
            </select>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div><label class="block text-xs font-bold text-gray-700 mb-1">Stok Awal</label><input type="text"
                value="{{ number_format($stok_awal) }}" readonly disabled
                class="w-full rounded-xl border-2 border-gray-200 bg-gray-100 px-4 py-3 text-sm font-medium text-gray-500 outline-none">
            </div>
            <div><label class="block text-xs font-bold text-gray-700 mb-1">Barang Masuk</label><input type="text"
                value="+{{ number_format($barang_masuk) }}" readonly disabled
                class="w-full rounded-xl border-2 border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-600 outline-none">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div><label class="block text-xs font-bold text-gray-700 mb-1">Barang Keluar</label><input type="text"
                value="-{{ number_format($barang_keluar) }}" readonly disabled
                class="w-full rounded-xl border-2 border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-500 outline-none">
            </div>
            <div><label class="block text-xs font-bold text-gray-700 mb-1">Stok Sistem</label><input type="text"
                value="{{ number_format($stok_sistem) }}" readonly disabled
                class="w-full rounded-xl border-2 border-gray-200 bg-gray-100 px-4 py-3 text-sm font-medium text-gray-500 outline-none">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Stok Fisik <span
                  class="text-red-500">*</span></label><input type="number" wire:model.live="stok_fisik"
                placeholder="Hasil hitung fisik"
                class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-100 transition outline-none">
            </div>
            <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Selisih</label><input type="text"
                value="{{ $selisih >= 0 ? '+' . $selisih : $selisih }}" readonly disabled
                class="w-full rounded-xl border-2 {{ $selisih != 0 ? 'border-red-200 bg-red-50 text-red-600' : 'border-gray-200 bg-gray-100 text-gray-500' }} px-4 py-3 text-sm font-bold outline-none">
            </div>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Tanggal <span
                class="text-red-500">*</span></label>
            <input type="date" wire:model="tanggal"
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-teal-500 transition outline-none">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Keterangan <span
                class="text-gray-400 font-normal">(opsional)</span></label>
            <textarea wire:model="keterangan" rows="2" placeholder="Catatan..."
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-teal-500 transition outline-none resize-none"></textarea>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3">
          <button @click="modalOpen = false"
            class="px-5 py-2.5 rounded-xl bg-white border-2 border-gray-200 hover:bg-gray-50 text-sm font-bold text-gray-700 transition">Batal</button>
          <button @click="$wire.simpan()"
            class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold transition shadow-lg">Simpan</button>
        </div>
      </div>
    </div>
  </template>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function inventoryManager() {
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
      openModal() {
        this.modalOpen = true;
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
            cancelButtonText: 'Batal'
          })
          .then((result) => {
            if (result.isConfirmed) {
              this.$wire.call('hapus', id);
            }
          });
      }
    };
  }
</script>
