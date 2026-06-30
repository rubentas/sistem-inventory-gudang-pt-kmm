<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Monitoring Stok Kritis</h1>
            <p class="text-sm text-gray-400 mt-0.5">Pantau data barang dengan stok menipis</p>
          </div>
        </div>
        <a href="{{ route('laporan.stok.pdf') }}" target="_blank"
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

  {{-- ALERT --}}
  <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4 flex items-center gap-3">
    <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
      <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
      </svg>
    </div>
    <div>
      <p class="text-sm font-semibold text-red-800">Stok Kritis</p>
      <p class="text-xs text-red-600 mt-0.5">Total {{ $totalStokKritis }} barang dengan stok menipis (stok &le; minimum)
      </p>
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
                Barang</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Satuan</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok
                Saat Ini</span></th>
            <th class="px-5 py-4 text-left"><span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok
                Minimum</span></th>
            <th class="px-5 py-4 text-left"><span
                class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status</span></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($stokKritis as $index => $stok)
            <tr class="hover:bg-red-50/30 transition">
              <td class="px-5 py-4 text-xs font-semibold text-gray-300">{{ $index + 1 }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                  {{ $stok->barang->kode_barang ?? '-' }}
                </span>
              </td>
              <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $stok->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4">
                <span
                  class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-200 rounded-lg text-xs font-semibold">{{ $stok->barang->kategori ?? '-' }}</span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ $stok->barang->satuan ?? 'Pcs' }}</td>
              <td class="px-5 py-4 text-sm font-bold text-red-600">{{ number_format($stok->jumlah_stok) }}</td>
              <td class="px-5 py-4 text-sm text-gray-600">{{ number_format($stok->stok_minimum) }}</td>
              <td class="px-5 py-4">
                <span
                  class="px-2.5 py-1 bg-red-50 text-red-700 border border-red-100 rounded-lg text-xs font-semibold">⚠
                  Menipis</span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-6 py-20">
                <div class="flex flex-col items-center text-center gap-5 max-w-sm mx-auto">
                  <div class="w-20 h-20 rounded-2xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-9 h-9 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-base font-bold text-gray-900 mb-1">Stok Aman</h3>
                    <p class="text-sm text-gray-400">Semua stok masih aman, tidak ada yang menipis.</p>
                  </div>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
