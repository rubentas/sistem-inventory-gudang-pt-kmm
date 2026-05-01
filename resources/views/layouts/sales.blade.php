<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales - Sistem Inventory KMM</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="bg-gray-100 font-sans" x-data="{ sidebarOpen: false }">

  <!-- NAVBAR ATAS -->
  <nav class="bg-orange-600 text-white px-4 py-3 flex items-center justify-between fixed top-0 w-full z-50 shadow-lg">
    <div class="flex items-center gap-3">
      <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1 rounded hover:bg-orange-500 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
      <div class="flex items-center gap-2">
        <span class="font-bold text-lg">PT. Kuda Mas Mandiri</span>
        <span class="hidden sm:inline text-orange-200 text-sm">| Tanjung Tabalong</span>
      </div>
    </div>
    <div class="flex items-center gap-3">
      <div class="hidden sm:flex flex-col text-right">
        <span class="text-sm font-medium">{{ auth()->user()->nama }}</span>
        <span class="text-xs text-orange-200">Sales</span>
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

        <a href="{{ route('sales.dashboard') }}"
          class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs('sales.dashboard') ? 'bg-orange-600 text-white' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
          Dashboard
        </a>

        <a href="{{ route('sales.order-sales') }}"
          class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs('sales.order-sales') ? 'bg-orange-600 text-white' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
          Order Sales
        </a>

        <a href="{{ route('sales.stok-barang') }}"
          class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ request()->routeIs('sales.stok-barang') ? 'bg-orange-600 text-white' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' }}">
          Stok Barang
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
