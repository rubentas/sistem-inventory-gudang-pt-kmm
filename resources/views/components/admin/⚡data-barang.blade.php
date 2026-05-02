<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Data Barang</h1>
      <p class="text-gray-500 text-sm">Mengelola master data barang</p>
    </div>
    <button wire:click="bukaTambah"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
      + Tambah Barang
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
      <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $isEdit ? 'Edit' : 'Tambah' }} Barang</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang <span
              class="text-red-500">*</span></label>
          <input type="text" wire:model="kode_barang" placeholder="Contoh: A281008S"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('kode_barang')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span
              class="text-red-500">*</span></label>
          <input type="text" wire:model="nama_barang" placeholder="Nama lengkap barang"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('nama_barang')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span
              class="text-red-500">*</span></label>
          <select wire:model="kategori"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Kategori --</option>
            <option value="Snack">Snack</option>
            <option value="Mie">Mie</option>
            <option value="Bumbu">Bumbu</option>
            <option value="Minuman">Minuman</option>
            <option value="Susu">Susu</option>
            <option value="Lainnya">Lainnya</option>
          </select>
          @error('kategori')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Satuan <span class="text-red-500">*</span></label>
          <select wire:model="satuan"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="Pcs">Pcs</option>
            <option value="Kg">Kg</option>
            <option value="Gram">Gram</option>
            <option value="Liter">Liter</option>
            <option value="Dus">Dus</option>
          </select>
          @error('satuan')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum <span
              class="text-red-500">*</span></label>
          <input type="number" wire:model="stok_minimum" placeholder="0"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('stok_minimum')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
          <p class="text-xs text-gray-400 mt-1">Jika stok <= nilai ini, status akan "Menipis" </p>
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
          <textarea wire:model="keterangan" rows="2" placeholder="Catatan tambahan..."
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
          Simpan
        </button>
      </div>
    </div>
  @endif

  <!-- Search -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex gap-4">
      <div class="flex-1">
        <input type="text" wire:model.live="search" placeholder="Cari kode atau nama barang..."
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
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kode</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Kategori</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Satuan</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Minimum</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Stok Saat Ini</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($barangs as $index => $barang)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $barangs->firstItem() + $index }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $barang->kode_barang }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $barang->nama_barang }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $barang->kategori ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $barang->satuan }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($barang->stok_minimum) }}</td>
              <td
                class="px-4 py-3 text-sm font-semibold {{ $barang->stok && $barang->stok->jumlah_stok <= $barang->stok_minimum ? 'text-red-600' : 'text-gray-800' }}">
                {{ $barang->stok ? number_format($barang->stok->jumlah_stok) : '0' }}
              </td>
              <td class="px-4 py-3 text-sm">
                <button wire:click="bukaEdit({{ $barang->id_barang }})"
                  class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs transition mr-2">
                  Edit
                </button>
                <button wire:click="hapus({{ $barang->id_barang }})" wire:confirm="Yakin hapus data barang ini?"
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                  Hapus
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data barang</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $barangs->links() }}
    </div>
  </div>
</div>
