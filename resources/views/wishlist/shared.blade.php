@extends('layouts.shop')

@section('title', $share->title ?? 'Shared Wishlist')

@section('content')
<div class="max-w-[1440px] mx-auto px-6 md:px-12 py-12">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-serif tracking-wide text-slate-900 mb-3">{{ $share->title ?? 'Shared Wishlist' }}</h1>
        @if($share->description)
        <p class="text-slate-500 text-sm max-w-xl mx-auto">{{ $share->description }}</p>
        @endif
        <p class="text-xs text-slate-400 mt-4">Shared by {{ $share->user->name }}</p>
    </div>

    @if($items->count() === 0)
    <div class="text-center py-20">
        <svg class="w-16 h-16 mx-auto text-slate-200 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        <h2 class="text-xl font-serif text-slate-900 mb-3">This wishlist is empty</h2>
        <p class="text-slate-500 text-sm">No items have been added to this wishlist yet.</p>
    </div>
    @else
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($items as $item)
        @if($item->product)
        <div class="group">
            <a href="{{ route('shop.show', $item->product) }}" class="block">
                <div class="aspect-[3/4] bg-slate-100 overflow-hidden mb-4">
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <h3 class="text-sm font-medium text-slate-900 group-hover:text-slate-600 transition-colors mb-1">{{ $item->product->name }}</h3>
                <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mb-2">{{ $item->product->category_name }}</p>
                <div class="flex items-baseline gap-2">
                    @if($item->product->discount_price)
                    <span class="text-sm font-semibold text-red-600">Rs. {{ number_format($item->product->discount_price, 2) }}</span>
                    <span class="text-xs text-slate-400 line-through">Rs. {{ number_format($item->product->price, 2) }}</span>
                    @else
                    <span class="text-sm font-semibold text-slate-900">Rs. {{ number_format($item->product->price, 2) }}</span>
                    @endif
                </div>
            </a>
            @auth
            <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" {{ $item->product->stock <= 0 ? 'disabled' : '' }} class="w-full py-2 bg-slate-900 text-white text-[10px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    {{ $item->product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                </button>
            </form>
            @endauth
        </div>
        @endif
        @endforeach
    </div>
    @endif

    <div class="text-center mt-12">
        <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
            Browse More Products
        </a>
    </div>
</div>
@endsection
