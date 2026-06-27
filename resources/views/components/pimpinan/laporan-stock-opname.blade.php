<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Monitoring Stock Opname</h1>
            <p class="text-sm text-gray-400 mt-0.5">Pantau data pengecekan stok fisik vs sistem</p>
          </div>
        </div>
        <a href="{{ route('laporan.opname.pdf', ['tanggal_awal' => $tanggalAwal ?: now()->startOfMonth()->format('Y-m-d'), 'tanggal_akhir' => $tanggalAkhir ?: now()->format('Y-m-d')]) }}"
          target="_blank"
          class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          PDF
        </a>
      </div>
    </div>
  </div>

  {{-- TOTAL --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="bg-purple-50 border border-purple-200 rounded-2xl px-6 py-4 flex items-center justify-between">
      <p class="text-sm font-semibold text-purple-700">Total Data Opname</p>
      <p class="text-2xl font-extrabold text-purple-700">{{ number_format($totalData) }}</p>
    </div>
    <div class="bg-purple-50 border border-purple-200 rounded-2xl px-6 py-4 flex items-center justify-between">
      <p class="text-sm font-semibold text-purple-700">Total Selisih</p>
      <p class="text-2xl font-extrabold {{ $totalSelisih >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
        {{ number_format($totalSelisih) }}
      </p>
    </div>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Cari Barang</label>
          <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau kode barang..."
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-medium bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition outline-none">
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Awal</label>
          <input type="date" wire:model.live="tanggalAwal"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition outline-none cursor-pointer">
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Akhir</label>
          <input type="date" wire:model.live="tanggalAkhir"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition outline-none cursor-pointer">
        </div>
      </div>
    </div>
    @if ($search || $tanggalAwal || $tanggalAkhir)
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
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Barang</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok
                Sistem</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok
                Fisik</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Selisih</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Input
                Oleh</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($stockOpnames as $item)
            <tr class="hover:bg-purple-50/30 transition">
              <td class="px-5 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                {{ $item->tanggal_opname->translatedFormat('d/m/Y') }}
              </td>
              <td class="px-5 py-4">
                <p class="text-sm font-bold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</p>
                <p class="text-xs text-gray-400 font-mono">{{ $item->barang->kode_barang ?? '-' }}</p>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ number_format($item->stok_sistem) }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ number_format($item->stok_fisik) }}</td>
              <td class="px-5 py-4">
                <span
                  class="text-sm font-bold {{ $item->selisih >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ number_format($item->selisih) }}</span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $item->user->nama ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-500">{{ $item->keterangan ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Tidak Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada data stock opname.</p>
                  </div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($stockOpnames->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $stockOpnames->links() }}</div>
    @endif
  </div>
</div>