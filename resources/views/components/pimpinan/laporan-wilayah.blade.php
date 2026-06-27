<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Monitoring Wilayah</h1>
            <p class="text-sm text-gray-400 mt-0.5">Pantau data wilayah distribusi dan jumlah toko</p>
          </div>
        </div>
        <a href="{{ route('laporan.wilayah.pdf') }}" target="_blank"
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
      <p class="text-sm font-semibold text-purple-700">Total Wilayah</p>
      <p class="text-2xl font-extrabold text-purple-700">{{ number_format($totalWilayah) }}</p>
    </div>
    <div class="bg-purple-50 border border-purple-200 rounded-2xl px-6 py-4 flex items-center justify-between">
      <p class="text-sm font-semibold text-purple-700">Total Toko</p>
      <p class="text-2xl font-extrabold text-purple-700">{{ number_format($totalToko) }}</p>
    </div>
  </div>

  {{-- SEARCH --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div
        class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-purple-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-purple-100 transition">
        <div class="pl-3.5 shrink-0 text-gray-400">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama wilayah…"
          class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
      </div>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[600px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left w-12"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">#</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama
                Wilayah</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah Toko</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sales
                PJ</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($wilayahs as $index => $wilayah)
            <tr class="hover:bg-purple-50/30 transition">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $wilayahs->firstItem() + $index }}</td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $wilayah->nama_wilayah }}</td>
              <td class="px-5 py-4">
                <span class="text-sm font-bold text-gray-700">{{ number_format($wilayah->jumlah_toko) }}</span>
                <span class="text-xs text-gray-400 ml-1">toko</span>
              </td>
              <td class="px-5 py-4">
                <p class="text-sm text-gray-700">{{ $wilayah->sales->nama ?? '-' }}</p>
                @if ($wilayah->sales->username ?? false)
                  <p class="text-xs text-gray-400">{{ $wilayah->sales->username }}</p>
                @endif
              </td>
              <td class="px-5 py-4 text-sm text-gray-500 max-w-[200px] truncate">{{ $wilayah->keterangan ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Tidak Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada data wilayah.</p>
                  </div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($wilayahs->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $wilayahs->links() }}</div>
    @endif
  </div>
</div>