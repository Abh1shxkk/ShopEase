@extends('layouts.shop')

@section('title', 'Shop')

@section('content')
<div x-data="{ 
    showFilters: false,
    viewMode: 'grid',
    wishlist: JSON.parse(localStorage.getItem('wishlist') || '[]'),
    toggleWishlist(id) {
        if (this.wishlist.includes(id)) {
            this.wishlist = this.wishlist.filter(i => i !== id);
        } else {
            this.wishlist.push(id);
            showToast('Added to wishlist!');
        }
        localStorage.setItem('wishlist', JSON.stringify(this.wishlist));
    }
}">
    <!-- Hero Banner -->
    <div class="relative bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative">
            <nav class="flex items-center gap-2 text-sm text-slate-400 mb-6">
                <a href="/" class="hover:text-white transition-colors">Home</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-white">Shop</span>
            </nav>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Shop Our Collection</h1>
            <p class="text-slate-300 text-lg max-w-xl">Discover amazing products at unbeatable prices. Quality guaranteed.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden mb-6">
            <button @click="showFilters = true" class="w-full flex items-center justify-center gap-2 h-12 bg-white border border-gray-200 rounded-xl font-medium text-slate-700 hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filters & Sort
            </button>
        </div>

        <div class="flex gap-8">
            <!-- Sidebar Filters -->
            <aside class="hidden lg:block w-72 flex-shrink-0">
                <div class="sticky top-24 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-900">Filters</h3>
                        <a href="{{ route('shop.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">Clear All</a>
                    </div>
                    @include('shop.partials.filters')
                </div>
            </aside>

            <!-- Mobile Filters Modal -->
            <div x-show="showFilters" x-cloak class="fixed inset-0 z-50 lg:hidden">
                <div @click="showFilters = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div x-show="showFilters" 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="translate-y-full" 
                     x-transition:enter-end="translate-y-0" 
                     x-transition:leave="transition ease-in duration-200" 
                     x-transition:leave-start="translate-y-0" 
                     x-transition:leave-end="translate-y-full" 
                     class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl max-h-[85vh] overflow-hidden">
                    <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Filters</h3>
                        <button @click="showFilters = false" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto max-h-[calc(85vh-80px)]">
                        @include('shop.partials.filters')
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="flex-1 min-w-0">
                <!-- Toolbar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 bg-white rounded-xl border border-gray-100 p-4">
                    <p class="text-slate-600">
                        Showing <span class="font-semibold text-slate-900">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> of <span class="font-semibold text-slate-900">{{ $products->total() }}</span> products
                    </p>
                    <div class="flex items-center gap-4">
                        <select name="sort" onchange="window.location.href = this.value" class="h-10 pl-4 pr-10 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236b7280\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                        <div class="hidden sm:flex items-center bg-gray-100 rounded-lg p-1">
                            <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white shadow-sm' : ''" class="p-2 rounded-md transition-all">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            </button>
                            <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white shadow-sm' : ''" class="p-2 rounded-md transition-all">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

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

                <!-- Pagination -->
                <div class="mt-10">
                    {{ $products->links() }}
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">No products found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your filters or search terms</p>
                    <a href="{{ route('shop.index') }}" class="inline-flex h-11 px-6 bg-blue-600 text-white font-medium rounded-xl items-center justify-center hover:bg-blue-700 transition-colors">
                        Clear All Filters
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
