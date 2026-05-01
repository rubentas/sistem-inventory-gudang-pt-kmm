<div>
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Kepala Gudang</h1>
    <p class="text-gray-500 text-sm">Selamat datang, {{ auth()->user()->nama }}</p>
  </div>

  <!-- Statistik Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
      <p class="text-gray-500 text-sm">Barang Masuk Hari Ini</p>
      <p class="text-2xl font-bold text-gray-800">{{ number_format($totalMasukHariIni) }}</p>
      <p class="text-xs text-gray-400 mt-1">Total jumlah barang masuk hari ini</p>
    </div>

    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
      <p class="text-gray-500 text-sm">Total Jenis Barang</p>
      <p class="text-2xl font-bold text-gray-800">{{ number_format($totalJenisBarang) }}</p>
      <p class="text-xs text-gray-400 mt-1">Jumlah SKU aktif</p>
    </div>

    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">
      <p class="text-gray-500 text-sm">Stok Menipis</p>
      <p class="text-2xl font-bold text-red-600">{{ number_format($stokMenipis) }}</p>
      <p class="text-xs text-gray-400 mt-1">Barang dengan stok <= minimum</p>
    </div>

    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
      <p class="text-gray-500 text-sm">Opname Terakhir</p>
      <p class="text-2xl font-bold text-gray-800">
        {{ $opnameTerakhir ? $opnameTerakhir->tanggal_opname->format('d/m/Y') : '-' }}
      </p>
      <p class="text-xs text-gray-400 mt-1">
        {{ $opnameTerakhir ? $opnameTerakhir->barang->nama_barang ?? '-' : 'Belum pernah opname' }}
      </p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Barang Masuk Terbaru -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-5 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Barang Masuk Terbaru</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sumber</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @forelse($barangMasukTerbaru as $item)
              <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-sm text-gray-600">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                <td class="px-5 py-3 text-sm text-gray-800">{{ $item->barang->nama_barang ?? '-' }}</td>
                <td class="px-5 py-3 text-sm text-gray-600">{{ number_format($item->jumlah) }}</td>
                <td class="px-5 py-3 text-sm text-gray-600">{{ $item->sumber }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-5 py-8 text-center text-gray-500">Belum ada data</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Stok Menipis -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-5 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-red-600">⚠️ Stok Menipis</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Minimum</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @forelse($stokMenipisList as $stok)
              <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-sm text-gray-600">{{ $stok->barang->kode_barang ?? '-' }}</td>
                <td class="px-5 py-3 text-sm text-gray-800">{{ $stok->barang->nama_barang ?? '-' }}</td>
                <td class="px-5 py-3 text-sm text-red-600 font-semibold">{{ number_format($stok->jumlah_stok) }}</td>
                <td class="px-5 py-3 text-sm text-gray-600">{{ number_format($stok->stok_minimum) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-5 py-8 text-center text-gray-500">Semua stok aman</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
