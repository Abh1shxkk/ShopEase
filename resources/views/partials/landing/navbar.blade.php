{{-- Navbar Component --}}
<style>
    /* Prevent FOUC - Critical CSS for navbar */
    #main-header { position: fixed; top: 0; left: 0; right: 0; z-index: 50; }
    #navbar { background: rgba(255,255,255,0.95); border-bottom: 1px solid #e2e8f0; }
</style>
<header id="main-header" class="fixed top-0 left-0 right-0 z-50 transition-transform duration-300 ease-out">
    {{-- Main Navigation --}}
    <nav id="navbar" class="border-b border-slate-200 bg-white/95 backdrop-blur-md transition-all duration-300">
        <div class="max-w-[1440px] mx-auto px-6 md:px-12 h-12 grid grid-cols-3 items-center">
            
            {{-- Left Navigation (Desktop) --}}
            <div class="hidden lg:flex items-center gap-6">
                <a href="{{ route('shop.index', ['gender' => 'women']) }}" 
                   class="text-[11px] font-semibold tracking-widest uppercase text-slate-800 hover:text-slate-500 transition-colors">
                    {{ __('messages.nav.women') }}
                </a>
                <a href="{{ route('shop.index', ['gender' => 'men']) }}" 
                   class="text-[11px] font-semibold tracking-widest uppercase text-slate-800 hover:text-slate-500 transition-colors">
                    {{ __('messages.nav.men') }}
                </a>
                <a href="{{ route('shop.index', ['category' => 'Accessories']) }}" 
                   class="text-[11px] font-semibold tracking-widest uppercase text-slate-800 hover:text-slate-500 transition-colors">
                    {{ __('messages.nav.accessories') }}
                </a>
                <a href="{{ route('bundles.index') }}" 
                   class="text-[11px] font-semibold tracking-widest uppercase text-red-600 hover:text-red-500 transition-colors">
                    Offers
                </a>
                <a href="{{ route('flash-sales.index') }}" 
                   class="text-[11px] font-semibold tracking-widest uppercase text-amber-600 hover:text-amber-500 transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Flash Sale
                </a>
                <a href="{{ route('shop.index') }}" 
                   class="text-[11px] font-semibold tracking-widest uppercase text-slate-800 hover:text-slate-500 transition-colors">
                    {{ __('messages.nav.shop') }}
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <div class="lg:hidden">
                <button id="mobile-menu-btn" class="p-1 text-slate-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- Center Logo --}}
            <div class="flex justify-center">
                <a href="{{ route('home') }}" class="text-xl font-serif tracking-[0.1em] text-slate-900">
                    Shop<span class="mx-1 italic text-blue-600">/</span>Ease
                </a>
            </div>

            {{-- Right Icons --}}
            <div class="flex items-center gap-3 justify-end">
                {{-- Language Switcher --}}
                @include('components.language-switcher')

                {{-- Search --}}
                <div class="relative" x-data="{ searchOpen: false }">
                    <button @click="searchOpen = !searchOpen" class="p-1 hover:text-slate-500 transition-colors" title="Search">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                    
                    {{-- Search Dropdown --}}
                    <div x-show="searchOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         @click.away="searchOpen = false"
                         class="absolute right-0 top-full mt-2 w-80 z-50"
                         style="display: none;">
                        @include('components.search-box')
                    </div>
                </div>

                {{-- User Account with Dropdown --}}
                @auth
                    <div class="relative hidden sm:block" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="p-1 hover:text-slate-500 transition-colors relative" title="My Account">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            @if(auth()->user()->isMember())
                            <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-purple-500 rounded-full border border-white"></span>
                            @endif
                        </button>
                        {{-- Profile Dropdown --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute right-0 top-full mt-1 z-50"
                             style="display: none;">
                            <div class="bg-white rounded-lg border border-slate-200 shadow-xl min-w-[200px] overflow-hidden">
                                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100">
                                    <p class="text-[12px] font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                    <p class="text-[10px] text-slate-500">{{ auth()->user()->email }}</p>
                                    @if(auth()->user()->isMember())
                                    <div class="mt-2 inline-flex items-center gap-1 px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-[9px] font-semibold">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                        {{ auth()->user()->getCurrentPlan()?->name ?? 'Member' }}
                                    </div>
                                    @endif
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 text-[11px] text-slate-700 hover:bg-slate-50">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        {{ __('messages.nav.profile') }}
                                    </a>
                                    @if(auth()->user()->isMember())
                                    <a href="{{ route('membership.manage') }}" class="flex items-center gap-2 px-4 py-2 text-[11px] text-purple-700 hover:bg-purple-50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        My Membership
                                    </a>
                                    @else
                                    <a href="{{ route('membership.index') }}" class="flex items-center gap-2 px-4 py-2 text-[11px] text-purple-700 hover:bg-purple-50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                        Become a Member
                                    </a>
                                    @endif
                                    <a href="{{ route('orders') }}" class="flex items-center gap-2 px-4 py-2 text-[11px] text-slate-700 hover:bg-slate-50">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        {{ __('messages.account.my_orders') }}
                                    </a>
                                    <a href="{{ route('wishlist') }}" class="flex items-center gap-2 px-4 py-2 text-[11px] text-slate-700 hover:bg-slate-50">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        {{ __('messages.nav.wishlist') }}
                                    </a>
                                    <a href="{{ route('loyalty.index') }}" class="flex items-center gap-2 px-4 py-2 text-[11px] text-amber-700 hover:bg-amber-50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Loyalty Points
                                        @if(auth()->user()->reward_points > 0)
                                        <span class="ml-auto text-[9px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded-full">{{ number_format(auth()->user()->reward_points) }} pts</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('referrals.index') }}" class="flex items-center gap-2 px-4 py-2 text-[11px] text-slate-700 hover:bg-slate-50">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        Refer & Earn
                                    </a>
                                    <a href="{{ route('cart') }}" class="flex items-center gap-2 px-4 py-2 text-[11px] text-slate-700 hover:bg-slate-50">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        {{ __('messages.nav.cart') }}
                                    </a>
                                </div>
                                <div class="border-t border-slate-100 py-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-[11px] text-red-600 hover:bg-red-50">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            {{ __('messages.nav.logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <a href="{{ route('cart') }}" class="p-1 relative hover:text-slate-500 transition-colors" title="Cart">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity'); @endphp
                        <span data-cart-count class="absolute -top-1 -right-1 bg-slate-900 text-white text-[8px] w-3.5 h-3.5 rounded-full flex items-center justify-center" style="display: {{ $cartCount > 0 ? 'flex' : 'none' }}">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="p-1 hover:text-slate-500 transition-colors" title="Cart">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
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
            
            <div class="flex flex-col gap-6 mt-12">
                <a href="{{ route('shop.index', ['gender' => 'women']) }}" class="text-2xl font-serif tracking-wide">{{ __('messages.nav.women') }}</a>
                <a href="{{ route('shop.index', ['gender' => 'men']) }}" class="text-2xl font-serif tracking-wide">{{ __('messages.nav.men') }}</a>
                <a href="{{ route('shop.index', ['category' => 'Accessories']) }}" class="text-2xl font-serif tracking-wide">{{ __('messages.nav.accessories') }}</a>
                <a href="{{ route('bundles.index') }}" class="text-2xl font-serif tracking-wide text-red-600">Offers</a>
                <a href="{{ route('flash-sales.index') }}" class="text-2xl font-serif tracking-wide text-amber-600 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Flash Sale
                </a>
                <a href="{{ route('shop.index') }}" class="text-2xl font-serif tracking-wide">{{ __('messages.nav.shop') }}</a>
                
                <div class="border-t border-slate-200 pt-6 mt-4">
                    @auth
                        <a href="{{ route('profile') }}" class="text-lg font-medium block mb-3">{{ __('messages.account.my_account') }}</a>
                        @if(auth()->user()->isMember())
                        <a href="{{ route('membership.manage') }}" class="text-lg font-medium block mb-3 text-purple-600 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            My Membership
                        </a>
                        @else
                        <a href="{{ route('membership.index') }}" class="text-lg font-medium block mb-3 text-purple-600 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            Become a Member
                        </a>
                        @endif
                        <a href="{{ route('orders') }}" class="text-lg font-medium block mb-3">{{ __('messages.account.my_orders') }}</a>
                        <a href="{{ route('wishlist') }}" class="text-lg font-medium block mb-3">{{ __('messages.account.my_wishlist') }}</a>
                        <a href="{{ route('cart') }}" class="text-lg font-medium block mb-3">{{ __('messages.nav.cart') }}</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-lg font-medium text-red-600">{{ __('messages.nav.logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-lg font-medium block mb-3">{{ __('messages.nav.login') }}</a>
                        <a href="{{ route('register') }}" class="text-lg font-medium block">{{ __('messages.nav.register') }}</a>
                    @endauth
                </div>

                {{-- Language Switch (Mobile) --}}
                <div class="border-t border-slate-200 pt-6 mt-4">
                    <p class="text-sm text-slate-500 mb-3">{{ __('messages.common.language') }}</p>
                    <div class="flex gap-3">
                        <a href="{{ route('language.switch', 'en') }}" 
                           class="flex items-center gap-2 px-4 py-2 border {{ app()->getLocale() === 'en' ? 'border-slate-900 bg-slate-50' : 'border-slate-200' }}">
                            <span>🇬🇧</span> English
                        </a>
                        <a href="{{ route('language.switch', 'hi') }}" 
                           class="flex items-center gap-2 px-4 py-2 border {{ app()->getLocale() === 'hi' ? 'border-slate-900 bg-slate-50' : 'border-slate-200' }}">
                            <span>🇮🇳</span> हिंदी
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
(function() {
    let lastScrollY = 0;
    let ticking = false;
    const header = document.getElementById('main-header');
    const navbar = document.getElementById('navbar');
    const navbarHeight = 48; // h-12 = 48px
    
    function updateHeader() {
        const currentScrollY = window.scrollY;
        const scrollDelta = currentScrollY - lastScrollY;
        
        if (currentScrollY <= 10) {
            // At top - show everything normally, no shadow
            header.style.transform = 'translateY(0)';
            navbar.classList.remove('shadow-md');
        } else if (scrollDelta > 0 && currentScrollY > navbarHeight) {
            // Scrolling DOWN & past header - hide navbar completely
            header.style.transform = 'translateY(-100%)';
        } else if (scrollDelta < 0) {
            // Scrolling UP - show navbar with shadow
            header.style.transform = 'translateY(0)';
            navbar.classList.add('shadow-md');
        }
        
        lastScrollY = currentScrollY;
        ticking = false;
    }
    
    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateHeader);
            ticking = true;
        }
    }, { passive: true });
    
    // Initial state
    updateHeader();
})();
</script>