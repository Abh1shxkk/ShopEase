@extends('layouts.shop')

@section('title', 'Shop All Products')
@section('description', 'Browse our curated collection of premium products')

@section('content')
{{-- Hero Image Slider --}}
@php
    $defaultBanners = [
        ['image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600', 'title' => 'New Season Arrivals', 'subtitle' => 'Discover the latest trends'],
        ['image' => 'https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1600', 'title' => 'Summer Collection', 'subtitle' => 'Light & breezy styles'],
        ['image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1600', 'title' => 'Premium Quality', 'subtitle' => 'Crafted with care']
    ];
    $bannerData = $shopBanners->count() > 0 
        ? $shopBanners->map(fn($b) => ['image' => $b->image_url, 'title' => $b->title, 'subtitle' => $b->subtitle])->toArray()
        : $defaultBanners;
@endphp
<div x-data="{ 
    currentSlide: 0, 
    slides: {{ json_encode($bannerData) }},
    autoSlide: null,
    init() {
        this.autoSlide = setInterval(() => this.next(), 5000);
    },
    next() {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
    },
    prev() {
        this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
    },
    goTo(index) {
        this.currentSlide = index;
    }
}" class="relative h-[50vh] md:h-[60vh] overflow-hidden bg-slate-900">
    {{-- Slides --}}
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="currentSlide === index"
             x-transition:enter="transition ease-out duration-700"
             x-transition:enter-start="opacity-0 scale-105"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute inset-0">
            <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="absolute inset-0 flex items-center justify-center text-center px-6">
                <div>
                    <p class="text-[11px] font-bold tracking-[0.3em] uppercase text-white/80 mb-4" x-text="slide.subtitle"></p>
                    <h2 class="text-4xl md:text-6xl font-serif text-white" x-text="slide.title"></h2>
                </div>
            </div>
        </div>
    </template>

    {{-- Navigation Arrows --}}
    <button @click="prev()" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 backdrop-blur flex items-center justify-center text-white hover:bg-white hover:text-slate-900 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button @click="next()" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 backdrop-blur flex items-center justify-center text-white hover:bg-white hover:text-slate-900 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>

    {{-- Dots --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
        <template x-for="(slide, index) in slides" :key="'dot-'+index">
            <button @click="goTo(index)" :class="currentSlide === index ? 'bg-white w-8' : 'bg-white/40 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
        </template>
    </div>
</div>

<div id="products" x-data="{ 
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
    <div class="max-w-[1440px] mx-auto px-6 md:px-12 py-12">
        {{-- Mobile Filter Toggle --}}
        <div class="lg:hidden mb-8">
            <button @click="showFilters = true" class="w-full flex items-center justify-center gap-2 h-12 border border-slate-900 font-medium text-sm tracking-wider uppercase hover:bg-slate-900 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filters
            </button>
        </div>

        <div class="flex gap-12">
            {{-- Sidebar Filters (Desktop) --}}
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-32">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-sm font-bold tracking-widest uppercase">Filters</h3>
                        <a href="{{ route('shop.index') }}" class="text-xs text-slate-500 hover:text-slate-900 underline">Clear All</a>
                    </div>
                    @include('shop.partials.filters')
                </div>
            </aside>

            {{-- Mobile Filters Modal --}}
            <div x-show="showFilters" x-cloak class="fixed inset-0 z-50 lg:hidden">
                <div @click="showFilters = false" class="absolute inset-0 bg-black/60"></div>
                <div x-show="showFilters" 
                     x-transition:enter="transition ease-out duration-300" 
                     x-transition:enter-start="translate-y-full" 
                     x-transition:enter-end="translate-y-0" 
                     x-transition:leave="transition ease-in duration-200" 
                     x-transition:leave-start="translate-y-0" 
                     x-transition:leave-end="translate-y-full" 
                     class="absolute bottom-0 left-0 right-0 bg-white max-h-[85vh] overflow-hidden">
                    <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-sm font-bold tracking-widest uppercase">Filters</h3>
                        <button @click="showFilters = false" class="p-2 hover:bg-slate-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto max-h-[calc(85vh-80px)]">
                        @include('shop.partials.filters')
                    </div>
                </div>
            </div>

            {{-- Products Section --}}
            <div class="flex-1 min-w-0">
                {{-- Toolbar --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-100">
                    <p class="text-sm text-slate-600" id="product-count">
                        <span class="font-medium text-slate-900">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> of <span class="font-medium text-slate-900">{{ $products->total() }}</span> products
                    </p>
                    <div class="flex items-center gap-4">
                        <select name="sort" onchange="changeSort(this.value)" class="h-10 pl-4 pr-10 border border-slate-200 text-sm font-medium focus:outline-none focus:border-slate-900 appearance-none cursor-pointer bg-white" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%23000\'%3E%3Cpath stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 9l-7 7-7-7\'/%3E%3C/svg%3E'); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                        <div class="hidden sm:flex items-center border border-slate-200">
                            <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-slate-900 text-white' : 'bg-white text-slate-900'" class="p-2 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            </button>
                            <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-slate-900 text-white' : 'bg-white text-slate-900'" class="p-2 transition-all border-l border-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Products Container --}}
                <div id="products-container">
                    @include('shop.partials.products')
                </div>

                {{-- Pagination --}}
                <div id="pagination-container">
                    {{ $products->links('vendor.pagination.luxury') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ Vite::asset('resources/js/cart.js') }}"></script>
<script>
function changeSort(url) {
    const sortValue = new URL(url).searchParams.get('sort');
    const form = document.getElementById('filter-form');
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    if (sortValue) params.append('sort', sortValue);
    
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    fetchProducts(params.toString());
}

document.addEventListener('click', function(e) {
    if (e.target.closest('#pagination-container a')) {
        e.preventDefault();
        const link = e.target.closest('a');
        const url = new URL(link.href);
        fetchProducts(url.search.substring(1));
    }
});
</script>
@endpush

