<div>
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin Fakturis</h1>
    <p class="text-gray-500 text-sm">Selamat datang, {{ auth()->user()->nama }}</p>
  </div>

  <!-- Statistik Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
      <p class="text-gray-500 text-sm">Barang Masuk Hari Ini</p>
      <p class="text-2xl font-bold text-gray-800">{{ number_format($totalMasukHariIni) }}</p>
      <p class="text-xs text-gray-400 mt-1">Total jumlah barang masuk</p>
    </div>

    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-orange-500">
      <p class="text-gray-500 text-sm">Barang Keluar Hari Ini</p>
      <p class="text-2xl font-bold text-gray-800">{{ number_format($totalKeluarHariIni) }}</p>
      <p class="text-xs text-gray-400 mt-1">Total jumlah barang keluar</p>
    </div>

    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
      <p class="text-gray-500 text-sm">Order Sales Hari Ini</p>
      <p class="text-2xl font-bold text-gray-800">{{ number_format($totalOrderHariIni) }}</p>
      <p class="text-xs text-gray-400 mt-1">Jumlah order masuk</p>
    </div>

    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
      <p class="text-gray-500 text-sm">Total Supplier</p>
      <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSupplier) }}</p>
      <p class="text-xs text-gray-400 mt-1">Supplier aktif</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Order Sales Terbaru -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-5 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Order Sales Terbaru</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
              <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @forelse($orderTerbaru as $order)
              <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-sm text-gray-600">{{ $order->tanggal_order->format('d/m/Y') }}</td>
                <td class="px-5 py-3 text-sm text-gray-800">{{ $order->barang->nama_barang ?? '-' }}</td>
                <td class="px-5 py-3 text-sm text-gray-600">{{ number_format($order->jumlah) }}</td>
                <td class="px-5 py-3 text-sm">
                  @if ($order->status == 'pending')
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                  @elseif($order->status == 'diproses')
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Diproses</span>
                  @else
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-5 py-8 text-center text-gray-500">Belum ada order</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

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
  </div>
</div>
