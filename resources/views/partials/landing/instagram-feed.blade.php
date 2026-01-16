{{-- Instagram Feed Component - Enhanced --}}
@php
$instagramImages = [
    ['src' => 'https://images.unsplash.com/photo-1556906781-9a412961c28c?auto=format&fit=crop&q=80&w=400', 'likes' => '2.4K'],
    ['src' => 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?auto=format&fit=crop&q=80&w=400', 'likes' => '1.8K'],
    ['src' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&q=80&w=400', 'likes' => '3.2K'],
    ['src' => 'https://images.unsplash.com/photo-1558171813-4c088753af8f?auto=format&fit=crop&q=80&w=400', 'likes' => '2.1K'],
    ['src' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=400', 'likes' => '4.5K'],
    ['src' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400', 'likes' => '5.2K'],
];
@endphp

<section class="py-20 overflow-hidden bg-white">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        {{-- Section Header --}}
        <div class="mb-14 text-center reveal">
            {{-- Instagram Icon - Gradient Circle with Outlined Logo --}}
            <div class="relative inline-block mb-8">
                <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 flex items-center justify-center p-[3px]">
                    <div class="w-full h-full rounded-full bg-white flex items-center justify-center">
                        <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none">
                            <defs>
                                <linearGradient id="instagram-gradient" x1="0%" y1="100%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#FFDC80"/>
                                    <stop offset="25%" style="stop-color:#F77737"/>
                                    <stop offset="50%" style="stop-color:#E1306C"/>
                                    <stop offset="75%" style="stop-color:#C13584"/>
                                    <stop offset="100%" style="stop-color:#833AB4"/>
                                </linearGradient>
                            </defs>
                            <rect x="2" y="2" width="20" height="20" rx="5" stroke="url(#instagram-gradient)" stroke-width="2"/>
                            <circle cx="12" cy="12" r="4" stroke="url(#instagram-gradient)" stroke-width="2"/>
                            <circle cx="17.5" cy="6.5" r="1.5" fill="url(#instagram-gradient)"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <h3 class="text-3xl md:text-4xl font-serif tracking-wide text-slate-800 mb-3">
                Follow Our Journey
            </h3>
            <a href="https://instagram.com/shopease" target="_blank" 
               class="text-base text-slate-400 hover:text-pink-500 transition-colors inline-flex items-center gap-2">
                @shopease
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>

        {{-- Instagram Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 stagger-container">
            @foreach($instagramImages as $index => $image)
                <a href="https://instagram.com/shopease" target="_blank" 
                   class="aspect-square bg-slate-100 overflow-hidden block stagger-item group relative rounded-lg">
                    
                    <img 
                        src="{{ $image['src'] }}" 
                        alt="Instagram feed item {{ $index + 1 }}" 
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                    
                    {{-- Hover Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent 
                                opacity-0 group-hover:opacity-100 transition-all duration-300 
                                flex flex-col items-center justify-center rounded-lg">
                        
                        {{-- Like Count --}}
                        <div class="flex items-center gap-2 text-white transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span class="text-sm font-medium">{{ $image['likes'] }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Follow Button - Gradient --}}
        <div class="mt-14 text-center reveal">
            <a href="https://instagram.com/shopease" target="_blank" 
               class="inline-flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-yellow-400 via-pink-500 to-purple-600 
                      text-white text-[12px] font-bold tracking-[0.2em] uppercase rounded-lg
                      hover:shadow-xl hover:shadow-pink-500/30 transition-all duration-300 
                      transform hover:-translate-y-1">
                {{-- Instagram Icon --}}
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>
                </svg>
                Follow Us on Instagram
            </a>
        </div>
    </div>
</section>
