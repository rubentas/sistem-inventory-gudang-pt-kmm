<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Barang Keluar</h1>
      <p class="text-gray-500 text-sm">Mengelola pengeluaran barang berdasarkan order sales</p>
    </div>
    <button wire:click="bukaTambah"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
      + Proses Barang Keluar
    </button>
  </div>

  <!-- Success/Error Message -->
  @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
      {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
      {{ session('error') }}
    </div>
  @endif

  <!-- Form Tambah -->
  @if ($showForm)
    <div class="bg-white rounded-lg shadow p-5 mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Proses Barang Keluar</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Order Sales <span
              class="text-red-500">*</span></label>
          <select wire:model="id_order"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Order --</option>
            @foreach ($orders as $order)
              <option value="{{ $order->id_order }}">
                {{ $order->tanggal_order->format('d/m/Y') }} -
                {{ $order->barang->nama_barang ?? '-' }} -
                {{ number_format($order->jumlah) }} pcs -
                {{ $order->wilayah->nama_wilayah ?? '-' }}
              </option>
            @endforeach
          </select>
          @error('id_order')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        @if ($nama_barang_display)
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
            <input type="text" value="{{ $nama_barang_display }}" readonly disabled
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah</label>
            <input type="text"
              value="{{ $nama_wilayah_display ?? ($orders->firstWhere('id_order', $id_order)?->wilayah?->nama_wilayah ?? '-') }}"
              readonly disabled class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Order</label>
            <input type="text" value="{{ number_format($jumlah) }} {{ $satuan_display }}" readonly disabled
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100">
          </div>
        @endif

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Keluar <span
              class="text-red-500">*</span></label>
          <input type="date" wire:model="tanggal_keluar"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('tanggal_keluar')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
          <textarea wire:model="keterangan" rows="2" placeholder="Catatan tambahan"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
      </div>
      <div class="flex justify-end gap-3 mt-4">
        <button wire:click="batal"
          class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm transition">
          Batal
        </button>
        <button wire:click="simpan"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
          Proses & Kurangi Stok
        </button>
      </div>
    </div>
  @endif

  <!-- Search -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex gap-4">
      <div class="flex-1">
        <input type="text" wire:model.live="search" placeholder="Cari barang atau wilayah..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-blue-600 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Tanggal Keluar</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Order ID</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Wilayah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Jumlah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Diproses Oleh</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($barangKeluar as $item)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_keluar->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">#{{ $item->id_order }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->wilayah->nama_wilayah ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($item->jumlah) }}
                {{ $item->barang->satuan ?? 'pcs' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->user->nama ?? '-' }}</td>
              <td class="px-4 py-3 text-sm">
                <button wire:click="hapus({{ $item->id_keluar }})"
                  wire:confirm="Yakin hapus data ini? Stok akan bertambah kembali."
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                  Hapus
                </button>
              </td>
            </tr>
            @emkspty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data barang keluar</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $barangKeluar->links() }}
    </div>
  </div>
</div>
