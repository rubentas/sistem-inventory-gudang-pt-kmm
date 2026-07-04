<div x-data="barangMasukManager()" x-init="init()" class="space-y-5">
  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Barang Masuk</h1>
            <p class="text-sm text-gray-400 mt-0.5">Inventory Management System</p>
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
              <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
              </div>
              <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Bulan Ini</p>
                <p class="text-xl font-bold text-gray-900">{{ $stats['thisMonth'] }}</p>
              </div>
            </div>
          </div>

          @php
            $pdfParams = [];
            if ($filterType === 'today') {
                $pdfParams['tanggal_awal'] = $filterDate;
                $pdfParams['tanggal_akhir'] = $filterDate;
            } elseif ($filterType === 'week') {
                $pdfParams['tanggal_awal'] = now()->subDays(6)->format('Y-m-d');
                $pdfParams['tanggal_akhir'] = now()->format('Y-m-d');
            } elseif ($filterType === 'month') {
                $pdfParams['tanggal_awal'] = \Carbon\Carbon::parse($filterDate)->startOfMonth()->format('Y-m-d');
                $pdfParams['tanggal_akhir'] = \Carbon\Carbon::parse($filterDate)->endOfMonth()->format('Y-m-d');
            } elseif ($filterType === 'custom' && $filterDate) {
                $pdfParams['tanggal_awal'] = $filterDate;
                $pdfParams['tanggal_akhir'] = $filterDate;
            } else {
                $pdfParams['tanggal_awal'] = now()->startOfMonth()->format('Y-m-d');
                $pdfParams['tanggal_akhir'] = now()->format('Y-m-d');
            }
            if ($filterSupplier) {
                $pdfParams['id_supplier'] = $filterSupplier;
            }
          @endphp

          <a href="{{ route('laporan.masuk.pdf', $pdfParams) }}" target="_blank"
            class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm {{ !$barangMasuk->count() ? 'opacity-50 pointer-events-none' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>PDF
          </a>

          <button wire:click="openAddModal"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_4px_12px_rgba(37,99,235,0.25)]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>Tambah Barang
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
          class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-blue-400 transition">
          <div class="pl-3.5 shrink-0 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg></div>
          <input type="text" wire:model.live.debounce.300ms="search"
            placeholder="Cari barang, supplier, atau nomor nota…"
            class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
        </div>
        <div class="relative shrink-0" x-data="{ showFilter: false }" @click.outside="showFilter = false">
          <button @click="showFilter = !showFilter"
            :class="showFilter ? 'bg-blue-600 text-white border-blue-600' :
                'bg-white text-gray-700 border-gray-200 hover:border-gray-300'"
            class="h-11 px-5 border rounded-xl text-sm font-semibold transition flex items-center gap-2 w-full sm:w-auto justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <span x-text="showFilter ? 'Tutup' : 'Filter'"></span>
            @if ($filterSupplier || $filterDate)
              <span class="w-2 h-2 bg-orange-400 rounded-full"></span>
            @endif
          </button>
          <div x-show="showFilter" x-cloak x-transition
            class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-lg border border-gray-200 p-6 z-[9999]">
            <p class="text-sm font-black text-gray-900 mb-5">Filter Data</p>
            <div class="space-y-5">
              <div><label class="block text-xs font-bold text-gray-700 uppercase mb-2">Supplier</label><select
                  wire:model.live="filterSupplier"
                  class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-blue-500 transition outline-none cursor-pointer">
                  <option value="">— Semua Supplier —</option>
                  @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id_supplier }}">{{ $supplier->nama_supplier }}</option>
                  @endforeach
                </select>
              </div>
              <div><label class="block text-xs font-bold text-gray-700 uppercase mb-2">Tanggal</label><input
                  type="date" wire:model.live="filterDate"
                  class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-blue-500 transition outline-none cursor-pointer">
              </div>
              <div class="flex gap-3 pt-3 border-t border-gray-100"><button wire:click="resetFilters"
                  @click="showFilter = false"
                  class="flex-1 px-4 py-2.5 text-sm font-bold bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition">Reset</button><button
                  @click="showFilter = false"
                  class="flex-1 px-4 py-2.5 text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition shadow-lg">Terapkan</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="px-4 sm:px-5 py-3 border-t border-gray-100 bg-gray-50/50 flex items-center gap-2 flex-wrap">
      <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mr-1">Cepat:</span>
      <button wire:click="setFilter('today')"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-blue-300 hover:text-blue-600 text-xs font-semibold text-gray-600 transition {{ $filterType === 'today' ? 'bg-blue-50 border-blue-300 text-blue-600' : '' }}">Hari
        Ini</button>
      <button wire:click="setFilter('week')"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-blue-300 hover:text-blue-600 text-xs font-semibold text-gray-600 transition {{ $filterType === 'week' ? 'bg-blue-50 border-blue-300 text-blue-600' : '' }}">7
        Hari</button>
      <button wire:click="setFilter('month')"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-blue-300 hover:text-blue-600 text-xs font-semibold text-gray-600 transition {{ $filterType === 'month' ? 'bg-blue-50 border-blue-300 text-blue-600' : '' }}">Bulan
        Ini</button>
      @if ($filterDate || $filterSupplier || $search)
        <button wire:click="resetFilters"
          class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 hover:bg-red-100 text-xs font-semibold text-red-600 transition ml-1">Reset</button>
      @endif
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left w-12 text-xs font-bold text-gray-400 uppercase">#</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Tanggal</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">No. Nota</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Barang</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Jumlah</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Supplier</th>
            <th class="px-5 py-4 text-center w-32 text-xs font-bold text-gray-400 uppercase">Aksi</th>
          </tr>
        </thead>
        @forelse($barangMasuk as $index => $item)
          <tbody x-data="{ expanded: false }" class="table-row-animate">
            <tr class="data-row border-b border-gray-50 hover:bg-blue-50/30">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $barangMasuk->firstItem() + $index }}</td>
              <td class="px-5 py-4 whitespace-nowrap">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-blue-100 flex flex-col items-center justify-center shrink-0"><span
                      class="text-[9px] font-semibold text-blue-500 uppercase">{{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('M') }}</span><span
                      class="text-sm font-bold text-blue-700">{{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d') }}</span>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">
                      {{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d M Y') }}</p>
                    <p class="text-xs text-gray-400">
                      {{ \Carbon\Carbon::parse($item->tanggal_masuk)->diffForHumans() }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4 whitespace-nowrap"><span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-violet-50 text-violet-700 border border-violet-100">{{ $item->no_nota }}</span>
              </td>
              <td class="px-5 py-4">
                <p class="text-sm font-bold text-gray-900">{{ $item->barang->nama_barang ?? '—' }}</p>
                <p class="text-xs text-gray-400 font-mono">{{ $item->barang->kode_barang ?? '—' }}</p>
              </td>
              <td class="px-5 py-4 whitespace-nowrap"><span
                  class="text-base font-bold text-gray-900">{{ number_format($item->jumlah) }}</span><span
                  class="text-xs text-gray-400 ml-1">unit</span></td>
              <td class="px-5 py-4">{{ $item->supplier->nama_supplier ?? '—' }}</td>
              <td class="px-5 py-4">
                <div class="flex items-center justify-center gap-0.5">
                  <button @click="expanded = !expanded"
                    :class="expanded ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-blue-600 hover:bg-blue-50'"
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition" title="Detail"><svg
                      class="w-4 h-4 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none"
                      stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg></button>
                  <button wire:click="edit({{ $item->id_masuk }})"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition"
                    title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg></button>
                  <button @click="confirmDelete({{ $item->id_masuk }})"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition"
                    title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg></button>
                </div>
              </td>
            </tr>
            <tr x-show="expanded" x-cloak class="border-b border-gray-100">
              <td colspan="7" class="px-5 pb-4 pt-1">
                <div class="rounded-xl bg-gradient-to-br from-gray-50 to-blue-50/30 border border-gray-200 p-4">
                  <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Detail Transaksi</p>
                  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div><span class="text-xs text-gray-400 block">Surat Jalan</span><span
                        class="text-sm font-bold text-gray-800 font-mono">{{ $item->no_surat_jalan ?? '—' }}</span>
                    </div>
                    <div><span class="text-xs text-gray-400 block">Sumber</span><span
                        class="text-sm font-bold text-gray-800">{{ $item->sumber ?? '—' }}</span></div>
                    <div><span class="text-xs text-gray-400 block">ID Transaksi</span><span
                        class="text-sm font-bold text-gray-800 font-mono">#{{ $item->id_masuk }}</span></div>
                    <div><span class="text-xs text-gray-400 block">Dicatat</span><span
                        class="text-sm font-semibold text-gray-600">{{ $item->created_at?->translatedFormat('d M Y, H:i') ?? '—' }}</span>
                    </div>
                  </div>
                  @if ($item->no_invoice)
                    <div class="mt-3 pt-3 border-t border-gray-200 flex items-center gap-4"><span
                        class="text-xs text-gray-400">Invoice:</span><span
                        class="text-sm font-mono font-bold text-blue-600">{{ $item->no_invoice }}</span>
                      @if ($item->bukti_pembayaran)
                        <a href="{{ Storage::url($item->bukti_pembayaran) }}" target="_blank"
                          class="text-xs text-blue-600 hover:underline">Lihat Bukti</a>
                      @endif
                    </div>
                  @endif
                  @if ($item->keterangan)
                    <div class="mt-3 pt-3 border-t border-gray-200"><span
                        class="text-xs text-gray-400 block mb-1">Keterangan</span>
                      <p class="text-sm text-gray-700">{{ $item->keterangan }}</p>
                    </div>
                  @endif
                </div>
              </td>
            </tr>
          </tbody>
        @empty
          <tbody>
            <tr>
              <td colspan="7" class="px-6 py-20 text-center text-gray-400">Belum ada barang masuk.</td>
            </tr>
          </tbody>
        @endforelse
      </table>
    </div>
    @if ($barangMasuk->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $barangMasuk->links() }}</div>
    @endif
  </div>

  {{-- MODAL --}}
  <template x-teleport="body">
    <div x-show="modalOpen" x-cloak x-transition class="fixed inset-0 z-[100] flex items-center justify-center p-4"
      @keydown.escape.window="modalOpen = false">
      <div @click="modalOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-md z-40"></div>
      <div @click.stop class="relative z-50 w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-blue-600 px-6 py-5 flex items-center justify-between">
          <div class="flex items-center gap-3 text-white">
            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"><svg x-show="!isEdit"
                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
              </svg><svg x-show="isEdit" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg></div>
            <div>
              <h2 class="text-lg font-bold" x-text="isEdit ? 'Edit Barang Masuk' : 'Tambah Barang Masuk'"></h2>
              <p class="text-blue-100 text-xs">Lengkapi field wajib <span class="text-white">*</span></p>
            </div>
          </div>
          <button @click="modalOpen = false"
            class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition"><svg
              class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg></button>
        </div>
        <div class="px-6 py-5 space-y-4 overflow-y-auto" style="max-height: calc(100vh - 250px);">
          <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Barang <span
                class="text-red-500">*</span></label><select wire:model.live="id_barang"
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-blue-500 transition outline-none">
              <option value="">— Pilih Barang —</option>
              @foreach ($barangs as $barang)
                <option value="{{ $barang->id_barang }}">{{ $barang->kode_barang }} – {{ $barang->nama_barang }}
                </option>
              @endforeach
            </select>
            @error('id_barang')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Satuan</label><input type="text"
                value="{{ $satuan_display }}" readonly disabled
                class="w-full rounded-xl border-2 border-gray-200 bg-gray-100 px-4 py-3 text-sm font-medium text-gray-500 outline-none">
            </div>
            <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Stok Tersedia</label>
              @if ($id_barang)
                @php $stok = \App\Models\Stok::where('id_barang', $id_barang)->first(); @endphp
                <input type="text"
                  value="{{ $stok ? number_format($stok->jumlah_stok) . ' ' . $satuan_display : '0 ' . $satuan_display }}"
                  readonly disabled
                class="w-full rounded-xl border-2 {{ $stok && $stok->jumlah_stok <= $stok->stok_minimum ? 'border-red-200 bg-red-50 text-red-600' : 'border-emerald-200 bg-emerald-50 text-emerald-600' }} px-4 py-3 text-sm font-bold outline-none">@else<input
                  type="text" value="Pilih barang dulu" readonly disabled
                  class="w-full rounded-xl border-2 border-gray-200 bg-gray-100 px-4 py-3 text-sm font-medium text-gray-400 outline-none">
              @endif
            </div>
          </div>
          <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Sumber <span
                class="text-red-500">*</span></label><select wire:model.live="sumber"
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-blue-500 transition outline-none">
              <option value="">— Pilih Sumber —</option>
              @foreach ($sumberList as $s)
                <option value="{{ $s }}">{{ $s }}</option>
              @endforeach
            </select>
          </div>
          <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Supplier @if ($sumber !== 'Supplier')
              <span class="text-gray-400 font-normal">(otomatis)</span>@else<span class="text-red-500">*</span>
              @endif
            </label>
            <select wire:model="id_supplier"
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-blue-500 transition outline-none cursor-pointer"
              {{ $sumber !== 'Supplier' ? 'disabled' : '' }}>
              <option value="">— Pilih Supplier —</option>
              @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id_supplier }}">{{ $supplier->kode_supplier }} –
                  {{ $supplier->nama_supplier }}</option>
              @endforeach
            </select>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Nomor Nota <span
                  class="text-red-500">*</span></label><input type="text" wire:model="no_nota"
                placeholder="NOT-2025-001"
                class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-blue-500 transition outline-none">
            </div>
            <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Surat Jalan</label><input type="text"
                wire:model="no_surat_jalan" placeholder="SJ-2025-001"
                class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-blue-500 transition outline-none">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Jumlah <span
                  class="text-red-500">*</span></label><input type="number" wire:model="jumlah" placeholder="0"
                min="1"
                class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-blue-500 transition outline-none">
            </div>
            <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Tanggal <span
                  class="text-red-500">*</span></label><input type="date" wire:model="tanggal_masuk"
                class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-blue-500 transition outline-none">
            </div>
          </div>
          <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Bukti Pembayaran <span
                class="text-red-500">*</span></label><input type="file" wire:model="bukti_pembayaran"
              accept="image/*,.pdf"
              class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
            @error('bukti_pembayaran')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>
          <div><label class="block text-sm font-bold text-gray-900 mb-1.5">Keterangan <span
                class="text-gray-400 font-normal">(opsional)</span></label>
            <textarea wire:model="keterangan" rows="3" placeholder="Catatan tambahan…"
              class="w-full rounded-xl border-2 border-gray-300 px-4 py-3 text-sm font-medium focus:border-blue-500 transition outline-none resize-none"></textarea>
          </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-3">
          <p class="text-xs text-gray-400"><span class="text-red-500">*</span> Wajib diisi</p>
          <div class="flex items-center gap-2">
            <button @click="modalOpen = false"
              class="px-5 py-2.5 rounded-xl bg-white border-2 border-gray-200 hover:bg-gray-50 text-sm font-bold text-gray-700 transition">Batal</button>
            <button @click="$wire.isEdit ? $wire.update() : $wire.simpan()"
              class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-lg flex items-center gap-2"><svg
                class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg><span x-text="$wire.isEdit ? 'Simpan Perubahan' : 'Simpan Data'"></span></button>
          </div>
        </div>
      </div>
    </div>
  </template>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function barangMasukManager() {
    return {
      modalOpen: false,
      isEdit: false,
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
          this.isEdit = false;
          this.modalOpen = true;
        });
        window.addEventListener('openEditModal', () => {
          this.isEdit = true;
          this.modalOpen = true;
        });
      },
      openAddModal() {
        this.isEdit = false;
        this.modalOpen = true;
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
<style>
  [x-cloak] {
    display: none !important
  }

  .table-row-animate {
    animation: rowFadeIn 0.25s ease-out both
  }

  @keyframes rowFadeIn {
    from {
      opacity: 0;
      transform: translateY(6px)
    }

    to {
      opacity: 1;
      transform: translateY(0)
    }
  }
</style>
