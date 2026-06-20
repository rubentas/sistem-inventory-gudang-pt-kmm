<di v class="space-y-5">
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Barang Expired</h1>
          <p class="text-sm text-gray-400 mt-0.5">Monitoring masa kadaluarsa barang</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('laporan.expired.excel', ['status' => $filterStatus]) }}"
          class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-green-600/25"><svg
            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>Excel</a>
        <a href="{{ route('laporan.expired.pdf', ['status' => $filterStatus]) }}" target="_blank"
          class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-red-600/25"><svg
            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>PDF</a>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center"><svg class="w-5 h-5 text-gray-600"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Total Tercatat</p>
        <p class="text-xl font-bold text-gray-700">{{ $ringkasan['totalTercatat'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center"><svg class="w-5 h-5 text-red-600"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Expired</p>
        <p class="text-xl font-bold text-red-600">{{ $ringkasan['totalExpired'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center"><svg
          class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Hampir Expired</p>
        <p class="text-xl font-bold text-amber-600">{{ $ringkasan['totalHampirExpired'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center"><svg
          class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Aman</p>
        <p class="text-xl font-bold text-emerald-600">{{ $ringkasan['totalAman'] }}</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Status Expired</h3>
      <div class="h-72"><canvas id="chartExpired"></canvas></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b flex gap-3 items-center">
        <h3 class="text-sm font-bold text-gray-900">Daftar Barang</h3>
        <select wire:model.live="filterStatus" class="text-xs border rounded-lg px-2 py-1.5 ml-auto">
          <option value="">Semua</option>
          <option value="expired">Expired</option>
          <option value="hampir_expired">Hampir Expired</option>
          <option value="aman">Aman</option>
        </select>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari..."
          class="text-xs border rounded-lg px-2 py-1.5">
      </div>
      <div class="overflow-x-auto max-h-96 overflow-y-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Barang</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Tgl Masuk</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Tgl Expired</th>
              <th class="px-5 py-3 text-center text-xs font-bold text-gray-400">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tabelData as $d)
              <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-sm font-semibold">{{ $d->barang->nama_barang ?? '-' }}</td>
                <td class="px-5 py-3 text-sm">{{ $d->tanggal_masuk->format('d/m/Y') }}</td>
                <td class="px-5 py-3 text-sm font-bold {{ $d->status_expired == 'expired' ? 'text-red-600' : '' }}">
                  {{ $d->tanggal_expired->format('d/m/Y') }}</td>
                <td class="px-5 py-3 text-center">
                  @if ($d->status_expired == 'expired')
                    <span class="px-2 py-0.5 bg-red-50 text-red-700 text-xs font-semibold rounded-lg">Expired</span>
                  @elseif($d->status_expired == 'hampir_expired')
                    <span
                    class="px-2 py-0.5 bg-amber-50 text-amber-700 text-xs font-semibold rounded-lg">Hampir</span>@else<span
                      class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg">Aman</span>
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
</di>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.3/dist/chart.umd.min.js"></script>
<script>
  document.addEventListener('livewire:initialized', () => {
    let c = null;

    function r() {
      const d = @json($expiredChart);
      const x = document.getElementById('chartExpired');
      if (!x) return;
      if (c) c.destroy();
      c = new Chart(x, {
        type: 'doughnut',
        data: {
          labels: d.labels,
          datasets: [{
            data: d.values,
            backgroundColor: ['#EF4444', '#F59E0B', '#10B981']
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
