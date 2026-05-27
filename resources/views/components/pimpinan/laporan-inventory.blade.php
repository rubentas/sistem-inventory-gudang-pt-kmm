<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Laporan Inventory Keseluruhan</h1>
      <p class="text-gray-500 text-sm">Laporan pergerakan stok barang dalam periode tertentu</p>
    </div>
    <div class="flex gap-2">
      <button wire:click="resetFilters"
        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
        Reset Tanggal
      </button>
      <a href="{{ route('laporan.inventory.pdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
        target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
        📄 Export PDF
      </a>
    </div>
  </div>

  <!-- Filter Tanggal -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
    <div class="text-sm text-gray-500 mt-2">
      Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->isoFormat('D MMMM Y') }} -
      {{ \Carbon\Carbon::parse($tanggalAkhir)->isoFormat('D MMMM Y') }}
    </div>
  </div>

  <!-- Statistik Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
      <p class="text-gray-500 text-sm">Total Barang Masuk</p>
      <p class="text-2xl font-bold text-blue-600">{{ number_format($totalMasukKeseluruhan) }}</p>
      <p class="text-xs text-gray-400 mt-1">Dalam periode</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
      <p class="text-gray-500 text-sm">Total Barang Keluar</p>
      <p class="text-2xl font-bold text-red-600">{{ number_format($totalKeluarKeseluruhan) }}</p>
      <p class="text-xs text-gray-400 mt-1">Dalam periode</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
      <p class="text-gray-500 text-sm">Total Stok Akhir</p>
      <p class="text-2xl font-bold text-green-600">{{ number_format($totalStokAkhir) }}</p>
      <p class="text-xs text-gray-400 mt-1">Sisa stok saat ini</p>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-purple-700 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kode Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Satuan</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Awal</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Masuk</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Keluar</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Akhir</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($stoks as $index => $stok)
            @php
              $stokAwal = $stok->jumlah_stok - $stok->total_masuk + $stok->total_keluar;
            @endphp
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $index + 1 }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $stok->barang->kode_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $stok->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $stok->barang->satuan ?? 'Pcs' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($stokAwal) }}</td>
              <td class="px-4 py-3 text-sm text-blue-600 font-semibold">{{ number_format($stok->total_masuk) }}</td>
              <td class="px-4 py-3 text-sm text-red-600 font-semibold">{{ number_format($stok->total_keluar) }}</td>
              <td class="px-4 py-3 text-sm text-gray-800 font-semibold">{{ number_format($stok->jumlah_stok) }}</td>
              <td class="px-4 py-3 text-sm">
                @if ($stok->status == 'Menipis')
                  <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Menipis</span>
                @else
                  <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Aman</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-4 py-8 text-center text-gray-500">Tidak ada data stok</td>
            </tr>
          @endforelse
        </tbody>
        <tfoot class="bg-gray-50 font-semibold">
          <tr>
            <td colspan="4" class="px-4 py-3 text-right text-gray-700">TOTAL:</td>
            <td class="px-4 py-3 text-gray-800">
              {{ number_format($stoks->sum(function ($s) {return $s->jumlah_stok - $s->total_masuk + $s->total_keluar;})) }}
            </td>
            <td class="px-4 py-3 text-blue-700">{{ number_format($totalMasukKeseluruhan) }}</td>
            <td class="px-4 py-3 text-red-700">{{ number_format($totalKeluarKeseluruhan) }}</td>
            <td class="px-4 py-3 text-green-700">{{ number_format($totalStokAkhir) }}</td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
