@props(['excludeProductId' => null])

@auth
@php
    $recentlyViewed = \App\Models\RecentlyViewed::getForUser(auth()->id(), 8);
    if ($excludeProductId) {
        $recentlyViewed = $recentlyViewed->filter(fn($p) => $p->id != $excludeProductId);
    }
@endphp

@if($recentlyViewed->count() > 0)
<div class="mt-20 border-t border-slate-100 pt-12">
    <h2 class="text-xl font-serif text-slate-900 mb-8">Recently Viewed</h2>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($recentlyViewed->take(4) as $product)
        <a href="{{ route('shop.show', $product) }}" class="group">
            <div class="aspect-[3/4] bg-slate-100 overflow-hidden mb-3">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            </div>
            <h3 class="text-sm font-medium text-slate-900 group-hover:text-slate-600 transition-colors truncate">{{ $product->name }}</h3>
            <p class="text-sm text-slate-500 mt-1">Rs. {{ number_format($product->discount_price ?? $product->price, 2) }}</p>
        </a>
        @endforeach
    </div>
</div>
@endif
@endauth
