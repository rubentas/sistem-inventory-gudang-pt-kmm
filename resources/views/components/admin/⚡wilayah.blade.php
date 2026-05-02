<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Data Wilayah Distribusi</h1>
      <p class="text-gray-500 text-sm">Mengelola master data wilayah distribusi sales</p>
    </div>
    <button wire:click="bukaTambah"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
      + Tambah Wilayah
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
      <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $isEdit ? 'Edit' : 'Tambah' }} Wilayah</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Wilayah <span
              class="text-red-500">*</span></label>
          <input type="text" wire:model="nama_wilayah" placeholder="Contoh: Wilayah A - Tanjung Kota"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('nama_wilayah')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Toko <span
              class="text-red-500">*</span></label>
          <input type="number" wire:model="jumlah_toko" placeholder="0"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('jumlah_toko')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Sales Penanggung Jawab</label>
          <select wire:model="id_user"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Sales --</option>
            @foreach ($sales as $sale)
              <option value="{{ $sale->id_user }}">{{ $sale->nama }} ({{ $sale->username }})</option>
            @endforeach
          </select>
          @error('id_user')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
          <textarea wire:model="keterangan" rows="2" placeholder="Catatan tambahan tentang wilayah"
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
        <input type="text" wire:model.live="search" placeholder="Cari nama wilayah..."
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
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama Wilayah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Jumlah Toko</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Sales Penanggung Jawab</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Keterangan</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($wilayahs as $index => $wilayah)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $wilayahs->firstItem() + $index }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $wilayah->nama_wilayah }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($wilayah->jumlah_toko) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $wilayah->sales->nama ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $wilayah->keterangan ?? '-' }}</td>
              <td class="px-4 py-3 text-sm">
                <button wire:click="bukaEdit({{ $wilayah->id_wilayah }})"
                  class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs transition mr-2">
                  Edit
                </button>
                <button wire:click="hapus({{ $wilayah->id_wilayah }})" wire:confirm="Yakin hapus data wilayah ini?"
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                  Hapus
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data wilayah</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $wilayahs->links() }}
    </div>
  </div>
</div>
