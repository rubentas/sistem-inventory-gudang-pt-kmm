<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Monitoring Barang Terlaris</h1>
            <p class="text-sm text-gray-400 mt-0.5">Pantau data 5 barang dengan penjualan terbanyak per bulan</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div class="flex flex-wrap gap-4 items-end">
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Bulan</label>
          <select wire:model.live="bulan"
            class="h-11 px-4 border-2 border-gray-200 rounded-xl text-sm font-semibold bg-white text-gray-700 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition outline-none cursor-pointer">
            <option value="01">Januari</option>
            <option value="02">Februari</option>
            <option value="03">Maret</option>
            <option value="04">April</option>
            <option value="05">Mei</option>
            <option value="06">Juni</option>
            <option value="07">Juli</option>
            <option value="08">Agustus</option>
            <option value="09">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tahun</label>
          <select wire:model.live="tahun"
            class="h-11 px-4 border-2 border-gray-200 rounded-xl text-sm font-semibold bg-white text-gray-700 focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition outline-none cursor-pointer">
            <option value="2024">2024</option>
            <option value="2025">2025</option>
            <option value="2026">2026</option>
          </select>
        </div>
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
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kode</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama
                Barang</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total
                Terjual</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total
                Omzet</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($terlaris as $index => $item)
            <tr class="hover:bg-purple-50/30 transition">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $index + 1 }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-violet-50 text-violet-700 border border-violet-100">
                  {{ $item->barang->kode_barang ?? '-' }}
                </span>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4">
                <span class="text-sm font-bold text-emerald-600">{{ number_format($item->total_terjual) }}</span>
                <span class="text-xs text-gray-400 ml-1">unit</span>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-blue-600">Rp
                {{ number_format($item->total_omzet, 0, ',', '.') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Belum Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada penjualan untuk periode ini.</p>
                  </div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>