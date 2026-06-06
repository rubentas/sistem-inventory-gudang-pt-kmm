<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Laporan 5 Barang Terlaris</h1>
      <p class="text-gray-500 text-sm">5 barang dengan penjualan terbanyak per bulan</p>
    </div>
  </div>

  <!-- Filter -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex flex-wrap gap-4 items-end">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
        <select wire:model.live="bulan" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
          <option value="01">Januari</option>
          <option value="02">Februari</option>
          <option value="03">Maret</option>
          <option value="04">April</option>
          <option value="05">Mei</option>
          <option value="06">Juni</option>
          <option value="07">Juli</option>
          <option value="08">Agustus</option>
          <option value="09">September</option>
          <option value="10">Oktober</option>
          <option value="11">November</option>
          <option value="12">Desember</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
        <select wire:model.live="tahun" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
          <option value="2024">2024</option>
          <option value="2025">2025</option>
          <option value="2026">2026</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Tabel -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-emerald-600 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kode Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Total Terjual</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($terlaris as $index => $item)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $index + 1 }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->barang->kode_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm font-semibold text-emerald-600">{{ number_format($item->total_terjual) }}
                unit</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
