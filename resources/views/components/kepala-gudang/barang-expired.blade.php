<div x-data="barangExpiredManager()" x-init="init()" class="space-y-5">

  {{-- TOAST NOTIFICATION --}}
  <div x-show="toast.show" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-4" class="fixed top-5 right-5 z-[100]">
    <div
      :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' :
          'bg-red-50 border-red-200 text-red-800'"
      class="border rounded-xl shadow-lg flex items-center gap-3 min-w-[280px] px-4 py-3">
      <div :class="toast.type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'"
        class="w-8 h-8 rounded-lg flex items-center justify-center">
        <svg x-show="toast.type === 'success'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <svg x-show="toast.type === 'error'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>
      <div class="flex-1">
        <p class="font-semibold text-sm" x-text="toast.title"></p>
        <p class="text-xs opacity-75" x-text="toast.message"></p>
      </div>
      <button @click="toast.show = false" class="opacity-50 hover:opacity-100">✕</button>
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
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Barang Expired</h1>
            <p class="text-sm text-gray-400 mt-0.5">Catat tanggal kadaluarsa barang per batch</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- STATS CARD --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
          <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <p class="text-xs text-red-600 font-semibold">Sudah Expired</p>
          <p class="text-2xl font-bold text-red-700">{{ $sudahExpired }}</p>
        </div>
      </div>
    </div>
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
          <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <p class="text-xs text-yellow-600 font-semibold">Hampir Expired (≤30 hari)</p>
          <p class="text-2xl font-bold text-yellow-700">{{ $hampirExpired }}</p>
        </div>
      </div>
    </div>
  </div>

  {{-- SEARCH --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div
        class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-emerald-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-emerald-100 transition">
        <div class="pl-3.5 shrink-0 text-gray-400">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search"
          placeholder="Cari barang yang belum punya tanggal expired..."
          class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
      </div>
    </div>
  </div>

  {{-- TABLE LIST BARANG YANG BELUM PUNYA EXPIRED --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
      <h3 class="text-sm font-semibold text-gray-700">Daftar Barang Masuk (Belum punya tanggal expired)</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Tanggal Masuk</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Kode Barang</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Nama Barang</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Supplier</th>
            <th class="px-5 py-3 text-center text-xs font-bold text-gray-400 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($barangMasuk as $item)
            <tr class="hover:bg-emerald-50/30 transition">
              <td class="px-5 py-3 text-sm text-gray-600">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
              <td class="px-5 py-3 text-sm font-mono text-gray-700">{{ $item->barang->kode_barang ?? '-' }}</td>
              <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-3 text-sm text-gray-500">{{ $item->supplier->nama_supplier ?? '-' }}</td>
              <td class="px-5 py-3 text-center">
                <button wire:click="edit({{ $item->id_masuk }})"
                  class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Input Expired
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                <div class="flex flex-col items-center">
                  <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <p class="text-sm">Semua barang sudah punya tanggal expired</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
      {{ $barangMasuk->links() }}
    </div>
  </div>

  {{-- TABLE SUDAH DIINPUT --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mt-5">
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
      <h3 class="text-sm font-semibold text-gray-700">Daftar Barang Sudah Punya Tanggal Expired</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Tanggal Masuk</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Kode Barang</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Nama Barang</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Tgl Expired</th>
            <th class="px-5 py-3 text-center text-xs font-bold text-gray-400 uppercase">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($sudahDinput as $item)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-5 py-3 text-sm text-gray-600">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
              <td class="px-5 py-3 text-sm font-mono text-gray-700">{{ $item->barang->kode_barang ?? '-' }}</td>
              <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-3 text-sm font-semibold">{{ $item->tanggal_expired->format('d/m/Y') }}</td>
              <td class="px-5 py-3 text-center">
                @if ($item->status_expired == 'expired')
                  <span class="px-2 py-0.5 bg-red-50 text-red-700 text-xs font-semibold rounded-lg">❌ Expired</span>
                @elseif($item->status_expired == 'hampir_expired')
                  <span class="px-2 py-0.5 bg-yellow-50 text-yellow-700 text-xs font-semibold rounded-lg">⚠️
                    Hampir</span>
                @else
                  <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg">✅
                    Aman</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-12 text-center text-gray-500">Belum ada data expired</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
      {{ $sudahDinput->links() }}
    </div>
  </div>

  {{-- MODAL INPUT EXPIRED --}}
  <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition.opacity>
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="modalOpen = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div @click.stop class="relative bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="bg-emerald-600 px-5 py-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </div>
              <div class="text-white">
                <h2 class="text-base font-bold">Input Tanggal Expired</h2>
                <p class="text-emerald-100 text-xs">Catat kadaluarsa barang batch ini</p>
              </div>
            </div>
            <button @click="modalOpen = false" class="text-white/80 hover:text-white">✕</button>
          </div>
        </div>

        <div class="p-5 space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Expired <span
                class="text-red-500">*</span></label>
            <input type="date" wire:model="tanggal_expired" min="{{ date('Y-m-d') }}"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            @error('tanggal_expired')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="bg-gray-50 rounded-lg p-3 text-xs text-gray-500">
            <p class="font-semibold mb-1">📌 Keterangan Status:</p>
            <ul class="space-y-1">
              <li>✅ <span class="text-green-600">Aman</span> : > 30 hari lagi</li>
              <li>⚠️ <span class="text-yellow-600">Hampir Expired</span> : ≤ 30 hari</li>
              <li>❌ <span class="text-red-600">Expired</span> : sudah melewati tanggal expired</li>
            </ul>
          </div>
        </div>

        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex justify-end gap-2">
          <button @click="modalOpen = false"
            class="px-3 py-1.5 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium">Batal</button>
          <button wire:click="simpan"
            class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium">Simpan</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function barangExpiredManager() {
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

      openModal(id) {
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
      }
    };
  }
</script>
<style>
  [x-cloak] {
    display: none !important;
  }
</style>
