<div class="space-y-5">
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Supplier</h1>
          <p class="text-sm text-gray-400 mt-0.5">Daftar supplier & total barang masuk</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('laporan.supplier.excel') }}"
          class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-green-600/25"><svg
            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>Excel</a>
        <a href="{{ route('laporan.supplier.pdf') }}" target="_blank"
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
      <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center"><svg
          class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Total Supplier</p>
        <p class="text-xl font-bold text-indigo-600">{{ $ringkasan['total_supplier'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center"><svg class="w-5 h-5 text-blue-600"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase font-semibold">Total Barang Masuk</p>
        <p class="text-xl font-bold text-blue-600">{{ $ringkasan['total_barang'] }}</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
      <h3 class="text-sm font-bold text-gray-900 mb-4">Barang Masuk per Supplier</h3>
      <div class="h-72"><canvas id="chartSupplier"></canvas></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
      <div class="px-5 py-4 border-b">
        <h3 class="text-sm font-bold text-gray-900">Daftar Supplier</h3>
      </div>
      <div class="overflow-x-auto max-h-80 overflow-y-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50">
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Kode</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Nama</th>
              <th class="px-5 py-3 text-left text-xs font-bold text-gray-400">Alamat</th>
              <th class="px-5 py-3 text-right text-xs font-bold text-gray-400">Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tabelData as $d)
              <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-xs font-mono">{{ $d->kode_supplier }}</td>
                <td class="px-5 py-3 text-sm font-semibold">{{ $d->nama_supplier }}</td>
                <td class="px-5 py-3 text-xs text-gray-500">{{ $d->alamat }}</td>
                <td class="px-5 py-3 text-sm text-right font-bold">{{ number_format($d->total_masuk ?? 0) }}</td>
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
      const d = @json($perSupplier);
      const x = document.getElementById('chartSupplier');
      if (!x) return;
      if (c) c.destroy();
      c = new Chart(x, {
        type: 'doughnut',
        data: {
          labels: d.labels,
          datasets: [{
            data: d.values,
            backgroundColor: ['#6366F1', '#8B5CF6', '#EC4899', '#F59E0B', '#10B981', '#3B82F6', '#EF4444']
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
