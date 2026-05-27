<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Laporan Wilayah Distribusi</h1>
      <p class="text-gray-500 text-sm">Laporan wilayah distribusi dan jumlah toko</p>
    </div>
    <div class="flex gap-2">
      <button wire:click="resetFilters"
        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
        Reset Filter
      </button>
      <a href="{{ route('laporan.wilayah.pdf') }}" target="_blank"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
        📄 Export PDF
      </a>
    </div>
  </div>

  <!-- Total Stat -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div class="bg-purple-50 rounded-lg p-3">
      <span class="text-sm text-gray-600">Total Wilayah: </span>
      <span class="text-xl font-bold text-purple-700">{{ number_format($totalWilayah) }}</span>
    </div>
    <div class="bg-purple-50 rounded-lg p-3">
      <span class="text-sm text-gray-600">Total Toko: </span>
      <span class="text-xl font-bold text-purple-700">{{ number_format($totalToko) }}</span>
    </div>
  </div>

  <!-- Search -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex gap-4">
      <div class="flex-1">
        <input type="text" wire:model.live="search" placeholder="Cari nama wilayah..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
      </div>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-purple-700 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Wilayah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Jumlah Toko</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Sales Penanggung Jawab</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Keterangan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($wilayahs as $index => $wilayah)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $wilayahs->firstItem() + $index }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $wilayah->nama_wilayah }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($wilayah->jumlah_toko) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $wilayah->sales->nama ?? '-' }}<br>
                <span class="text-xs text-gray-400">{{ $wilayah->sales->username ?? '' }}</span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $wilayah->keterangan ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data wilayah</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $wilayahs->links() }}
    </div>
  </div>
</div>
