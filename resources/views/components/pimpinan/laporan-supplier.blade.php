<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Data Supplier</h1>
            <p class="text-sm text-gray-400 mt-0.5">Laporan lengkap supplier/pemasok barang</p>
          </div>
        </div>
        <a href="{{ route('laporan.supplier.pdf') }}" target="_blank"
          class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-600 hover:text-red-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          PDF
        </a>
      </div>
    </div>
  </div>

  {{-- TOTAL --}}
  <div class="bg-purple-50 border border-purple-200 rounded-2xl px-6 py-4 flex items-center justify-between">
    <p class="text-sm font-semibold text-purple-700">Total Supplier</p>
    <p class="text-2xl font-extrabold text-purple-700">{{ number_format($totalSupplier) }}</p>
  </div>

  {{-- SEARCH --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="p-4 sm:p-5">
      <div
        class="flex-1 flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-purple-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-purple-100 transition">
        <div class="pl-3.5 shrink-0 text-gray-400">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode atau nama supplier…"
          class="flex-1 h-11 px-3 text-sm bg-transparent focus:outline-none placeholder-gray-400 text-gray-900">
      </div>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[800px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left w-12"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">#</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kode</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama
                Supplier</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">No.
                Telepon</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Email</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($suppliers as $index => $supplier)
            <tr class="hover:bg-purple-50/30 transition">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $suppliers->firstItem() + $index }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-violet-50 text-violet-700 border border-violet-100">{{ $supplier->kode_supplier }}</span>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $supplier->nama_supplier }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $supplier->no_telp ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $supplier->email ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-600 max-w-[200px] truncate">{{ $supplier->alamat ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-gray-500 max-w-[150px] truncate">{{ $supplier->keterangan ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Tidak Ada Data</h3>
                    <p class="text-sm text-gray-400">Belum ada data supplier.</p>
                  </div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($suppliers->hasPages())
      <div class="px-5 py-4 border-t border-gray-100">{{ $suppliers->links() }}</div>
    @endif
  </div>
</div>
