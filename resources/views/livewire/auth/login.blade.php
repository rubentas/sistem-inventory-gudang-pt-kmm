<div class="min-h-screen bg-gradient-to-br from-blue-700 to-blue-900 flex items-center justify-center p-4">
  <div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-center">
        <h1 class="text-2xl font-bold text-white">PT. Kuda Mas Mandiri</h1>
        <p class="text-blue-100 text-sm mt-1">Sistem Informasi Inventory Barang Gudang</p>
        <p class="text-blue-200 text-xs mt-1">Tanjung Tabalong, Kalimantan Selatan</p>
      </div>

      <div class="p-8">
        @if ($errors->any())
          <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
            {{ $errors->first() }}
          </div>
        @endif

        <form wire:submit.prevent="login" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" wire:model="username" placeholder="Masukkan username"
              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('username')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" wire:model="password" placeholder="Masukkan password"
              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium text-sm transition">
            Masuk
          </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-6">
          &copy; {{ date('Y') }} PT. Kuda Mas Mandiri Tanjung Tabalong
        </p>
      </div>
    </div>
  </div>
</div>
