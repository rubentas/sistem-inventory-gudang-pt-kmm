<div class="space-y-5">

  {{-- TOAST NOTIFICATION --}}
  <div x-data="{ show: false, message: '' }" x-show="show" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-x-8 scale-95"
    x-transition:enter-end="opacity-100 translate-x-0 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
    x-transition:leave-end="opacity-0 translate-x-8 scale-95" class="fixed bottom-5 right-5 z-200">
    <div
      class="flex items-start gap-3 w-80 bg-white rounded-2xl shadow-[0_8px_32px_rgba(0,0,0,0.12)] border border-gray-100 px-4 py-3.5 border-l-[3px] border-emerald-500">
      <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-bold text-gray-900">Berhasil</p>
        <p class="text-xs text-gray-500 mt-0.5" x-text="message"></p>
      </div>
      <button @click="show = false" class="text-gray-300 hover:text-gray-500 transition shrink-0">
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
    {{-- Barang Masuk --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 16V4m0 0L3 8m4-4l4 4m6 12v-4m0 0l4 4m-4-4l-4 4" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Barang Masuk</p>
          <p class="text-2xl font-extrabold text-gray-900">{{ number_format($totalMasukHariIni) }}</p>
        </div>
      </div>
      <div class="mt-4">
        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">Hari Ini</span>
      </div>
    </div>

    {{-- Barang Keluar --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16V4m0 0l4 4m-4-4l-4 4M7 16v4m0 0l-4-4m4 4l4-4" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Barang Keluar</p>
          <p class="text-2xl font-extrabold text-gray-900">{{ number_format($totalKeluarHariIni) }}</p>
        </div>
      </div>
      <div class="mt-4">
        <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-lg">Hari Ini</span>
      </div>
    </div>

    {{-- Order Sales --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Order Sales</p>
          <p class="text-2xl font-extrabold text-gray-900">{{ number_format($totalOrderHariIni) }}</p>
        </div>
      </div>
      <div class="mt-4">
        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Hari Ini</span>
      </div>
    </div>

    {{-- Total Supplier --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Supplier</p>
          <p class="text-2xl font-extrabold text-gray-900">{{ number_format($totalSupplier) }}</p>
        </div>
      </div>
      <div class="mt-4">
        <span class="text-xs font-semibold text-violet-600 bg-violet-50 px-2.5 py-1 rounded-lg">Terdaftar</span>
      </div>
    </div>
  </div>

  {{-- TABLES GRID --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    {{-- Order Sales Terbaru --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <h2 class="text-sm font-extrabold text-gray-900">Order Sales Terbaru</h2>
        </div>
        <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-3 py-1 rounded-lg">5 terbaru</span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[500px]">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Barang</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            @forelse($orderTerbaru as $order)
              <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3.5 text-sm font-medium text-gray-700 whitespace-nowrap">
                  {{ $order->tanggal_order->translatedFormat('d/m/Y') }}</td>
                <td class="px-5 py-3.5 text-sm font-semibold text-gray-900 whitespace-nowrap">
                  {{ $order->barang->nama_barang ?? '-' }}</td>
                <td class="px-5 py-3.5 whitespace-nowrap">
                  <span class="text-sm font-bold text-gray-700">{{ number_format($order->jumlah) }}</span>
                  <span class="text-xs text-gray-400 ml-1">unit</span>
                </td>
                <td class="px-5 py-3.5 whitespace-nowrap">
                  @if ($order->status == 'pending')
                    <span
                      class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg text-xs font-semibold">Pending</span>
                  @elseif($order->status == 'diproses')
                    <span
                      class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-xs font-semibold">Diproses</span>
                  @else
                    <span
                      class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold">Selesai</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-6 py-16">
                  <div class="flex flex-col items-center text-center gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center">
                      <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                      </svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-400">Belum ada order</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Barang Masuk Terbaru --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
      <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
          </div>
          <h2 class="text-sm font-extrabold text-gray-900">Barang Masuk Terbaru</h2>
        </div>
        <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-3 py-1 rounded-lg">5 terbaru</span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full min-w-[500px]">
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
                <td class="px-5 py-3.5 text-sm font-semibold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}
                </td>
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
  </div>
</div>
