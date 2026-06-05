<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Dashboard</h1>
            <p class="text-sm text-gray-400 mt-0.5">Selamat datang, {{ auth()->user()->nama }}</p>
          </div>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 text-center">
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Hari Ini</p>
          <p class="text-lg font-bold text-gray-700 mt-0.5">{{ now()->translatedFormat('d F Y') }}</p>
        </div>
      </div>
    </div>
  </div>

  {{-- STATS CARDS --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 16V4m0 0L3 8m4-4l4 4m6 12v-4m0 0l4 4m-4-4l-4 4" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Barang Masuk</p>
          <p class="text-2xl font-extrabold text-gray-900">{{ number_format($totalMasukHariIni) }}</p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">Total barang masuk hari ini</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Jenis Barang</p>
          <p class="text-2xl font-extrabold text-gray-900">{{ number_format($totalJenisBarang) }}</p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">Jumlah SKU aktif</p>
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
          <p class="text-2xl font-extrabold text-red-600">{{ number_format($stokMenipis) }}</p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">Stok &le; Minimum</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Opname Terakhir</p>
          <p class="text-2xl font-extrabold text-gray-900">
            {{ $opnameTerakhir ? $opnameTerakhir->tanggal_opname->translatedFormat('d/m/Y') : '-' }}
          </p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">
        {{ $opnameTerakhir ? $opnameTerakhir->barang->nama_barang ?? '-' : 'Belum ada opname' }}</p>
    </div>
  </div>

  {{-- TABLES --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    {{-- Barang Masuk Terbaru --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
          </div>
          <h2 class="text-sm font-extrabold text-gray-900">Barang Masuk Terbaru</h2>
        </div>
        <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-3 py-1 rounded-lg">5 terbaru</span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[400px]">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Barang</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Sumber</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            @forelse($barangMasukTerbaru as $item)
              <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3.5 text-sm font-medium text-gray-700 whitespace-nowrap">
                  {{ $item->tanggal_masuk->translatedFormat('d/m/Y') }}</td>
                <td class="px-5 py-3.5 text-sm font-semibold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</td>
                <td class="px-5 py-3.5 whitespace-nowrap">
                  <span class="text-sm font-bold text-gray-700">{{ number_format($item->jumlah) }}</span>
                  <span class="text-xs text-gray-400 ml-1">unit</span>
                </td>
                <td class="px-5 py-3.5 whitespace-nowrap">
                  <span
                    class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-200 rounded-lg text-xs font-semibold">{{ $item->sumber }}</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-16">
                  <div class="flex flex-col items-center text-center gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center">
                      <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                      </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-400">Belum ada data</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Stok Menipis --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>
          <h2 class="text-sm font-extrabold text-red-600">Stok Menipis</h2>
        </div>
        <span class="text-xs font-semibold text-red-400 bg-red-50 px-3 py-1 rounded-lg">Perlu Restock</span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[400px]">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kode</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Barang</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Stok</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Min</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            @forelse($stokMenipisList as $stok)
              <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3.5">
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                    {{ $stok->barang->kode_barang ?? '-' }}
                  </span>
                </td>
                <td class="px-5 py-3.5 text-sm font-semibold text-gray-900">{{ $stok->barang->nama_barang ?? '-' }}
                </td>
                <td class="px-5 py-3.5 text-sm font-bold text-red-600">{{ number_format($stok->jumlah_stok) }}</td>
                <td class="px-5 py-3.5 text-sm text-gray-600">{{ number_format($stok->stok_minimum) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-16">
                  <div class="flex flex-col items-center text-center gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center">
                      <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                      </svg>
                    </div>
                    <p class="text-sm font-semibold text-emerald-600">Semua stok aman</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
