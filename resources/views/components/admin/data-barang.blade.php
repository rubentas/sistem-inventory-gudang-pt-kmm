<div x-data="dataBarangManager()" x-init="init()" class="space-y-5">
  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Data Barang</h1>
            <p class="text-sm text-gray-400 mt-0.5">Master Data Management</p>
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
                <p class="text-xl font-bold text-gray-900">{{ $stats['totalItems'] }}</p>
              </div>
            </div>
            <div class="w-px h-10 bg-gray-200"></div>
            <div class="flex items-center gap-2.5">
              <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
              </div>
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kategori</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['kategori'] }}</p>
              </div>
            </div>
          </div>

          <button wire:click="exportPdf"
            class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            PDF
          </button>

          <button wire:click="openAddModal"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_4px_12px_rgba(37,99,235,0.25)]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Barang
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- SEARCH & FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm relative z-20">
    <div class="p-4 sm:p-5">
      <div class="flex flex-col sm:flex-row gap-2.5">
        <div
          class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-blue-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 transition">
          <div class="pl-3.5 shrink-0 text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode atau nama barang…"
            class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
        </div>

        <select wire:model="filterKategori" wire:change="$refresh"
          class="h-11 px-4 border-2 border-gray-200 rounded-xl text-sm font-semibold bg-white text-gray-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
          <option value="">Semua Kategori</option>
          @foreach ($kategoriList as $kat)
            <option value="{{ $kat }}">{{ $kat }}</option>
          @endforeach
        </select>

        <select wire:model="filterStok" wire:change="$refresh"
          class="h-11 px-4 border-2 border-gray-200 rounded-xl text-sm font-semibold bg-white text-gray-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
          <option value="">Semua Stok</option>
          <option value="habis">Stok Habis</option>
          <option value="aman">Stok Aman</option>
          <option value="menipis">Stok Menipis</option>
        </select>
      </div>
    </div>
    @if ($filterKategori || $filterStok || $search)
      <div class="px-4 sm:px-5 py-3 border-t border-gray-100 bg-gray-50/50 flex items-center gap-2">
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
      <table class="w-full min-w-[900px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left w-12"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">#</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kode</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Barang</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Satuan</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Harga Jual</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Min</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Saat Ini</span></th>
            <th class="px-5 py-4 text-center w-32"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($barangs as $index => $barang)
            <tr class="hover:bg-blue-50/30 transition">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $barangs->firstItem() + $index }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-violet-50 text-violet-700 border border-violet-100">{{ $barang->kode_barang }}</span>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $barang->nama_barang }}</td>
              <td class="px-5 py-4">
                <span
                  class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg text-xs font-semibold">{{ $barang->kategori ?? '-' }}</span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $barang->satuan }}</td>
              <td class="px-5 py-4 text-sm font-medium text-gray-700">Rp
                {{ number_format($barang->harga_jual_default ?? 0, 0, ',', '.') }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ number_format($barang->stok_minimum) }}</td>
              <td class="px-5 py-4">
                @php $stokSekarang = $barang->stok ? $barang->stok->jumlah_stok : 0; @endphp
                <div class="flex items-center gap-1.5">
                  <span
                    class="text-sm font-bold {{ $stokSekarang <= $barang->stok_minimum ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($stokSekarang) }}</span>
                </div>
                <div class="mt-1">
                  @if ($stokSekarang <= 0)
                    <span
                      class="px-1.5 py-0.5 bg-gray-50 text-gray-700 border border-gray-100 rounded-md text-[10px] font-bold uppercase">Habis</span>
                  @elseif ($stokSekarang <= $barang->stok_minimum)
                    <span
                      class="px-1.5 py-0.5 bg-red-50 text-red-600 border border-red-100 rounded-md text-[10px] font-bold uppercase">Menipis</span>
                  @else
                    <span
                      class="px-1.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-md text-[10px] font-bold uppercase">Aman</span>
                  @endif
                </div>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center justify-center gap-0.5">
                  <button wire:click="edit({{ $barang->id_barang }})"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition"
                    title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button @click="confirmDelete({{ $barang->id_barang }})"
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
              <td colspan="9" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Belum Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada data barang yang tercatat.</p>
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
    @if ($barangs->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $barangs->links() }}</div>
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
                <h2 class="text-lg font-bold" x-text="$wire.isEdit ? 'Edit Barang' : 'Tambah Barang'"></h2>
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
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Kode Barang <span
                class="text-red-500">*</span></label>
            <input type="text" wire:model="kode_barang" placeholder="Contoh: A281008S"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
            @error('kode_barang')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Nama Barang <span
                class="text-red-500">*</span></label>
            <input type="text" wire:model="nama_barang" placeholder="Nama lengkap barang"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
            @error('nama_barang')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">Kategori <span
                  class="text-red-500">*</span></label>
              <select wire:model="kategori"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
                <option value="">-- Pilih --</option>
                <option value="Snack">Snack</option>
                <option value="Mie">Mie</option>
                <option value="Roti & Kue">Roti & Kue</option>
                <option value="Permen & Coklat">Permen & Coklat</option>
                <option value="Minuman">Minuman</option>
                <option value="Susu & Nutrisi">Susu & Nutrisi</option>
                <option value="Bumbu & Sambal">Bumbu & Sambal</option>
                <option value="Lainnya">Lainnya</option>
              </select>
              @error('kategori')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">Satuan <span
                  class="text-red-500">*</span></label>
              <select wire:model="satuan"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
                <option value="Pcs">Pcs</option>
                <option value="Kg">Kg</option>
                <option value="Gram">Gram</option>
                <option value="Liter">Liter</option>
                <option value="Dus">Dus</option>
                <option value="Pak">Pak</option>
                <option value="Ball">Ball</option>
                <option value="Zak">Zak</option>
              </select>
              @error('satuan')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
              @enderror
            </div>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Stok Minimum <span
                class="text-red-500">*</span></label>
            <input type="number" wire:model="stok_minimum" placeholder="0" min="0"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
            @error('stok_minimum')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-400 mt-1">Jika stok &le; nilai ini, status "Menipis". Patokan minimal 10
              karton.</p>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Harga Jual Default (Rp)</label>
            <input type="number" wire:model="harga_jual_default" placeholder="Harga jual default" min="0"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 placeholder-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
            @error('harga_jual_default')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Keterangan <span
                class="text-gray-400 font-normal">(opsional)</span></label>
            <textarea wire:model="keterangan" rows="3" placeholder="Catatan tambahan…"
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
  function dataBarangManager() {
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
            customClass: {
              popup: 'rounded-2xl',
              confirmButton: 'rounded-xl text-sm font-bold px-5 py-2.5'
            },
            toast: false,
            position: 'center',
            showConfirmButton: true,
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
