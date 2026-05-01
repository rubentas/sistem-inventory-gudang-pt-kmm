<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Stock Opname</h1>
      <p class="text-gray-500 text-sm">Mencatat hasil pengecekan stok fisik setiap 3 bulan</p>
    </div>
    <button wire:click="bukaTambah"
      class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
      + Tambah Opname
    </button>
  </div>

  <!-- Success Message -->
  @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
      {{ session('success') }}
    </div>
  @endif

  <!-- Form Tambah/Edit -->
  @if ($showForm)
    <div class="bg-white rounded-lg shadow p-5 mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Stock Opname</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Barang <span class="text-red-500">*</span></label>
          <select wire:model="id_barang"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
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
          <label class="block text-sm font-medium text-gray-700 mb-1">Stok Sistem</label>
          <input type="text" value="{{ number_format($stok_sistem) }}" readonly disabled
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Stok Fisik <span
              class="text-red-500">*</span></label>
          <input type="number" wire:model="stok_fisik" placeholder="Masukkan hasil hitung fisik"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
          @error('stok_fisik')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Selisih</label>
          <input type="text" value="{{ number_format($selisih) }}" readonly disabled
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100 {{ $selisih != 0 ? 'text-red-600 font-semibold' : '' }}">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Opname <span
              class="text-red-500">*</span></label>
          <input type="date" wire:model="tanggal_opname"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
          @error('tanggal_opname')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
          <textarea wire:model="keterangan" rows="2" placeholder="Catatan tambahan..."
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
        </div>
      </div>
      <div class="flex justify-end gap-3 mt-4">
        <button wire:click="batal"
          class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm transition">
          Batal
        </button>
        <button wire:click="simpan"
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
          Simpan
        </button>
      </div>
    </div>
  @endif

  <!-- Search -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex gap-4">
      <div class="flex-1">
        <input type="text" wire:model.live="search" placeholder="Cari barang..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
      </div>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-green-600 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Tanggal</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Sistem</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Fisik</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Selisih</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Input Oleh</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($opnames as $opname)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $opname->tanggal_opname->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $opname->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($opname->stok_sistem) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($opname->stok_fisik) }}</td>
              <td
                class="px-4 py-3 text-sm {{ $opname->selisih != 0 ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                {{ number_format($opname->selisih) }}
              </td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $opname->user->nama ?? '-' }}</td>
              <td class="px-4 py-3 text-sm">
                <button wire:click="hapus({{ $opname->id_opname }})" wire:confirm="Yakin hapus data opname ini?"
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                  Hapus
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data stock opname</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $opnames->links() }}
    </div>
  </div>
</div>
