@if($products->count() > 0)
<!-- Grid View -->
<div x-show="viewMode === 'grid'" class="grid grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($products as $product)
    <div class="group">
        <div class="relative aspect-[3/4] overflow-hidden bg-slate-50 mb-4">
            <a href="{{ route('shop.show', $product) }}">
                @if($product->image)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif
            </a>
            
            @if($product->discount_price)
            <span class="absolute top-3 left-3 bg-slate-900 text-white text-[10px] font-bold tracking-wider uppercase px-3 py-1.5">
                Sale
            </span>
            @endif
            
            <button @click="toggleWishlist({{ $product->id }})" class="absolute top-3 right-3 w-9 h-9 bg-white flex items-center justify-center hover:bg-slate-900 hover:text-white transition-colors">
                <svg :class="wishlist.includes({{ $product->id }}) ? 'fill-current' : ''" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>

            @if($product->stock > 0)
            @auth
            <button onclick="addToCartAjax({{ $product->id }})" class="absolute bottom-0 left-0 right-0 bg-white/95 backdrop-blur py-3 text-[11px] font-bold tracking-widest uppercase opacity-0 group-hover:opacity-100 transition-opacity hover:bg-slate-900 hover:text-white">
                Add to Cart
            </button>
            @else
            <a href="{{ route('login') }}" class="absolute bottom-0 left-0 right-0 bg-white/95 backdrop-blur py-3 text-[11px] font-bold tracking-widest uppercase opacity-0 group-hover:opacity-100 transition-opacity hover:bg-slate-900 hover:text-white text-center">
                Login to Purchase
            </a>
            @endauth
            @else
            <div class="absolute bottom-0 left-0 right-0 bg-slate-100 py-3 text-[11px] font-bold tracking-widest uppercase text-slate-400 text-center">
                Out of Stock
            </div>
            @endif
        </div>
        <div>
            <p class="text-[10px] font-medium tracking-widest uppercase text-slate-500 mb-2">{{ $product->category_name }}</p>
            <a href="{{ route('shop.show', $product) }}" class="block font-medium text-slate-900 hover:text-slate-600 transition-colors mb-2 line-clamp-2">{{ $product->name }}</a>
            <div class="flex items-baseline gap-2">
                @if($product->discount_price)
                <span class="font-medium text-slate-900">${{ number_format($product->discount_price, 2) }}</span>
                <span class="text-sm text-slate-400 line-through">${{ number_format($product->price, 2) }}</span>
                @else
                <span class="font-medium text-slate-900">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- List View -->
<div x-show="viewMode === 'list'" x-cloak class="space-y-6">
    @foreach($products as $product)
    <div class="group flex flex-col sm:flex-row gap-6 border-b border-slate-100 pb-6">
        <div class="relative w-full sm:w-64 aspect-[3/4] overflow-hidden bg-slate-50 flex-shrink-0">
            <a href="{{ route('shop.show', $product) }}">
                @if($product->image)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif
            </a>
            @if($product->discount_price)
            <span class="absolute top-3 left-3 bg-slate-900 text-white text-[10px] font-bold tracking-wider uppercase px-3 py-1.5">Sale</span>
            @endif
        </div>
        <div class="flex-1 flex flex-col justify-between">
            <div>
                <p class="text-[10px] font-medium tracking-widest uppercase text-slate-500 mb-2">{{ $product->category_name }}</p>
                <a href="{{ route('shop.show', $product) }}" class="text-xl font-medium text-slate-900 hover:text-slate-600 transition-colors mb-3 block">{{ $product->name }}</a>
                <p class="text-slate-600 text-sm leading-relaxed line-clamp-3">{{ $product->description }}</p>
            </div>
            <div class="flex items-center justify-between mt-6 pt-6 border-t border-slate-100">
                <div class="flex items-baseline gap-3">
                    @if($product->discount_price)
                    <span class="text-xl font-medium text-slate-900">${{ number_format($product->discount_price, 2) }}</span>
                    <span class="text-slate-400 line-through">${{ number_format($product->price, 2) }}</span>
                    @else
                    <span class="text-xl font-medium text-slate-900">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <button @click="toggleWishlist({{ $product->id }})" class="w-10 h-10 border border-slate-200 flex items-center justify-center hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-colors">
                        <svg :class="wishlist.includes({{ $product->id }}) ? 'fill-current' : ''" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                    @if($product->stock > 0)
                    @auth
                    <button onclick="addToCartAjax({{ $product->id }})" class="h-10 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
                        Add to Cart
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="h-10 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors flex items-center">
                        Login
                    </a>
                    @endauth
                    @else
                    <button disabled class="h-10 px-6 bg-slate-100 text-slate-400 text-[11px] font-bold tracking-widest uppercase cursor-not-allowed">
                        Out of Stock
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<!-- Empty State -->
<div class="text-center py-24">
    <div class="w-24 h-24 bg-slate-100 mx-auto mb-8 flex items-center justify-center">
        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <h3 class="text-2xl font-serif mb-3">No products found</h3>
    <p class="text-slate-600 mb-8">Try adjusting your filters or search terms</p>
    <button onclick="clearFilters()" class="inline-flex h-12 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
        Clear All Filters
    </button>
</div>
@endif
