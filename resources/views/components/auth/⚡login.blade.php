<div class="w-full max-w-md">
  <div class="bg-white rounded-2xl shadow-2xl p-8">

    <!-- Header -->
    <div class="text-center mb-8">
      <h1 class="text-2xl font-bold text-gray-800">PT. Kuda Mas Mandiri</h1>
      <p class="text-gray-500 text-sm mt-1">Sistem Informasi Inventory Barang Gudang</p>
      <p class="text-gray-400 text-xs mt-1">Tanjung Tabalong, Kalimantan Selatan</p>
    </div>

    <!-- Alert Error -->
    @if ($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
        {{ $errors->first() }}
      </div>
    @endif

    <!-- Form Login -->
    <div class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
        <input wire:model="username" type="text" placeholder="Masukkan username" @class([
            'w-full rounded-lg px-4 py-2.5 text-sm border focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition',
            'border-red-500' => $errors->has('username'),
            'border-gray-300' => !$errors->has('username'),
        ])>
        @error('username')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input wire:model="password" type="password" placeholder="Masukkan password" wire:keydown.enter="login"
          @class([
              'w-full rounded-lg px-4 py-2.5 text-sm border focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition',
              'border-red-500' => $errors->has('password'),
              'border-gray-300' => !$errors->has('password'),
          ])>
        @error('password')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <button wire:click="login" wire:loading.attr="disabled"
        class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white
                           py-2.5 rounded-lg font-medium text-sm transition mt-2">
        <span wire:loading.remove>Masuk</span>
        <span wire:loading>Memproses...</span>
      </button>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">
      &copy; {{ date('Y') }} PT. Kuda Mas Mandiri Tanjung Tabalong
    </p>
  </div>
</div>
