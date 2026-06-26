<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Invoice</h1>
            <p class="text-sm text-gray-400 mt-0.5">Invoice Barang Masuk & Keluar</p>
          </div>
        </div>
        <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm">
          <div>
            <p class="text-xs text-gray-400 uppercase font-semibold">Total</p>
            <p class="text-xl font-bold text-gray-900">{{ $stats['total'] }}</p>
          </div>
          <div class="w-px h-8 bg-gray-200"></div>
          <div>
            <p class="text-xs text-gray-400 uppercase font-semibold">Masuk</p>
            <p class="text-xl font-bold text-green-600">{{ $stats['total_masuk'] }}</p>
          </div>
          <div class="w-px h-8 bg-gray-200"></div>
          <div>
            <p class="text-xs text-gray-400 uppercase font-semibold">Keluar</p>
            <p class="text-xl font-bold text-blue-600">{{ $stats['total_keluar'] }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- SEARCH & FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 flex gap-3">
    <div
      class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-blue-400 transition">
      <div class="pl-3.5 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg></div>
      <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari no invoice..."
        class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none text-gray-900">
    </div>
    <select wire:model.live="filterJenis"
      class="h-11 px-4 border-2 border-gray-200 rounded-xl text-sm font-semibold cursor-pointer">
      <option value="">Semua</option>
      <option value="masuk">Barang Masuk</option>
      <option value="keluar">Barang Keluar</option>
    </select>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[900px]">
        <thead>
          <tr class="bg-gray-50">
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">No. Invoice</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Jenis</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Tanggal</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Toko/Supplier</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Sales/Admin</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Barang</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Jumlah</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Total</th>
            <th class="px-5 py-4 text-center text-xs font-bold text-gray-400 uppercase w-24">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($invoices as $inv)
            <tr class="hover:bg-blue-50/30">
              <td class="px-5 py-4"><span
                  class="text-xs font-mono font-semibold bg-violet-50 text-violet-700 px-2 py-1 rounded-md">{{ $inv['no_invoice'] }}</span>
              </td>
              <td class="px-5 py-4">
                @if ($inv['jenis'] === 'masuk')
                  <span class="px-2 py-0.5 bg-green-50 text-green-700 text-xs font-semibold rounded-lg">Masuk</span>
                @else
                  <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg">Keluar</span>
                @endif
              </td>
              <td class="px-5 py-4 text-sm">{{ \Carbon\Carbon::parse($inv['tanggal'])->format('d/m/Y') }}</td>
              <td class="px-5 py-4 text-sm font-semibold">{{ $inv['toko'] }}</td>
              <td class="px-5 py-4 text-sm">{{ $inv['sales'] }}</td>
              <td class="px-5 py-4 text-sm">{{ $inv['barang'] }}</td>
              <td class="px-5 py-4 text-sm">{{ number_format($inv['jumlah']) }} {{ $inv['satuan'] }}</td>
              <td
                class="px-5 py-4 text-sm font-bold {{ $inv['jenis'] === 'masuk' ? 'text-gray-500' : 'text-blue-600' }}">
                {{ $inv['jenis'] === 'masuk' ? '—' : 'Rp ' . number_format($inv['total'], 0, ',', '.') }}
              </td>
              <td class="px-5 py-4 text-center">
                <div class="flex items-center justify-center gap-1">
                  @if ($inv['jenis'] === 'masuk')
                    <button wire:click="cetakPdfMasuk({{ $inv['id'] }})"
                      class="text-xs bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 px-3 py-1.5 rounded-lg font-semibold transition">PDF</button>
                  @else
                    <button wire:click="cetakPdfKeluar({{ $inv['id'] }})"
                      class="text-xs bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 px-3 py-1.5 rounded-lg font-semibold transition">PDF</button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-6 py-20 text-center text-gray-400">Belum ada invoice</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($invoices->hasPages())
      <div class="px-5 py-3">{{ $invoices->links() }}</div>
    @endif
  </div>
</div>
