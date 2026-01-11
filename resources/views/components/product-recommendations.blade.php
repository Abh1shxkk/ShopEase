@props(['product' => null, 'title' => 'You May Also Like'])

@php
    $recommendationService = app(\App\Services\RecommendationService::class);
    
    if ($product) {
        $recommendations = $recommendationService->getRelatedProducts($product, 8);
    } else {
        $recommendations = $recommendationService->getPersonalizedRecommendations(auth()->id(), 8);
    }
@endphp

@if($recommendations->count() > 0)
<div class="mt-20 border-t border-slate-100 pt-12">
    <h2 class="text-xl font-serif text-slate-900 mb-8">{{ $title }}</h2>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($recommendations->take(4) as $rec)
        <div class="group">
            <a href="{{ route('shop.show', $rec) }}" class="block">
                <div class="aspect-[3/4] bg-slate-100 overflow-hidden mb-3 relative">
                    <img src="{{ $rec->image_url }}" alt="{{ $rec->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @if($rec->discount_price)
                    <span class="absolute top-2 left-2 bg-red-600 text-white text-[9px] font-bold tracking-wider uppercase px-2 py-1">Sale</span>
                    @endif
                </div>
                <h3 class="text-sm font-medium text-slate-900 group-hover:text-slate-600 transition-colors truncate">{{ $rec->name }}</h3>
                <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mt-1">{{ $rec->category_name }}</p>
                <div class="flex items-baseline gap-2 mt-1">
                    @if($rec->discount_price)
                    <span class="text-sm font-semibold text-red-600">Rs. {{ number_format($rec->discount_price, 2) }}</span>
                    <span class="text-xs text-slate-400 line-through">Rs. {{ number_format($rec->price, 2) }}</span>
                    @else
                    <span class="text-sm font-semibold text-slate-900">Rs. {{ number_format($rec->price, 2) }}</span>
                    @endif
                </div>
            </a>
            @auth
            <form action="{{ route('cart.add') }}" method="POST" class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                @csrf
                <input type="hidden" name="product_id" value="{{ $rec->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" {{ $rec->stock <= 0 ? 'disabled' : '' }} class="w-full py-2 bg-slate-900 text-white text-[10px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors disabled:opacity-50">
                    {{ $rec->stock > 0 ? 'Quick Add' : 'Out of Stock' }}
                </button>
            </form>
            @endauth
        </div>
        @endforeach
    </div>
</div>
@endif
