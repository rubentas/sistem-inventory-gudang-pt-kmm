<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Barang Masuk</h1>
      <p class="text-gray-500 text-sm">Mengelola data barang masuk dari supplier dan pusat</p>
    </div>
    <button wire:click="bukaTambah"
      class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
      + Tambah Barang Masuk
    </button>
  </div>

  <!-- Success Message -->
  @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
      {{ session('success') }}
    </div>
  @endif

  <!-- Form Tambah -->
  @if ($showForm)
    <div class="bg-white rounded-lg shadow p-5 mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $editId ? 'Edit' : 'Tambah' }} Barang Masuk</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Barang <span class="text-red-500">*</span></label>
          <select wire:model="id_barang"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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
          <label class="block text-sm font-medium text-gray-700 mb-1">Supplier <span
              class="text-red-500">*</span></label>
          <select wire:model="id_supplier"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Supplier --</option>
            @foreach ($suppliers as $supplier)
              <option value="{{ $supplier->id_supplier }}">{{ $supplier->kode_supplier }} -
                {{ $supplier->nama_supplier }}</option>
            @endforeach
          </select>
          @error('id_supplier')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">No. Nota <span
              class="text-red-500">*</span></label>
          <input type="text" wire:model="no_nota" placeholder="Nomor nota dari supplier"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('no_nota')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">No. Surat Jalan <span
              class="text-red-500">*</span></label>
          <input type="text" wire:model="no_surat_jalan" placeholder="Nomor surat jalan"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('no_surat_jalan')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah <span class="text-red-500">*</span></label>
          <input type="number" wire:model="jumlah" placeholder="Jumlah barang"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('jumlah')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk <span
              class="text-red-500">*</span></label>
          <input type="date" wire:model="tanggal_masuk"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          @error('tanggal_masuk')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Sumber <span class="text-red-500">*</span></label>
          <select wire:model="sumber"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Sumber --</option>
            @foreach ($sumberList as $sumber)
              <option value="{{ $sumber }}">{{ $sumber }}</option>
            @endforeach
          </select>
          @error('sumber')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
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
        <input type="text" wire:model.live="search" placeholder="Cari barang, nota, atau surat jalan..."
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
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Tanggal</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No Nota</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No Surat Jalan</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Barang</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Jumlah</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Supplier</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Sumber</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($barangMasuk as $item)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->no_nota }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->no_surat_jalan }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $item->barang->nama_barang ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($item->jumlah) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $item->supplier->nama_supplier ?? '-' }}</td>
              <td class="px-4 py-3 text-sm">
                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ $item->sumber }}</span>
              </td>
              <td class="px-4 py-3 text-sm">
                <button wire:click="hapus({{ $item->id_masuk }})"
                  wire:confirm="Yakin hapus data ini? Stok akan berkurang kembali."
                  class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                  Hapus
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada数据</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $barangMasuk->links() }}
    </div>
  </div>
</div>
