<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Order Sales</h1>
      <p class="text-gray-500 text-sm">Buat dan kelola pesanan Anda</p>
    </div>
    <button wire:click="bukaTambah"
      class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm transition">
      + Buat Order Baru
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

  <!-- Form Tambah/Edit -->
  @if ($showForm)
    <div class="bg-white rounded-lg shadow p-5 mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $editId ? 'Edit' : 'Buat' }} Order Sales</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Barang <span class="text-red-500">*</span></label>
          <select wire:model="id_barang"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option value="">-- Pilih Barang --</option>
            @foreach ($barangs as $barang)
              <option value="{{ $barang->id_barang }}">{{ $barang->kode_barang }} - {{ $barang->nama_barang }}</option>
            @endforeach
          </select>
          @error('id_barang')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Wilayah <span
              class="text-red-500">*</span></label>
          <select wire:model="id_wilayah"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            <option value="">-- Pilih Wilayah --</option>
            @foreach ($wilayahs as $wilayah)
              <option value="{{ $wilayah->id_wilayah }}">{{ $wilayah->nama_wilayah }}</option>
            @endforeach
          </select>
          @error('id_wilayah')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
          <input type="number" wire:model="jumlah" placeholder="Jumlah pesanan"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
          @error('jumlah')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Order <span
              class="text-red-500">*</span></label>
          <input type="date" wire:model="tanggal_order"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
          @error('tanggal_order')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
          <textarea wire:model="keterangan" rows="2" placeholder="Catatan tambahan (opsional)"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
        </div>
      </div>
      <div class="flex justify-end gap-3 mt-4">
        <button wire:click="batal"
          class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm transition">
          Batal
        </button>
        <button wire:click="simpan"
          class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm transition">
          Simpan Order
        </button>
      </div>
    </div>
  @endif

  <!-- Filter -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Cari Order</label>
        <input type="text" wire:model.live="search" placeholder="Cari nama barang..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Filter Status</label>
        <select wire:model.live="filterStatus"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="diproses">Diproses</option>
          <option value="selesai">Selesai</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-orange-600 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Tanggal</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Wilayah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Jumlah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Status</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($orders as $order)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $order->tanggal_order->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $order->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $order->wilayah->nama_wilayah ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($order->jumlah) }}
                {{ $order->barang->satuan ?? 'pcs' }}</td>
              <td class="px-4 py-3 text-sm">
                @if ($order->status == 'pending')
                  <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
                @elseif($order->status == 'diproses')
                  <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Diproses</span>
                @else
                  <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                @endif
              </td>
              <td class="px-4 py-3 text-sm">
                @if ($order->status == 'pending')
                  <button wire:click="hapus({{ $order->id_order }})" wire:confirm="Yakin hapus order ini?"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                    Hapus
                  </button>
                @else
                  <span class="text-gray-400 text-xs">Tidak bisa dihapus</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada order yang dibuat</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $orders->links() }}
    </div>
  </div>
</div>
