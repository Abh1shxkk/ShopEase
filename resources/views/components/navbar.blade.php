<nav x-data="{ 
    mobileMenu: false, 
    profileOpen: false,
    categoriesOpen: false,
    cartCount: 0,
    wishlistCount: 0,
    init() {
        this.cartCount = JSON.parse(localStorage.getItem('cart') || '[]').length;
        this.wishlistCount = JSON.parse(localStorage.getItem('wishlist') || '[]').length;
        window.addEventListener('cart-updated', () => {
            this.cartCount = JSON.parse(localStorage.getItem('cart') || '[]').length;
        });
        window.addEventListener('wishlist-updated', () => {
            this.wishlistCount = JSON.parse(localStorage.getItem('wishlist') || '[]').length;
        });
    }
}" class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 shadow-sm">
    <!-- Top Bar -->
    <div class="bg-slate-900 text-white text-center py-2 text-sm hidden sm:block">
        <span>🎉 Free shipping on orders over $50 | Use code <strong>SHOP10</strong> for 10% off</span>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 flex-shrink-0">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-slate-900">ShopEase</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-8">
                <a href="/" class="text-slate-600 hover:text-slate-900 font-medium transition-colors {{ request()->is('/') ? 'text-blue-600' : '' }}">Home</a>
                <a href="{{ route('shop.index') }}" class="text-slate-600 hover:text-slate-900 font-medium transition-colors {{ request()->is('shop*') ? 'text-blue-600' : '' }}">Shop</a>
                
                <!-- Categories Dropdown -->
                <div class="relative" @click.away="categoriesOpen = false">
                    <button @click="categoriesOpen = !categoriesOpen" class="flex items-center gap-1 text-slate-600 hover:text-slate-900 font-medium transition-colors">
                        Categories
                        <svg class="w-4 h-4 transition-transform" :class="categoriesOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="categoriesOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                        <a href="{{ route('shop.index', ['category' => 'Electronics']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-gray-50 hover:text-slate-900">Electronics</a>
                        <a href="{{ route('shop.index', ['category' => 'Fashion']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-gray-50 hover:text-slate-900">Fashion</a>
                        <a href="{{ route('shop.index', ['category' => 'Home']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-gray-50 hover:text-slate-900">Home & Living</a>
                        <a href="{{ route('shop.index', ['category' => 'Books']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-gray-50 hover:text-slate-900">Books</a>
                        <a href="{{ route('shop.index', ['category' => 'Sports']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-gray-50 hover:text-slate-900">Sports</a>
                        <a href="{{ route('shop.index', ['category' => 'Beauty']) }}" class="block px-4 py-2 text-sm text-slate-600 hover:bg-gray-50 hover:text-slate-900">Beauty</a>
                    </div>
                </div>
                
                <a href="/#contact" class="text-slate-600 hover:text-slate-900 font-medium transition-colors">Contact</a>
            </nav>

            <!-- Search Bar -->
            <form action="{{ route('shop.index') }}" method="GET" class="hidden md:flex flex-1 max-w-md mx-8">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." 
                        class="w-full h-11 pl-11 pr-4 bg-gray-50 border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>

            <!-- Right Side Actions -->
            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="hidden sm:flex h-10 px-5 text-slate-700 font-medium rounded-full items-center justify-center border border-gray-200 hover:bg-gray-50 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="hidden sm:flex h-10 px-5 bg-blue-600 text-white font-medium rounded-full items-center justify-center hover:bg-blue-700 transition-colors">
                        Sign Up
                    </a>
                @else
                    <!-- Wishlist -->
                    <a href="{{ route('wishlist') }}" class="relative p-2 text-slate-600 hover:text-slate-900 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span x-show="wishlistCount > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center" x-text="wishlistCount"></span>
                    </a>
                    
                    <!-- Cart -->
                    <a href="{{ route('cart') }}" class="relative p-2 text-slate-600 hover:text-slate-900 hover:bg-gray-100 rounded-full transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span x-show="cartCount > 0" class="absolute -top-1 -right-1 w-5 h-5 bg-blue-600 text-white text-xs font-bold rounded-full flex items-center justify-center" x-text="cartCount"></span>
                    </a>

                    <!-- Profile Dropdown -->
                    <div class="relative hidden sm:block" @click.away="profileOpen = false">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 p-2 text-slate-600 hover:text-slate-900 hover:bg-gray-100 rounded-full transition-colors">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-semibold text-blue-600">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        
                        <div x-show="profileOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute top-full right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('orders') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-gray-50 hover:text-slate-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                My Orders
                            </a>
                            <a href="{{ route('wishlist') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-gray-50 hover:text-slate-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                Wishlist
                            </a>
                            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-gray-50 hover:text-slate-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profile Settings
                            </a>
                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-gray-50 hover:text-slate-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Admin Panel
                            </a>
                            @endif
                            <div class="border-t border-gray-100 mt-2 pt-2">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest

                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 text-slate-600 hover:bg-gray-100 rounded-full transition-colors">
                    <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenu" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
         class="lg:hidden bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-4">
            <!-- Mobile Search -->
            <form action="{{ route('shop.index') }}" method="GET">
                <div class="relative">
                    <input type="text" name="search" placeholder="Search products..." 
                        class="w-full h-11 pl-11 pr-4 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>
            
            <!-- Mobile Links -->
            <div class="space-y-1">
                <a href="/" class="block px-4 py-3 text-slate-700 font-medium rounded-xl hover:bg-gray-50">Home</a>
                <a href="{{ route('shop.index') }}" class="block px-4 py-3 text-slate-700 font-medium rounded-xl hover:bg-gray-50">Shop</a>
                <a href="{{ route('shop.index', ['category' => 'Electronics']) }}" class="block px-4 py-3 text-slate-600 rounded-xl hover:bg-gray-50 pl-8">Electronics</a>
                <a href="{{ route('shop.index', ['category' => 'Fashion']) }}" class="block px-4 py-3 text-slate-600 rounded-xl hover:bg-gray-50 pl-8">Fashion</a>
                <a href="{{ route('shop.index', ['category' => 'Home']) }}" class="block px-4 py-3 text-slate-600 rounded-xl hover:bg-gray-50 pl-8">Home & Living</a>
                <a href="/#contact" class="block px-4 py-3 text-slate-700 font-medium rounded-xl hover:bg-gray-50">Contact</a>
            </div>
            
            @guest
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('login') }}" class="flex-1 h-11 flex items-center justify-center text-slate-700 font-medium rounded-xl border border-gray-200 hover:bg-gray-50">Login</a>
                <a href="{{ route('register') }}" class="flex-1 h-11 flex items-center justify-center bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700">Sign Up</a>
            </div>
            @else
            <div class="pt-4 border-t border-gray-100 space-y-1">
                <a href="{{ route('orders') }}" class="block px-4 py-3 text-slate-700 rounded-xl hover:bg-gray-50">My Orders</a>
                <a href="{{ route('wishlist') }}" class="block px-4 py-3 text-slate-700 rounded-xl hover:bg-gray-50">Wishlist</a>
                <a href="{{ route('profile') }}" class="block px-4 py-3 text-slate-700 rounded-xl hover:bg-gray-50">Profile Settings</a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-slate-700 rounded-xl hover:bg-gray-50">Admin Panel</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full h-11 flex items-center justify-center text-red-600 font-medium rounded-xl border border-red-200 hover:bg-red-50">Logout</button>
                </form>
            </div>
            @endguest
        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div class="h-16 sm:h-[104px]"></div>
