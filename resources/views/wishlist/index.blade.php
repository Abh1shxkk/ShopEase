@extends('layouts.shop')

@section('title', 'My Wishlist')

@section('content')
<div class="max-w-[1440px] mx-auto px-6 md:px-12 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-12">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-slate-900">Wishlist</span>
    </nav>

    <div class="flex items-center justify-between mb-12">
        <h1 class="text-3xl font-serif tracking-wide">My Wishlist</h1>
        @if($wishlistItems->count() > 0)
        <a href="{{ route('wishlist.share.create') }}" class="flex items-center gap-2 text-[11px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            Share Wishlist
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="mb-8 p-4 bg-slate-50 border border-slate-200 text-slate-700 text-sm">{{ session('success') }}</div>
    @endif

    @if($wishlistItems->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-12">
        @foreach($wishlistItems as $item)
        <div class="group">
            <div class="relative aspect-[3/4] overflow-hidden mb-4 bg-[#f7f7f7]">
                <a href="{{ route('shop.show', $item->product) }}">
                    @if($item->product->image)
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover image-zoom">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </a>
                
                {{-- Remove from Wishlist --}}
                <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-4 right-4 product-card-icon product-card-icon-delayed">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                    <button type="submit" class="p-2 text-red-500 hover:text-red-600 transition-colors drop-shadow-md">
                        <svg class="w-5 h-5 fill-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </form>

                {{-- Add to Cart --}}
                @if($item->product->stock > 0)
                <div class="absolute top-4 left-4 product-card-icon">
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                        <button type="submit" class="p-2 text-white hover:text-slate-200 transition-colors drop-shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @else
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2">
                    <span class="text-[8px] font-bold tracking-[0.2em] uppercase bg-red-500 text-white px-3 py-1.5">
                        Out of Stock
                    </span>
                </div>
                @endif
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-baseline gap-2">
                <a href="{{ route('shop.show', $item->product) }}" class="text-[13px] font-medium tracking-tight text-slate-900 hover:text-slate-600 transition-colors">
                    {{ $item->product->name }}
                </a>
                <p class="text-[12px] font-semibold text-slate-900 whitespace-nowrap">
                    Rs. {{ number_format($item->product->discount_price ?? $item->product->price, 2) }}
                </p>
            </div>
            <p class="text-[10px] text-slate-400 tracking-widest uppercase mt-1">{{ $item->product->category_name }}</p>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-24 bg-slate-50">
        <div class="w-20 h-20 bg-white border border-slate-200 flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <h3 class="text-xl font-serif tracking-wide text-slate-900 mb-3">Your wishlist is empty</h3>
        <p class="text-[13px] text-slate-500 mb-8">Save items you love by clicking the heart icon.</p>
        <a href="{{ route('shop.index') }}" class="inline-flex h-12 px-10 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase items-center justify-center hover:bg-slate-800 transition-colors">
            Explore Products
        </a>
    </div>
    @endif
</div>
@endsection
