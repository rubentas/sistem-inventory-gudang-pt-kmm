<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Monitoring Inventory</h1>
            <p class="text-sm text-gray-400 mt-0.5">Pantau data pergerakan stok barang dalam periode tertentu</p>
          </div>
        </div>
        <a href="{{ route('laporan.inventory.pdf', ['tanggal_awal' => $tanggalAwal ?: now()->startOfMonth()->format('Y-m-d'), 'tanggal_akhir' => $tanggalAkhir ?: now()->format('Y-m-d')]) }}"
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

  {{-- STATS --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden group hover:shadow-md transition">
      <div class="h-0.5 bg-blue-500"></div>
      <div class="p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Barang Masuk</p>
        <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ number_format($totalMasukKeseluruhan) }}</p>
        <p class="text-xs text-gray-400 mt-3">Dalam periode</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden group hover:shadow-md transition">
      <div class="h-0.5 bg-red-500"></div>
      <div class="p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Barang Keluar</p>
        <p class="text-2xl font-extrabold text-red-600 mt-1">{{ number_format($totalKeluarKeseluruhan) }}</p>
        <p class="text-xs text-gray-400 mt-3">Dalam periode</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden group hover:shadow-md transition">
      <div class="h-0.5 bg-emerald-500"></div>
      <div class="p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Stok Akhir</p>
        <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($totalStokAkhir) }}</p>
        <p class="text-xs text-gray-400 mt-3">Sisa stok saat ini</p>
      </div>
    </div>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
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
      <p class="text-sm text-gray-500 mt-3">
        Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }} —
        {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}
      </p>
    </div>
    @if ($tanggalAwal || $tanggalAkhir)
      <div class="px-4 sm:px-5 py-3 border-t border-gray-100 bg-gray-50/50">
        <button wire:click="resetFilters"
          class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 hover:bg-red-100 text-xs font-semibold text-red-600 transition">
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Reset Tanggal
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
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama
                Barang</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Satuan</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok
                Awal</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Masuk</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keluar</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok
                Akhir</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($stoks as $index => $stok)
            @php $stokAwal = $stok->jumlah_stok - $stok->total_masuk + $stok->total_keluar; @endphp
            <tr class="hover:bg-purple-50/30 transition">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $index + 1 }}</td>
              <td class="px-5 py-4"><span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-gray-100 text-gray-700 border border-gray-200">{{ $stok->barang->kode_barang ?? '-' }}</span>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $stok->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $stok->barang->satuan ?? 'Pcs' }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ number_format($stokAwal) }}</td>
              <td class="px-5 py-4 text-sm font-bold text-blue-600">{{ number_format($stok->total_masuk) }}</td>
              <td class="px-5 py-4 text-sm font-bold text-red-600">{{ number_format($stok->total_keluar) }}</td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ number_format($stok->jumlah_stok) }}</td>
              <td class="px-5 py-4">
                @if ($stok->status == 'Menipis')
                  <span class="px-2.5 py-1 bg-red-50 text-red-700 border border-red-100 rounded-lg text-xs font-semibold">⚠
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
              <td colspan="9" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Tidak Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada data stok.</p>
                  </div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr class="bg-gray-50 border-t-2 border-gray-200 font-bold">
            <td colspan="4" class="px-5 py-4 text-right text-gray-700">TOTAL:</td>
            <td class="px-5 py-4 text-gray-900">
              {{ number_format($stoks->sum(function ($s) {
  return $s->jumlah_stok - $s->total_masuk + $s->total_keluar; })) }}
            </td>
            <td class="px-5 py-4 text-blue-600">{{ number_format($totalMasukKeseluruhan) }}</td>
            <td class="px-5 py-4 text-red-600">{{ number_format($totalKeluarKeseluruhan) }}</td>
            <td class="px-5 py-4 text-emerald-600">{{ number_format($totalStokAkhir) }}</td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>