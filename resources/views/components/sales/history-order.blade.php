<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Riwayat Order</h1>
          <p class="text-sm text-gray-400 mt-0.5">Semua riwayat pesanan Anda</p>
        </div>
      </div>
    </div>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5 flex gap-3">
      <div
        class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-orange-400 transition">
        <div class="pl-3.5 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg></div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang..."
          class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none text-gray-900">
      </div>
      <select wire:model.live="filterStatus"
        class="h-11 px-4 border-2 border-gray-200 rounded-xl text-sm font-semibold cursor-pointer">
        <option value="">Semua Status</option>
        <option value="pending">Pending</option>
        <option value="diproses">Diproses</option>
        <option value="selesai">Selesai</option>
      </select>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[700px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Tanggal</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Barang</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Wilayah</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Jumlah</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Total</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($orders as $order)
            <tr class="hover:bg-gray-50">
              <td class="px-5 py-4 text-sm">{{ $order->tanggal_order->translatedFormat('d/m/Y') }}</td>
              <td class="px-5 py-4 text-sm font-semibold">{{ $order->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-500">{{ $order->wilayah->nama_wilayah ?? '-' }}</td>
              <td class="px-5 py-4 text-sm">{{ number_format($order->jumlah) }} {{ $order->barang->satuan ?? 'pcs' }}
              </td>
              <td class="px-5 py-4 text-sm font-bold text-blue-600">Rp
                {{ number_format($order->subtotal, 0, ',', '.') }}</td>
              <td class="px-5 py-4">
                @if ($order->status == 'pending')
                  <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-xs font-semibold rounded-lg">Pending</span>
                @elseif($order->status == 'diproses')
                  <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg">Diproses</span>
                @else
                  <span
                    class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg">Selesai</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-20 text-center text-gray-400">Belum ada riwayat order</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($orders->hasPages())
      <div class="px-5 py-3">{{ $orders->links() }}</div>
    @endif
  </div>

</div>
