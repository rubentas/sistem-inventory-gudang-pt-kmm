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
        <div class="flex items-center gap-3">
          <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 text-center">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Hari Ini</p>
            <p class="text-lg font-bold text-gray-700 mt-0.5">{{ now()->translatedFormat('d F Y') }}</p>
          </div>
          <a href="{{ route('sales.order-sales') }}"
            class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition shadow-[0_4px_12px_rgba(234,88,12,0.25)]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Buat Order
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- STATS --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Order</p>
          <p class="text-2xl font-extrabold text-gray-900">{{ $stats['total'] }}</p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">Semua order Anda</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pending</p>
          <p class="text-2xl font-extrabold text-amber-600">{{ $stats['pending'] }}</p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">Menunggu diproses</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Diproses</p>
          <p class="text-2xl font-extrabold text-blue-600">{{ $stats['diproses'] }}</p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">Sedang dikerjakan</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 hover:shadow-md transition">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Selesai</p>
          <p class="text-2xl font-extrabold text-emerald-600">{{ $stats['selesai'] }}</p>
        </div>
      </div>
      <p class="text-xs text-gray-400 mt-3">Order selesai</p>
    </div>
  </div>

  {{-- ORDER TERBARU --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-orange-100 flex items-center justify-center">
          <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
        </div>
        <h2 class="text-sm font-extrabold text-gray-900">Order Terbaru</h2>
      </div>
      <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-3 py-1 rounded-lg">5 terbaru</span>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full min-w-[500px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Barang</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Wilayah</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($orderTerbaru as $order)
            <tr class="hover:bg-orange-50/30 transition">
              <td class="px-5 py-3.5 text-sm font-medium text-gray-700 whitespace-nowrap">
                {{ $order->tanggal_order->translatedFormat('d/m/Y') }}</td>
              <td class="px-5 py-3.5 text-sm font-bold text-gray-900">{{ $order->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-3.5 text-sm text-gray-600">{{ $order->wilayah->nama_wilayah ?? '-' }}</td>
              <td class="px-5 py-3.5">
                <span class="text-sm font-bold text-gray-700">{{ number_format($order->jumlah) }}</span>
                <span class="text-xs text-gray-400 ml-1">{{ $order->barang->satuan ?? 'pcs' }}</span>
              </td>
              <td class="px-5 py-3.5">
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
              <td colspan="5" class="px-6 py-16">
                <div class="flex flex-col items-center text-center gap-3">
                  <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                  </div>
                  <p class="text-sm font-semibold text-gray-400">Belum ada order</p>
                  <a href="{{ route('sales.order-sales') }}"
                    class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition">
                    Buat Order Pertama
                  </a>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
