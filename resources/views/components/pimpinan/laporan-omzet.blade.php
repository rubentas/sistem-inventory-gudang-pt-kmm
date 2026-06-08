<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Laporan Omzet Penjualan</h1>
      <p class="text-gray-500 text-sm">Total penjualan per periode</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('laporan.order.pdf') }}" target="_blank"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
        📄 Export PDF
      </a>
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

  <!-- Statistik Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
      <p class="text-sm text-gray-500">Total Omzet</p>
      <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($omzet, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
      <p class="text-sm text-gray-500">Total Order</p>
      <p class="text-2xl font-bold text-green-600">{{ number_format($totalOrder) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-orange-500">
      <p class="text-sm text-gray-500">Total Barang Terjual</p>
      <p class="text-2xl font-bold text-orange-600">{{ number_format($totalTerjual) }} unit</p>
    </div>
  </div>

  <!-- Tabel Detail -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Omzet</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terjual</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="px-4 py-3 text-sm text-gray-800">
              {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
            </td>
            <td class="px-4 py-3 text-sm font-semibold text-blue-600">Rp {{ number_format($omzet, 0, ',', '.') }}</td>
            <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($totalOrder) }}</td>
            <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($totalTerjual) }} unit</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
