<div x-data="{ modalOpen: false }" class="space-y-5">
  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-orange-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Pengajuan Retur</h1>
          <p class="text-sm text-gray-400 mt-0.5">Ajukan retur penjualan dari toko</p>
        </div>
      </div>
      <button @click="modalOpen = true"
        class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-orange-600/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
        </svg>
        Ajukan Retur
      </button>
    </div>
  </div>

  {{-- TABEL PENGAJUAN --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
      <h3 class="text-sm font-bold text-gray-900">Riwayat Pengajuan Retur</h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">No Retur</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">Barang</th>
            <th class="px-4 py-3 text-right text-xs font-bold text-gray-400 uppercase">Jumlah</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse ($pengajuan as $p)
            @php $d = $p->detailRetur->first(); @endphp
            <tr class="hover:bg-orange-50/40">
              <td class="px-4 py-3 text-sm font-mono text-orange-600">{{ $p->no_retur }}</td>
              <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $d?->barang?->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-right font-bold text-gray-700">{{ $d?->jumlah_retur ?? 0 }}</td>
              <td class="px-4 py-3">
                @if ($p->status === 'Selesai')
                  <span
                    class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold">Selesai</span>
                @else
                  <span
                    class="px-2.5 py-1 bg-yellow-50 text-yellow-700 border border-yellow-100 rounded-lg text-xs font-semibold">{{ $p->status }}</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-20 text-center text-gray-400">Belum ada pengajuan retur.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- MODAL --}}
  <template x-teleport="body">
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4"
      @keydown.escape.window="modalOpen = false">
      <div @click="modalOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-md z-40"></div>
      <div @click.stop
        class="relative z-50 w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">

        <div class="bg-orange-600 px-6 py-5 flex items-center justify-between">
          <h2 class="text-base font-bold text-white">Form Pengajuan Retur</h2>
          <button @click="modalOpen = false"
            class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="px-6 py-5 space-y-4">
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">Order Asal <span
                class="text-red-500">*</span></label>
            <select wire:model.live="id_order"
              class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
              <option value="">-- Pilih Order --</option>
              @foreach ($orderList as $o)
                <option value="{{ $o['id_order'] }}">
                  {{ $o['no_invoice'] ?? 'ORDER-' . $o['id_order'] }} | {{ $o['barang']['nama_barang'] ?? '' }}
                  ({{ $o['jumlah'] }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="bg-gray-50 rounded-xl p-3">
              <label class="block text-xs font-bold text-gray-400 mb-1">Customer / Toko</label>
              <p class="text-sm font-semibold text-gray-900" x-text="$wire.nama_toko || '-'"></p>
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Retur <span
                  class="text-red-500">*</span></label>
              <input type="date" wire:model="tanggal_retur"
                class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
            </div>
          </div>

          @if (!empty($detail) && !empty($detail[0]))
            @php $d = $detail[0]; @endphp
            <div class="border-t border-gray-100 pt-4 space-y-3">
              <h3 class="text-sm font-bold text-gray-900">Detail Barang Retur</h3>
              <div class="bg-orange-50/50 border border-orange-100 rounded-xl p-4 space-y-3">
                <div>
                  <label class="block text-xs font-bold text-gray-500 mb-1">Barang</label>
                  <p class="text-sm font-bold text-gray-900">{{ $d['nama_barang'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                  <div class="bg-white rounded-lg p-2.5 border border-gray-100">
                    <label class="text-xs text-gray-400">Jumlah Order</label>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $d['jumlah_order'] ?? 0 }}</p>
                  </div>
                  <div>
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Jumlah Retur <span
                        class="text-red-500">*</span></label>
                    <input type="number" wire:model.live="detail.0.jumlah_retur" min="1"
                      class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                  </div>
                </div>

                <div>
                  <label class="text-xs font-bold text-gray-500 mb-1 block">Alasan <span
                      class="text-red-500">*</span></label>
                  <select wire:model.live="detail.0.alasan"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                    <option value="">-- Pilih --</option>
                    <option value="Rusak">Rusak</option>
                    <option value="Kadaluarsa">Kadaluarsa</option>
                    <option value="Salah Kirim">Salah Kirim</option>
                    <option value="Batal Beli">Batal Beli</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </div>

                <div>
                  <label class="text-xs font-bold text-gray-500 mb-1 block">Keterangan</label>
                  <input type="text" wire:model.live="detail.0.keterangan"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                    placeholder="Opsional">
                </div>
              </div>
            </div>
          @endif
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-2">
          <button @click="modalOpen = false"
            class="px-5 py-2.5 rounded-xl bg-white border-2 border-gray-200 text-sm font-bold text-gray-700 transition">Batal</button>
          <button wire:click="ajukan"
            class="px-6 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold transition shadow-lg shadow-orange-600/25">
            Ajukan Retur
          </button>
        </div>
      </div>
    </div>
  </template>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('livewire:initialized', () => {
    window.addEventListener('dataSaved', (e) => {
      Swal.fire({
        title: e.detail.title || 'Berhasil!',
        text: e.detail.message || 'Data berhasil disimpan.',
        icon: e.detail.type || 'success',
        confirmButtonColor: '#EA580C',
        customClass: {
          popup: 'rounded-2xl',
          confirmButton: 'rounded-xl text-sm font-bold px-5 py-2.5'
        },
        toast: false,
        position: 'center',
        showConfirmButton: true,
      });
    });
  });
</script>
