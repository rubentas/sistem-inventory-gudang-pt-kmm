<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Laporan Barang Masuk</h1>
      <p class="text-gray-500 text-sm">Laporan lengkap barang masuk dari semua sumber</p>
    </div>
    <div class="flex gap-2">
      <button wire:click="resetFilters"
        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
        Reset Filter
      </button>
      <a href="{{ route('laporan.masuk.pdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
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
        <input type="text" wire:model.live="search" placeholder="Cari barang, nota..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sumber</label>
        <select wire:model.live="filterSumber"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
          <option value="">Semua Sumber</option>
          @foreach ($sumberList as $sumber)
            <option value="{{ $sumber }}">{{ $sumber }}</option>
          @endforeach
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

  <!-- Total Stat -->
  <div class="bg-purple-50 rounded-lg p-3 mb-4 text-right">
    <span class="text-sm text-gray-600">Total Jumlah Barang Masuk: </span>
    <span class="text-xl font-bold text-purple-700">{{ number_format($totalJumlah) }}</span>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-purple-700 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Tanggal</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No Nota</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No Surat Jalan</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Jumlah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Supplier</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Sumber</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Input Oleh</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($barangMasuk as $item)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->no_nota }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->no_surat_jalan }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($item->jumlah) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->supplier->nama_supplier ?? '-' }}</td>
              <td class="px-4 py-3 text-sm">
                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $item->sumber }}</span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->user->nama ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $barangMasuk->links() }}
    </div>
  </div>
</div>
