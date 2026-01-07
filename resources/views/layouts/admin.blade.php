<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | ShopEase Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js'])
    
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
        /* Hide scrollbar but keep scroll functionality */
        .sidebar-nav { scrollbar-width: thin; scrollbar-color: #475569 transparent; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        /* Custom select styling */
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
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white flex items-center justify-center">
                    <span class="text-slate-900 font-serif font-bold text-lg">S</span>
                </div>
                <div>
                    <span class="text-white font-serif text-lg tracking-wide">ShopEase</span>
                    <span class="block text-[9px] tracking-[0.3em] uppercase text-slate-400">Admin Panel</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav flex-1 px-4 py-8 space-y-1 overflow-y-auto">
            <p class="px-3 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Main</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                <span>Dashboard</span>
            </a>

            <p class="px-3 mt-8 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Catalog</p>
            
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Products</span>
            </a>
            
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span>Categories</span>
            </a>

            <p class="px-3 mt-8 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Sales</p>
            
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Orders</span>
                @php $pendingOrders = \App\Models\Order::where('status', 'pending')->count(); @endphp
                @if($pendingOrders > 0)
                <span class="ml-auto px-2 py-0.5 bg-amber-500 text-white text-[10px] font-bold">{{ $pendingOrders }}</span>
                @endif
            </a>
            
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.coupons.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span>Coupons</span>
            </a>
            
            <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.inventory.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span>Inventory</span>
                @php $lowStockCount = \App\Services\InventoryService::getLowStockCount() + \App\Services\InventoryService::getOutOfStockCount(); @endphp
                @if($lowStockCount > 0)
                <span class="ml-auto px-2 py-0.5 bg-red-500 text-white text-[10px] font-bold">{{ $lowStockCount }}</span>
                @endif
            </a>

            <p class="px-3 mt-8 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Customers</p>
            
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Users</span>
            </a>

            <p class="px-3 mt-8 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Support</p>
            
            <a href="{{ route('admin.support.tickets') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.support.tickets*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Tickets</span>
                @php $openTickets = \App\Models\SupportTicket::whereIn('status', ['open', 'in_progress'])->count(); @endphp
                @if($openTickets > 0)
                <span class="ml-auto px-2 py-0.5 bg-blue-500 text-white text-[10px] font-bold">{{ $openTickets }}</span>
                @endif
            </a>
            
            <a href="{{ route('admin.support.faqs') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.support.faq*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>FAQs</span>
            </a>
            
            <a href="{{ route('admin.support.live-chat') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.support.live-chat*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>Live Chat</span>
            </a>

            <p class="px-3 mt-8 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Membership</p>
            
            <a href="{{ route('admin.membership.plans') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.membership.plans*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span>Plans</span>
            </a>
            
            <a href="{{ route('admin.membership.subscribers') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.membership.subscribers*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Subscribers</span>
            </a>
            
            <a href="{{ route('admin.membership.sales') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.membership.sales*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Early Access Sales</span>
            </a>

            <p class="px-3 mt-8 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Content</p>
            
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Site Settings</span>
            </a>

            <p class="px-3 mt-8 mb-4 text-[9px] font-bold tracking-[0.2em] uppercase text-slate-500">Account</p>
            
            <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-4 py-3 text-[12px] transition-colors {{ request()->routeIs('admin.profile.*') ? 'bg-white text-slate-900' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>My Profile</span>
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
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900 transition-colors">Admin</a>
                <span>/</span>
                <span class="text-slate-900">@yield('title', 'Dashboard')</span>
            </nav>

            <!-- Right Side -->
            <div class="flex items-center gap-6">
                <!-- Quick Stats -->
                <div class="hidden md:flex items-center gap-6 text-[11px]">
                    <div class="text-center">
                        <p class="text-slate-400 tracking-widest uppercase">Today</p>
                        <p class="font-semibold text-slate-900">₹{{ number_format(\App\Models\Order::whereDate('created_at', today())->where('payment_status', 'paid')->sum('total'), 0) }}</p>
                    </div>
                    <div class="w-px h-8 bg-slate-200"></div>
                    <div class="text-center">
                        <p class="text-slate-400 tracking-widest uppercase">Orders</p>
                        <p class="font-semibold text-slate-900">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</p>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative" @click.away="profileOpen = false">
                    <button @click="profileOpen = !profileOpen" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-900 flex items-center justify-center text-white font-medium overflow-hidden">
                            @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                            {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-[12px] font-medium text-slate-900">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-400 tracking-widest uppercase">Administrator</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white border border-slate-200 shadow-lg py-1">
                        <a href="{{ route('admin.profile.index') }}" class="block px-4 py-2.5 text-[12px] text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">My Profile</a>
                        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2.5 text-[12px] text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">Settings</a>
                        <hr class="my-1 border-slate-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-[12px] text-red-600 hover:bg-red-50 transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6 lg:p-8">
            <!-- Toast Notifications -->
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition 
                class="fixed top-24 right-6 z-50 px-6 py-4 bg-slate-900 text-white shadow-lg flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="text-[13px]">{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-slate-400 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition 
                class="fixed top-24 right-6 z-50 px-6 py-4 bg-red-600 text-white shadow-lg flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-[13px]">{{ session('error') }}</span>
                <button @click="show = false" class="ml-4 text-red-200 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
