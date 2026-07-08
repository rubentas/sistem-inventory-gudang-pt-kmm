<div class="space-y-5">
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-cyan-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Wilayah</h1>
          <p class="text-sm text-gray-400 mt-0.5">Daftar wilayah & total penjualan</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('laporan.wilayah.excel') }}"
          class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-green-600/25"><svg
            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>Excel</a>
        <a href="{{ route('laporan.wilayah.pdf') }}" target="_blank"
          class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-red-600/25"><svg
            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>PDF</a>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-cyan-100 flex items-center justify-center"><svg class="w-5 h-5 text-cyan-600"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Total Wilayah</p>
        <p class="text-xl font-bold text-cyan-600">{{ $ringkasan['total_wilayah'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center"><svg class="w-5 h-5 text-blue-600"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Total Keluar</p>
        <p class="text-xl font-bold text-blue-600">{{ number_format($ringkasan['total_keluar']) }}</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Barang Keluar per Wilayah</h3>
      <div class="h-72"><canvas id="chartWilayah"></canvas></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b">
        <h3 class="text-sm font-bold text-gray-900">Daftar Wilayah</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Wilayah</th>
              <th class="px-5 py-3 text-right text-xs font-bold text-gray-400">Total Keluar</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tabelData as $d)
              <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-sm font-semibold">{{ $d->nama_wilayah }}</td>
                <td class="px-5 py-3 text-sm text-right font-bold">
                  {{ number_format($d->barang_keluar_sum_jumlah ?? 0) }}</td>
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
    let c = null;

    function r() {
      const d = @json($perWilayah);
      const x = document.getElementById('chartWilayah');
      if (!x) return;
      if (c) c.destroy();
      c = new Chart(x, {
        type: 'doughnut',
        data: {
          labels: d.labels,
          datasets: [{
            data: d.values,
            backgroundColor: ['#06B6D4', '#0891B2', '#0E7490', '#155E75', '#164E63']
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
                padding: 12,
                usePointStyle: true,
                font: {
                  size: 11
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
