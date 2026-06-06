<div class="space-y-5">

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
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Barang Masuk</h1>
            <p class="text-sm text-gray-400 mt-0.5">Laporan lengkap barang masuk dari semua sumber</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <a href="{{ route('laporan.masuk.pdf', ['tanggal_awal' => $tanggalAwal ?: now()->startOfMonth()->format('Y-m-d'), 'tanggal_akhir' => $tanggalAkhir ?: now()->format('Y-m-d')]) }}"
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
  </div>

  {{-- TOTAL --}}
  <div class="bg-purple-50 border border-purple-200 rounded-2xl px-6 py-4 flex items-center justify-between">
    <p class="text-sm font-semibold text-purple-700">Total Jumlah Barang Masuk</p>
    <p class="text-2xl font-extrabold text-purple-700">{{ number_format($totalJumlah) }}</p>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Cari</label>
          <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang, nota..."
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-medium bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition outline-none">
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Sumber</label>
          <select wire:model.live="filterSumber"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition outline-none cursor-pointer">
            <option value="">Semua Sumber</option>
            @foreach ($sumberList as $sumber)
              <option value="{{ $sumber }}">{{ $sumber }}</option>
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
    @if ($search || $filterSumber || $tanggalAwal || $tanggalAkhir)
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
      <table class="w-full min-w-[900px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">No
                Nota</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Surat
                Jalan</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Barang</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Supplier</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sumber</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Input
                Oleh</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($barangMasuk as $item)
            <tr class="hover:bg-purple-50/30 transition">
              <td class="px-5 py-4 text-sm font-medium text-gray-700 whitespace-nowrap">
                {{ $item->tanggal_masuk->translatedFormat('d/m/Y') }}</td>
              <td class="px-5 py-4"><span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-violet-50 text-violet-700 border border-violet-100">{{ $item->no_nota }}</span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600 font-mono">{{ $item->no_surat_jalan }}</td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4"><span
                  class="text-sm font-bold text-gray-700">{{ number_format($item->jumlah) }}</span></td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $item->supplier->nama_supplier ?? '-' }}</td>
              <td class="px-5 py-4"><span
                  class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-100 rounded-lg text-xs font-semibold">{{ $item->sumber }}</span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-500">{{ $item->user->nama ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Tidak Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada barang masuk yang tercatat.</p>
                  </div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($barangMasuk->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $barangMasuk->links() }}</div>
    @endif
  </div>
</div>
