<div x-data="returManager()" x-init="init()" class="space-y-5">
  {{-- HEADER --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 sm:px-8 sm:py-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
          </svg>
        </div>
        <div>
          <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Retur Penjualan</h1>
          <p class="text-sm text-gray-400 mt-0.5">Input & kelola retur penjualan</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('admin.retur-barang.pdf', ['search' => $search, 'filterStatus' => $filterStatus]) }}"
          target="_blank"
          class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-red-600/25">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>PDF
        </a>
        <button @click="modalOpen = true; $wire.resetForm(); $wire.editMode = false;"
          class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-600/25">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Retur
        </button>
      </div>
    </div>
  </div>

  {{-- TABEL --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100">
      <div class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[180px]">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z" />
          </svg>
          <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari no retur..."
            class="w-full text-xs border border-gray-200 rounded-lg pl-8 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <select wire:model.live="filterStatus"
          class="text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
          <option value="">Semua Status</option>
          <option value="Pengajuan">Pengajuan</option>
          <option value="Cek Gudang">Cek Gudang</option>
          <option value="Cek Kasir">Cek Kasir</option>
          <option value="Disetujui">Disetujui</option>
          <option value="Selesai">Selesai</option>
        </select>
        @if ($search || $filterStatus)
          <button wire:click="resetFilters"
            class="inline-flex items-center gap-1 text-xs text-red-500 hover:text-red-700 font-semibold px-3 py-2 rounded-lg hover:bg-red-50 transition">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Reset
          </button>
        @endif
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100">
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No Retur</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Order</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Barang</th>
            <th class="px-4 py-3 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Kondisi</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Alasan</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Diinput Oleh</th>
            <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-4 py-3 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse ($returs as $r)
            @php $det = $r->detailRetur->first(); @endphp
            <tr class="hover:bg-blue-50/40 transition-colors">
              <td class="px-4 py-3 text-sm font-mono font-semibold text-blue-600">{{ $r->no_retur }}</td>
              <td class="px-4 py-3">
                <p class="text-sm font-medium text-gray-800">{{ $r->order->no_invoice ?? $r->id_order }}</p>
              </td>
              <td class="px-4 py-3 text-xs text-gray-500">{{ $r->tanggal_retur->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm font-bold text-gray-900">{{ $det?->barang?->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-right font-bold text-gray-700">{{ $det?->jumlah_retur ?? 0 }}</td>
              <td class="px-4 py-3">
                @if (($det?->kondisi_barang ?? '') === 'Bagus')
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Bagus
                  </span>
                @else
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-700 border border-red-100 rounded-lg text-xs font-semibold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Rusak
                  </span>
                @endif
              </td>
              <td class="px-4 py-3 text-xs text-gray-600">{{ $det?->alasan ?? '-' }}</td>
              <td class="px-4 py-3 text-xs text-gray-600">{{ $r->user->nama ?? 'Admin' }}</td>
              <td class="px-4 py-3">
                @if ($r->status === 'Selesai')
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-semibold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Selesai
                  </span>
                @elseif ($r->status === 'Pengajuan')
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-50 text-yellow-700 border border-yellow-100 rounded-lg text-xs font-semibold">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pengajuan
                  </span>
                @else
                  <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-xs font-semibold">
                    {{ $r->status }}
                  </span>
                @endif
              </td>
              <td class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-1">
                  @if ($r->status !== 'Selesai' && $r->status !== 'Ditolak')
                    <button wire:click="editRetur({{ $r->id_retur }})"
                      class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition"
                      title="Edit">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button wire:click="approve({{ $r->id_retur }})"
                      class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition shadow-sm shadow-blue-600/25">
                      {{ match ($r->status) {
                          'Pengajuan' => 'Cek Gudang',
                          'Cek Gudang' => 'Cek Kasir',
                          'Cek Kasir' => 'Setujui',
                          'Disetujui' => 'Selesaikan',
                          default => 'Proses',
                      } }}
                    </button>
                  @else
                    <span class="text-xs text-gray-300">—</span>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="px-6 py-20 text-center">
                <div class="flex flex-col items-center gap-3">
                  <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                    </svg>
                  </div>
                  <p class="text-sm font-medium text-gray-400">Belum ada data retur</p>
                  <p class="text-xs text-gray-300">Klik tombol Tambah Retur untuk memulai</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $returs->links() }}</div>
  </div>

  {{-- MODAL --}}
  <template x-teleport="body">
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4"
      @keydown.escape.window="modalOpen = false">
      <div @click="modalOpen = false" class="fixed inset-0 bg-black/50 backdrop-blur-md z-40"></div>
      <div @click.stop
        class="relative z-50 w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">

        {{-- Modal Header --}}
        <div class="bg-blue-600 px-6 py-5 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3" />
              </svg>
            </div>
            <h2 class="text-base font-bold text-white"
              x-text="$wire.editMode ? 'Edit Retur Penjualan' : 'Form Retur Penjualan'"></h2>
          </div>
          <button @click="modalOpen = false"
            class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="px-6 py-5 space-y-4">
          {{-- Order Asal --}}
          <div>
            <label class="block text-sm font-bold text-gray-900 mb-1.5">
              Order Asal <span class="text-red-500">*</span>
            </label>
            @if ($editMode)
              <p class="text-sm font-semibold text-gray-900 py-3">{{ $no_retur }}</p>
            @else
              <select wire:model.live="id_order"
                class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <option value="">-- Pilih Order --</option>
                @foreach ($orderList as $o)
                  <option value="{{ $o['id_order'] }}">
                    {{ $o['no_invoice'] ?? 'ORDER-' . $o['id_order'] }} | {{ $o['barang']['nama_barang'] ?? '' }}
                    ({{ $o['jumlah'] }})
                  </option>
                @endforeach
              </select>
            @endif
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="bg-gray-50 rounded-xl p-3">
              <label class="block text-xs font-bold text-gray-400 mb-1">Customer / Toko</label>
              <p class="text-sm font-semibold text-gray-900">{{ $nama_toko ?? '-' }}</p>
            </div>
            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1.5">
                Tanggal Retur <span class="text-red-500">*</span>
              </label>
              @if ($editMode)
                <p class="text-sm font-semibold text-gray-900 py-2.5">{{ $tanggal_retur }}</p>
              @else
                <input type="date" wire:model="tanggal_retur"
                  class="w-full rounded-xl border-2 border-gray-200 px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
              @endif
            </div>
          </div>

          {{-- Detail Barang --}}
          @if (count($detail) > 0)
            @php $d = $detail[0]; @endphp
            <div class="border-t border-gray-100 pt-4">
              <h3 class="text-sm font-bold text-gray-900 mb-3">Detail Barang Retur</h3>
              <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 space-y-3">
                <div>
                  <label class="block text-xs font-bold text-gray-500 mb-1">Barang</label>
                  <p class="text-sm font-bold text-gray-900">{{ $d['nama_barang'] }}</p>
                </div>

                <div class="grid grid-cols-3 gap-2">
                  <div class="bg-white rounded-lg p-2.5 border border-gray-100">
                    <label class="text-xs text-gray-400 font-medium">Jumlah Order</label>
                    <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $d['jumlah_order'] ?? 0 }}</p>
                  </div>
                  <div>
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Jumlah Retur <span
                        class="text-red-500">*</span></label>
                    <input type="number" wire:model.live="detail.0.jumlah_retur" min="1"
                      class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    @error('detail.0.jumlah_retur')
                      <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                  <div>
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Kondisi <span
                        class="text-red-500">*</span></label>
                    <select wire:model.live="detail.0.kondisi_barang"
                      class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                      <option value="Bagus">Bagus</option>
                      <option value="Rusak">Rusak</option>
                    </select>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                  <div>
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Harga Satuan</label>
                    <input type="number" wire:model.live="detail.0.harga_satuan"
                      class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                  </div>
                  <div class="bg-white rounded-lg p-2.5 border border-gray-100">
                    <label class="text-xs text-gray-400 font-medium">Subtotal</label>
                    <p class="text-sm font-bold text-blue-600 mt-0.5">Rp
                      {{ number_format($d['subtotal_retur'] ?? 0, 0, ',', '.') }}</p>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                  <div>
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Alasan <span
                        class="text-red-500">*</span></label>
                    <select wire:model.live="detail.0.alasan"
                      class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                      <option value="">-- Pilih --</option>
                      <option value="Rusak">Rusak</option>
                      <option value="Kadaluarsa">Kadaluarsa</option>
                      <option value="Salah Kirim">Salah Kirim</option>
                      <option value="Batal Beli">Batal Beli</option>
                      <option value="Lainnya">Lainnya</option>
                    </select>
                    @error('detail.0.alasan')
                      <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                  <div>
                    <label class="text-xs font-bold text-gray-500 mb-1 block">Tujuan <span
                        class="text-red-500">*</span></label>
                    <select wire:model.live="detail.0.tujuan"
                      class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                      <option value="Stok Utama">Stok Utama</option>
                      <option value="Dimusnahkan">Dimusnahkan</option>
                      <option value="Gudang Pusat">Gudang Pusat</option>
                      <option value="Supplier">Supplier</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label class="text-xs font-bold text-gray-500 mb-1 block">Keterangan</label>
                  <input type="text" wire:model.live="detail.0.keterangan"
                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Opsional">
                </div>
              </div>
            </div>
          @endif
        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-end gap-2">
          <button @click="modalOpen = false"
            class="px-5 py-2.5 rounded-xl bg-white border-2 border-gray-200 hover:border-gray-300 text-sm font-bold text-gray-700 transition">
            Batal
          </button>
          <button wire:click="simpan"
            class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold transition shadow-lg shadow-blue-600/25"
            x-text="$wire.editMode ? 'Simpan Perubahan' : 'Simpan Retur'">
          </button>
        </div>
      </div>
    </div>
  </template>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function returManager() {
    return {
      modalOpen: false,
      init() {
        window.addEventListener('dataSaved', (e) => {
          this.modalOpen = false;
          Swal.fire({
            title: e.detail.title || 'Berhasil!',
            text: e.detail.message || 'Data berhasil disimpan.',
            icon: e.detail.type || 'success',
            confirmButtonColor: '#2563EB',
            customClass: {
              popup: 'rounded-2xl',
              confirmButton: 'rounded-xl text-sm font-bold px-5 py-2.5'
            },
            toast: false,
            position: 'center',
            showConfirmButton: true,
          });
        });

        window.addEventListener('openModal', () => {
          this.modalOpen = true;
        });
      }
    };
  }
</script>
