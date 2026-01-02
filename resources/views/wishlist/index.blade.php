@extends('layouts.shop')

@section('title', 'My Wishlist')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-8">
        <a href="/" class="hover:text-slate-900">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-900 font-medium">Wishlist</span>
    </nav>

    <h1 class="text-3xl font-bold text-slate-900 mb-8">My Wishlist</h1>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">{{ session('success') }}</div>
    @endif

    @if($wishlistItems->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($wishlistItems as $item)
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all">
            <div class="relative aspect-square bg-gray-50">
                <a href="{{ route('shop.show', $item->product) }}">
                    @if($item->product->image)
                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </a>
                <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-3 right-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                    <button type="submit" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5 text-red-500 fill-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </form>
            </div>
            <div class="p-4">
                <p class="text-xs font-medium text-blue-600 mb-1">{{ $item->product->category }}</p>
                <a href="{{ route('shop.show', $item->product) }}" class="font-semibold text-slate-900 hover:text-blue-600 transition-colors line-clamp-2">{{ $item->product->name }}</a>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-lg font-bold text-slate-900">${{ number_format($item->product->discount_price ?? $item->product->price, 2) }}</span>
                    @if($item->product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                        <button type="submit" class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </form>
                    @else
                    <span class="text-xs text-red-500 font-medium">Out of Stock</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <h3 class="text-xl font-semibold text-slate-900 mb-2">Your wishlist is empty</h3>
        <p class="text-slate-500 mb-6">Save items you love by clicking the heart icon.</p>
        <a href="{{ route('shop.index') }}" class="inline-flex h-12 px-8 bg-blue-600 text-white font-semibold rounded-xl items-center justify-center hover:bg-blue-700 transition-colors">
            Explore Products
        </a>
    </div>
    @endif
</div>
@endsection
