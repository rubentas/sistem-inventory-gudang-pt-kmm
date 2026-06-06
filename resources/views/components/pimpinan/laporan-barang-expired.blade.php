<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Laporan Barang Expired</h1>
      <p class="text-gray-500 text-sm">Daftar barang yang mendekati atau sudah expired</p>
    </div>
  </div>

  <!-- Filter -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex flex-wrap gap-4">
      <div class="flex-1">
        <input type="text" wire:model.live="search" placeholder="Cari barang..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
      </div>
      <select wire:model.live="filterStatus" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">Semua Status</option>
        <option value="aman">Aman</option>
        <option value="hampir_expired">Hampir Expired</option>
        <option value="expired">Expired</option>
      </select>
    </div>
  </div>

  <!-- Tabel -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-purple-600 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Tanggal Masuk</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kode Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Supplier</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Tanggal Expired</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($expired as $item)
            <tr>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->barang->kode_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->supplier->nama_supplier ?? '-' }}</td>
              <td class="px-4 py-3 text-sm font-mono">
                {{ \Carbon\Carbon::parse($item->tanggal_expired)->format('d/m/Y') }}</td>
              <td class="px-4 py-3">
                @if ($item->status_expired == 'expired')
                  <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Expired</span>
                @elseif($item->status_expired == 'hampir_expired')
                  <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">⚠️ Hampir Expired</span>
                @else
                  <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Aman</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada data expired</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $expired->links() }}
    </div>
  </div>
</div>
