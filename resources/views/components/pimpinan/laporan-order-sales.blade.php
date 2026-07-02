<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Monitoring Order Sales</h1>
            <p class="text-sm text-gray-400 mt-0.5">Pantau data pesanan dari para sales</p>
          </div>
        </div>
        <a href="{{ route('laporan.order.pdf', ['tanggal_awal' => $tanggalAwal ?: now()->startOfMonth()->format('Y-m-d'), 'tanggal_akhir' => $tanggalAkhir ?: now()->format('Y-m-d'), 'status' => $filterStatus]) }}"
          target="_blank"
          class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>PDF
        </a>
      </div>
    </div>
  </div>

  {{-- STATS --}}
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl p-5 text-center shadow-sm">
      <p class="text-xs text-gray-400 uppercase font-semibold">Total Order</p>
      <p class="text-2xl font-bold text-purple-600 mt-1">{{ $orders->total() }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 text-center shadow-sm">
      <p class="text-xs text-gray-400 uppercase font-semibold">Pending</p>
      <p class="text-2xl font-bold text-amber-600 mt-1">{{ $totalPending }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 text-center shadow-sm">
      <p class="text-xs text-gray-400 uppercase font-semibold">Diproses</p>
      <p class="text-2xl font-bold text-blue-600 mt-1">{{ $totalDiproses }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 text-center shadow-sm">
      <p class="text-xs text-gray-400 uppercase font-semibold">Selesai</p>
      <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalSelesai }}</p>
    </div>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Cari</label>
          <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang, wilayah, sales..."
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-medium bg-gray-50 focus:bg-white focus:border-amber-500 transition outline-none">
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Status</label>
          <select wire:model.live="filterStatus"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-amber-500 transition outline-none cursor-pointer">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="diproses">Diproses</option>
            <option value="selesai">Selesai</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Awal</label>
          <input type="date" wire:model.live="tanggalAwal"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-amber-500 transition outline-none cursor-pointer">
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tanggal Akhir</label>
          <input type="date" wire:model.live="tanggalAkhir"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-semibold bg-gray-50 focus:bg-white focus:border-amber-500 transition outline-none cursor-pointer">
        </div>
      </div>
    </div>
    @if ($search || $filterStatus || $tanggalAwal || $tanggalAkhir)
      <div class="px-4 sm:px-5 py-3 border-t border-gray-100 bg-gray-50/50">
        <button wire:click="resetFilters"
          class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 hover:bg-red-100 text-xs font-semibold text-red-600 transition">
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
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Tanggal</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Barang</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Wilayah</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Jumlah</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Status</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Sales</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($orders as $order)
            <tr class="hover:bg-amber-50/30">
              <td class="px-5 py-4 text-sm">{{ $order->tanggal_order->format('d/m/Y') }}</td>
              <td class="px-5 py-4 text-sm font-semibold">{{ $order->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-sm">{{ $order->wilayah->nama_wilayah ?? '-' }}</td>
              <td class="px-5 py-4 text-sm font-bold">{{ number_format($order->jumlah) }}</td>
              <td class="px-5 py-4">
                @if ($order->status == 'pending')
                  <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded-lg text-xs font-semibold">Pending</span>
                @elseif($order->status == 'diproses')
                  <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold">Diproses</span>
                @else
                  <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold">Selesai</span>
                @endif
              </td>
              <td class="px-5 py-4 text-sm">{{ $order->sales->nama_sales ?? ($order->user->nama ?? '-') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-20 text-center text-gray-400">Belum ada data order sales</td>
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
