<div x-data="barangKeluarManager()" x-init="init()" class="space-y-5">

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
                d="M17 16V4m0 0l4 4m-4-4l-4 4M7 16v4m0 0l-4-4m4 4l4-4" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Barang Keluar</h1>
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
              <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                $pdfParams['tanggal_awal'] = Carbon\Carbon::parse($filterDate)->startOfMonth()->format('Y-m-d');
                $pdfParams['tanggal_akhir'] = Carbon\Carbon::parse($filterDate)->endOfMonth()->format('Y-m-d');
            } elseif ($filterType === 'custom' && $filterDate) {
                $pdfParams['tanggal_awal'] = $filterDate;
                $pdfParams['tanggal_akhir'] = $filterDate;
            } else {
                $pdfParams['tanggal_awal'] = now()->startOfMonth()->format('Y-m-d');
                $pdfParams['tanggal_akhir'] = now()->format('Y-m-d');
            }
            if ($filterWilayah) {
                $pdfParams['id_wilayah'] = $filterWilayah;
            }
          @endphp

          <a href="{{ route('laporan.keluar.pdf', $pdfParams) }}" target="_blank"
            class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm {{ !$barangKeluar->count() ? 'opacity-50 pointer-events-none' : '' }}">
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
            Tambah Barang Keluar
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
          <input type="text" wire:model.live.debounce.300ms="search"
            placeholder="Cari barang, wilayah, atau sales…"
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
            @if ($filterWilayah || $filterDate)
              <span class="w-2 h-2 bg-orange-400 rounded-full"></span>
            @endif
          </button>

          <div x-show="showFilter" x-cloak x-transition
            class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-[0_20px_80px_rgba(0,0,0,0.3)] border border-gray-200 p-6 z-[9999] max-h-[450px] overflow-y-auto">
            <p class="text-sm font-black text-gray-900 mb-5 flex items-center gap-2">
              <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              Filter Data
            </p>
            <div class="space-y-5">
              <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Wilayah</label>
                <select wire:model.live="filterWilayah"
                  class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
                  <option value="">— Semua Wilayah —</option>
                  @foreach ($wilayahs as $w)
                    <option value="{{ $w->id_wilayah }}">{{ $w->nama_wilayah }}</option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal</label>
                <input type="date" wire:model.live="filterDate" wire:change="$set('filterType', 'custom')"
                  class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
              </div>
              <div class="flex gap-3 pt-3 border-t border-gray-100">
                <button wire:click="resetFilters" @click="showFilter = false"
                  class="flex-1 px-4 py-2.5 text-sm font-bold bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition">Reset</button>
                <button @click="showFilter = false"
                  class="flex-1 px-4 py-2.5 text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition shadow-lg shadow-blue-500/20">Terapkan</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="px-4 sm:px-5 py-3 border-t border-gray-100 bg-gray-50/50 flex items-center gap-2 flex-wrap">
      <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mr-1">Cepat:</span>
      <button wire:click="setFilter('today')"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-blue-300 hover:text-blue-600 text-xs font-semibold text-gray-600 transition {{ $filterType === 'today' ? 'bg-blue-50 border-blue-300 text-blue-600' : '' }}">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Hari Ini
      </button>
      <button wire:click="setFilter('week')"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-blue-300 hover:text-blue-600 text-xs font-semibold text-gray-600 transition {{ $filterType === 'week' ? 'bg-blue-50 border-blue-300 text-blue-600' : '' }}">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        7 Hari
      </button>
      <button wire:click="setFilter('month')"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:border-blue-300 hover:text-blue-600 text-xs font-semibold text-gray-600 transition {{ $filterType === 'month' ? 'bg-blue-50 border-blue-300 text-blue-600' : '' }}">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        Bulan Ini
      </button>
      @if ($filterDate || $filterWilayah || $search)
        <button wire:click="resetFilters"
          class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 hover:bg-red-100 text-xs font-semibold text-red-600 transition ml-1">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Reset
        </button>
      @endif
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[1100px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left w-12"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">#</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Barang</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Wilayah</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sales</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Order</span></th>
            <th class="px-5 py-4 text-center w-32"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($barangKeluar as $index => $item)
            <tr class="hover:bg-blue-50/30 transition table-row-animate">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $barangKeluar->firstItem() + $index }}</td>
              <td class="px-5 py-4 whitespace-nowrap">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-orange-100 flex flex-col items-center justify-center shrink-0">
                    <span
                      class="text-[9px] font-semibold text-orange-500 uppercase">{{ \Carbon\Carbon::parse($item->tanggal_keluar)->translatedFormat('M') }}</span>
                    <span
                      class="text-sm font-bold text-orange-700">{{ \Carbon\Carbon::parse($item->tanggal_keluar)->translatedFormat('d') }}</span>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">
                      {{ \Carbon\Carbon::parse($item->tanggal_keluar)->translatedFormat('d M Y') }}</p>
                    <p class="text-xs text-gray-400">
                      {{ \Carbon\Carbon::parse($item->tanggal_keluar)->diffForHumans() }}</p>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4">
                <p class="text-sm font-bold text-gray-900">{{ $item->barang->nama_barang ?? '—' }}</p>
                <p class="text-xs text-gray-400 font-mono">{{ $item->barang->kode_barang ?? '—' }}</p>
              </td>
              <td class="px-5 py-4 whitespace-nowrap">
                <div class="flex items-center gap-1">
                  <span class="text-base font-bold text-gray-900">{{ number_format($item->jumlah) }}</span>
                  <span class="text-xs text-gray-400">unit</span>
                </div>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0">
                    <span
                      class="text-xs font-bold text-indigo-600 uppercase">{{ substr($item->wilayah->nama_wilayah ?? '—', 0, 2) }}</span>
                  </div>
                  <span class="text-sm font-medium text-gray-700">{{ $item->wilayah->nama_wilayah ?? '—' }}</span>
                </div>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <span class="text-sm font-semibold text-gray-700">
                    {{ $item->order->sales->nama_sales ?? '—' }}
                  </span>
                </div>
              </td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-violet-50 text-violet-700 border border-violet-100">
                  #{{ $item->id_order ?? '—' }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center justify-center gap-0.5">
                  <button @click="confirmDelete({{ $item->id_keluar }})"
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
                        d="M17 16V4m0 0l4 4m-4-4l-4 4M7 16v4m0 0l-4-4m4 4l4-4" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Belum Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada barang keluar yang tercatat.</p>
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
    @if ($barangKeluar->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $barangKeluar->links() }}</div>
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
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
              </div>
              <div class="text-white">
                <h2 class="text-lg font-bold">Tambah Barang Keluar</h2>
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
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Order Sales <span
                class="text-red-500">*</span></label>
            <select wire:model.live="id_order"
              class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none cursor-pointer">
              <option value="">— Pilih Order —</option>
              @foreach ($orders as $order)
                <option value="{{ $order->id_order }}">
                  #{{ $order->id_order }} — {{ $order->barang->nama_barang ?? '' }} ({{ $order->jumlah }} unit) —
                  {{ $order->wilayah->nama_wilayah ?? '' }}
                </option>
              @endforeach
            </select>
            @error('id_order')
              <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
          </div>

          @if ($id_order)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 space-y-2">
              <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Detail Order</p>
              <p class="text-sm"><span class="text-gray-500">Barang:</span> <span
                  class="font-semibold">{{ $nama_barang_display }}</span></p>
              <p class="text-sm"><span class="text-gray-500">Jumlah:</span> <span
                  class="font-semibold">{{ number_format($jumlah) }} {{ $satuan_display }}</span></p>
              <p class="text-sm"><span class="text-gray-500">Sales:</span> <span
                  class="font-semibold text-emerald-700">{{ $nama_sales_display }}</span></p>
              <p class="text-sm"><span class="text-gray-500">Status:</span> <span
                  class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">{{ $order_status_display }}</span>
              </p>
            </div>
          @endif

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-bold text-gray-900 mb-1.5">Tanggal Keluar <span
                  class="text-red-500">*</span></label>
              <input type="date" wire:model="tanggal_keluar"
                class="w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-900 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition outline-none">
              @error('tanggal_keluar')
                <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
              @enderror
            </div>
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
            <button @click="$wire.simpan()"
              class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-lg shadow-blue-600/25 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              Simpan Data
            </button>
          </div>
        </div>
      </div>
    </div>
  </template>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function barangKeluarManager() {
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
          text: 'Stok akan dikembalikan ke gudang.',
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
    display: none !important;
  }

  .table-row-animate {
    animation: rowFadeIn 0.25s ease-out both;
  }

  @keyframes rowFadeIn {
    from {
      opacity: 0;
      transform: translateY(6px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
