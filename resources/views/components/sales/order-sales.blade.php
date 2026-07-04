<div x-data="salesOrderManager()" x-init="init()" class="space-y-5">
{{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Order Sales</h1>
            <p class="text-sm text-gray-400 mt-0.5">Buat dan kelola pesanan Anda</p>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm">
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
            <div class="w-px h-10 bg-gray-200"></div>
            <div class="flex items-center gap-2.5">
              <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pending</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
              </div>
            </div>
          </div>

          <button wire:click="openAddModal"
            class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 active:scale-95 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_4px_12px_rgba(234,88,12,0.25)]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Buat Order
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- SEARCH & FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div class="flex flex-col sm:flex-row gap-2.5">
        <div
          class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-orange-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-orange-100 transition">
          <div class="pl-3.5 shrink-0 text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama barang…"
            class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
        </div>
        <select wire:model.live="filterStatus"
          class="h-11 px-4 border-2 border-gray-200 rounded-xl text-sm font-semibold bg-white text-gray-700 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none cursor-pointer">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="diproses">Diproses</option>
          <option value="selesai">Selesai</option>
        </select>
      </div>
    </div>
    @if ($filterStatus || $search)
      <div class="px-4 sm:px-5 py-3 border-t border-gray-100 bg-gray-50/50">
        <button wire:click="resetFilters"
          class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 hover:bg-red-100 text-xs font-semibold text-red-600 transition">
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Reset Filter
        </button>
      </div>
    @endif
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[700px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Barang</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Wilayah</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Toko</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Subtotal</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status</span></th>
            <th class="px-5 py-4 text-center w-24"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($orders as $order)
            @php
              $harga = is_numeric($order->harga_jual) ? (int) $order->harga_jual : 0;
              $subtotal = $harga * (int) $order->jumlah;
            @endphp
            <tr class="hover:bg-orange-50/30 transition">
              <td class="px-5 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                {{ $order->tanggal_order->translatedFormat('d/m/Y') }}</td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $order->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $order->wilayah->nama_wilayah ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $order->nama_toko ?: '-' }}</td>
              <td class="px-5 py-4"><span
                  class="text-sm font-bold text-orange-600">{{ number_format($order->jumlah) }}</span><span
                  class="text-xs text-gray-400 ml-1">{{ $order->barang->satuan ?? 'pcs' }}</span></td>
              <td class="px-5 py-4 text-sm font-bold text-blue-600">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
              <td class="px-5 py-4">
                @if ($order->status == 'pending')
                  <span
                    class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg text-xs font-semibold">Pending</span>
                @elseif($order->status == 'diproses')
                  <span
                    class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-xs font-semibold">Diproses</span>
                @else
                  <span
                    class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold">Selesai</span>
                @endif
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center justify-center gap-0.5">
                  @if ($order->status == 'pending')
                    <button wire:click="edit({{ $order->id_order }})"
                      class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition"
                      title="Edit">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button @click="confirmDelete({{ $order->id_order }})"
                      class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                      title="Hapus">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  @else
                    <span class="text-xs text-gray-400">—</span>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Belum Ada Order</h3>
                    <p class="text-sm text-gray-400">Belum ada order yang dibuat.</p>
                  </div>
                  <button wire:click="openAddModal"
                    class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_4px_12px_rgba(234,88,12,0.25)]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Sekarang
                  </button>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($orders->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
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

        <div class="bg-orange-600 px-6 py-5">
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
                <h2 class="text-lg font-bold" x-text="$wire.isEdit ? 'Edit Order' : 'Buat Order'"></h2>
                <p class="text-orange-100 text-xs">Lengkapi field wajib <span class="text-white">*</span></p>
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
          {{-- BARANG --}}
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Barang <span
                class="text-red-500">*</span></label>
            <select wire:model.live="id_barang"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none cursor-pointer">
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

          {{-- INFO STOK --}}
          @if ($id_barang)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                  </svg>
                  <span class="text-sm font-semibold text-gray-700">Stok Tersedia: <span
                      class="text-emerald-600 font-bold">{{ number_format($stok_tersedia) }} Dos</span></span>
                </div>
                @if ($stok_tersedia <= $stok_minimum && $stok_tersedia > 0)
                  <span
                    class="px-2 py-0.5 bg-red-50 text-red-700 border border-red-100 rounded-lg text-xs font-semibold">⚠️
                    Menipis</span>
                @elseif($stok_tersedia > 0)
                  <span
                    class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold">✅
                    Aman</span>
                @endif
              </div>
            </div>
          @endif

          {{-- WILAYAH --}}
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Wilayah <span
                class="text-red-500">*</span></label>
            <select wire:model="id_wilayah"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none cursor-pointer">
              <option value="">-- Pilih Wilayah --</option>
              @foreach ($wilayahs as $wilayah)
                <option value="{{ $wilayah->id_wilayah }}">{{ $wilayah->nama_wilayah }}</option>
              @endforeach
            </select>
            @error('id_wilayah')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>

          {{-- SALES --}}
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Sales</label>
            <select wire:model="id_sales"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none cursor-pointer">
              <option value="">— Pilih Sales —</option>
              @foreach ($salesList as $sales)
                <option value="{{ $sales->id_sales }}">{{ $sales->kode_sales }} - {{ $sales->nama_sales }}</option>
              @endforeach
            </select>
          </div>

          {{-- NAMA TOKO --}}
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Nama Toko</label>
            <input type="text" wire:model="nama_toko" placeholder="Masukkan nama toko"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none">
          </div>

          <div class="grid grid-cols-2 gap-3">
            {{-- JUMLAH --}}
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">Jumlah <span
                  class="text-red-500">*</span></label>
              <input type="number" wire:model.live="jumlah" placeholder="0" min="1"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none">
              @error('jumlah')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
              @enderror
            </div>

            {{-- TANGGAL --}}
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">Tanggal <span
                  class="text-red-500">*</span></label>
              <input type="date" wire:model="tanggal_order"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none">
              @error('tanggal_order')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
              @enderror
            </div>
          </div>

          {{-- HARGA JUAL --}}
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Harga Jual (Rp) <span
                class="text-red-500">*</span></label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
              <input type="number" wire:model.live="harga_jual" placeholder="0"
                class="w-full rounded-xl border-2 border-gray-300 bg-white pl-12 pr-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none">
            </div>
            @error('harga_jual')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>

          {{-- KETERANGAN --}}
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Keterangan <span
                class="text-gray-400 font-normal">(opsional)</span></label>
            <textarea wire:model="keterangan" rows="3" placeholder="Catatan tambahan…"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition outline-none resize-none"></textarea>
          </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-3">
          <p class="text-xs text-gray-400"><span class="text-red-500">*</span> Wajib diisi</p>
          <div class="flex items-center gap-2">
            <button @click="modalOpen = false"
              class="px-5 py-2.5 rounded-xl bg-white border-2 border-gray-200 hover:bg-gray-50 text-sm font-bold text-gray-700 transition">Batal</button>
            <button @click="$wire.isEdit ? $wire.update() : $wire.simpan()"
              class="px-6 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold transition shadow-lg shadow-orange-600/25 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span x-text="$wire.isEdit ? 'Simpan Perubahan' : 'Simpan Order'"></span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function salesOrderManager() {
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
