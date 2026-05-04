<div>
  <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h1>
      <p class="text-gray-500 text-sm">Kelola akun pengguna sistem</p>
    </div>
    <button wire:click="bukaTambah"
      class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition">
      + Tambah Pengguna
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
      <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $isEdit ? 'Edit' : 'Tambah' }} Pengguna</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span
              class="text-red-500">*</span></label>
          <input type="text" wire:model="nama" placeholder="Nama lengkap"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
          @error('nama')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Username <span
              class="text-red-500">*</span></label>
          <input type="text" wire:model="username" placeholder="Username untuk login"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
          @error('username')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">
              @if (!$isEdit)
                *
              @endif
            </span></label>
          <input type="password" wire:model="password"
            placeholder="{{ $isEdit ? 'Kosongkan jika tidak diubah' : 'Minimal 4 karakter' }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
          @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
          <select wire:model="role"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">-- Pilih Role --</option>
            <option value="pimpinan">Pimpinan</option>
            <option value="admin_fakturis">Admin Fakturis</option>
            <option value="kepala_gudang">Kepala Gudang</option>
            <option value="sales">Sales</option>
          </select>
          @error('role')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input type="email" wire:model="email" placeholder="email@example.com"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
          @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
          <input type="text" wire:model="no_telp" placeholder="08123456789"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
          @error('no_telp')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>
      <div class="flex justify-end gap-3 mt-4">
        <button wire:click="batal"
          class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm transition">
          Batal
        </button>
        <button wire:click="simpan"
          class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition">
          Simpan
        </button>
      </div>
    </div>
  @endif

  <!-- Search -->
  <div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex gap-4">
      <div class="flex-1">
        <input type="text" wire:model.live="search" placeholder="Cari nama atau username..."
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
      </div>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-purple-700 text-white">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Nama</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Username</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Email</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">No. Telepon</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Role</th>
            <th class="px-4 py-3 text-left text-xs font-medium uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($users as $index => $user)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-600">{{ $users->firstItem() + $index }}</td>
              <td class="px-4 py-3 text-sm text-gray-800">{{ $user->nama }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $user->username }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email ?? '-' }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $user->no_telp ?? '-' }}</td>
              <td class="px-4 py-3 text-sm">
                @if ($user->role == 'pimpinan')
                  <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Pimpinan</span>
                @elseif($user->role == 'admin_fakturis')
                  <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Admin Fakturis</span>
                @elseif($user->role == 'kepala_gudang')
                  <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Kepala Gudang</span>
                @else
                  <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs">Sales</span>
                @endif
              </td>
              <td class="px-4 py-3 text-sm">
                <button wire:click="bukaEdit({{ $user->id_user }})"
                  class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs transition mr-2">
                  Edit
                </button>
                @if ($user->id_user !== auth()->user()->id_user)
                  <button wire:click="hapus({{ $user->id_user }})" wire:confirm="Yakin hapus pengguna ini?"
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition">
                    Hapus
                  </button>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data pengguna</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-200">
      {{ $users->links() }}
    </div>
  </div>
</div>
