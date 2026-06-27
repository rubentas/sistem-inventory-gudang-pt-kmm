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
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Monitoring Omzet</h1>
            <p class="text-sm text-gray-400 mt-0.5">Pantau data total penjualan per periode</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('laporan.omzet.pdf') }}" target="_blank"
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

  {{-- STATS --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden group hover:shadow-md transition">
      <div class="h-0.5 bg-blue-500"></div>
      <div class="p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Omzet</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1">Rp {{ number_format($omzet, 0, ',', '.') }}</p>
          </div>
          <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden group hover:shadow-md transition">
      <div class="h-0.5 bg-emerald-500"></div>
      <div class="p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Order</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($totalOrder) }}</p>
          </div>
          <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden group hover:shadow-md transition">
      <div class="h-0.5 bg-amber-500"></div>
      <div class="p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Barang Terjual</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ number_format($totalTerjual) }} <span
                class="text-sm font-medium">unit</span></p>
          </div>
          <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
      <h2 class="text-sm font-extrabold text-gray-900">Detail Periode</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full min-w-[400px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Periode</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Omzet</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Order</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Terjual</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr class="hover:bg-purple-50/30 transition">
            <td class="px-5 py-4 text-sm font-bold text-gray-900">
              {{ \Carbon\Carbon::createFromDate((int) $tahun, (int) $bulan, 1)->translatedFormat('F Y') }}
            </td>
            <td class="px-5 py-4 text-sm font-bold text-blue-600">Rp {{ number_format($omzet, 0, ',', '.') }}</td>
            <td class="px-5 py-4 text-sm text-gray-700">{{ number_format($totalOrder) }}</td>
            <td class="px-5 py-4 text-sm text-gray-700">{{ number_format($totalTerjual) }} unit</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>