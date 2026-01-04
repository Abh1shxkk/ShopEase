{{-- Footer Component --}}
@php
    $footerSettings = $footerSettings ?? [
        'email' => 'support@shopease.com',
        'phone' => '+91 98765 43210',
        'address' => '123 Commerce Street, Mumbai, MH 400001',
        'copyright' => '© ' . date('Y') . ' ShopEase. All rights reserved.',
        'instagram_url' => '',
        'facebook_url' => '',
        'twitter_url' => '',
        'youtube_url' => '',
    ];
    $footerLinks = $footerLinks ?? collect();
@endphp
<footer class="bg-white border-t border-slate-100 py-24 text-slate-900">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-24">
            
            {{-- Logo & Contact Info --}}
            <div class="md:col-span-1">
                <a href="{{ route('home') }}" class="text-2xl font-serif tracking-[0.1em] mb-8 block">
                    Shop<span class="italic mx-1 text-blue-600">/</span>Ease
                </a>
                <div class="space-y-3 text-[12px] font-medium tracking-wide">
                    @if($footerSettings['email'])
                    <p>Email: {{ $footerSettings['email'] }}</p>
                    @endif
                    @if($footerSettings['phone'])
                    <p>Phone: {{ $footerSettings['phone'] }}</p>
                    @endif
                    @if($footerSettings['address'])
                    <p class="mt-6 leading-relaxed text-slate-500">
                        {!! nl2br(e($footerSettings['address'])) !!}
                    </p>
                    @endif
                </div>
                
                {{-- Social Icons --}}
                <div class="flex gap-6 mt-8">
                    @if($footerSettings['instagram_url'])
                    <a href="{{ $footerSettings['instagram_url'] }}" class="hover:text-slate-500 transition-colors" target="_blank" rel="noopener">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    @endif
                    
                    @if($footerSettings['facebook_url'])
                    <a href="{{ $footerSettings['facebook_url'] }}" class="hover:text-slate-500 transition-colors" target="_blank" rel="noopener">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    @endif
                    
                    @if($footerSettings['twitter_url'])
                    <a href="{{ $footerSettings['twitter_url'] }}" class="hover:text-slate-500 transition-colors" target="_blank" rel="noopener">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    @endif
                    
                    @if($footerSettings['youtube_url'])
                    <a href="{{ $footerSettings['youtube_url'] }}" class="hover:text-slate-500 transition-colors" target="_blank" rel="noopener">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    @endif
                    
                    {{-- Show default icons if no social links configured --}}
                    @if(!$footerSettings['instagram_url'] && !$footerSettings['facebook_url'] && !$footerSettings['twitter_url'] && !$footerSettings['youtube_url'])
                    <a href="#" class="hover:text-slate-500 transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                    <a href="#" class="hover:text-slate-500 transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                    <a href="#" class="hover:text-slate-500 transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                    @endif
                </div>
            </div>

            {{-- Navigation Links --}}
            <div class="grid grid-cols-2 gap-8 md:col-span-2">
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.2em] uppercase mb-8">Shop</h4>
                    <ul class="space-y-4 text-[12px]">
                        @if(isset($footerLinks['shop']) && $footerLinks['shop']->count() > 0)
                            @foreach($footerLinks['shop'] as $link)
                            <li><a href="{{ $link->url }}" class="hover:underline">{{ $link->title }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ route('shop.index', ['gender' => 'women']) }}" class="hover:underline">Women</a></li>
                            <li><a href="{{ route('shop.index', ['gender' => 'men']) }}" class="hover:underline">Men</a></li>
                            <li><a href="{{ route('shop.index') }}" class="hover:underline">All Products</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.2em] uppercase mb-8">Account</h4>
                    <ul class="space-y-4 text-[12px]">
                        @if(isset($footerLinks['account']) && $footerLinks['account']->count() > 0)
                            @foreach($footerLinks['account'] as $link)
                            <li><a href="{{ $link->url }}" class="hover:underline">{{ $link->title }}</a></li>
                            @endforeach
                        @else
                            @auth
                                <li><a href="{{ route('profile') }}" class="hover:underline">My Profile</a></li>
                                <li><a href="{{ route('orders') }}" class="hover:underline">My Orders</a></li>
                                <li><a href="{{ route('wishlist') }}" class="hover:underline">Wishlist</a></li>
                                <li><a href="{{ route('cart') }}" class="hover:underline">Shopping Cart</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="hover:underline">Login</a></li>
                                <li><a href="{{ route('register') }}" class="hover:underline">Create Account</a></li>
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Newsletter --}}
            <div>
                <h4 class="text-[12px] font-bold tracking-[0.1em] mb-6">Subscribe to our newsletter and don't miss a thing.</h4>
                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div class="border-b border-slate-900">
                        <input 
                            type="email" 
                            name="email"
                            placeholder="Email Address" 
                            class="w-full py-3 text-[13px] bg-transparent focus:outline-none placeholder:text-slate-300"
                            required
                        />
                    </div>
                    <button type="submit" class="w-full bg-slate-900 text-white py-4 text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-all rounded-none">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-12 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
            {{-- Payment Icons --}}
            <div class="flex gap-4 grayscale opacity-50">
                <div class="w-10 h-6 bg-slate-100 border border-slate-200 flex items-center justify-center text-[8px] font-bold">VISA</div>
                <div class="w-10 h-6 bg-slate-100 border border-slate-200 flex items-center justify-center text-[8px] font-bold">MC</div>
                <div class="w-10 h-6 bg-slate-100 border border-slate-200 flex items-center justify-center text-[8px] font-bold">AMEX</div>
                <div class="w-10 h-6 bg-slate-100 border border-slate-200 flex items-center justify-center text-[8px] font-bold">UPI</div>
            </div>
            
            <p class="text-[10px] font-medium tracking-widest text-slate-400 uppercase">
                {{ $footerSettings['copyright'] }}
            </p>
        </div>
    </div>
</footer>
