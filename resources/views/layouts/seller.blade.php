<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller Dashboard') - {{ config('app.name') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Playfair Display', serif; }
        :root { --sidebar-width: 260px; }
        .sidebar { width: var(--sidebar-width); }
        .main-content { margin-left: var(--sidebar-width); }
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
        [x-cloak] { display: none !important; }
        .sidebar-nav { scrollbar-width: thin; scrollbar-color: #475569 transparent; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
        select {
            background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%23475569%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E');
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1rem;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
    </style>
</head>
<body class="antialiased bg-white text-slate-900" x-data="{ sidebarOpen: false, profileOpen: false }">
    
    <!-- Mobile Overlay -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/30 z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'open' : ''" class="sidebar fixed top-0 left-0 h-full bg-slate-900 z-50 transition-transform duration-300 flex flex-col">
        <!-- Logo -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white flex items-center justify-center">
                    <span class="text-slate-900 font-serif font-bold text-lg">S</span>
                </div>
                <div>
                    <span class="text-white font-serif text-lg tracking-wide">ShopEase</span>
                    <span class="block text-[9px] tracking-[0.3em] uppercase text-amber-400">Seller Portal</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav flex-1 px-4 py-8 space-y-1 overflow-y-auto">
            <p class="px-3 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Overview</p>
            
            <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('seller.dashboard') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                <span>Dashboard</span>
            </a>

            <p class="px-3 mt-8 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Catalog</p>
            
            <a href="{{ route('seller.products.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('seller.products.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Products</span>
            </a>

            <p class="px-3 mt-8 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Sales</p>
            
            <a href="{{ route('seller.orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('seller.orders.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Orders</span>
            </a>
            
            <a href="{{ route('seller.payouts.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('seller.payouts.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Payouts</span>
            </a>

            <p class="px-3 mt-8 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Settings</p>
            
            <a href="{{ route('seller.profile.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('seller.profile.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Store Settings</span>
            </a>

            <hr class="my-6 border-slate-800">
            
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                <span>Back to Store</span>
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-[12px] text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content min-h-screen bg-slate-50">
        <!-- Top Bar -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-6 lg:px-8 sticky top-0 z-30">
            <!-- Mobile Menu -->
            <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-slate-600 hover:text-slate-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <!-- Breadcrumb -->
            <nav class="hidden lg:flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400">
                <a href="{{ route('seller.dashboard') }}" class="hover:text-slate-900 transition-colors">Seller</a>
                <span>/</span>
                <span class="text-slate-900">@yield('title', 'Dashboard')</span>
            </nav>

            <!-- Right Side -->
            <div class="flex items-center gap-6">
                <!-- Quick Stats -->
                <div class="hidden md:flex items-center gap-6 text-[11px]">
                    <div class="text-center">
                        <p class="text-slate-400 tracking-widest uppercase">Balance</p>
                        <p class="font-semibold text-slate-900">₹{{ number_format(auth()->user()->seller->wallet_balance ?? 0, 0) }}</p>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div class="text-center">
                        <p class="text-slate-400 tracking-widest uppercase">Products</p>
                        <p class="font-semibold text-slate-900">{{ auth()->user()->seller->total_products ?? 0 }}</p>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative" @click.away="profileOpen = false">
                    <button @click="profileOpen = !profileOpen" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-900 flex items-center justify-center text-white font-medium overflow-hidden">
                            @if(auth()->user()->seller && auth()->user()->seller->store_logo)
                            <img src="{{ Storage::url(auth()->user()->seller->store_logo) }}" alt="Store" class="w-full h-full object-cover">
                            @else
                            {{ substr(auth()->user()->seller->store_name ?? 'S', 0, 1) }}
                            @endif
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-[12px] font-medium text-slate-900">{{ auth()->user()->seller->store_name ?? 'My Store' }}</p>
                            <p class="text-[10px] text-slate-400 tracking-widest uppercase">Seller</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" x-cloak x-transition class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 shadow-xl py-1">
                        <a href="{{ route('seller.profile.index') }}" class="block px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-50">Store Settings</a>
                        <a href="{{ route('home') }}" class="block px-4 py-2 text-[12px] text-slate-700 hover:bg-slate-50">Back to Store</a>
                        <hr class="my-1 border-slate-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-[12px] text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6 lg:p-8">
            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-[13px]">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-[13px]">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white py-6 px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-[11px] text-slate-400">
                <p>© {{ date('Y') }} ShopEase. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('support.index') }}" class="hover:text-slate-600 transition-colors">Help Center</a>
                    <a href="{{ route('support.faq') }}" class="hover:text-slate-600 transition-colors">FAQs</a>
                    <a href="{{ route('support.ticket.create') }}" class="hover:text-slate-600 transition-colors">Contact Support</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
