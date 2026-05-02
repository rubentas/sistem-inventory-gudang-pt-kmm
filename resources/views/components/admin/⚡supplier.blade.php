<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Data Supplier</h1>
      <p class="text-gray-500 text-sm">Mengelola master data supplier/pemasok barang</p>
    </div>
    <button wire:click="bukaTambah"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
      + Tambah Supplier
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
      <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $isEdit ? 'Edit' : 'Tambah' }} Supplier</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kode Supplier <span
              class="text-red-500">*</span></label>
          <input type="text" wire:model="kode_supplier" placeholder="Contoh: SUP001"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('kode_supplier')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier <span
              class="text-red-500">*</span></label>
          <input type="text" wire:model="nama_supplier" placeholder="Nama lengkap supplier"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('nama_supplier')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
          <input type="text" wire:model="no_telp" placeholder="Contoh: 0511-123456"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('no_telp')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" wire:model="email" placeholder="supplier@email.com"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
          <textarea wire:model="alamat" rows="2" placeholder="Alamat lengkap supplier"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
          @error('alamat')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
          <textarea wire:model="keterangan" rows="2" placeholder="Catatan tambahan tentang supplier"
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
        <input type="text" wire:model.live="search" placeholder="Cari kode atau nama supplier..."
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
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Supplier</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No. Telepon</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Email</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($suppliers as $index => $supplier)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $suppliers->firstItem() + $index }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $supplier->kode_supplier }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $supplier->nama_supplier }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $supplier->no_telp ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $supplier->email ?? '-' }}</td>
              <td class="px-4 py-3 text-sm">
                <button wire:click="bukaEdit({{ $supplier->id_supplier }})"
                  class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs transition mr-2">
                  Edit
                </button>
                <button wire:click="hapus({{ $supplier->id_supplier }})" wire:confirm="Yakin hapus data supplier ini?"
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                  Hapus
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data supplier</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $suppliers->links() }}
    </div>
  </div>
</div>
