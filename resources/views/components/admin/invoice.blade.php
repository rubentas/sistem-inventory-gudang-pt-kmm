<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Invoice Penjualan</h1>
      <p class="text-gray-500 text-sm">Kelola invoice dari order sales</p>
    </div>
    <div class="flex gap-2">
      <button wire:click="resetFilters"
        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
        Reset Filter
      </button>
    </div>
  </div>

  <!-- Pending Alert -->
  @if ($pendingInvoices > 0)
    <div
      class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-5 py-3 rounded-lg mb-4 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
        <span class="text-sm font-medium">Terdapat {{ $pendingInvoices }} order yang belum memiliki invoice.</span>
      </div>
    </div>
  @endif

  <!-- Search -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex gap-4">
      <div class="flex-1">
        <input type="text" wire:model.live="search" placeholder="Cari no invoice atau nama toko..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
      <select wire:model.live="filterStatus" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <option value="">Semua Status</option>
        <option value="pending">Pending</option>
        <option value="diproses">Diproses</option>
        <option value="selesai">Selesai</option>
      </select>
    </div>
  </div>

  <!-- Tabel -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No. Invoice</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Tanggal Order</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Toko</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Jumlah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Total Harga</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
            <th class="px-4 py-3 text-center text-xs font-medium uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($invoices as $invoice)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm font-mono text-gray-800">{{ $invoice->no_invoice }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $invoice->tanggal_order->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $invoice->nama_toko ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $invoice->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($invoice->jumlah) }}
                {{ $invoice->barang->satuan ?? 'pcs' }}</td>
              <td class="px-4 py-3 text-sm font-semibold text-blue-600">Rp
                {{ number_format($invoice->total_harga, 0, ',', '.') }}</td>
              <td class="px-4 py-3">
                @if ($invoice->status == 'pending')
                  <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Pending</span>
                @elseif($invoice->status == 'diproses')
                  <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">Diproses</span>
                @else
                  <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Selesai</span>
                @endif
              </td>
              <td class="px-4 py-3 text-center">
                <button wire:click="generateInvoice({{ $invoice->id_order }})"
                  class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1 rounded-lg text-xs transition">
                  Cetak PDF
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-4 py-8 text-center text-gray-500">Belum ada invoice yang digenerate</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $invoices->links() }}
    </div>
  </div>
</div>
