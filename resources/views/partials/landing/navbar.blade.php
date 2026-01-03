{{-- Navbar Component --}}
<header class="fixed top-0 left-0 right-0 z-50">
    {{-- Announcement Bar --}}
    <div class="bg-slate-50 border-b border-slate-200 py-2.5 text-center">
        <p class="text-[10px] font-medium tracking-[0.15em] uppercase text-slate-500">
            Free Shipping Over $250 | Free Exchanges <span class="ml-2">→</span>
        </p>
    </div>

    {{-- Main Navigation --}}
    <nav id="navbar" class="transition-all duration-300 border-b navbar-default">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12 flex items-center justify-between">
            
            {{-- Left Navigation (Desktop) --}}
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('shop.index', ['category' => 'Women']) }}" 
                   class="text-[11px] font-semibold tracking-widest uppercase text-slate-800 hover:text-slate-500 transition-colors">
                    Women
                </a>
                <a href="{{ route('shop.index', ['category' => 'Men']) }}" 
                   class="text-[11px] font-semibold tracking-widest uppercase text-slate-800 hover:text-slate-500 transition-colors">
                    Men
                </a>
                <a href="{{ route('shop.index', ['category' => 'Accessories']) }}" 
                   class="text-[11px] font-semibold tracking-widest uppercase text-slate-800 hover:text-slate-500 transition-colors">
                    Accessories
                </a>
                <a href="{{ route('shop.index') }}" 
                   class="text-[11px] font-semibold tracking-widest uppercase text-slate-800 hover:text-slate-500 transition-colors">
                    Shop All
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-btn" class="lg:hidden p-1 text-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Center Logo --}}
            <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                <a href="{{ route('home') }}" class="text-2xl font-serif tracking-[0.1em] text-slate-900">
                    Shop<span class="mx-1 italic text-blue-600">/</span>Ease
                </a>
            </div>

            {{-- Right Icons --}}
            <div class="flex items-center gap-5">
                {{-- Search --}}
                <a href="{{ route('shop.index') }}" class="p-1 hover:text-slate-500 transition-colors" title="Search Products">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>

                {{-- User Account --}}
                @auth
                    <a href="{{ route('profile') }}" class="hidden sm:block p-1 hover:text-slate-500 transition-colors" title="My Account">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:block p-1 hover:text-slate-500 transition-colors" title="Login">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                @endauth

                {{-- Wishlist --}}
                @auth
                    <a href="{{ route('wishlist') }}" class="hidden sm:block p-1 hover:text-slate-500 transition-colors" title="Wishlist">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:block p-1 hover:text-slate-500 transition-colors" title="Wishlist">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </a>
                @endauth

                {{-- Shopping Cart --}}
                @auth
                    <a href="{{ route('cart') }}" class="p-1 relative hover:text-slate-500 transition-colors" title="Shopping Cart">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @php
                            $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                        @endphp
                        <span class="absolute -top-1 -right-1 bg-slate-900 text-white text-[8px] w-3.5 h-3.5 flex items-center justify-center">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="p-1 relative hover:text-slate-500 transition-colors" title="Shopping Cart">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-slate-900 text-white text-[8px] w-3.5 h-3.5 flex items-center justify-center">
                            0
                        </span>
                    </a>
                @endauth
            </div>
        </div>

        {{-- Mobile Menu Overlay --}}
        <div id="mobile-menu" class="mobile-menu closed lg:hidden fixed inset-0 w-full h-screen bg-white z-[60] p-12">
            <button id="mobile-menu-close" class="absolute top-8 right-8">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <div class="flex flex-col gap-8 mt-12">
                {{-- Shop Categories --}}
                <a href="{{ route('shop.index', ['category' => 'Women']) }}" class="text-2xl font-serif tracking-wide">Women</a>
                <a href="{{ route('shop.index', ['category' => 'Men']) }}" class="text-2xl font-serif tracking-wide">Men</a>
                <a href="{{ route('shop.index', ['category' => 'Accessories']) }}" class="text-2xl font-serif tracking-wide">Accessories</a>
                <a href="{{ route('shop.index') }}" class="text-2xl font-serif tracking-wide">Shop All</a>
                
                <div class="border-t border-slate-200 pt-8 mt-4">
                    {{-- Account Links --}}
                    @auth
                        <a href="{{ route('profile') }}" class="text-xl font-medium tracking-wide block mb-4">My Account</a>
                        <a href="{{ route('orders') }}" class="text-xl font-medium tracking-wide block mb-4">My Orders</a>
                        <a href="{{ route('wishlist') }}" class="text-xl font-medium tracking-wide block mb-4">Wishlist</a>
                        <a href="{{ route('cart') }}" class="text-xl font-medium tracking-wide block mb-4">Cart</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xl font-medium tracking-wide text-red-600 hover:text-red-800">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-xl font-medium tracking-wide block mb-4">Login</a>
                        <a href="{{ route('register') }}" class="text-xl font-medium tracking-wide block">Create Account</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
