<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Stok Barang</h1>
      <p class="text-gray-500 text-sm">Lihat ketersediaan stok barang gudang</p>
    </div>
    <button wire:click="resetFilters"
      class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
      Reset Filter
    </button>
  </div>

  <!-- Statistik Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-orange-500">
      <p class="text-gray-500 text-sm">Total Stok</p>
      <p class="text-2xl font-bold text-gray-800">{{ number_format($totalStok) }}</p>
      <p class="text-xs text-gray-400 mt-1">Seluruh barang (dalam satuan)</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
      <p class="text-gray-500 text-sm">Stok Menipis</p>
      <p class="text-2xl font-bold text-red-600">{{ number_format($totalMenipis) }}</p>
      <p class="text-xs text-gray-400 mt-1">Stok <= Minimum</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
      <p class="text-gray-500 text-sm">Stok Aman</p>
      <p class="text-2xl font-bold text-green-600">{{ number_format($totalAman) }}</p>
      <p class="text-xs text-gray-400 mt-1">Stok > Minimum</p>
    </div>
  </div>

  <!-- Filter -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Barang</label>
        <input type="text" wire:model.live="search" placeholder="Cari nama atau kode barang..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
        <select wire:model.live="filterStatus"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
          <option value="">Semua Status</option>
          <option value="aman">Stok Aman</option>
          <option value="menipis">Stok Menipis</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-orange-600 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kode Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kategori</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Satuan</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Minimum</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Jumlah Stok</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($stoks as $index => $stok)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $stoks->firstItem() + $index }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $stok->barang->kode_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $stok->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $stok->barang->kategori ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $stok->barang->satuan ?? 'Pcs' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($stok->stok_minimum) }}</td>
              <td
                class="px-4 py-3 text-sm font-semibold {{ $stok->status == 'Menipis' ? 'text-red-600' : 'text-gray-800' }}">
                {{ number_format($stok->jumlah_stok) }}
              </td>
              <td class="px-4 py-3 text-sm">
                @if ($stok->status == 'Menipis')
                  <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                    ⚠️ Menipis
                  </span>
                @else
                  <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                    ✓ Aman
                  </span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                  </path>
                </svg>
                Tidak ada data stok barang
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $stoks->links() }}
    </div>
  </div>
</div>
