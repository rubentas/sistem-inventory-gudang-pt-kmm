<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Laporan Order Sales</h1>
      <p class="text-gray-500 text-sm">Laporan pesanan dari para sales</p>
    </div>
    <div class="flex gap-2">
      <button wire:click="resetFilters"
        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
        Reset Filter
      </button>
      <a href="{{ route('laporan.order.pdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir, 'status' => $filterStatus]) }}"
        target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
        📄 Export PDF
      </a>
    </div>
  </div>

  <!-- Filter -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
        <input type="text" wire:model.live="search" placeholder="Cari barang, wilayah, sales..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select wire:model.live="filterStatus"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="diproses">Diproses</option>
          <option value="selesai">Selesai</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Awal</label>
        <input type="date" wire:model.live="tanggalAwal"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
        <input type="date" wire:model.live="tanggalAkhir"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
      </div>
    </div>
  </div>

  <!-- Statistik Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-purple-50 rounded-lg p-3 text-center">
      <span class="text-sm text-gray-600">Total Jumlah</span>
      <p class="text-xl font-bold text-purple-700">{{ number_format($totalJumlah) }}</p>
    </div>
    <div class="bg-yellow-50 rounded-lg p-3 text-center">
      <span class="text-sm text-gray-600">Pending</span>
      <p class="text-xl font-bold text-yellow-600">{{ number_format($totalPending) }}</p>
    </div>
    <div class="bg-blue-50 rounded-lg p-3 text-center">
      <span class="text-sm text-gray-600">Diproses</span>
      <p class="text-xl font-bold text-blue-600">{{ number_format($totalDiproses) }}</p>
    </div>
    <div class="bg-green-50 rounded-lg p-3 text-center">
      <span class="text-sm text-gray-600">Selesai</span>
      <p class="text-xl font-bold text-green-600">{{ number_format($totalSelesai) }}</p>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-purple-700 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Tanggal</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Wilayah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Jumlah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Sales</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($orders as $order)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $order->tanggal_order->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $order->barang->nama_barang ?? '-' }}<br>
                <span class="text-xs text-gray-400">{{ $order->barang->kode_barang ?? '-' }}</span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $order->wilayah->nama_wilayah ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($order->jumlah) }}
                {{ $order->barang->satuan ?? 'pcs' }}</td>
              <td class="px-4 py-3 text-sm">
                @if ($order->status == 'pending')
                  <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                @elseif($order->status == 'diproses')
                  <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Diproses</span>
                @else
                  <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                @endif
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $order->user->nama ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data order sales</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $orders->links() }}
    </div>
  </div>
</div>
