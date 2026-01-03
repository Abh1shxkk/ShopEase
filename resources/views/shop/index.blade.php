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
                    <p class="text-slate-600" id="product-count">
                        Showing <span class="font-semibold text-slate-900">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> of <span class="font-semibold text-slate-900">{{ $products->total() }}</span> products
                    </p>
                    <div class="flex items-center gap-4">
                        <select name="sort" onchange="changeSort(this.value)" class="h-10 pl-4 pr-10 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none cursor-pointer" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%236b7280\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
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

                <!-- Products Container -->
                <div id="products-container">
                    @include('shop.partials.products')
                </div>

                <!-- Pagination -->
                <div class="mt-10" id="pagination-container">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeSort(url) {
    const sortValue = new URL(url).searchParams.get('sort');
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    // Add sort
    if (sortValue) params.append('sort', sortValue);
    
    // Add form data
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    fetchProducts(params.toString());
}

// Handle pagination clicks
document.addEventListener('click', function(e) {
    if (e.target.closest('#pagination-container a')) {
        e.preventDefault();
        const link = e.target.closest('a');
        const url = new URL(link.href);
        fetchProducts(url.search.substring(1));
    }
});
</script>
@endsection
