<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-orange-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16V4m0 0l4 4m-4-4l-4 4M7 16v4m0 0l-4-4m4 4l4-4" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Barang Keluar</h1>
            <p class="text-sm text-gray-400 mt-0.5">Visualisasi & ringkasan data barang keluar</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <a href="{{ route('laporan.keluar.excel', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir, 'id_wilayah' => $filterWilayah]) }}"
            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-green-600/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Excel
          </a>
          <a href="{{ route('laporan.keluar.pdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir, 'id_wilayah' => $filterWilayah]) }}"
            target="_blank"
            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-red-600/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            PDF
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="px-4 sm:px-5 py-3 flex items-center gap-2 flex-wrap">
      <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mr-1">Periode:</span>
      <button wire:click="setFilter('today')"
        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'today' ? 'bg-orange-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-orange-300 hover:text-orange-600' }}">
        Hari Ini
      </button>
      <button wire:click="setFilter('week')"
        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'week' ? 'bg-orange-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-orange-300 hover:text-orange-600' }}">
        7 Hari
      </button>
      <button wire:click="setFilter('month')"
        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'month' ? 'bg-orange-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-orange-300 hover:text-orange-600' }}">
        Bulan Ini
      </button>
      <span class="text-xs text-gray-400">|</span>
      <input type="date" wire:model.live="tanggalAwal" wire:change="$set('filterType', 'custom')"
        class="text-xs border border-gray-200 rounded-lg px-2 py-1.5">
      <span class="text-xs text-gray-400">s/d</span>
      <input type="date" wire:model.live="tanggalAkhir" wire:change="$set('filterType', 'custom')"
        class="text-xs border border-gray-200 rounded-lg px-2 py-1.5">
      <span class="text-xs text-gray-400">|</span>
      <select wire:model.live="filterWilayah" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5">
        <option value="">Semua Wilayah</option>
        @foreach ($wilayahList as $w)
          <option value="{{ $w->id_wilayah }}">{{ $w->nama_wilayah }}</option>
        @endforeach
      </select>
    </div>
  </div>

  {{-- SUMMARY CARDS --}}
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
      <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total Keluar</p>
      <p class="text-2xl font-bold text-orange-600 mt-1">{{ number_format($ringkasan['total_keluar']) }}</p>
      <p class="text-xs text-gray-400 mt-1">unit</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
      <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total Item</p>
      <p class="text-2xl font-bold text-green-600 mt-1">{{ $ringkasan['total_item'] }}</p>
      <p class="text-xs text-gray-400 mt-1">transaksi</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
      <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Rata-rata</p>
      <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($ringkasan['rata_rata']) }}</p>
      <p class="text-xs text-gray-400 mt-1">unit/transaksi</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
      <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Wilayah Aktif</p>
      <p class="text-2xl font-bold text-purple-600 mt-1">{{ $ringkasan['total_wilayah'] }}</p>
      <p class="text-xs text-gray-400 mt-1">wilayah</p>
    </div>
  </div>

  {{-- CHARTS --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
      <h3 class="text-sm font-bold text-gray-900 mb-4">📊 Barang Keluar Per Hari</h3>
      <div class="h-64"><canvas id="chartPerHari"></canvas></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
      <h3 class="text-sm font-bold text-gray-900 mb-4">🥧 Barang Keluar Per Wilayah</h3>
      <div class="h-64"><canvas id="chartPerWilayah"></canvas></div>
    </div>
  </div>

  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
    <h3 class="text-sm font-bold text-gray-900 mb-4">📈 Barang Keluar Per Bulan ({{ now()->year }})</h3>
    <div class="h-64"><canvas id="chartPerBulan"></canvas></div>
  </div>

  {{-- TABEL --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
      <h3 class="text-sm font-bold text-gray-900">📋 5 Transaksi Terakhir</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Tanggal</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Barang</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Jumlah</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Wilayah</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @foreach ($tabelRingkas as $item)
            <tr class="hover:bg-orange-50/30">
              <td class="px-5 py-3 text-sm">{{ $item->tanggal_keluar->format('d/m/Y') }}</td>
              <td class="px-5 py-3 text-sm font-semibold">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-3 text-sm font-bold text-gray-700">{{ number_format($item->jumlah) }} unit</td>
              <td class="px-5 py-3 text-sm">{{ $item->wilayah->nama_wilayah ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('livewire:initialized', () => {
    let chartPerHari = null;
    let chartPerBulan = null;
    let chartPerWilayah = null;

    function renderCharts() {
      const perHari = @json($perHari);
      const perBulan = @json($perBulan);
      const perWilayah = @json($perWilayah);

      const ctx1 = document.getElementById('chartPerHari');
      if (ctx1) {
        if (chartPerHari) chartPerHari.destroy();
        chartPerHari = new Chart(ctx1, {
          type: 'bar',
          data: {
            labels: perHari.labels.length > 0 ? perHari.labels : ['-'],
            datasets: [{
              label: 'Jumlah Keluar',
              data: perHari.values.length > 0 ? perHari.values : [0],
              backgroundColor: '#F97316',
              borderRadius: 8
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
                beginAtZero: true
              }
            }
          }
        });
      }

      const ctx2 = document.getElementById('chartPerBulan');
      if (ctx2) {
        if (chartPerBulan) chartPerBulan.destroy();
        chartPerBulan = new Chart(ctx2, {
          type: 'bar',
          data: {
            labels: perBulan.labels,
            datasets: [{
              label: 'Jumlah Keluar',
              data: perBulan.values,
              backgroundColor: '#10B981',
              borderRadius: 8
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
                beginAtZero: true
              }
            }
          }
        });
      }

      const ctx3 = document.getElementById('chartPerWilayah');
      if (ctx3) {
        if (chartPerWilayah) chartPerWilayah.destroy();
        chartPerWilayah = new Chart(ctx3, {
          type: 'doughnut',
          data: {
            labels: perWilayah.labels.length > 0 ? perWilayah.labels : ['-'],
            datasets: [{
              data: perWilayah.values.length > 0 ? perWilayah.values : [0],
              backgroundColor: ['#F97316', '#3B82F6', '#10B981', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4']
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  boxWidth: 12,
                  font: {
                    size: 10
                  }
                }
              }
            }
          }
        });
      }
    }

    renderCharts();
    Livewire.hook('morph.updated', () => setTimeout(() => renderCharts(), 200));
  });
</script>
