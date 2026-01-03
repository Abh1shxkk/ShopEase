@if($products->count() > 0)
<!-- Grid View -->
<div x-show="viewMode === 'grid'" class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
    @foreach($products as $product)
    <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:border-gray-200 transition-all duration-300">
        <div class="relative aspect-square overflow-hidden bg-gray-50">
            <a href="{{ route('shop.show', $product) }}">
                @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-50">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif
            </a>
            
            @if($product->discount_price)
            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
            </span>
            @endif
            
            <button @click="toggleWishlist({{ $product->id }})" class="absolute top-3 right-3 w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                <svg :class="wishlist.includes({{ $product->id }}) ? 'text-red-500 fill-red-500' : 'text-gray-400'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>

            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="{{ route('shop.show', $product) }}" class="block w-full h-10 bg-white text-slate-900 font-medium rounded-lg flex items-center justify-center hover:bg-gray-100 transition-colors text-sm">
                    Quick View
                </a>
            </div>
        </div>
        <div class="p-4">
            <p class="text-xs font-medium text-blue-600 mb-1">{{ $product->category }}</p>
            <a href="{{ route('shop.show', $product) }}" class="block font-semibold text-slate-900 hover:text-blue-600 transition-colors line-clamp-2 mb-2 min-h-[2.5rem]">{{ $product->name }}</a>
            <div class="flex items-center gap-1 mb-3">
                <div class="flex">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 {{ $i < 4 ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-xs text-gray-500">({{ rand(10, 200) }})</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-baseline gap-2">
                    @if($product->discount_price)
                    <span class="text-lg font-bold text-slate-900">${{ number_format($product->discount_price, 2) }}</span>
                    <span class="text-sm text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                    @else
                    <span class="text-lg font-bold text-slate-900">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
                @if($product->stock > 0)
                @auth
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </a>
                @endauth
                @else
                <span class="text-xs font-medium text-red-500 bg-red-50 px-2 py-1 rounded">Out of Stock</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- List View -->
<div x-show="viewMode === 'list'" x-cloak class="space-y-4">
    @foreach($products as $product)
    <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg hover:border-gray-200 transition-all flex flex-col sm:flex-row">
        <div class="relative w-full sm:w-56 aspect-square sm:aspect-auto overflow-hidden bg-gray-50 flex-shrink-0">
            <a href="{{ route('shop.show', $product) }}">
                @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif
            </a>
            @if($product->discount_price)
            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">Sale</span>
            @endif
        </div>
        <div class="flex-1 p-5 flex flex-col justify-between">
            <div>
                <p class="text-xs font-medium text-blue-600 mb-1">{{ $product->category }}</p>
                <a href="{{ route('shop.show', $product) }}" class="text-lg font-semibold text-slate-900 hover:text-blue-600 transition-colors">{{ $product->name }}</a>
                <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ $product->description }}</p>
            </div>
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-baseline gap-2">
                    @if($product->discount_price)
                    <span class="text-xl font-bold text-slate-900">${{ number_format($product->discount_price, 2) }}</span>
                    <span class="text-sm text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                    @else
                    <span class="text-xl font-bold text-slate-900">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <button @click="toggleWishlist({{ $product->id }})" class="w-10 h-10 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-gray-50 transition-colors">
                        <svg :class="wishlist.includes({{ $product->id }}) ? 'text-red-500 fill-red-500' : 'text-gray-400'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                    @if($product->stock > 0)
                    @auth
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="h-10 px-5 bg-blue-600 text-white font-medium rounded-xl flex items-center justify-center gap-2 hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Add to Cart
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="h-10 px-5 bg-blue-600 text-white font-medium rounded-xl flex items-center justify-center gap-2 hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Add to Cart
                    </a>
                    @endauth
                    @else
                    <button disabled class="h-10 px-5 bg-gray-100 text-gray-400 font-medium rounded-xl cursor-not-allowed">Out of Stock</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<!-- Empty State -->
<div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <h3 class="text-xl font-semibold text-slate-900 mb-2">No products found</h3>
    <p class="text-gray-500 mb-6">Try adjusting your filters or search terms</p>
    <button onclick="clearFilters()" class="inline-flex h-11 px-6 bg-blue-600 text-white font-medium rounded-xl items-center justify-center hover:bg-blue-700 transition-colors">
        Clear All Filters
    </button>
</div>
@endif
