<div class="space-y-5">

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
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Stok Barang</h1>
            <p class="text-sm text-gray-400 mt-0.5">Memantau stok barang secara real-time</p>
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
      </div>
    </div>
  </div>

  {{-- STATS CARDS --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Stok</p>
          <p class="text-2xl font-extrabold text-gray-900">{{ number_format($totalStok) }}</p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">Seluruh barang</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Stok Menipis</p>
          <p class="text-2xl font-extrabold text-red-600">{{ number_format($totalMenipis) }}</p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">Stok &le; Minimum</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Stok Aman</p>
          <p class="text-2xl font-extrabold text-emerald-600">{{ number_format($totalAman) }}</p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">Stok &gt; Minimum</p>
    </div>
  </div>

  {{-- SEARCH & FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div class="flex flex-col sm:flex-row gap-2.5">
        <div
          class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-emerald-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-emerald-100 transition">
          <div class="pl-3.5 shrink-0 text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau kode barang…"
            class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
        </div>
        <select wire:model.live="filterStatus"
          class="h-11 px-4 border-2 border-gray-200 rounded-xl text-sm font-semibold bg-white text-gray-700 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition outline-none cursor-pointer">
          <option value="">Semua Status</option>
          <option value="habis">Stok Habis</option>
          <option value="menipis">Stok Menipis</option>
          <option value="aman">Stok Aman</option>
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
      <table class="w-full min-w-[800px]">
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
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah Stok</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Min</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($stoks as $index => $stok)
            <tr class="hover:bg-emerald-50/30 transition">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $stoks->firstItem() + $index }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                  {{ $stok->barang->kode_barang ?? '-' }}
                </span>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $stok->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4">
                <span
                  class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-200 rounded-lg text-xs font-semibold">{{ $stok->barang->kategori ?? '-' }}</span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $stok->barang->satuan ?? 'Pcs' }}</td>
              <td class="px-5 py-4">
                <span class="text-sm font-bold {{ in_array($stok->status, ['Menipis', 'Habis']) ? 'text-red-600' : 'text-gray-900' }}">
                  {{ number_format($stok->jumlah_stok) }}
                </span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ number_format($stok->stok_minimum) }}</td>
              <td class="px-5 py-4">
                @if ($stok->status == 'Habis')
                  <span
                    class="px-2.5 py-1 bg-gray-50 text-gray-700 border border-gray-100 rounded-lg text-xs font-semibold">❌
                    Habis</span>
                @elseif ($stok->status == 'Menipis')
                  <span
                    class="px-2.5 py-1 bg-red-50 text-red-700 border border-red-100 rounded-lg text-xs font-semibold">⚠
                    Menipis</span>
                @else
                  <span
                    class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold">✓
                    Aman</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Belum Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada data stok barang.</p>
                  </div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($stoks->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $stoks->links() }}</div>
    @endif
  </div>
</div>
