<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pimpinan - Sistem Inventory KMM</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="bg-gray-100 font-sans" x-data="{ sidebarOpen: false, openLaporan: false }">

  <!-- NAVBAR ATAS -->
  <nav class="bg-purple-700 text-white px-4 py-3 flex items-center justify-between fixed top-0 w-full z-50 shadow-lg">
    <div class="flex items-center gap-3">
      <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1 rounded hover:bg-purple-600 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <div class="flex items-center gap-2">
        <span class="font-bold text-lg">PT. Kuda Mas Mandiri</span>
        <span class="hidden sm:inline text-purple-200 text-sm">| Tanjung Tabalong</span>
      </div>
    </div>
    <div class="flex items-center gap-3">
      <div class="hidden sm:flex flex-col text-right">
        <span class="text-sm font-medium">{{ auth()->user()->nama }}</span>
        <span class="text-xs text-purple-200">Pimpinan</span>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded text-sm font-medium transition">
          Logout
        </button>
      </form>
    </div>
  </nav>

  <div class="flex pt-14">

    <!-- SIDEBAR -->
    <aside
      class="fixed left-0 top-14 h-[calc(100vh-3.5rem)] w-64 bg-white shadow-md z-40
                      transform transition-transform duration-300 ease-in-out overflow-y-auto
                      -translate-x-full lg:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

      <nav class="p-3 space-y-1">

        <a href="{{ route('pimpinan.dashboard') }}"
          class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs('pimpinan.dashboard') ? 'bg-purple-600 text-white' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-700' }}">
          Dashboard
        </a>

        <!-- Laporan (Dropdown) -->
        <div>
          <button @click="openLaporan = !openLaporan"
            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
            <span>Laporan</span>
            <svg class="w-4 h-4 transition-transform duration-200" :class="openLaporan ? 'rotate-180' : ''"
              fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div x-show="openLaporan" class="pl-3 mt-1 space-y-1">
            <a href="{{ route('pimpinan.lap-masuk') }}"
              class="block px-3 py-2 rounded-lg text-sm transition
                                  {{ request()->routeIs('pimpinan.lap-masuk') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-600 hover:bg-purple-50' }}">
              Barang Masuk
            </a>
            <a href="{{ route('pimpinan.lap-keluar') }}"
              class="block px-3 py-2 rounded-lg text-sm transition
                                  {{ request()->routeIs('pimpinan.lap-keluar') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-600 hover:bg-purple-50' }}">
              Barang Keluar
            </a>
            <a href="{{ route('pimpinan.lap-stok') }}"
              class="block px-3 py-2 rounded-lg text-sm transition
                                  {{ request()->routeIs('pimpinan.lap-stok') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-600 hover:bg-purple-50' }}">
              Stok Barang
            </a>
            <a href="{{ route('pimpinan.lap-opname') }}"
              class="block px-3 py-2 rounded-lg text-sm transition
                                  {{ request()->routeIs('pimpinan.lap-opname') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-600 hover:bg-purple-50' }}">
              Stock Opname
            </a>
            <a href="{{ route('pimpinan.lap-order') }}"
              class="block px-3 py-2 rounded-lg text-sm transition
                                  {{ request()->routeIs('pimpinan.lap-order') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-600 hover:bg-purple-50' }}">
              Order Sales
            </a>
            <a href="{{ route('pimpinan.lap-supplier') }}"
              class="block px-3 py-2 rounded-lg text-sm transition
                                  {{ request()->routeIs('pimpinan.lap-supplier') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-600 hover:bg-purple-50' }}">
              Supplier
            </a>
            <a href="{{ route('pimpinan.lap-wilayah') }}"
              class="block px-3 py-2 rounded-lg text-sm transition
                                  {{ request()->routeIs('pimpinan.lap-wilayah') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-600 hover:bg-purple-50' }}">
              Wilayah
            </a>
            <a href="{{ route('pimpinan.lap-inventory') }}"
              class="block px-3 py-2 rounded-lg text-sm transition
                                  {{ request()->routeIs('pimpinan.lap-inventory') ? 'bg-purple-100 text-purple-700 font-medium' : 'text-gray-600 hover:bg-purple-50' }}">
              Inventory
            </a>
          </div>
        </div>

        <!-- Manajemen Pengguna -->
        <a href="{{ route('pimpinan.pengguna') }}"
          class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs('pimpinan.pengguna') ? 'bg-purple-600 text-white' : 'text-gray-700 hover:bg-purple-50 hover:text-purple-700' }}">
          Manajemen Pengguna
        </a>

      </nav>
    </aside>

    <!-- OVERLAY untuk mobile -->
    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
      class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden">
    </div>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 lg:ml-64 p-4 md:p-6 min-h-screen overflow-x-hidden">
      {{ $slot }}
    </main>

  </div>

  @livewireScripts
</body>

</html>
