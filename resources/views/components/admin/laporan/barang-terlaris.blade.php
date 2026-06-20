<div class="space-y-5">

  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Barang Terlaris</h1>
          <p class="text-sm text-gray-400 mt-0.5">Ranking produk paling banyak keluar</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <select wire:model.live="limit" class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 font-semibold">
          <option value="10">Top 10</option>
          <option value="20">Top 20</option>
          <option value="50">Top 50</option>
        </select>
        <a href="{{ route('laporan.terlaris.excel', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir, 'kategori' => $filterKategori, 'limit' => $limit]) }}"
          class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-green-600/25"><svg
            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>Excel</a>
        <a href="{{ route('laporan.terlaris.pdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir, 'kategori' => $filterKategori, 'limit' => $limit]) }}"
          target="_blank"
          class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-red-600/25"><svg
            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>PDF</a>
      </div>
    </div>
  </div>

  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 flex items-center gap-2 flex-wrap">
    <span class="text-xs font-bold text-gray-500 uppercase">Periode:</span>
    <button wire:click="setFilter('today')"
      class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'today' ? 'bg-rose-600 text-white' : 'bg-white border text-gray-600' }}">Hari
      Ini</button>
    <button wire:click="setFilter('week')"
      class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'week' ? 'bg-rose-600 text-white' : 'bg-white border text-gray-600' }}">7
      Hari</button>
    <button wire:click="setFilter('month')"
      class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'month' ? 'bg-rose-600 text-white' : 'bg-white border text-gray-600' }}">Bulan
      Ini</button>
    <span class="text-xs text-gray-400">|</span>
    <input type="date" wire:model.live="tanggalAwal" class="text-xs border rounded-lg px-2 py-1.5">
    <span class="text-xs text-gray-400">s/d</span>
    <input type="date" wire:model.live="tanggalAkhir" class="text-xs border rounded-lg px-2 py-1.5">
    <span class="text-xs text-gray-400">|</span>
    <select wire:model.live="filterKategori" class="text-xs border rounded-lg px-2 py-1.5">
      <option value="">Semua Kategori</option>
      @foreach ($kategoriList as $kat)
        <option value="{{ $kat }}">{{ $kat }}</option>
      @endforeach
    </select>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Top {{ $limit }} Barang Terlaris</h3>
      <div class="h-96"><canvas id="chartTerlaris"></canvas></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b">
        <h3 class="text-sm font-bold text-gray-900">Peringkat</h3>
      </div>
      <div class="overflow-x-auto max-h-96 overflow-y-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">#</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Barang</th>
              <th class="px-5 py-3 text-right text-xs font-bold text-gray-400">Total Keluar</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            @foreach ($topBarang['full'] as $i => $item)
              <tr class="hover:bg-gray-50 {{ $i < 3 ? 'bg-amber-50/50' : '' }}">
                <td class="px-5 py-3">
                  @if ($i == 0)
                    <span
                      class="w-6 h-6 rounded-full bg-yellow-400 text-white text-xs font-bold flex items-center justify-center">1</span>
                  @elseif($i == 1)
                    <span
                      class="w-6 h-6 rounded-full bg-gray-300 text-white text-xs font-bold flex items-center justify-center">2</span>
                  @elseif($i == 2)
                    <span
                      class="w-6 h-6 rounded-full bg-orange-300 text-white text-xs font-bold flex items-center justify-center">3</span>
                  @else
                    <span class="text-xs text-gray-400 pl-2">{{ $i + 1 }}</span>
                  @endif
                </td>
                <td class="px-5 py-3">
                  <p class="text-sm font-semibold text-gray-900">{{ $item->barang->nama_barang ?? '-' }}</p>
                  <p class="text-xs text-gray-400">{{ $item->barang->kategori ?? '-' }}</p>
                </td>
                <td class="px-5 py-3 text-sm text-right font-bold text-gray-700">
                  {{ number_format($item->total_keluar) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.3/dist/chart.umd.min.js"></script>
<script>
  document.addEventListener('livewire:initialized', () => {
    let chart = null;

    function r() {
      const d = @json($topBarang);
      const c = document.getElementById('chartTerlaris');
      if (!c) return;
      if (chart) chart.destroy();
      chart = new Chart(c, {
        type: 'bar',
        data: {
          labels: d.labels,
          datasets: [{
            data: d.values,
            backgroundColor: d.values.map((v, i) => i < 3 ? ['#F59E0B', '#9CA3AF', '#F97316'][i] :
              '#8B5CF6'),
            borderRadius: 6
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          animation: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            x: {
              beginAtZero: true,
              grid: {
                color: '#f1f5f9'
              }
            },
            y: {
              ticks: {
                font: {
                  size: 10
                }
              }
            }
          }
        }
      });
    }
    r();
    Livewire.hook('morph.updated', () => setTimeout(r, 200));
  });
</script>
