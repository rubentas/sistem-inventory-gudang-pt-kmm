<div class="w-full max-w-md">
  <div class="bg-white rounded-xl shadow-xl overflow-hidden">
    <!-- Header Sederhana -->
    <div class="px-6 py-5 text-center border-b border-gray-100">
      <div class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
        </svg>
      </div>
      <h1 class="text-lg font-bold text-gray-800">PT. Kuda Mas Mandiri</h1>
      <p class="text-xs text-gray-500 mt-1">Sistem Inventory Gudang</p>
    </div>

    <!-- Form -->
    <div class="p-6">
      <p class="text-sm text-gray-600 mb-5">Masukkan username dan password untuk melanjutkan.</p>

      @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-md mb-4 text-xs">
          {{ $errors->first() }}
        </div>
      @endif

      <form wire:submit.prevent="login" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Username</label>
          <input type="text" wire:model="username" placeholder="Masukkan username"
            class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
          @error('username')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Password</label>
          <input type="password" wire:model="password" placeholder="Masukkan password"
            class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
          @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg text-sm transition">
          Login
        </button>
      </form>

      <div class="mt-6 pt-4 border-t border-gray-100 text-center">
        <p class="text-xs text-gray-400">
          &copy; {{ date('Y') }} PT. Kuda Mas Mandiri
        </p>
      </div>
    </div>
  </div>
</div>
