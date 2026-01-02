<form action="{{ route('shop.index') }}" method="GET" class="space-y-6">
    @if(request('search'))
    <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
    @if(request('sort'))
    <input type="hidden" name="sort" value="{{ request('sort') }}">
    @endif

    <!-- Categories -->
    <div x-data="{ open: true }" class="pb-6">
        <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-left">
            <span class="font-semibold text-slate-900">Categories</span>
            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-collapse class="mt-4 space-y-2">
            @foreach($categories as $cat)
            <label class="flex items-center cursor-pointer group py-1.5 px-2 rounded-lg hover:bg-gray-50 transition-colors">
                <input type="checkbox" name="category[]" value="{{ $cat }}" {{ in_array($cat, (array)request('category', [])) ? 'checked' : '' }} 
                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">{{ $cat }}</span>
            </label>
            @endforeach
        </div>
    </div>

    <div class="border-t border-gray-100"></div>

    <!-- Price Range -->
    <div x-data="{ open: true }" class="pb-6">
        <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-left">
            <span class="font-semibold text-slate-900">Price Range</span>
            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-collapse class="mt-4 space-y-4">
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" 
                        class="w-full h-10 pl-7 pr-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <span class="text-gray-300">—</span>
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="999" 
                        class="w-full h-10 pl-7 pr-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" onclick="document.querySelector('[name=min_price]').value=0; document.querySelector('[name=max_price]').value=50;" 
                    class="h-9 text-xs font-medium text-slate-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 hover:border-gray-300 transition-all">
                    Under $50
                </button>
                <button type="button" onclick="document.querySelector('[name=min_price]').value=50; document.querySelector('[name=max_price]').value=100;" 
                    class="h-9 text-xs font-medium text-slate-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 hover:border-gray-300 transition-all">
                    $50 - $100
                </button>
                <button type="button" onclick="document.querySelector('[name=min_price]').value=100; document.querySelector('[name=max_price]').value=200;" 
                    class="h-9 text-xs font-medium text-slate-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 hover:border-gray-300 transition-all">
                    $100 - $200
                </button>
                <button type="button" onclick="document.querySelector('[name=min_price]').value=200; document.querySelector('[name=max_price]').value='';" 
                    class="h-9 text-xs font-medium text-slate-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 hover:border-gray-300 transition-all">
                    $200+
                </button>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-100"></div>

    <!-- Availability -->
    <div x-data="{ open: true }" class="pb-6">
        <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-left">
            <span class="font-semibold text-slate-900">Availability</span>
            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-slate-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-collapse class="mt-4 space-y-2">
            <label class="flex items-center cursor-pointer py-1.5 px-2 rounded-lg hover:bg-gray-50 transition-colors">
                <input type="radio" name="stock" value="" {{ !request('stock') ? 'checked' : '' }} 
                    class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600">All Products</span>
            </label>
            <label class="flex items-center cursor-pointer py-1.5 px-2 rounded-lg hover:bg-gray-50 transition-colors">
                <input type="radio" name="stock" value="in_stock" {{ request('stock') == 'in_stock' ? 'checked' : '' }} 
                    class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600">In Stock</span>
                <span class="ml-auto w-2 h-2 bg-green-500 rounded-full"></span>
            </label>
            <label class="flex items-center cursor-pointer py-1.5 px-2 rounded-lg hover:bg-gray-50 transition-colors">
                <input type="radio" name="stock" value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'checked' : '' }} 
                    class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                <span class="ml-3 text-sm text-slate-600">Out of Stock</span>
                <span class="ml-auto w-2 h-2 bg-red-500 rounded-full"></span>
            </label>
        </div>
    </div>

    <!-- Actions -->
    <div class="space-y-3 pt-2">
        <button type="submit" class="w-full h-11 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Apply Filters
        </button>
        <a href="{{ route('shop.index') }}" class="w-full h-11 bg-white text-slate-700 font-medium rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors flex items-center justify-center">
            Clear All
        </a>
    </div>
</form>
