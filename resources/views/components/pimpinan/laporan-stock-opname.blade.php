<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Laporan Stock Opname</h1>
      <p class="text-gray-500 text-sm">Laporan hasil pengecekan stok fisik vs sistem</p>
    </div>
    <div class="flex gap-2">
      <button wire:click="resetFilters"
        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
        Reset Filter
      </button>
      <a href="{{ route('laporan.opname.pdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
        target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
        📄 Export PDF
      </a>
    </div>
  </div>

  <!-- Filter -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Barang</label>
        <input type="text" wire:model.live="search" placeholder="Cari nama atau kode barang..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
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

  <!-- Total Stat -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div class="bg-purple-50 rounded-lg p-3">
      <span class="text-sm text-gray-600">Total Data Opname: </span>
      <span class="text-xl font-bold text-purple-700">{{ number_format($totalData) }}</span>
    </div>
    <div class="bg-purple-50 rounded-lg p-3">
      <span class="text-sm text-gray-600">Total Selisih: </span>
      <span class="text-xl font-bold {{ $totalSelisih >= 0 ? 'text-green-700' : 'text-red-700' }}">
        {{ number_format($totalSelisih) }}
      </span>
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
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Sistem</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Fisik</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Selisih</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Input Oleh</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Keterangan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($stockOpnames as $item)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_opname->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->barang->nama_barang ?? '-' }}<br>
                <span class="text-xs text-gray-400">{{ $item->barang->kode_barang ?? '-' }}</span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($item->stok_sistem) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($item->stok_fisik) }}</td>
              <td class="px-4 py-3 text-sm font-semibold {{ $item->selisih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ number_format($item->selisih) }}
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->user->nama ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->keterangan ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data stock opname</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $stockOpnames->links() }}
    </div>
  </div>
</div>
