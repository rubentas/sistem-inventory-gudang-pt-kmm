<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Laporan Data Supplier</h1>
      <p class="text-gray-500 text-sm">Laporan lengkap supplier/pemasok barang</p>
    </div>
    <div class="flex gap-2">
      <button wire:click="resetFilters"
        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
        Reset Filter
      </button>
      <a href="{{ route('laporan.supplier.pdf') }}" target="_blank"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
        📄 Export PDF
      </a>
    </div>
  </div>

  <!-- Total Stat -->
  <div class="bg-purple-50 rounded-lg p-3 mb-4">
    <span class="text-sm text-gray-600">Total Supplier: </span>
    <span class="text-xl font-bold text-purple-700">{{ number_format($totalSupplier) }}</span>
  </div>

  <!-- Search -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex gap-4">
      <div class="flex-1">
        <input type="text" wire:model.live="search" placeholder="Cari kode atau nama supplier..."
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
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kode Supplier</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Supplier</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No. Telepon</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Email</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Alamat</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Keterangan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($suppliers as $index => $supplier)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $suppliers->firstItem() + $index }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $supplier->kode_supplier }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $supplier->nama_supplier }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $supplier->no_telp ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $supplier->email ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">
                {{ \Illuminate\Support\Str::limit($supplier->alamat, 50) ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">
                {{ \Illuminate\Support\Str::limit($supplier->keterangan, 50) ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data supplier</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $suppliers->links() }}
    </div>
  </div>
</div>
