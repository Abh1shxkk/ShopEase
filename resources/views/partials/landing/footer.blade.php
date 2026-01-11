{{-- Footer Component --}}
@php
    $footerSettings = $footerSettings ?? [
        'email' => 'abhichauhan200504@gmail.com',
        'phone' => '+91 8279422813',
        'address' => 'Baral, Partapur, Meerut, Uttar Pradesh, India',
        'copyright' => '© ' . date('Y') . ' ShopEase. All rights reserved.',
        'instagram_url' => 'https://instagram.com/shopease',
        'facebook_url' => 'https://facebook.com/shopease',
        'twitter_url' => 'https://twitter.com/shopease',
        'linkedin_url' => 'https://www.linkedin.com/in/abhishek-chauhan-880496394',
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
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ $footerSettings['email'] }}" class="hover:text-blue-600 transition">{{ $footerSettings['email'] }}</a>
                    </p>
                    @endif
                    @if($footerSettings['phone'])
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:{{ $footerSettings['phone'] }}" class="hover:text-blue-600 transition">{{ $footerSettings['phone'] }}</a>
                    </p>
                    @endif
                    @if($footerSettings['address'])
                    <p class="flex items-start gap-2 mt-4 text-slate-500">
                        <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="leading-relaxed">{!! nl2br(e($footerSettings['address'])) !!}</span>
                    </p>
                    @endif
                </div>

                {{-- Social Icons with Colors --}}
                <div class="flex gap-4 mt-8">
                    @if($footerSettings['instagram_url'] ?? false)
                    <a href="{{ $footerSettings['instagram_url'] }}" class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 flex items-center justify-center text-white hover:scale-110 transition-transform" target="_blank" rel="noopener" title="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    @endif
                    
                    @if($footerSettings['facebook_url'] ?? false)
                    <a href="{{ $footerSettings['facebook_url'] }}" class="w-9 h-9 rounded-full bg-[#1877F2] flex items-center justify-center text-white hover:scale-110 transition-transform" target="_blank" rel="noopener" title="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    
                    @if($footerSettings['twitter_url'] ?? false)
                    <a href="{{ $footerSettings['twitter_url'] }}" class="w-9 h-9 rounded-full bg-black flex items-center justify-center text-white hover:scale-110 transition-transform" target="_blank" rel="noopener" title="X (Twitter)">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    @endif
                    
                    @if($footerSettings['linkedin_url'] ?? false)
                    <a href="{{ $footerSettings['linkedin_url'] }}" class="w-9 h-9 rounded-full bg-[#0A66C2] flex items-center justify-center text-white hover:scale-110 transition-transform" target="_blank" rel="noopener" title="LinkedIn">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    @endif
                    
                    @if($footerSettings['youtube_url'] ?? false)
                    <a href="{{ $footerSettings['youtube_url'] }}" class="w-9 h-9 rounded-full bg-[#FF0000] flex items-center justify-center text-white hover:scale-110 transition-transform" target="_blank" rel="noopener" title="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Navigation Links --}}
            <div class="grid grid-cols-2 gap-8 md:col-span-2">
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.2em] uppercase mb-8">{{ __('messages.nav.shop') }}</h4>
                    <ul class="space-y-4 text-[12px]">
                        @if(isset($footerLinks['shop']) && $footerLinks['shop']->count() > 0)
                            @foreach($footerLinks['shop'] as $link)
                            <li><a href="{{ $link->url }}" class="hover:underline">{{ $link->title }}</a></li>
                            @endforeach
                        @else
                            <li><a href="{{ route('shop.index', ['gender' => 'women']) }}" class="hover:underline">{{ __('messages.nav.women') }}</a></li>
                            <li><a href="{{ route('shop.index', ['gender' => 'men']) }}" class="hover:underline">{{ __('messages.nav.men') }}</a></li>
                            <li><a href="{{ route('shop.index') }}" class="hover:underline">{{ __('messages.common.view_all') }}</a></li>
                            <li><a href="{{ route('blog.index') }}" class="hover:underline">Journal</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold tracking-[0.2em] uppercase mb-8">{{ __('messages.footer.help') }}</h4>
                    <ul class="space-y-4 text-[12px]">
                        <li><a href="{{ route('support.index') }}" class="hover:underline">{{ __('messages.support.title') }}</a></li>
                        <li><a href="{{ route('support.faq') }}" class="hover:underline">{{ __('messages.support.faq') }}</a></li>
                        <li><a href="{{ route('support.ticket.create') }}" class="hover:underline">{{ __('messages.support.contact_us') }}</a></li>
                        <li><a href="{{ route('orders.track.form') }}" class="hover:underline">Track Order</a></li>
                    </ul>
                </div>
            </div>

            {{-- Account Links + Newsletter --}}
            <div>
                <h4 class="text-[10px] font-bold tracking-[0.2em] uppercase mb-8">{{ __('messages.nav.account') }}</h4>
                <ul class="space-y-4 text-[12px] mb-10">
                    @auth
                        <li><a href="{{ route('profile') }}" class="hover:underline">{{ __('messages.nav.profile') }}</a></li>
                        <li><a href="{{ route('orders') }}" class="hover:underline">{{ __('messages.account.my_orders') }}</a></li>
                        <li><a href="{{ route('wishlist') }}" class="hover:underline">{{ __('messages.account.my_wishlist') }}</a></li>
                        <li><a href="{{ route('cart') }}" class="hover:underline">{{ __('messages.cart.title') }}</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:underline">{{ __('messages.nav.login') }}</a></li>
                        <li><a href="{{ route('register') }}" class="hover:underline">{{ __('messages.nav.register') }}</a></li>
                    @endauth
                </ul>
                
                {{-- Newsletter --}}
                <h4 class="text-[12px] font-bold tracking-[0.1em] mb-6">{{ __('messages.footer.newsletter_text') }}</h4>
                <form id="newsletter-form" class="space-y-4">
                    @csrf
                    <div class="border-b border-slate-900">
                        <input 
                            type="email" 
                            name="email"
                            id="newsletter-email"
                            placeholder="{{ __('messages.auth.email') }}" 
                            class="w-full py-3 text-[13px] bg-transparent focus:outline-none placeholder:text-slate-300"
                            required
                        />
                    </div>
                    <button type="submit" id="newsletter-btn" class="w-full bg-slate-900 text-white py-4 text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-all rounded-none">
                        {{ __('messages.footer.subscribe') }}
                    </button>
                    <p id="newsletter-message" class="text-[11px] hidden"></p>
                </form>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-12 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
            {{-- Payment Icons - Real Colored --}}
            <div class="flex items-center gap-3">
                {{-- Visa --}}
                <div class="h-8 px-2 bg-white border border-slate-200 rounded flex items-center justify-center">
                    <svg class="h-5" viewBox="0 0 780 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M293.2 348.7l33.4-195.8h53.4l-33.4 195.8h-53.4z" fill="#1434CB"/>
                        <path d="M524.3 156.5c-10.5-4-27.1-8.3-47.7-8.3-52.6 0-89.7 26.5-89.9 64.5-.3 28.1 26.5 43.7 46.7 53.1 20.8 9.6 27.8 15.7 27.7 24.3-.1 13.1-16.6 19.1-32 19.1-21.4 0-32.7-3-50.3-10.3l-6.9-3.1-7.5 43.8c12.5 5.5 35.6 10.2 59.6 10.5 56 0 92.3-26.2 92.7-66.8.2-22.3-14-39.2-44.8-53.2-18.6-9.1-30.1-15.1-30-24.3 0-8.1 9.7-16.8 30.6-16.8 17.4-.3 30.1 3.5 40 7.5l4.8 2.3 7.2-42.3h.1z" fill="#1434CB"/>
                        <path d="M661.6 152.9h-41.2c-12.8 0-22.3 3.5-27.9 16.2l-79.2 179.6h56l11.2-29.4h68.4c1.6 6.8 6.5 29.4 6.5 29.4h49.5l-43.3-195.8zm-65.8 126.5c4.4-11.3 21.3-54.8 21.3-54.8-.3.5 4.4-11.4 7.1-18.8l3.6 17s10.2 46.8 12.4 56.6h-44.4z" fill="#1434CB"/>
                        <path d="M232.9 152.9l-52.1 133.5-5.5-27c-9.6-30.9-39.6-64.4-73.1-81.2l47.7 170.4h56.4l83.9-195.7h-57.3z" fill="#1434CB"/>
                        <path d="M138.2 152.9H52.5l-.6 3.6c66.9 16.2 111.2 55.3 129.5 102.3l-18.7-90c-3.2-12.3-12.5-15.5-24.5-15.9z" fill="#F9A533"/>
                    </svg>
                </div>
                {{-- Mastercard --}}
                <div class="h-8 px-2 bg-white border border-slate-200 rounded flex items-center justify-center">
                    <svg class="h-5" viewBox="0 0 780 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="312" cy="250" r="150" fill="#EB001B"/>
                        <circle cx="468" cy="250" r="150" fill="#F79E1B"/>
                        <path d="M390 130.7c-38.5 30.5-63.2 77.5-63.2 130.3s24.7 99.8 63.2 130.3c38.5-30.5 63.2-77.5 63.2-130.3s-24.7-99.8-63.2-130.3z" fill="#FF5F00"/>
                    </svg>
                </div>
                {{-- American Express --}}
                <div class="h-8 px-2 bg-[#006FCF] border border-slate-200 rounded flex items-center justify-center">
                    <span class="text-white text-[10px] font-bold tracking-tight">AMEX</span>
                </div>
                {{-- RuPay --}}
                <div class="h-8 px-2 bg-white border border-slate-200 rounded flex items-center justify-center">
                    <span class="text-[10px] font-bold" style="background: linear-gradient(90deg, #097A44 0%, #F37021 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">RuPay</span>
                </div>
                {{-- UPI --}}
                <div class="h-8 px-3 bg-white border border-slate-200 rounded flex items-center justify-center gap-1">
                    <svg class="h-4" viewBox="0 0 100 40" fill="none">
                        <rect width="100" height="40" rx="4" fill="white"/>
                        <text x="10" y="28" font-size="16" font-weight="bold" fill="#5F259F">U</text>
                        <text x="25" y="28" font-size="16" font-weight="bold" fill="#5F259F">P</text>
                        <text x="40" y="28" font-size="16" font-weight="bold" fill="#5F259F">I</text>
                    </svg>
                </div>
                {{-- Google Pay --}}
                <div class="h-8 px-2 bg-white border border-slate-200 rounded flex items-center justify-center">
                    <svg class="h-4" viewBox="0 0 435.97 173.13" fill="none">
                        <path d="M206.2,84.58v50.75H190.1V10h42.7a38.61,38.61,0,0,1,27.65,10.85A34.88,34.88,0,0,1,272,47.3a34.72,34.72,0,0,1-11.55,26.6q-11.2,10.68-27.65,10.67H206.2Zm0-59.15V69.16h27a21.28,21.28,0,0,0,15.93-6.48,21.36,21.36,0,0,0,0-30.63,20.78,20.78,0,0,0-15.93-6.62Z" fill="#5F6368"/>
                        <path d="M309.1,46.78q17.85,0,28.18,9.54T347.6,82.48v52.85H332.18v-11.9h-.7q-10,14.63-26.6,14.62-14.18,0-23.72-8.4a26.88,26.88,0,0,1-9.54-21.17q0-13.44,10.15-21.35t27-7.91q14.34,0,23.62,5.25V80.89a18.45,18.45,0,0,0-6.83-14.52,24.27,24.27,0,0,0-16.45-5.95q-14.34,0-22.75,12.07l-14-8.75Q283.08,46.78,309.1,46.78Zm-20.83,62.3a12.86,12.86,0,0,0,5.34,10.5,19.64,19.64,0,0,0,12.51,4.2,25.67,25.67,0,0,0,18.11-7.52q8-7.53,8-17.5-7.53-6-21-6-9.81,0-16.37,4.9T288.27,109.08Z" fill="#5F6368"/>
                        <path d="M436,49.53,382.24,173.13H365.09l20-43.23-35.35-80.37h18.2l24.85,60.55h.35l24.15-60.55Z" fill="#5F6368"/>
                        <path d="M141.14,73.64A85.79,85.79,0,0,0,139.9,59H72V86.73h38.89a33.33,33.33,0,0,1-14.38,21.88v18h23.21C133.31,114.08,141.14,95.55,141.14,73.64Z" fill="#4285F4"/>
                        <path d="M72,135.33c19.43,0,35.79-6.38,47.72-17.33l-23.21-18c-6.46,4.38-14.78,6.88-24.51,6.88-18.78,0-34.72-12.66-40.42-29.72H7.67v18.55A72,72,0,0,0,72,135.33Z" fill="#34A853"/>
                        <path d="M31.58,77.19a43.39,43.39,0,0,1,0-27.71V30.93H7.67a72.15,72.15,0,0,0,0,64.81Z" fill="#FBBC04"/>
                        <path d="M72,28.76a39,39,0,0,1,27.62,10.8l20.55-20.55A69.18,69.18,0,0,0,72,0,72,72,0,0,0,7.67,30.93l23.91,18.55C37.28,32.42,53.22,28.76,72,28.76Z" fill="#EA4335"/>
                    </svg>
                </div>
                {{-- PhonePe --}}
                <div class="h-8 px-2 bg-[#5F259F] border border-slate-200 rounded flex items-center justify-center">
                    <span class="text-white text-[9px] font-bold">PhonePe</span>
                </div>
            </div>
            
            <p class="text-[10px] font-medium tracking-widest text-slate-400 uppercase">
                {{ $footerSettings['copyright'] }}
            </p>
        </div>
    </div>
</footer>

{{-- Newsletter Script --}}
<script>
document.getElementById('newsletter-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const email = document.getElementById('newsletter-email').value;
    const btn = document.getElementById('newsletter-btn');
    const msg = document.getElementById('newsletter-message');
    const originalText = btn.textContent;
    
    btn.disabled = true;
    btn.textContent = '...';
    msg.classList.add('hidden');
    
    try {
        const response = await fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        });
        
        const data = await response.json();
        
        msg.classList.remove('hidden');
        if (data.success) {
            msg.textContent = data.message;
            msg.className = 'text-[11px] text-green-600';
            document.getElementById('newsletter-email').value = '';
        } else {
            msg.textContent = data.message;
            msg.className = 'text-[11px] text-red-600';
        }
    } catch (error) {
        msg.classList.remove('hidden');
        msg.textContent = 'Something went wrong. Please try again.';
        msg.className = 'text-[11px] text-red-600';
    }
    
    btn.disabled = false;
    btn.textContent = originalText;
});
</script>
