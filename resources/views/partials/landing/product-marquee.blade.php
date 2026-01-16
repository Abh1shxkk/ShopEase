{{-- Product Marquee / Infinite Scroll Carousel --}}
{{-- Admin controlled via: Admin > Settings > Featured Sections > Product Marquee --}}
@php
use App\Models\Product;
use App\Models\FeaturedSection;

// Try to get admin-managed marquee items first
$marqueeItems = FeaturedSection::active()->byType('marquee')->get();

// Split into two rows (sort_order 1-8 = top, 9-16 = bottom)
$topRowItems = $marqueeItems->filter(fn($item) => $item->sort_order <= 8);
$bottomRowItems = $marqueeItems->filter(fn($item) => $item->sort_order > 8);

// If not enough admin products, fall back to database products
if ($marqueeItems->isEmpty() || $marqueeItems->count() < 8) {
    $marqueeProducts = Product::where('status', 'active')
        ->whereNotNull('image')
        ->with('category')
        ->inRandomOrder()
        ->take(16)
        ->get();

    // Convert products to format matching featured sections
    $topRowProducts = $marqueeProducts->take(8)->map(function($product) {
        return (object)[
            'title' => $product->name,
            'price' => $product->price,
            'image_url' => $product->image_url,
            'link' => route('shop.show', $product),
        ];
    });
    
    $bottomRowProducts = $marqueeProducts->skip(8)->take(8)->map(function($product) {
        return (object)[
            'title' => $product->name,
            'price' => $product->price,
            'image_url' => $product->image_url,
            'link' => route('shop.show', $product),
        ];
    });
    
    // Merge with admin items if any
    if ($topRowItems->count() > 0) {
        $topRowProducts = $topRowItems->map(function($item) {
            return (object)[
                'title' => $item->title,
                'price' => $item->extra_data['price'] ?? null,
                'image_url' => $item->image_url,
                'link' => $item->link ?? route('shop.index'),
            ];
        });
    }
    if ($bottomRowItems->count() > 0) {
        $bottomRowProducts = $bottomRowItems->map(function($item) {
            return (object)[
                'title' => $item->title,
                'price' => $item->extra_data['price'] ?? null,
                'image_url' => $item->image_url,
                'link' => $item->link ?? route('shop.index'),
            ];
        });
    }
} else {
    // Use admin-managed items
    $topRowProducts = $topRowItems->map(function($item) {
        return (object)[
            'title' => $item->title,
            'price' => $item->extra_data['price'] ?? null,
            'image_url' => $item->image_url,
            'link' => $item->link ?? route('shop.index'),
        ];
    });
    $bottomRowProducts = $bottomRowItems->map(function($item) {
        return (object)[
            'title' => $item->title,
            'price' => $item->extra_data['price'] ?? null,
            'image_url' => $item->image_url,
            'link' => $item->link ?? route('shop.index'),
        ];
    });
    
    // If bottom row is empty, duplicate top row
    if ($bottomRowProducts->isEmpty()) {
        $bottomRowProducts = $topRowProducts;
    }
}

// Final fallback
if ($topRowProducts->isEmpty()) {
    $fallbackProducts = collect([
        (object)['title' => 'Classic Sneakers', 'price' => 4999, 'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400', 'link' => route('shop.index')],
        (object)['title' => 'Leather Bag', 'price' => 7999, 'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&q=80&w=400', 'link' => route('shop.index')],
        (object)['title' => 'Summer Dress', 'price' => 3499, 'image_url' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&q=80&w=400', 'link' => route('shop.index')],
        (object)['title' => 'Vintage Watch', 'price' => 12999, 'image_url' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&q=80&w=400', 'link' => route('shop.index')],
        (object)['title' => 'Running Shoes', 'price' => 5499, 'image_url' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?auto=format&fit=crop&q=80&w=400', 'link' => route('shop.index')],
        (object)['title' => 'Denim Jacket', 'price' => 6999, 'image_url' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&q=80&w=400', 'link' => route('shop.index')],
        (object)['title' => 'Gold Earrings', 'price' => 2499, 'image_url' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&q=80&w=400', 'link' => route('shop.index')],
        (object)['title' => 'Silk Scarf', 'price' => 1999, 'image_url' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&q=80&w=400', 'link' => route('shop.index')],
    ]);
    $topRowProducts = $fallbackProducts;
    $bottomRowProducts = $fallbackProducts;
}
@endphp

<style>
    @keyframes scroll-left {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    @keyframes scroll-right {
        0% { transform: translateX(-50%); }
        100% { transform: translateX(0); }
    }
    
    .marquee-left {
        animation: scroll-left 35s linear infinite;
    }
    
    .marquee-right {
        animation: scroll-right 35s linear infinite;
    }
    
    .marquee-wrapper:hover .marquee-left,
    .marquee-wrapper:hover .marquee-right {
        animation-play-state: paused;
    }
    
    @media (prefers-reduced-motion: reduce) {
        .marquee-left, .marquee-right {
            animation: none;
        }
    }
</style>

<section class="py-16 bg-white border-t border-b border-slate-100">
    {{-- Section Header --}}
    <div class="max-w-[1440px] mx-auto px-6 md:px-12 mb-12 text-center">
        <span class="text-[10px] font-bold tracking-[0.3em] uppercase text-slate-400 block mb-3">Trending Now</span>
        <h2 class="text-3xl md:text-4xl font-serif tracking-wide">Explore Collection</h2>
    </div>
    
    {{-- Marquee Row 1 - Moving Left --}}
    <div class="marquee-wrapper overflow-hidden mb-4">
        <div class="marquee-left flex" style="width: max-content;">
            @for($i = 0; $i < 2; $i++)
                @foreach($topRowProducts as $product)
                    <a href="{{ $product->link }}" 
                       class="group flex-shrink-0 w-64 mx-2">
                        {{-- Image --}}
                        <div class="aspect-[3/4] overflow-hidden bg-slate-100 relative">
                            <img 
                                src="{{ $product->image_url }}" 
                                alt="{{ $product->title }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            {{-- Hover Overlay --}}
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                            {{-- Quick View --}}
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/50 to-transparent 
                                        translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <span class="text-[10px] font-bold tracking-[0.2em] uppercase text-white">Quick View</span>
                            </div>
                        </div>
                        {{-- Product Info --}}
                        <div class="pt-4 text-center">
                            <h4 class="text-[13px] font-medium text-slate-800 truncate group-hover:text-slate-600 transition-colors">
                                {{ $product->title }}
                            </h4>
                            @if($product->price)
                            <p class="text-[12px] font-semibold text-slate-900 mt-1">
                                Rs. {{ number_format($product->price, 0) }}
                            </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            @endfor
        </div>
    </div>
    
    {{-- Marquee Row 2 - Moving Right --}}
    <div class="marquee-wrapper overflow-hidden">
        <div class="marquee-right flex" style="width: max-content;">
            @for($i = 0; $i < 2; $i++)
                @foreach($bottomRowProducts as $product)
                    <a href="{{ $product->link }}" 
                       class="group flex-shrink-0 w-64 mx-2">
                        {{-- Image --}}
                        <div class="aspect-[3/4] overflow-hidden bg-slate-100 relative">
                            <img 
                                src="{{ $product->image_url }}" 
                                alt="{{ $product->title }}" 
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            />
                            {{-- Hover Overlay --}}
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                            {{-- Quick View --}}
                            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/50 to-transparent 
                                        translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <span class="text-[10px] font-bold tracking-[0.2em] uppercase text-white">Quick View</span>
                            </div>
                        </div>
                        {{-- Product Info --}}
                        <div class="pt-4 text-center">
                            <h4 class="text-[13px] font-medium text-slate-800 truncate group-hover:text-slate-600 transition-colors">
                                {{ $product->title }}
                            </h4>
                            @if($product->price)
                            <p class="text-[12px] font-semibold text-slate-900 mt-1">
                                Rs. {{ number_format($product->price, 0) }}
                            </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            @endfor
        </div>
    </div>
    
    {{-- View All Link --}}
    <div class="mt-12 text-center">
        <a href="{{ route('shop.index') }}" 
           class="inline-flex items-center gap-2 text-[11px] font-bold tracking-[0.2em] uppercase text-slate-800
                  border-b border-slate-900 pb-1 hover:text-slate-500 hover:border-slate-500 transition-colors">
            View All Products
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>
