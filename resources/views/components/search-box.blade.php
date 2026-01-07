<div x-data="searchBox()" x-init="init()" @click.away="close()" class="relative">
    <!-- Search Input -->
    <div class="relative">
        <input 
            type="text" 
            x-model="query"
            @input.debounce.300ms="fetchSuggestions()"
            @focus="open()"
            @keydown.escape="close()"
            @keydown.enter="submitSearch()"
            @keydown.arrow-down.prevent="navigateDown()"
            @keydown.arrow-up.prevent="navigateUp()"
            placeholder="Search products..."
            class="w-full h-11 pl-11 pr-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 focus:bg-white transition-all"
        >
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <button x-show="query.length > 0" @click="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Dropdown -->
    <div x-show="isOpen && (suggestions.length > 0 || popular.length > 0 || history.length > 0 || trending.length > 0)" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         class="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 shadow-xl z-50 max-h-[400px] overflow-y-auto">
        
        <!-- Search History -->
        <template x-if="history.length > 0 && query.length === 0">
            <div class="p-3 border-b border-slate-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Recent Searches</span>
                    <button @click="clearHistory()" class="text-[10px] text-slate-400 hover:text-red-500">Clear</button>
                </div>
                <template x-for="(item, index) in history" :key="'h-'+index">
                    <div class="flex items-center justify-between group">
                        <button @click="selectSuggestion(item)" 
                                :class="{'bg-slate-50': selectedIndex === index}"
                                class="flex-1 flex items-center gap-2 px-2 py-2 text-left hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-[13px] text-slate-600" x-text="item"></span>
                        </button>
                        <button @click.stop="removeFromHistory(item)" class="p-1 text-slate-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>
        </template>

        <!-- Trending/Popular -->
        <template x-if="(trending.length > 0 || popular.length > 0) && query.length === 0">
            <div class="p-3 border-b border-slate-100">
                <span class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Popular Searches</span>
                <div class="flex flex-wrap gap-2">
                    <template x-for="item in (trending.length > 0 ? trending : popular)" :key="'p-'+item">
                        <button @click="selectSuggestion(item)" class="px-3 py-1.5 bg-slate-100 text-[12px] text-slate-600 hover:bg-slate-200 transition-colors">
                            <span x-text="item"></span>
                        </button>
                    </template>
                </div>
            </div>
        </template>

        <!-- Product Suggestions -->
        <template x-if="suggestions.length > 0">
            <div class="p-3">
                <span class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2" x-text="query.length > 0 ? 'Suggestions' : 'Products'"></span>
                <template x-for="(item, index) in suggestions" :key="'s-'+index">
                    <a :href="item.type === 'product' ? '/product/' + item.id : '/shop?category=' + encodeURIComponent(item.text)"
                       :class="{'bg-slate-50': selectedIndex === (history.length + index)}"
                       class="flex items-center gap-3 px-2 py-2 hover:bg-slate-50 transition-colors">
                        <template x-if="item.type === 'product'">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </template>
                        <template x-if="item.type === 'category'">
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </template>
                        <div class="flex-1">
                            <span class="text-[13px] text-slate-900" x-html="highlightMatch(item.text)"></span>
                            <span x-show="item.type === 'category'" class="text-[10px] text-slate-400 ml-2">in Categories</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </template>
            </div>
        </template>

        <!-- View All Results -->
        <template x-if="query.length >= 2">
            <div class="p-3 bg-slate-50 border-t border-slate-100">
                <button @click="submitSearch()" class="w-full text-center text-[12px] font-medium text-slate-900 hover:text-slate-600">
                    View all results for "<span x-text="query"></span>" →
                </button>
            </div>
        </template>
    </div>
</div>

<script>
function searchBox() {
    return {
        query: '',
        isOpen: false,
        suggestions: [],
        popular: [],
        trending: [],
        history: [],
        selectedIndex: -1,
        isLoading: false,

        init() {
            // Load initial data when focused
            this.loadDefaults();
        },

        open() {
            this.isOpen = true;
            if (this.query.length === 0) {
                this.loadDefaults();
            }
        },

        close() {
            this.isOpen = false;
            this.selectedIndex = -1;
        },

        async loadDefaults() {
            try {
                const response = await fetch('{{ route("search.suggestions") }}?q=');
                const data = await response.json();
                this.popular = data.popular || [];
                this.trending = data.trending || [];
                this.history = data.history || [];
            } catch (e) {
                console.error('Failed to load defaults', e);
            }
        },

        async fetchSuggestions() {
            if (this.query.length < 2) {
                this.suggestions = [];
                this.loadDefaults();
                return;
            }

            this.isLoading = true;
            try {
                const response = await fetch(`{{ route("search.suggestions") }}?q=${encodeURIComponent(this.query)}`);
                const data = await response.json();
                this.suggestions = data.suggestions || [];
                this.popular = data.popular || [];
                this.history = data.history || [];
            } catch (e) {
                console.error('Search failed', e);
            } finally {
                this.isLoading = false;
            }
        },

        selectSuggestion(text) {
            this.query = text;
            this.submitSearch();
        },

        submitSearch() {
            if (this.query.length > 0) {
                window.location.href = `{{ route("shop.index") }}?search=${encodeURIComponent(this.query)}`;
            }
        },

        clearSearch() {
            this.query = '';
            this.suggestions = [];
            this.loadDefaults();
        },

        async clearHistory() {
            try {
                await fetch('{{ route("search.history.clear") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                this.history = [];
            } catch (e) {
                console.error('Failed to clear history', e);
            }
        },

        async removeFromHistory(item) {
            try {
                await fetch('{{ route("search.history.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ query: item })
                });
                this.history = this.history.filter(h => h !== item);
            } catch (e) {
                console.error('Failed to remove from history', e);
            }
        },

        navigateDown() {
            const total = this.history.length + this.suggestions.length;
            if (this.selectedIndex < total - 1) {
                this.selectedIndex++;
            }
        },

        navigateUp() {
            if (this.selectedIndex > 0) {
                this.selectedIndex--;
            }
        },

        highlightMatch(text) {
            if (!this.query) return text;
            const regex = new RegExp(`(${this.query})`, 'gi');
            return text.replace(regex, '<strong class="text-slate-900">$1</strong>');
        }
    }
}
</script>
