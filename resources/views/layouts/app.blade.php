<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FIKRI WATER ERP')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body>

<div x-data="{ sidebarOpen: false, openMenu: null }" class="flex h-screen bg-gray-100">

    <!-- Overlay untuk mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-30 bg-black/50 lg:hidden"></div>

    <!-- ========== SIDEBAR ========== -->
    <aside 
        class="fixed top-0 left-0 z-40 h-full w-64 bg-white shadow-lg transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 overflow-y-auto"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 border-b bg-blue-600">
            <span class="text-xl font-bold text-white">FIKRI WATER</span>
            <button @click="sidebarOpen = false" class="text-white lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Menu -->
        @auth
        <nav class="px-3 py-4 text-sm font-medium">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-gray-700 hover:text-blue-600">Dashboard</a>

            <!-- MASTER DATA -->
            <div class="mt-1">
                <button @click="openMenu = (openMenu === 'master') ? null : 'master'" 
                        class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                    <span>Master Data</span>
                    <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': openMenu === 'master'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openMenu === 'master'" x-cloak class="ml-4 space-y-1 border-l-2 border-blue-200 pl-3">
                    <a href="{{ route('areas.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Area</a>
                    <a href="{{ route('customers.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Customer</a>
                    <a href="{{ route('products.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Produk</a>
                    <a href="{{ route('suppliers.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Supplier</a>
                    <a href="{{ route('couriers.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Kurir</a>
                </div>
            </div>

            <!-- TRANSAKSI -->
            <div class="mt-1">
                <button @click="openMenu = (openMenu === 'transaksi') ? null : 'transaksi'" 
                        class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                    <span>Transaksi</span>
                    <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': openMenu === 'transaksi'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openMenu === 'transaksi'" x-cloak class="ml-4 space-y-1 border-l-2 border-blue-200 pl-3">
                    <a href="{{ route('orders.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Order</a>
                    <a href="{{ route('productions.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Produksi</a>
                </div>
            </div>

            <!-- KEUANGAN & REWARD -->
            <div class="mt-1">
                <button @click="openMenu = (openMenu === 'keuangan') ? null : 'keuangan'" 
                        class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                    <span>Keuangan & Reward</span>
                    <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': openMenu === 'keuangan'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openMenu === 'keuangan'" x-cloak class="ml-4 space-y-1 border-l-2 border-blue-200 pl-3">
                    <a href="{{ route('financials.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Keuangan</a>
                    <a href="{{ route('rewards.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Reward</a>
                </div>
            </div>

            <!-- LAPORAN & SISTEM -->
            <div class="mt-1">
                <button @click="openMenu = (openMenu === 'sistem') ? null : 'sistem'" 
                        class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                    <span>Laporan & Sistem</span>
                    <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': openMenu === 'sistem'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="openMenu === 'sistem'" x-cloak class="ml-4 space-y-1 border-l-2 border-blue-200 pl-3">
                    <a href="{{ route('reports.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Laporan</a>
                    <a href="{{ route('backups.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Backup</a>
                    <a href="{{ route('audit-logs.index') }}" class="block px-3 py-1.5 rounded hover:bg-blue-50 text-gray-600 hover:text-blue-600">Audit Log</a>
                </div>
            </div>

            <!-- Logout -->
            <div class="mt-6 pt-4 border-t">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-3 py-2 rounded hover:bg-red-50 text-red-600 hover:text-red-700">Logout</button>
                </form>
            </div>
        </nav>
        @endauth
    </aside>

    <!-- ========== MAIN CONTENT ========== -->
    <div class="flex-1 flex flex-col min-h-0 bg-gray-100">
        <!-- Navbar Atas -->
        <header class="bg-white shadow-sm border-b px-4 py-3 flex items-center justify-between sticky top-0 z-20">
            <button @click="sidebarOpen = !sidebarOpen" class="p-1 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="flex items-center space-x-3">
                @auth
                    <span class="text-sm text-gray-700 hidden sm:inline">{{ Auth::user()->name }}</span>
                    <span class="text-xs text-gray-500 hidden sm:inline">({{ Auth::user()->role }})</span>
                @endauth
                @guest
                    <span class="text-sm text-gray-500">Silakan Login</span>
                @endguest
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 lg:p-6 overflow-y-auto">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>