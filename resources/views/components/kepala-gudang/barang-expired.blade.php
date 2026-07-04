<div x-data="barangExpiredManager()" x-init="init()" class="space-y-5">
{{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Barang Expired</h1>
          <p class="text-sm text-gray-400 mt-0.5">Catat tanggal kadaluarsa barang per batch</p>
        </div>
      </div>
    </div>
  </div>

  {{-- STATS --}}
  <div class="flex flex-wrap gap-4">
    <div class="flex-1 min-w-[150px] bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Sudah Expired</p>
        <p class="text-xl font-bold text-red-600">{{ $sudahExpired }}</p>
      </div>
    </div>
    <div class="flex-1 min-w-[150px] bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Hampir Expired</p>
        <p class="text-xl font-bold text-amber-600">{{ $hampirExpired }}</p>
      </div>
    </div>
    <div class="flex-1 min-w-[150px] bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Aman</p>
        <p class="text-xl font-bold text-emerald-600">{{ $sudahDinput->count() - $sudahExpired - $hampirExpired }}</p>
      </div>
    </div>
  </div>

  {{-- SEARCH --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div
        class="flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-red-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-red-100 transition">
        <div class="pl-3.5 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg></div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang..."
          class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
      </div>
    </div>
  </div>

  {{-- TABLE BELUM EXPIRED --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
      <h3 class="text-sm font-bold text-gray-700">Daftar Barang Masuk (Belum punya tanggal expired)</h3>
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
        <tbody class="divide-y divide-gray-50">
          @forelse($barangMasuk as $item)
            <tr class="hover:bg-red-50/30 transition">
              <td class="px-5 py-3 text-sm text-gray-600">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
              <td class="px-5 py-3 text-sm font-mono text-gray-700">{{ $item->barang->kode_barang ?? '-' }}</td>
              <td class="px-5 py-3 text-sm font-semibold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-3 text-sm text-gray-500">{{ $item->supplier->nama_supplier ?? '-' }}</td>
              <td class="px-5 py-3 text-center">
                <button wire:click="edit({{ $item->id_masuk }})"
                  class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>Input Expired
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-12 text-center text-gray-400">Semua barang sudah punya tanggal expired
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $barangMasuk->links() }}</div>
  </div>

  {{-- TABLE SUDAH EXPIRED --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
      <h3 class="text-sm font-bold text-gray-700">Daftar Barang Sudah Punya Tanggal Expired</h3>
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
        <tbody class="divide-y divide-gray-50">
          @forelse($sudahDinput as $item)
            <tr class="hover:bg-gray-50 transition">
              <td class="px-5 py-3 text-sm text-gray-600">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
              <td class="px-5 py-3 text-sm font-mono text-gray-700">{{ $item->barang->kode_barang ?? '-' }}</td>
              <td class="px-5 py-3 text-sm font-semibold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-3 text-sm font-semibold">{{ $item->tanggal_expired->format('d/m/Y') }}</td>
              <td class="px-5 py-3 text-center">
                @if ($item->status_expired == 'expired')
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-700 border border-red-100 rounded-lg text-xs font-semibold"><svg
                      class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>Expired</span>
                @elseif($item->status_expired == 'hampir_expired')
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg text-xs font-semibold"><svg
                      class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>Hampir</span>
                @else<span
                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold"><svg
                      class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>Aman</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-12 text-center text-gray-400">Belum ada data expired</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $sudahDinput->links() }}</div>
  </div>

  {{-- MODAL --}}
  <template x-teleport="body">
    <div x-show="modalOpen" x-cloak x-transition class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div @click="modalOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-md z-40"></div>
      <div @click.stop class="relative z-50 w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-red-600 px-6 py-5">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 text-white">
              <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"><svg class="w-5 h-5"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg></div>
              <div>
                <h2 class="text-lg font-bold">Input Tanggal Expired</h2>
                <p class="text-red-100 text-xs">Catat kadaluarsa barang batch ini</p>
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
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Tanggal Expired <span
                class="text-red-500">*</span></label>
            <input type="date" wire:model="tanggal_expired"
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-red-500 focus:ring-4 focus:ring-red-100 transition outline-none">
            @error('tanggal_expired')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div class="bg-gray-50 rounded-xl p-4 text-xs text-gray-500">
            <p class="font-bold mb-2">Keterangan Status:</p>
            <div class="flex items-center gap-2 mb-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aman :
              > 30 hari lagi</div>
            <div class="flex items-center gap-2 mb-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Hampir
              Expired : ≤ 30 hari</div>
            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> Expired : sudah
              melewati tanggal</div>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-3">
          <button @click="modalOpen = false"
            class="px-5 py-2.5 rounded-xl bg-white border-2 border-gray-200 hover:bg-gray-50 text-sm font-bold text-gray-700 transition">Batal</button>
          <button wire:click="simpan"
            class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition shadow-lg shadow-red-600/25">Simpan</button>
        </div>
      </div>
    </div>
  </template>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function barangExpiredManager() {
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
    };
  }
</script>
<style>
  [x-cloak] {
    display: none !important
  }
</style>
