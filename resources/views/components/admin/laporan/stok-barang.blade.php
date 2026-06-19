<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
          </div>
          <div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Stok Barang</h1>
            <p class="text-sm text-gray-400 mt-0.5">Monitoring stok & status ketersediaan barang</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <a href="{{ route('laporan.stok.excel', ['kategori' => $filterKategori, 'status' => $filterStatus, 'search' => $search]) }}"
            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-green-600/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Excel
          </a>
          <a href="{{ route('laporan.stok.pdf', ['kategori' => $filterKategori, 'status' => $filterStatus, 'search' => $search]) }}"
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

  {{-- WARNING STOK MENIPIS --}}
  @if ($ringkasan['stok_menipis'] > 0)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
      </div>
      <div>
        <p class="text-sm font-bold text-red-800">{{ $ringkasan['stok_menipis'] }} Barang Stok Menipis!</p>
        <p class="text-xs text-red-600 mt-0.5">Segera lakukan pembelian ulang untuk barang di bawah ini.</p>
      </div>
    </div>
  @endif

  {{-- STATS --}}
  <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total Stok</p>
        <p class="text-xl font-bold text-blue-600">{{ number_format($ringkasan['total_stok']) }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total Barang</p>
        <p class="text-xl font-bold text-purple-600">{{ $ringkasan['total_barang'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Stok Normal</p>
        <p class="text-xl font-bold text-emerald-600">{{ $ringkasan['stok_normal'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Stok Menipis</p>
        <p class="text-xl font-bold text-red-600">{{ $ringkasan['stok_menipis'] }}</p>
      </div>
    </div>
  </div>

  {{-- CHARTS --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Stok per Kategori</h3>
      <div class="h-72"><canvas id="chartKategori"></canvas></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Top 10 Stok Terbanyak</h3>
      <div class="h-72"><canvas id="chartTopStok"></canvas></div>
    </div>
  </div>

  {{-- FILTER + TABEL --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 flex flex-wrap gap-3 items-center">
      <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang..."
        class="text-xs border border-gray-200 rounded-lg px-3 py-2 flex-1 min-w-[150px]">
      <select wire:model.live="filterKategori" class="text-xs border border-gray-200 rounded-lg px-3 py-2">
        <option value="">Semua Kategori</option>
        @foreach ($kategoriList as $kat)
          <option value="{{ $kat }}">{{ $kat }}</option>
        @endforeach
      </select>
      <select wire:model.live="filterStatus" class="text-xs border border-gray-200 rounded-lg px-3 py-2">
        <option value="">Semua Status</option>
        <option value="aman">Aman</option>
        <option value="menipis">Menipis</option>
      </select>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Barang</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</th>
            <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Stok</th>
            <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Min</th>
            <th class="px-5 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @foreach ($tabelStok as $s)
            <tr class="hover:bg-gray-50">
              <td class="px-5 py-3">
                <p class="text-sm font-semibold text-gray-900">{{ $s->barang->nama_barang ?? '-' }}</p>
                <p class="text-xs text-gray-400">{{ $s->barang->kode_barang ?? '-' }}</p>
              </td>
              <td class="px-5 py-3 text-xs text-gray-500">{{ $s->barang->kategori ?? '-' }}</td>
              <td class="px-5 py-3 text-sm text-right font-bold text-gray-700">{{ number_format($s->jumlah_stok) }}
              </td>
              <td class="px-5 py-3 text-sm text-right text-gray-500">{{ $s->stok_minimum }}</td>
              <td class="px-5 py-3 text-center">
                @if ($s->status == 'Menipis')
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-700 border border-red-100 rounded-lg text-xs font-semibold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                    </svg>
                    Menipis
                  </span>
                @else
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Aman
                  </span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $tabelStok->links() }}</div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.3/dist/chart.umd.min.js"></script>
<script>
  (function() {
    var _charts = {};

    function renderCharts() {
      var kategori = @json($perKategori);
      var top = @json($topStok);

      var c1 = document.getElementById('chartKategori');
      var c2 = document.getElementById('chartTopStok');

      // Pie Chart - Stok per Kategori
      if (c1 && kategori.labels.length > 0) {
        if (_charts.kategori) {
          _charts.kategori.destroy();
          _charts.kategori = null;
        }
        _charts.kategori = new Chart(c1, {
          type: 'pie',
          data: {
            labels: kategori.labels,
            datasets: [{
              data: kategori.values,
              backgroundColor: ['#3B82F6', '#F59E0B', '#10B981', '#EF4444', '#8B5CF6', '#EC4899']
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
                  pointStyleWidth: 10,
                  font: {
                    size: 11
                  }
                }
              }
            }
          }
        });
      }

      // Horizontal Bar - Top 10 Stok Terbanyak
      if (c2 && top.labels.length > 0) {
        if (_charts.top) {
          _charts.top.destroy();
          _charts.top = null;
        }
        _charts.top = new Chart(c2, {
          type: 'bar',
          data: {
            labels: top.labels,
            datasets: [{
              label: 'Jumlah Stok',
              data: top.values,
              backgroundColor: top.values.map(function(_, i) {
                var colors = ['#3B82F6', '#2563EB', '#1D4ED8', '#1E40AF', '#1E3A8A', '#312E81', '#3730A3',
                  '#4338CA', '#4F46E5', '#6366F1'
                ];
                return colors[i] || '#3B82F6';
              }),
              borderRadius: 6,
              borderSkipped: false
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
                ticks: {
                  font: {
                    size: 10
                  }
                },
                grid: {
                  display: true,
                  color: '#f1f5f9'
                }
              },
              y: {
                ticks: {
                  font: {
                    size: 10
                  },
                  callback: function(value) {
                    var label = this.getLabelForValue(value);
                    return label.length > 30 ? label.substr(0, 30) + '...' : label;
                  }
                },
                grid: {
                  display: false
                }
              }
            }
          }
        });
      }
    }

    // Render pertama kali setelah DOM siap
    document.addEventListener('DOMContentLoaded', function() {
      renderCharts();
    });

    // Re-render setiap kali Livewire selesai update (Livewire v3)
    document.addEventListener('livewire:navigated', function() {
      setTimeout(renderCharts, 100);
    });

    document.addEventListener('livewire:update', function() {
      setTimeout(renderCharts, 200);
    });
  })();
</script>
