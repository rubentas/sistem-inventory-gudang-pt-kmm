<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-violet-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Omzet Penjualan</h1>
          <p class="text-sm text-gray-400 mt-0.5">Ringkasan omzet tahunan</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <select wire:model.live="tahun"
          class="text-sm border border-gray-200 rounded-xl px-4 py-2.5 font-semibold bg-white cursor-pointer focus:border-violet-500 focus:ring-4 focus:ring-violet-100 outline-none transition">
          @for ($y = now()->year; $y >= 2024; $y--)
            <option value="{{ $y }}">{{ $y }}</option>
          @endfor
        </select>
        <a href="{{ route('laporan.omzet.excel', ['tahun' => $tahun]) }}"
          class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-green-600/25">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>Excel
        </a>
        <a href="{{ route('laporan.omzet.pdf', ['tahun' => $tahun]) }}" target="_blank"
          class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-red-600/25">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>PDF
        </a>
      </div>
    </div>
  </div>

  {{-- OMZET CARDS --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
      <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total Omzet {{ $tahun }}</p>
      <p class="text-2xl font-bold text-violet-600 mt-1">Rp {{ number_format($ringkasan['total_omzet'], 0, ',', '.') }}
      </p>
      <p class="text-xs text-gray-400 mt-1">Akumulasi tahunan</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
      <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total Order</p>
      <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($ringkasan['total_order']) }}</p>
      <p class="text-xs text-gray-400 mt-1">Seluruh transaksi</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
      <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Rata-rata Order</p>
      <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($ringkasan['rata_omzet'], 0, ',', '.') }}
      </p>
      <p class="text-xs text-gray-400 mt-1">Per transaksi</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
      <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Bulan Tertinggi</p>
      <p class="text-2xl font-bold text-amber-600 mt-1">{{ $ringkasan['bulan_tertinggi'] }}</p>
      <p class="text-xs text-gray-400 mt-1">Rp {{ number_format($ringkasan['nilai_tertinggi'], 0, ',', '.') }}</p>
    </div>
  </div>

  {{-- CHART --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
    <h3 class="text-sm font-bold text-gray-900 mb-1">Omzet Per Bulan</h3>
    <p class="text-xs text-gray-400 mb-5">Tren omzet bulanan tahun {{ $tahun }}</p>
    <div class="h-80"><canvas id="chartOmzet"></canvas></div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.3/dist/chart.umd.min.js"></script>
<script>
  document.addEventListener('livewire:initialized', () => {
    let chart = null;

    function r() {
      const d = @json($omzetPerBulan);
      const c = document.getElementById('chartOmzet');
      if (!c) return;
      if (chart) chart.destroy();
      const max = Math.max(...d.values);
      chart = new Chart(c, {
        type: 'bar',
        data: {
          labels: d.labels,
          datasets: [{
            label: 'Omzet',
            data: d.values,
            backgroundColor: d.values.map(v => v === max ? '#8B5CF6' : '#DDD6FE'),
            borderRadius: 8,
            borderSkipped: false
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          animation: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: '#1F2937',
              padding: 12,
              cornerRadius: 8,
              callbacks: {
                label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: v => 'Rp ' + (v / 1000000).toFixed(0) + 'M',
                font: {
                  size: 11
                },
                color: '#9CA3AF'
              },
              grid: {
                color: '#F3F4F6'
              }
            },
            x: {
              ticks: {
                font: {
                  size: 11
                },
                color: '#6B7280'
              },
              grid: {
                display: false
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
