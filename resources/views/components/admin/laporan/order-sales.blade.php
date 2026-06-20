<div class="space-y-5">
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900">Laporan Order Sales</h1>
          <p class="text-sm text-gray-400">Ringkasan order & status</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('laporan.order.excel', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir, 'status' => $filterStatus]) }}"
          class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg"><svg
            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>Excel</a>
        <a href="{{ route('laporan.order.pdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir, 'status' => $filterStatus]) }}"
          target="_blank"
          class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg"><svg
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
      class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'today' ? 'bg-amber-600 text-white' : 'bg-white border text-gray-600' }}">Hari
      Ini</button>
    <button wire:click="setFilter('week')"
      class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'week' ? 'bg-amber-600 text-white' : 'bg-white border text-gray-600' }}">7
      Hari</button>
    <button wire:click="setFilter('month')"
      class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $filterType === 'month' ? 'bg-amber-600 text-white' : 'bg-white border text-gray-600' }}">Bulan
      Ini</button>
    <span class="text-xs text-gray-400">|</span>
    <input type="date" wire:model.live="tanggalAwal" class="text-xs border rounded-lg px-2 py-1.5">
    <span class="text-xs text-gray-400">s/d</span>
    <input type="date" wire:model.live="tanggalAkhir" class="text-xs border rounded-lg px-2 py-1.5">
    <span class="text-xs text-gray-400">|</span>
    <select wire:model.live="filterStatus" class="text-xs border rounded-lg px-2 py-1.5">
      <option value="">Semua Status</option>
      <option value="pending">Pending</option>
      <option value="diproses">Diproses</option>
      <option value="selesai">Selesai</option>
    </select>
  </div>

  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4">
      <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center"><svg class="w-5 h-5 text-blue-600"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Total Order</p>
        <p class="text-xl font-bold text-blue-600">{{ $ringkasan['total'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4">
      <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center"><svg
          class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Pending</p>
        <p class="text-xl font-bold text-amber-600">{{ $ringkasan['pending'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4">
      <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center"><svg class="w-5 h-5 text-blue-600"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Diproses</p>
        <p class="text-xl font-bold text-blue-600">{{ $ringkasan['diproses'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4">
      <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center"><svg
          class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Selesai</p>
        <p class="text-xl font-bold text-emerald-600">{{ $ringkasan['selesai'] }}</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Status Order</h3>
      <div class="h-72"><canvas id="chartStatus"></canvas></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Order Per Bulan ({{ now()->year }})</h3>
      <div class="h-72"><canvas id="chartBulan"></canvas></div>
    </div>
  </div>

  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b">
      <h3 class="text-sm font-bold text-gray-900">Daftar Order</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50">
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Tanggal</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Barang</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Jumlah</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Total</th>
            <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Sales</th>
            <th class="px-5 py-3 text-center text-xs font-bold text-gray-400">Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($tabelData as $d)
            <tr class="hover:bg-gray-50">
              <td class="px-5 py-3 text-sm">{{ $d->tanggal_order->format('d/m/Y') }}</td>
              <td class="px-5 py-3 text-sm font-semibold">{{ $d->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-3 text-sm">{{ number_format($d->jumlah) }}</td>
              <td class="px-5 py-3 text-sm font-bold text-blue-600">Rp {{ number_format($d->subtotal) }}</td>
              <td class="px-5 py-3 text-sm">{{ $d->sales->nama_sales ?? '-' }}</td>
              <td class="px-5 py-3 text-center">
                @if ($d->status == 'pending')
                  <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-xs font-semibold rounded-lg">Pending</span>
                @elseif($d->status == 'diproses')
                  <span
                  class="px-2 py-0.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg">Diproses</span>@else<span
                    class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg">Selesai</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="px-5 py-3">{{ $tabelData->links() }}</div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.3/dist/chart.umd.min.js"></script>
<script>
  document.addEventListener('livewire:initialized', () => {
    let c1 = null,
      c2 = null;

    function r() {
      const s = @json($statusChart),
        b = @json($perBulan);
      const x = document.getElementById('chartStatus'),
        y = document.getElementById('chartBulan');
      if (x) {
        if (c1) c1.destroy();
        c1 = new Chart(x, {
          type: 'doughnut',
          data: {
            labels: s.labels,
            datasets: [{
              data: s.values,
              backgroundColor: ['#F59E0B', '#3B82F6', '#10B981']
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
      if (y) {
        if (c2) c2.destroy();
        c2 = new Chart(y, {
          type: 'line',
          data: {
            labels: b.labels,
            datasets: [{
              label: 'Order',
              data: b.values,
              borderColor: '#3B82F6',
              backgroundColor: 'rgba(59,130,246,0.1)',
              fill: true,
              tension: 0.3,
              pointRadius: 4
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
    }
    r();
    Livewire.hook('morph.updated', () => setTimeout(r, 200));
  });
</script>
