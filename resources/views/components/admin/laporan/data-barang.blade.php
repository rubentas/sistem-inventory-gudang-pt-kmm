<div class="space-y-5">
  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-violet-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Data Barang</h1>
          <p class="text-sm text-gray-400 mt-0.5">Daftar barang & status stok</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <button wire:click="exportExcel"
          class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-green-600/25">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>Excel
        </button>
        <a href="{{ route('admin.laporan.data-barang.pdf', ['search' => $search, 'filterKategori' => $filterKategori, 'filterStok' => $filterStok]) }}"
          target="_blank"
          class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-red-600/25">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>PDF
        </a>
      </div>
    </div>
  </div>

  {{-- STATS --}}
  <div class="flex gap-4">
    <div class="flex-1 bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total Barang</p>
        <p class="text-xl font-bold text-violet-600">{{ $stats['total'] }}</p>
      </div>
    </div>
    <div class="flex-1 bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Kategori</p>
        <p class="text-xl font-bold text-blue-600">{{ $stats['kategori'] }}</p>
      </div>
    </div>
    <div class="flex-1 bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Stok Aman</p>
        <p class="text-xl font-bold text-emerald-600">{{ $stats['aman'] }}</p>
      </div>
    </div>
    <div class="flex-1 bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Stok Menipis</p>
        <p class="text-xl font-bold text-red-600">{{ $stats['menipis'] }}</p>
      </div>
    </div>
    <div class="flex-1 bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-gray-200 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Stok Habis</p>
        <p class="text-xl font-bold text-gray-700">{{ $stats['habis'] }}</p>
      </div>
    </div>
  </div>

  {{-- CHART --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
    <h3 class="text-sm font-bold text-gray-900 mb-4">Barang per Kategori</h3>
    <div class="h-72"><canvas id="chartKategori"></canvas></div>
  </div>

  {{-- FILTER + TABEL --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 flex flex-wrap gap-3 items-center">
      <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode atau nama barang..."
        class="text-xs border border-gray-200 rounded-lg px-3 py-2 flex-1 min-w-[150px]">
      <select wire:model.live="filterKategori" class="text-xs border border-gray-200 rounded-lg px-3 py-2">
        <option value="">Semua Kategori</option>
        @foreach ($kategoriList as $kat)
          <option value="{{ $kat }}">{{ $kat }}</option>
        @endforeach
      </select>
      <select wire:model.live="filterStok" class="text-xs border border-gray-200 rounded-lg px-3 py-2">
        <option value="">Semua Status</option>
        <option value="habis">Habis</option>
        <option value="menipis">Menipis</option>
        <option value="aman">Aman</option>
      </select>
      @if ($search || $filterKategori || $filterStok)
        <button wire:click="resetFilters" class="text-xs text-red-500 hover:text-red-700 font-semibold">Reset</button>
      @endif
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Kode</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Nama Barang</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Kategori</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase">Satuan</th>
            <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase">Harga Jual</th>
            <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase">Stok Min</th>
            <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase">Stok</th>
            <th class="px-5 py-3 text-center text-xs font-bold text-gray-400 uppercase">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @foreach ($barangs as $b)
            @php $stok = $b->stok->jumlah_stok ?? 0; @endphp
            <tr class="hover:bg-gray-50">
              <td class="px-5 py-3 text-xs font-mono text-gray-600">{{ $b->kode_barang }}</td>
              <td class="px-5 py-3 text-sm font-semibold text-gray-900">{{ $b->nama_barang }}</td>
              <td class="px-5 py-3 text-xs text-gray-500">{{ $b->kategori ?? '-' }}</td>
              <td class="px-5 py-3 text-xs text-gray-500">{{ $b->satuan }}</td>
              <td class="px-5 py-3 text-xs text-right text-gray-700">Rp
                {{ number_format($b->harga_jual_default ?? 0, 0, ',', '.') }}</td>
              <td class="px-5 py-3 text-xs text-right text-gray-700">{{ $b->stok_minimum }}</td>
              <td
                class="px-5 py-3 text-sm text-right font-bold {{ $stok <= $b->stok_minimum && $stok > 0 ? 'text-red-600' : ($stok <= 0 ? 'text-gray-400' : 'text-gray-700') }}">
                {{ number_format($stok) }}</td>
              <td class="px-5 py-3 text-center">
                @if ($stok <= 0)
                  <span
                    class="px-2 py-1 bg-gray-100 text-gray-700 border border-gray-200 rounded-lg text-xs font-semibold">Habis</span>
                @elseif ($stok <= $b->stok_minimum)
                  <span
                    class="px-2 py-1 bg-red-50 text-red-700 border border-red-100 rounded-lg text-xs font-semibold">Menipis</span>
                @else
                  <span
                    class="px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold">Aman</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $barangs->links() }}</div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.3/dist/chart.umd.min.js"></script>
<script>
  document.addEventListener('livewire:initialized', () => {
    let chartKategori = null;

    function renderChart() {
      const data = @json($chartKategori);
      const c1 = document.getElementById('chartKategori');

      if (chartKategori) {
        chartKategori.destroy();
        chartKategori = null;
      }

      if (c1) c1.style.display = data.labels.length > 0 ? '' : 'none';

      if (c1 && data.labels.length > 0) {
        chartKategori = new Chart(c1, {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Jumlah Barang',
              data: data.values,
              backgroundColor: ['#8B5CF6', '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#EC4899'],
              borderRadius: 6,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: {
              legend: {
                display: false
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  stepSize: 1
                }
              }
            }
          }
        });
      }

      setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
      }, 150);
    }

    setTimeout(renderChart, 100);

    Livewire.hook('morph.updated', ({
      component
    }) => {
      if (component.name === 'App\\Livewire\\Admin\\Laporan\\DataBarang') {
        setTimeout(renderChart, 300);
      }
    });

    window.addEventListener('resize', () => {
      setTimeout(renderChart, 200);
    });
  });
</script>
