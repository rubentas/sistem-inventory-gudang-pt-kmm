<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Laporan 5 Stok Kritis</h1>
      <p class="text-gray-500 text-sm">5 barang dengan stok paling menipis</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('laporan.stok.pdf') }}" target="_blank"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
        📄 Export PDF
      </a>
    </div>
  </div>

  <!-- Info -->
  <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
    <div class="flex items-center gap-2">
      <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
      </svg>
      <span class="text-sm text-yellow-700">Total {{ $totalStokKritis }} barang dengan stok menipis (stok ≤
        minimum)</span>
    </div>
  </div>

  <!-- Tabel -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-red-600 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kode Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kategori</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Satuan</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Saat Ini</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Minimum</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($stokKritis as $index => $stok)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $index + 1 }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $stok->barang->kode_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $stok->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $stok->barang->kategori ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $stok->barang->satuan ?? 'Pcs' }}</td>
              <td class="px-4 py-3 text-sm font-semibold text-red-600">{{ number_format($stok->jumlah_stok) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($stok->stok_minimum) }}</td>
              <td class="px-4 py-3">
                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">⚠️ Menipis</span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada stok menipis</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
