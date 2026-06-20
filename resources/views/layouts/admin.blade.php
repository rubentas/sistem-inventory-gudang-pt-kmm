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
      background: linear-gradient(to right, #2563eb, #3b82f6);
      color: white;
      box-shadow: 0 10px 25px rgba(37, 99, 235, 0.25);
    }

    .menu-inactive {
      color: #475569;
    }

    .menu-inactive:hover {
      background: #f1f5f9;
      color: #0f172a;
    }

    .sidebar-card {
      background: linear-gradient(135deg, #2563eb, #3b82f6);
    }
  </style>
</head>

<body class="antialiased" x-data="{ sidebarOpen: false }">

  <!-- NAVBAR -->
  <header class="fixed top-0 left-0 right-0 z-50 h-16 border-b border-slate-200 glass">
    <div class="h-full px-5 lg:px-8 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-blue-600 transition">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>

        <div class="flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
              <h2 class="text-sm font-semibold text-slate-800">{{ auth()->user()->nama }}</h2>
              <p class="text-xs text-slate-400">Admin Fakturis</p>
            </div>
            <div
              class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-blue-500 text-white font-bold flex items-center justify-center shadow-lg shadow-blue-500/20">
              {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
            </div>
          </button>

          <div x-show="open" @click.away="open = false" x-transition x-cloak
            class="absolute right-0 mt-3 w-60 rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden">
            <div class="p-4 border-b border-slate-100">
              <h3 class="font-semibold text-slate-800">{{ auth()->user()->nama }}</h3>
              <p class="text-sm text-slate-400">{{ auth()->user()->email ?? 'admin@kmm.com' }}</p>
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
        <nav class="flex-1 overflow-y-auto sidebar-scroll p-4 space-y-4">

          <!-- DASHBOARD -->
          <a href="{{ route('admin.dashboard') }}"
            class="relative flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium transition-all duration-300
              {{ request()->routeIs('admin.dashboard') ? 'menu-active' : 'menu-inactive' }}">
            @if (request()->routeIs('admin.dashboard'))
              <div class="absolute left-0 top-2 bottom-2 w-1 rounded-r-full bg-white"></div>
            @endif
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
              </path>
            </svg>
            Dashboard
          </a>

          <!-- ========== MASTER DATA ========== -->
          <div x-data="{
              open: localStorage.getItem('master-menu') === 'true' || {{ request()->routeIs(['admin.data-barang', 'admin.supplier', 'admin.wilayah']) ? 'true' : 'false' }},
              toggle() {
                  this.open = !this.open;
                  localStorage.setItem('master-menu', this.open);
              }
          }">
            <button @click="toggle()"
              class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-medium transition-all duration-300
                {{ request()->routeIs(['admin.data-barang', 'admin.supplier', 'admin.wilayah']) ? 'menu-active' : 'menu-inactive' }}">
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                  </path>
                </svg>
                Master Data
              </div>
              <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>

            <div x-show="open" x-transition x-cloak class="mt-2 pl-4 space-y-1">
              <a href="{{ route('admin.data-barang') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.data-barang') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                  </path>
                </svg>
                Data Barang
              </a>
              <a href="{{ route('admin.supplier') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.supplier') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                </svg>
                Supplier
              </a>
              <a href="{{ route('admin.wilayah') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.wilayah') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                  </path>
                </svg>
                Wilayah
              </a>
              <a href="{{ route('admin.data-sales') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-300
  {{ request()->routeIs('admin.data-sales') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Data Sales
              </a>
            </div>
          </div>

          <!-- ========== TRANSAKSI ========== -->
          <div x-data="{
              open: localStorage.getItem('transaksi-menu') === 'true' || {{ request()->routeIs(['admin.barang-masuk', 'admin.barang-keluar', 'admin.order-sales', 'admin.invoice']) ? 'true' : 'false' }},
              toggle() {
                  this.open = !this.open;
                  localStorage.setItem('transaksi-menu', this.open);
              }
          }">
            <button @click="toggle()"
              class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-medium transition-all duration-300
                {{ request()->routeIs(['admin.barang-masuk', 'admin.barang-keluar', 'admin.order-sales', 'admin.invoice']) ? 'menu-active' : 'menu-inactive' }}">
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                Transaksi
              </div>
              <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>

            <div x-show="open" x-transition x-cloak class="mt-2 pl-4 space-y-1">
              <a href="{{ route('admin.barang-masuk') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.barang-masuk') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16V4m0 0L3 8m4-4l4 4m6 12v-4m0 0l4 4m-4-4l-4 4"></path>
                </svg>
                Barang Masuk
              </a>
              <a href="{{ route('admin.barang-keluar') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.barang-keluar') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                  </path>
                </svg>
                Barang Keluar
              </a>
              <a href="{{ route('admin.order-sales') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.order-sales') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Order Sales
              </a>
              <!-- MENU INVOICE -->
              <a href="{{ route('admin.invoice') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.invoice') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                  </path>
                </svg>
                Invoice
              </a>
            </div>
          </div>

          <!-- ========== PERSEDIAAN ========== -->
          <div x-data="{
              open: localStorage.getItem('persediaan-menu') === 'true' || {{ request()->routeIs(['admin.stok-barang']) ? 'true' : 'false' }},
              toggle() {
                  this.open = !this.open;
                  localStorage.setItem('persediaan-menu', this.open);
              }
          }">
            <button @click="toggle()"
              class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-medium transition-all duration-300
                {{ request()->routeIs(['admin.stok-barang']) ? 'menu-active' : 'menu-inactive' }}">
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                  </path>
                </svg>
                Persediaan
              </div>
              <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>

            <div x-show="open" x-transition x-cloak class="mt-2 pl-4 space-y-1">
              <a href="{{ route('admin.stok-barang') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.stok-barang') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                  </path>
                </svg>
                Stok Barang
              </a>
            </div>
          </div>

          <!-- ========== LAPORAN ========== -->
          <div x-data="{
              open: localStorage.getItem('laporan-menu') === 'true' || {{ request()->routeIs(['admin.laporan.masuk', 'admin.laporan.keluar', 'laporan.stok', 'laporan.keluar', 'laporan.wilayah']) ? 'true' : 'false' }},
              toggle() {
                  this.open = !this.open;
                  localStorage.setItem('laporan-menu', this.open);
              }
          }">
            <button @click="toggle()"
              class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-medium transition-all duration-300
      {{ request()->routeIs(['admin.laporan.masuk', 'admin.laporan.keluar', 'laporan.stok', 'laporan.keluar', 'laporan.wilayah']) ? 'menu-active' : 'menu-inactive' }}">
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2a3 3 0 013-3h0a3 3 0 013 3v2m-9 0H5a2 2 0 01-2-2v-2a9 9 0 0118 0v2a2 2 0 01-2 2h-4m-6 0h6">
                  </path>
                </svg>
                Laporan
              </div>
              <svg class="w-4 h-4 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>

            <div x-show="open" x-transition x-cloak class="mt-2 pl-4 space-y-1">
              {{-- LAPORAN BARANG MASUK --}}
              <a href="{{ route('admin.laporan.masuk') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.laporan.masuk') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Laporan Barang Masuk
              </a>

              {{-- LAPORAN BARANG KELUAR --}}
              <a href="{{ route('admin.laporan.keluar') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.laporan.keluar') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16V4m0 0l4 4m-4-4l-4 4M7 16v4m0 0l-4-4m4 4l4-4" />
                </svg>
                Laporan Barang Keluar
              </a>

              {{-- LAPORAN STOK BARANG --}}
              <a href="{{ route('admin.laporan.stok') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.laporan.stok') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                Laporan Stok Barang
              </a>

              {{-- LAPORAN ORDER SALES --}}
              <a href="{{ route('admin.laporan.order') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.laporan.order') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Laporan Order Sales
              </a>

              {{-- LAPORAN OMZET --}}
              <a href="{{ route('admin.laporan.omzet') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-xl text-sm transition {{ request()->routeIs('admin.laporan.omzet') ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Laporan Omzet
              </a>
            </div>
          </div>

        </nav>

        <div class="border-t border-slate-200 p-4">
          <div class="sidebar-card rounded-2xl p-4 text-white shadow-lg">
            <p class="text-sm font-semibold">Sistem Inventory Gudang</p>
            <p class="text-xs text-blue-100 mt-1">PT Kuda Mas Mandiri</p>
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

  <script>
    document.addEventListener('livewire:navigated', () => {
      const sidebar = document.querySelector('.sidebar-scroll');
      if (sidebar) {
        const scrollPos = sessionStorage.getItem('sidebar-scroll');
        if (scrollPos) {
          sidebar.scrollTop = parseInt(scrollPos);
        }

        sidebar.addEventListener('scroll', () => {
          sessionStorage.setItem('sidebar-scroll', sidebar.scrollTop);
        });
      }
    });
  </script>
</body>

</html>
