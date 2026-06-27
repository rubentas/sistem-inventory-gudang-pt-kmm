<div class="space-y-5">

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
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Monitoring Barang Keluar</h1>
            <p class="text-sm text-gray-400 mt-0.5">Pantau data barang keluar per wilayah</p>
          </div>
        </div>
        <a href="{{ route('laporan.keluar.pdf', ['tanggal_awal' => $tanggalAwal ?: now()->startOfMonth()->format('Y-m-d'), 'tanggal_akhir' => $tanggalAkhir ?: now()->format('Y-m-d')]) }}"
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
  <div class="bg-purple-50 border border-purple-200 rounded-2xl px-6 py-4 flex items-center justify-between">
    <p class="text-sm font-semibold text-purple-700">Total Jumlah Barang Keluar</p>
    <p class="text-2xl font-extrabold text-purple-700">{{ number_format($totalJumlah) }}</p>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Cari</label>
          <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang atau wilayah..."
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-medium bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition outline-none">
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Wilayah</label>
          <select wire:model.live="filterWilayah"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition outline-none cursor-pointer">
            <option value="">Semua Wilayah</option>
            @foreach ($wilayahList as $wilayah)
              <option value="{{ $wilayah->id_wilayah }}">{{ $wilayah->nama_wilayah }}</option>
            @endforeach
          </select>
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
    @if ($search || $filterWilayah || $tanggalAwal || $tanggalAkhir)
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
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Order
                ID</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Diproses Oleh</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($barangKeluar as $item)
            <tr class="hover:bg-purple-50/30 transition">
              <td class="px-5 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                {{ $item->tanggal_keluar->translatedFormat('d/m/Y') }}
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $item->wilayah->nama_wilayah ?? '-' }}</td>
              <td class="px-5 py-4"><span
                  class="text-sm font-bold text-gray-700">{{ number_format($item->jumlah) }}</span> <span
                  class="text-xs text-gray-400">{{ $item->barang->satuan ?? 'pcs' }}</span></td>
              <td class="px-5 py-4 text-sm font-mono text-gray-500">#{{ $item->id_order }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $item->user->nama ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 16V4m0 0l4 4m-4-4l-4 4M7 16v4m0 0l-4-4m4 4l4-4" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Tidak Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada barang keluar yang tercatat.</p>
                  </div>
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
</div>