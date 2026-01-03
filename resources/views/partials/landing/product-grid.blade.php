{{-- Product Grid Component --}}
@php
$products = [
    ['id' => 'p1', 'name' => 'Kilim Weekender Bag', 'price' => 745.00, 'category' => 'Travel', 'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&q=80&w=800', 'rating' => 5, 'tag' => 'Artisanal'],
    ['id' => 'p2', 'name' => "Men's Kilim Loafers", 'price' => 395.00, 'category' => 'Footwear', 'image' => 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?auto=format&fit=crop&q=80&w=800', 'rating' => 4.9, 'tag' => 'Classic'],
    ['id' => 'p3', 'name' => "Women's Kilim Clogs", 'price' => 245.00, 'category' => 'Footwear', 'image' => 'https://images.unsplash.com/photo-1603191659812-ee978eeeef76?auto=format&fit=crop&q=80&w=800', 'rating' => 4.8, 'tag' => null],
    ['id' => 'p4', 'name' => 'Geometric Travel Tote', 'price' => 185.00, 'category' => 'Travel', 'image' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&q=80&w=800', 'rating' => 4.7, 'tag' => null],
    ['id' => 'p5', 'name' => 'Hand-woven Scarf', 'price' => 85.00, 'category' => 'Accessories', 'image' => 'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?auto=format&fit=crop&q=80&w=800', 'rating' => 4.9, 'tag' => null],
    ['id' => 'p6', 'name' => 'Leather Bound Journal', 'price' => 45.00, 'category' => 'Lifestyle', 'image' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&q=80&w=800', 'rating' => 4.6, 'tag' => null],
];
@endphp

<section id="shop" class="py-24 bg-white overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-6 md:px-12 relative">
        
        {{-- Curated Collection Header --}}
        <div class="mb-16 flex items-center justify-between">
            <div class="w-1/3 invisible lg:visible"></div>
            <div class="text-center">
                <h2 class="text-3xl font-serif tracking-wide mb-4">Curated Collection</h2>
                <div class="w-12 h-[1px] bg-slate-900 mx-auto"></div>
            </div>
            <div class="flex gap-4 w-1/3 justify-end">
                {{-- Scroll Left Button --}}
                <button 
                    id="product-prev"
                    class="scroll-arrow p-2 border border-slate-200"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                {{-- Scroll Right Button --}}
                <button 
                    id="product-next"
                    class="scroll-arrow p-2 border border-slate-200"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Horizontal Slider for Curated Collection --}}
        <div 
            id="product-scroll"
            class="flex overflow-x-auto hide-scrollbar snap-x scroll-smooth"
        >
            @foreach($products as $product)
                <div class="snap-start flex-shrink-0 w-full sm:w-1/2 md:w-1/4 px-4 first:pl-0 last:pr-0">
                    {{-- Product Card --}}
                    <div class="group cursor-pointer">
                        <div class="relative aspect-[3/4] overflow-hidden mb-6 bg-[#f7f7f7]">
                            <img 
                                src="{{ $product['image'] }}" 
                                alt="{{ $product['name'] }}"
                                class="w-full h-full object-cover image-zoom"
                            />
                            
                            {{-- Overlay on hover --}}
                            <div class="product-card-overlay absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100"></div>

                            {{-- Top-Left Shopping Bag Icon --}}
                            <div class="absolute top-4 left-4 product-card-icon">
                                <button class="p-2 text-white hover:text-slate-200 transition-colors drop-shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Top-Right Heart Icon --}}
                            <div class="absolute top-4 right-4 product-card-icon product-card-icon-delayed">
                                <button class="p-2 text-white hover:text-red-400 transition-colors drop-shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </div>

                            @if($product['tag'])
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
                                    <span class="text-[8px] font-bold tracking-[0.2em] uppercase bg-white/90 backdrop-blur-sm px-3 py-1.5 shadow-sm border border-slate-100">
                                        {{ $product['tag'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex flex-col md:flex-row justify-between items-baseline gap-2">
                            <h3 class="text-[13px] font-medium tracking-tight text-slate-900 hover:text-slate-600 transition-colors">
                                {{ $product['name'] }}
                            </h3>
                            <p class="text-[12px] font-semibold text-slate-900 whitespace-nowrap">
                                Rs. {{ number_format($product['price'], 2) }}
                            </p>
                        </div>
                        <p class="text-[10px] text-slate-400 tracking-widest uppercase mt-1">{{ $product['category'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- New Arrivals Section --}}
        <div class="mt-32 mb-16 text-center">
            <h2 class="text-3xl font-serif tracking-wide mb-4">New Arrivals</h2>
            <div class="w-12 h-[1px] bg-slate-900 mx-auto"></div>
        </div>

        {{-- Standard Grid for New Arrivals --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-x-12 gap-y-20">
            @foreach(array_slice($products, 0, 6) as $product)
                <div class="w-full px-0">
                    <div class="group cursor-pointer">
                        <div class="relative aspect-[3/4] overflow-hidden mb-6 bg-[#f7f7f7]">
                            <img 
                                src="{{ $product['image'] }}" 
                                class="w-full h-full object-cover image-zoom"
                                alt="{{ $product['name'] }}"
                            />
                            <div class="product-card-overlay absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100"></div>
                            
                            <div class="absolute top-4 left-4 product-card-icon">
                                <button class="p-2 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="absolute top-4 right-4 product-card-icon product-card-icon-delayed">
                                <button class="p-2 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex flex-col md:flex-row justify-between items-baseline gap-2">
                            <h3 class="text-[13px] font-medium tracking-tight text-slate-900">{{ $product['name'] }}</h3>
                            <p class="text-[12px] font-semibold text-slate-900">Rs. {{ number_format($product['price'], 2) }}</p>
                        </div>
                        <p class="text-[10px] text-slate-400 tracking-widest uppercase mt-1">{{ $product['category'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- View More Button --}}
        <div class="mt-20 text-center">
            <button class="text-[11px] font-bold tracking-[0.2em] uppercase underline underline-offset-8 decoration-slate-200 underline-animate hover:decoration-slate-900 transition-all">
                View More
            </button>
        </div>
    </div>
</section>
