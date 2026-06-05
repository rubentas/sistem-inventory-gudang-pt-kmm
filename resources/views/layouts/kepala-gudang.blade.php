<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Sistem Inventory Gudang PT KMM' }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles

  <style>
    [x-cloak] {
      display: none !important;
    }

    .sidebar-scroll::-webkit-scrollbar {
      width: 5px;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 999px;
    }

    .glass {
      background: rgba(255, 255, 255, 0.78);
      backdrop-filter: blur(18px);
    }

    .menu-active {
      background: linear-gradient(to right, #059669, #10b981);
      color: white;
      box-shadow: 0 10px 25px rgba(5, 150, 105, 0.25);
    }

    .menu-inactive {
      color: #475569;
    }

    .menu-inactive:hover {
      background: #f1f5f9;
      color: #0f172a;
    }

    .sidebar-card {
      background: linear-gradient(135deg, #059669, #10b981);
    }
  </style>
</head>

<body class="antialiased" x-data="{ sidebarOpen: false }">

  <!-- NAVBAR -->
  <header class="fixed top-0 left-0 right-0 z-50 h-16 border-b border-slate-200 glass">
    <div class="h-full px-5 lg:px-8 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-emerald-600 transition">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>

        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-emerald-100 flex items-center justify-center shadow-lg">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
            </svg>
          </div>
          <div class="leading-tight">
            <h1 class="text-sm font-bold text-slate-800">PT Kuda Mas Mandiri</h1>
            <p class="text-[11px] tracking-widest text-slate-400 uppercase">Inventory System</p>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-5">
        <div class="relative" x-data="{ open: false }">
          <button @click="open = !open" class="flex items-center gap-3 group">
            <div class="hidden sm:block text-right">
              <h2 class="text-sm font-semibold text-slate-800">{{ auth()->user()->nama ?? 'User' }}</h2>
              <p class="text-xs text-slate-400">Kepala Gudang</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shadow-lg">
              <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
          </button>

          <div x-show="open" @click.away="open = false" x-transition x-cloak
            class="absolute right-0 mt-3 w-60 rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden">
            <div class="p-4 border-b border-slate-100">
              <h3 class="font-semibold text-slate-800">{{ auth()->user()->nama ?? 'User' }}</h3>
              <p class="text-sm text-slate-400">{{ auth()->user()->email ?? 'kepala@kmm.com' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7">
                  </path>
                </svg>
                Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="flex pt-16">

    <!-- SIDEBAR -->
    <aside
      class="fixed top-16 left-0 z-40 w-72 h-[calc(100vh-4rem)] bg-white border-r border-slate-200 shadow-[0_10px_50px_rgba(0,0,0,0.05)] transition-transform duration-300 overflow-hidden lg:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

      <div class="h-full flex flex-col">
        <nav class="flex-1 overflow-y-auto sidebar-scroll p-4 space-y-2">
          <a href="{{ route('kg.dashboard') }}"
            class="relative flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium transition-all duration-300
                          {{ request()->routeIs('kg.dashboard') ? 'menu-active' : 'menu-inactive' }}">
            @if (request()->routeIs('kg.dashboard'))
              <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-white"></div>
            @endif
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
              </path>
            </svg>
            Dashboard
          </a>

          <a href="{{ route('kg.barang-masuk') }}"
            class="relative flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium transition-all duration-300
                          {{ request()->routeIs('kg.barang-masuk') ? 'menu-active' : 'menu-inactive' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 16V4m0 0L3 8m4-4l4 4m6 12v-4m0 0l4 4m-4-4l-4 4"></path>
            </svg>
            Laporan Barang Masuk
          </a>

          <a href="{{ route('kg.stock-opname') }}"
            class="relative flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium transition-all duration-300
                          {{ request()->routeIs('kg.stock-opname') ? 'menu-active' : 'menu-inactive' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
              </path>
            </svg>
            Stock Opname
          </a>

          <a href="{{ route('kg.stok-barang') }}"
            class="relative flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium transition-all duration-300
                          {{ request()->routeIs('kg.stok-barang') ? 'menu-active' : 'menu-inactive' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
              </path>
            </svg>
            Stok Barang
          </a>
        </nav>

        <div class="border-t border-slate-200 p-4">
          <div class="sidebar-card rounded-2xl p-4 text-white shadow-lg">
            <p class="text-sm font-semibold">Sistem Inventory Gudang</p>
            <p class="text-xs text-emerald-100 mt-1">PT Kuda Mas Mandiri</p>
          </div>
        </div>
      </div>
    </aside>

    <!-- OVERLAY -->
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
      class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 lg:hidden" x-cloak>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 lg:ml-72 p-6 lg:p-8 min-h-screen overflow-x-auto w-full">
      {{ $slot }}
    </main>
  </div>

  @livewireScripts
</body>

</html>
