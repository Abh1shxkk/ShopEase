<form id="filter-form" class="space-y-8">
    @if(request('search'))
    <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
    @if(request('gender'))
    <input type="hidden" name="gender" value="{{ request('gender') }}">
    @endif

    {{-- Gender Filter --}}
    <div x-data="{ open: true }" class="pb-6 border-b border-slate-100">
        <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-left mb-6">
            <span class="text-[11px] font-bold tracking-widest uppercase">Shop For</span>
            <svg :class="open ? 'rotate-180' : ''" class="w-3 h-3 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-collapse class="space-y-3">
            <label class="flex items-center cursor-pointer group">
                <input type="radio" name="gender" value="" {{ !request('gender') ? 'checked' : '' }} 
                    onchange="applyFilters()"
                    class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">All</span>
            </label>
            <label class="flex items-center cursor-pointer group">
                <input type="radio" name="gender" value="women" {{ request('gender') == 'women' ? 'checked' : '' }} 
                    onchange="applyFilters()"
                    class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Women</span>
            </label>
            <label class="flex items-center cursor-pointer group">
                <input type="radio" name="gender" value="men" {{ request('gender') == 'men' ? 'checked' : '' }} 
                    onchange="applyFilters()"
                    class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Men</span>
            </label>
        </div>
    </div>

    {{-- Categories --}}
    <div x-data="{ open: true }" class="pb-6 border-b border-slate-100">
        <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-left mb-6">
            <span class="text-[11px] font-bold tracking-widest uppercase">Categories</span>
            <svg :class="open ? 'rotate-180' : ''" class="w-3 h-3 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-collapse class="space-y-3">
            @foreach($categories as $cat)
            <label class="flex items-center cursor-pointer group">
                <input type="checkbox" name="category[]" value="{{ $cat }}" {{ in_array($cat, (array)request('category', [])) ? 'checked' : '' }} 
                    onchange="applyFilters()"
                    class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">{{ $cat }}</span>
            </label>
            @endforeach
        </div>
    </div>

    {{-- Price Range --}}
    <div x-data="{ open: true }" class="pb-6 border-b border-slate-100">
        <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-left mb-6">
            <span class="text-[11px] font-bold tracking-widest uppercase">Price Range</span>
            <svg :class="open ? 'rotate-180' : ''" class="w-3 h-3 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-collapse class="space-y-4">
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">$</span>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" 
                        class="w-full h-10 pl-7 pr-3 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-all">
                </div>
                <span class="text-slate-300">—</span>
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">$</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="999" 
                        class="w-full h-10 pl-7 pr-3 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-all">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="setPriceRange(0, 50)" 
                    class="h-9 text-[10px] font-medium tracking-wider uppercase text-slate-600 border border-slate-200 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                    Under $50
                </button>
                <button type="button" onclick="setPriceRange(50, 100)" 
                    class="h-9 text-[10px] font-medium tracking-wider uppercase text-slate-600 border border-slate-200 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                    $50 - $100
                </button>
                <button type="button" onclick="setPriceRange(100, 200)" 
                    class="h-9 text-[10px] font-medium tracking-wider uppercase text-slate-600 border border-slate-200 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                    $100 - $200
                </button>
                <button type="button" onclick="setPriceRange(200, '')" 
                    class="h-9 text-[10px] font-medium tracking-wider uppercase text-slate-600 border border-slate-200 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                    $200+
                </button>
            </div>
        </div>
    </div>

    {{-- Availability --}}
    <div x-data="{ open: true }" class="pb-6 border-b border-slate-100">
        <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-left mb-6">
            <span class="text-[11px] font-bold tracking-widest uppercase">Availability</span>
            <svg :class="open ? 'rotate-180' : ''" class="w-3 h-3 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-collapse class="space-y-3">
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="stock" value="" {{ !request('stock') ? 'checked' : '' }} 
                    onchange="applyFilters()"
                    class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600">All Products</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="stock" value="in_stock" {{ request('stock') == 'in_stock' ? 'checked' : '' }} 
                    onchange="applyFilters()"
                    class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600">In Stock</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="radio" name="stock" value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'checked' : '' }} 
                    onchange="applyFilters()"
                    class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600">Out of Stock</span>
            </label>
        </div>
    </div>

    {{-- Actions --}}
    <div class="space-y-3 pt-2">
        <button type="button" onclick="applyFilters()" class="w-full h-11 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors flex items-center justify-center gap-2">
            <span>Apply Filters</span>
            <svg class="w-4 h-4 animate-spin hidden" id="filter-spinner" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
        </button>
        <button type="button" onclick="clearFilters()" class="w-full h-11 border border-slate-900 text-slate-900 text-[11px] font-bold tracking-widest uppercase hover:bg-slate-900 hover:text-white transition-colors flex items-center justify-center">
            Clear All
        </button>
    </div>
</form>

<script>
let filterTimeout;

function applyFilters() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        const form = document.getElementById('filter-form');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        const sortSelect = document.querySelector('select[name="sort"]');
        if (sortSelect) {
            const sortValue = new URL(sortSelect.value).searchParams.get('sort');
            if (sortValue) params.append('sort', sortValue);
        }
        
        for (let [key, value] of formData.entries()) {
            if (value) params.append(key, value);
        }
        
        fetchProducts(params.toString());
    }, 300);
}

function setPriceRange(min, max) {
    document.querySelector('[name=min_price]').value = min;
    document.querySelector('[name=max_price]').value = max;
    applyFilters();
}

function clearFilters() {
    document.getElementById('filter-form').reset();
    const params = new URLSearchParams();
    
    const searchInput = document.querySelector('[name="search"]');
    if (searchInput && searchInput.value) {
        params.append('search', searchInput.value);
    }
    
    fetchProducts(params.toString());
    
    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.history.pushState({}, '', newUrl);
}

async function fetchProducts(queryString) {
    const spinner = document.getElementById('filter-spinner');
    const productsContainer = document.getElementById('products-container');
    const paginationContainer = document.getElementById('pagination-container');
    const productCount = document.getElementById('product-count');
    
    if (spinner) spinner.classList.remove('hidden');
    
    try {
        const response = await fetch(`{{ route('shop.index') }}?${queryString}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            productsContainer.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
            
            if (productCount) {
                productCount.innerHTML = `<span class="font-medium text-slate-900">${data.count.from}-${data.count.to}</span> of <span class="font-medium text-slate-900">${data.count.total}</span> products`;
            }
            
            const newUrl = window.location.pathname + (queryString ? '?' + queryString : '');
            window.history.pushState({}, '', newUrl);
            
            if (window.Alpine) {
                const alpineData = window.Alpine.$data(document.querySelector('[x-data]'));
                if (alpineData) alpineData.showFilters = false;
            }
        }
    } catch (error) {
        console.error('Error fetching products:', error);
    } finally {
        if (spinner) spinner.classList.add('hidden');
    }
}
</script>
