<div class="space-y-5">

  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-teal-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Laporan Inventory</h1>
          <p class="text-sm text-gray-400 mt-0.5">Ringkasan stok fisik vs sistem per periode</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('laporan.inventory.excel', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
          class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg"><svg
            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>Excel</a>
        <a href="{{ route('laporan.inventory.pdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir, 'filterSelisih' => $filterSelisih]) }}"
          target="_blank"
          class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg"><svg
            class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>PDF</a>
      </div>
    </div>
  </div>

  {{-- FILTER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="px-4 sm:px-5 py-3 flex items-center gap-3 flex-wrap">
      <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Periode:</span>
      <input type="date" wire:model.live="tanggalAwal"
        class="text-sm border border-gray-200 rounded-xl px-3 py-2 font-semibold bg-white focus:border-teal-500 transition outline-none cursor-pointer">
      <span class="text-xs text-gray-400">s/d</span>
      <input type="date" wire:model.live="tanggalAkhir"
        class="text-sm border border-gray-200 rounded-xl px-3 py-2 font-semibold bg-white focus:border-teal-500 transition outline-none cursor-pointer">
      <select wire:model.live="filterSelisih"
        class="text-sm border border-gray-200 rounded-xl px-3 py-2 font-semibold bg-white focus:border-teal-500 transition outline-none cursor-pointer">
        <option value="">Semua Selisih</option>
        <option value="negatif">🔻 Negatif (Kurang)</option>
        <option value="nol">✅ Pas (Nol)</option>
        <option value="positif">🔺 Positif (Lebih)</option>
      </select>
      <div
        class="flex items-center bg-gray-50 border border-gray-200 rounded-xl focus-within:border-teal-400 transition">
        <div class="pl-3 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg></div>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang..."
          class="flex-1 h-10 px-3 text-sm bg-transparent focus:outline-none text-gray-900 w-36">
      </div>
    </div>
  </div>

  {{-- STATS --}}
  <div class="grid grid-cols-3 gap-4">
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center shrink-0"><svg
          class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total Data</p>
        <p class="text-xl font-bold text-teal-600">{{ $ringkasan['total_data'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0"><svg
          class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total Selisih</p>
        <p class="text-xl font-bold text-amber-600">{{ $ringkasan['total_selisih'] }}</p>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
      <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0"><svg
          class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg></div>
      <div>
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Rata Selisih</p>
        <p class="text-xl font-bold text-blue-600">{{ $ringkasan['rata_selisih'] }}</p>
      </div>
    </div>
  </div>

  {{-- CHART --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
    <h3 class="text-sm font-bold text-gray-900 mb-4">Grafik Inventory per Tanggal</h3>
    <div class="h-64"><canvas id="chartInventory"></canvas></div>
  </div>

  {{-- TABLE --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
      <h3 class="text-sm font-bold text-gray-900">Detail Inventory</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full min-w-[900px]">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Tanggal</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Barang</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Awal</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Masuk</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Keluar</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Sistem</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Fisik</th>
            <th class="px-5 py-4 text-right text-xs font-bold text-gray-400 uppercase">Selisih</th>
            <th class="px-5 py-4 text-left text-xs font-bold text-gray-400 uppercase">Input Oleh</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($tabelData as $d)
            <tr class="hover:bg-gray-50">
              <td class="px-5 py-4 text-sm">{{ $d->tanggal->format('d/m/Y') }}</td>
              <td class="px-5 py-4 text-sm font-semibold">{{ $d->barang->nama_barang ?? '-' }}</td>
              <td class="px-5 py-4 text-sm text-right">{{ number_format($d->stok_awal) }}</td>
              <td class="px-5 py-4 text-sm text-right text-emerald-600">+{{ number_format($d->barang_masuk) }}</td>
              <td class="px-5 py-4 text-sm text-right text-red-500">-{{ number_format($d->barang_keluar) }}</td>
              <td class="px-5 py-4 text-sm text-right font-semibold">{{ number_format($d->stok_sistem) }}</td>
              <td class="px-5 py-4 text-sm text-right font-bold">{{ number_format($d->stok_fisik) }}</td>
              <td
                class="px-5 py-4 text-sm text-right font-bold {{ $d->selisih < 0 ? 'text-red-600' : ($d->selisih > 0 ? 'text-emerald-600' : 'text-gray-600') }}">
                {{ $d->selisih >= 0 ? '+' . $d->selisih : $d->selisih }}</td>
              <td class="px-5 py-4 text-sm text-gray-500">{{ $d->user->nama ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-6 py-20 text-center text-gray-400">Belum ada data inventory</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($tabelData->hasPages())
      <div class="px-5 py-3 border-t border-gray-100">{{ $tabelData->links() }}</div>
    @endif
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.3/dist/chart.umd.min.js"></script>
<script>
  document.addEventListener('livewire:initialized', () => {
    let chart = null;

    function render() {
      const d = @json($chartData),
        c = document.getElementById('chartInventory');
      if (!c) return;
      if (chart) chart.destroy();
      chart = new Chart(c, {
        type: 'bar',
        data: {
          labels: d.labels,
          datasets: [{
            data: d.values,
            backgroundColor: '#0D9488',
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
      setTimeout(() => window.dispatchEvent(new Event('reapp/Livewire/Admin/Laporan/BarangMasuk.phpsize')), 150);
    }
    render();
    Livewire.hook('morph.updated', () => {
      setTimeout(render, 300);
      setTimeout(() => window.dispatchEvent(new Event('resize')), 400);
    });
    window.addEventListener('resize', () => setTimeout(render, 200));
  });
</script>
