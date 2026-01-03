{{-- Product Grid Component --}}
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
                    class="scroll-arrow p-2 border border-slate-200 hover:border-slate-900 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                {{-- Scroll Right Button --}}
                <button 
                    id="product-next"
                    class="scroll-arrow p-2 border border-slate-200 hover:border-slate-900 transition-colors"
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
            @forelse($curatedProducts ?? [] as $product)
                <div class="snap-start flex-shrink-0 w-full sm:w-1/2 md:w-1/4 px-4 first:pl-0 last:pr-0">
                    {{-- Product Card --}}
                    <a href="{{ route('shop.show', $product) }}" class="group cursor-pointer block">
                        <div class="relative aspect-[3/4] overflow-hidden mb-6 bg-[#f7f7f7]">
                            @if($product->image)
                            <img 
                                src="{{ $product->image_url }}" 
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover image-zoom"
                            />
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                            
                            {{-- Overlay on hover --}}
                            <div class="product-card-overlay absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100"></div>

                            {{-- Top-Left Shopping Bag Icon --}}
                            <div class="absolute top-4 left-4 product-card-icon">
                                <form action="{{ route('cart.add') }}" method="POST" onclick="event.stopPropagation();">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="p-2 text-white hover:text-slate-200 transition-colors drop-shadow-md">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            {{-- Top-Right Heart Icon --}}
                            <div class="absolute top-4 right-4 product-card-icon product-card-icon-delayed">
                                <form action="{{ route('wishlist.toggle') }}" method="POST" onclick="event.stopPropagation();">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="p-2 text-white hover:text-red-400 transition-colors drop-shadow-md">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            @if($product->discount_price)
                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
                                    <span class="text-[8px] font-bold tracking-[0.2em] uppercase bg-white/90 backdrop-blur-sm px-3 py-1.5 shadow-sm border border-slate-100">
                                        Sale
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex flex-col md:flex-row justify-between items-baseline gap-2">
                            <h3 class="text-[13px] font-medium tracking-tight text-slate-900 hover:text-slate-600 transition-colors">
                                {{ $product->name }}
                            </h3>
                            <p class="text-[12px] font-semibold text-slate-900 whitespace-nowrap">
                                Rs. {{ number_format($product->discount_price ?? $product->price, 2) }}
                            </p>
                        </div>
                        <p class="text-[10px] text-slate-400 tracking-widest uppercase mt-1">{{ $product->category_name }}</p>
                    </a>
                </div>
            @empty
                <div class="w-full text-center py-12 text-slate-500">
                    No products available yet.
                </div>
            @endforelse
        </div>

        {{-- New Arrivals Section --}}
        <div class="mt-32 mb-16 text-center">
            <h2 class="text-3xl font-serif tracking-wide mb-4">New Arrivals</h2>
            <div class="w-12 h-[1px] bg-slate-900 mx-auto"></div>
        </div>

        {{-- Standard Grid for New Arrivals --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-x-12 gap-y-20">
            @forelse($newArrivals ?? [] as $product)
                <div class="w-full px-0">
                    <a href="{{ route('shop.show', $product) }}" class="group cursor-pointer block">
                        <div class="relative aspect-[3/4] overflow-hidden mb-6 bg-[#f7f7f7]">
                            @if($product->image)
                            <img 
                                src="{{ $product->image_url }}" 
                                class="w-full h-full object-cover image-zoom"
                                alt="{{ $product->name }}"
                            />
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                            <div class="product-card-overlay absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100"></div>
                            
                            <div class="absolute top-4 left-4 product-card-icon">
                                <form action="{{ route('cart.add') }}" method="POST" onclick="event.stopPropagation();">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="p-2 text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="absolute top-4 right-4 product-card-icon product-card-icon-delayed">
                                <form action="{{ route('wishlist.toggle') }}" method="POST" onclick="event.stopPropagation();">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="p-2 text-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            
                            @if($product->created_at->diffInDays(now()) < 7)
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
                                <span class="text-[8px] font-bold tracking-[0.2em] uppercase bg-slate-900 text-white px-3 py-1.5">
                                    New
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex flex-col md:flex-row justify-between items-baseline gap-2">
                            <h3 class="text-[13px] font-medium tracking-tight text-slate-900">{{ $product->name }}</h3>
                            <p class="text-[12px] font-semibold text-slate-900">Rs. {{ number_format($product->discount_price ?? $product->price, 2) }}</p>
                        </div>
                        <p class="text-[10px] text-slate-400 tracking-widest uppercase mt-1">{{ $product->category_name }}</p>
                    </a>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500">
                    No new arrivals yet.
                </div>
            @endforelse
        </div>

        {{-- View More Button --}}
        <div class="mt-20 text-center">
            <a href="{{ route('shop.index') }}" class="text-[11px] font-bold tracking-[0.2em] uppercase underline underline-offset-8 decoration-slate-200 underline-animate hover:decoration-slate-900 transition-all">
                View More
            </a>
        </div>
    </div>
</section>
