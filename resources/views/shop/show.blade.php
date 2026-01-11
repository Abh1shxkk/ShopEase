@extends('layouts.shop')

@section('title', $product->name)

@section('content')
<div x-data="{ 
    quantity: 1,
    activeTab: 'details',
    selectedVariant: null,
    selectedSize: null,
    selectedColor: null,
    selectedMaterial: null,
    variants: {{ $product->has_variants ? json_encode($product->activeVariants) : '[]' }},
    hasVariants: {{ $product->has_variants ? 'true' : 'false' }},
    get maxStock() {
        if (this.hasVariants && this.selectedVariant) {
            return Math.min(10, this.selectedVariant.stock);
        }
        return {{ min(10, $product->stock) }};
    },
    get currentPrice() {
        if (this.hasVariants && this.selectedVariant) {
            return this.selectedVariant.discount_price || this.selectedVariant.price || {{ $product->discount_price ?? $product->price }};
        }
        return {{ $product->discount_price ?? $product->price }};
    },
    get originalPrice() {
        if (this.hasVariants && this.selectedVariant && this.selectedVariant.price) {
            return this.selectedVariant.price;
        }
        return {{ $product->price }};
    },
    get isOnSale() {
        if (this.hasVariants && this.selectedVariant) {
            return this.selectedVariant.discount_price && this.selectedVariant.discount_price < (this.selectedVariant.price || {{ $product->price }});
        }
        return {{ $product->discount_price ? 'true' : 'false' }};
    },
    get totalVariantStock() {
        return this.variants.reduce((sum, v) => sum + (v.stock || 0), 0);
    },
    get currentStock() {
        if (this.hasVariants) {
            if (this.selectedVariant) {
                return this.selectedVariant.stock;
            }
            // Show total variant stock when no variant selected
            return this.totalVariantStock;
        }
        return {{ $product->stock }};
    },
    get currentImage() {
        if (this.hasVariants && this.selectedVariant && this.selectedVariant.image) {
            if (this.selectedVariant.image.startsWith('http')) {
                return this.selectedVariant.image;
            }
            return '/storage/' + this.selectedVariant.image;
        }
        return '{{ $product->image_url }}';
    },
    get availableSizes() {
        return [...new Set(this.variants.filter(v => v.size).map(v => v.size))];
    },
    get availableColors() {
        let filtered = this.variants;
        if (this.selectedSize) {
            filtered = filtered.filter(v => v.size === this.selectedSize);
        }
        return filtered.filter(v => v.color).map(v => ({ color: v.color, code: v.color_code }))
            .filter((v, i, a) => a.findIndex(t => t.color === v.color) === i);
    },
    get availableMaterials() {
        let filtered = this.variants;
        if (this.selectedSize) {
            filtered = filtered.filter(v => v.size === this.selectedSize);
        }
        if (this.selectedColor) {
            filtered = filtered.filter(v => v.color === this.selectedColor);
        }
        return [...new Set(filtered.filter(v => v.material).map(v => v.material))];
    },
    selectSize(size) {
        this.selectedSize = size;
        this.selectedColor = null;
        this.selectedMaterial = null;
        this.updateSelectedVariant();
    },
    selectColor(color) {
        this.selectedColor = color;
        this.selectedMaterial = null;
        this.updateSelectedVariant();
    },
    selectMaterial(material) {
        this.selectedMaterial = material;
        this.updateSelectedVariant();
    },
    updateSelectedVariant() {
        this.selectedVariant = this.variants.find(v => 
            (!this.selectedSize || v.size === this.selectedSize) &&
            (!this.selectedColor || v.color === this.selectedColor) &&
            (!this.selectedMaterial || v.material === this.selectedMaterial)
        ) || null;
        this.quantity = 1;
    },
    get canAddToCart() {
        if (!this.hasVariants) return this.currentStock > 0;
        // For products with variants, need to select a variant with stock
        return this.selectedVariant && this.selectedVariant.stock > 0;
    },
    get needsVariantSelection() {
        // Show variant selection prompt when variants exist but none selected
        return this.hasVariants && !this.selectedVariant && this.totalVariantStock > 0;
    },
    get isOutOfStock() {
        // True only when there's genuinely no stock
        if (!this.hasVariants) return this.currentStock <= 0;
        return this.totalVariantStock <= 0;
    },
    increment() { if (this.quantity < this.maxStock) this.quantity++ },
    decrement() { if (this.quantity > 1) this.quantity-- }
}" class="max-w-[1440px] mx-auto px-6 md:px-12 py-12">
    
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-12">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-slate-900 transition-colors">Shop</a>
        <span>/</span>
        <a href="{{ route('shop.index', ['category' => $product->category_name]) }}" class="hover:text-slate-900 transition-colors">{{ $product->category_name }}</a>
        <span>/</span>
        <span class="text-slate-900">{{ Str::limit($product->name, 30) }}</span>
    </nav>

    {{-- Product Section --}}
    <div class="grid lg:grid-cols-2 gap-12 lg:gap-20">
        {{-- Product Image --}}
        <div class="relative">
            <div class="aspect-[4/5] bg-[#f7f7f7] overflow-hidden">
                <img :src="currentImage" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
            <div x-show="isOnSale" class="absolute top-4 left-4 bg-slate-900 text-white text-[10px] font-bold tracking-widest uppercase px-3 py-2">
                Sale
            </div>
        </div>

        {{-- Product Info --}}
        <div class="lg:py-6">
            {{-- Category --}}
            <a href="{{ route('shop.index', ['category' => $product->category_name]) }}" class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-400 hover:text-slate-900 transition-colors">
                {{ $product->category_name }}
            </a>

            {{-- Title --}}
            <h1 class="text-3xl lg:text-4xl font-serif tracking-wide text-slate-900 mt-3 mb-6">{{ $product->name }}</h1>

            {{-- Flash Sale Banner --}}
            @if(isset($flashSaleInfo) && $flashSaleInfo)
            <div class="bg-gradient-to-r from-red-600 to-red-500 text-white p-4 mb-6" x-data="{ 
                endTime: new Date('{{ $flashSaleInfo['ends_at'] }}').getTime(),
                timeLeft: '',
                init() {
                    this.updateTimer();
                    setInterval(() => this.updateTimer(), 1000);
                },
                updateTimer() {
                    const now = new Date().getTime();
                    const distance = this.endTime - now;
                    if (distance < 0) {
                        this.timeLeft = 'Sale Ended';
                        return;
                    }
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    this.timeLeft = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
                }
            }">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <div>
                            <p class="text-[11px] font-bold tracking-widest uppercase">Flash Sale</p>
                            <p class="text-[10px] opacity-90">{{ $flashSaleInfo['name'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] opacity-90">Ends in</p>
                        <p class="text-lg font-bold font-mono" x-text="timeLeft"></p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Price --}}
            <div class="flex items-baseline gap-4 mb-8">
                @if(isset($flashSaleInfo) && $flashSaleInfo)
                <span class="text-2xl font-semibold text-red-600">Rs. {{ number_format($flashSaleInfo['sale_price'], 2) }}</span>
                <span class="text-lg text-slate-400 line-through">Rs. {{ number_format($product->price, 2) }}</span>
                <span class="text-[11px] font-bold tracking-wider uppercase text-red-600">
                    Save {{ round((($product->price - $flashSaleInfo['sale_price']) / $product->price) * 100) }}%
                </span>
                @else
                <span class="text-2xl font-semibold text-slate-900">Rs. <span x-text="currentPrice.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                <template x-if="isOnSale">
                    <span class="text-lg text-slate-400 line-through">Rs. <span x-text="originalPrice.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
                </template>
                <template x-if="isOnSale">
                    <span class="text-[11px] font-bold tracking-wider uppercase text-red-600">
                        Save <span x-text="Math.round(((originalPrice - currentPrice) / originalPrice) * 100)"></span>%
                    </span>
                </template>
                @endif
            </div>

            {{-- Description --}}
            <p class="text-[14px] text-slate-600 leading-relaxed mb-8">{{ $product->description }}</p>

            {{-- Stock Status --}}
            <div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-100">
                <template x-if="currentStock > 0">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-[12px] font-medium text-green-700">In Stock</span>
                        <span class="text-[12px] text-slate-400"><span x-text="currentStock"></span> available</span>
                    </div>
                </template>
                <template x-if="currentStock <= 0">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        <span class="text-[12px] font-medium text-red-700">Out of Stock</span>
                    </div>
                </template>
            </div>

            {{-- Variant Selection --}}
            @if($product->has_variants)
            <div class="space-y-6 mb-8 pb-8 border-b border-slate-100">
                {{-- Size Guide Link --}}
                @php $sizeGuide = $product->sizeGuide(); @endphp
                @if($sizeGuide)
                <div x-data="{ showSizeGuide: false }">
                    <button @click="showSizeGuide = true" type="button" class="text-[11px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Size Guide
                    </button>
                    
                    {{-- Size Guide Modal --}}
                    <div x-show="showSizeGuide" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                        <div class="fixed inset-0 bg-black/50" @click="showSizeGuide = false"></div>
                        <div class="relative bg-white max-w-2xl w-full max-h-[90vh] overflow-y-auto p-8" @click.stop>
                            <button @click="showSizeGuide = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <h2 class="text-2xl font-serif text-slate-900 mb-2">{{ $sizeGuide->name }}</h2>
                            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mb-6">{{ ucfirst($sizeGuide->type) }} Size Guide</p>
                            @if($sizeGuide->measurements)
                            <div class="overflow-x-auto mb-6">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-slate-50">
                                            @foreach(array_keys($sizeGuide->measurements[0] ?? []) as $header)
                                            <th class="px-4 py-3 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500 border-b border-slate-100">{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sizeGuide->measurements as $row)
                                        <tr class="border-b border-slate-100">
                                            @foreach($row as $value)
                                            <td class="px-4 py-3 text-slate-600">{{ $value }}</td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            @if($sizeGuide->fit_tips)
                            <div class="bg-slate-50 p-4">
                                <h3 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Fit Tips</h3>
                                <p class="text-sm text-slate-600">{{ $sizeGuide->fit_tips }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                {{-- Size Selection --}}
                <template x-if="availableSizes.length > 0">
                    <div>
                        <span class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 block mb-3">Size</span>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="size in availableSizes" :key="size">
                                <button type="button" @click="selectSize(size)" 
                                    :class="selectedSize === size ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 hover:border-slate-400'"
                                    class="min-w-[44px] h-11 px-4 border text-[12px] font-medium transition-colors">
                                    <span x-text="size"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Color Selection --}}
                <template x-if="availableColors.length > 0">
                    <div>
                        <span class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 block mb-3">
                            Color<span x-show="selectedColor" class="font-normal text-slate-400">: <span x-text="selectedColor"></span></span>
                        </span>
                        <div class="flex flex-wrap gap-3">
                            <template x-for="colorObj in availableColors" :key="colorObj.color">
                                <button type="button" @click="selectColor(colorObj.color)" 
                                    :class="selectedColor === colorObj.color ? 'ring-2 ring-slate-900 ring-offset-2' : ''"
                                    class="w-10 h-10 rounded-full border border-slate-200 transition-all"
                                    :style="colorObj.code ? 'background-color: ' + colorObj.code : ''"
                                    :title="colorObj.color">
                                    <span x-show="!colorObj.code" x-text="colorObj.color.charAt(0)" class="text-[11px] font-bold text-slate-600"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Material Selection --}}
                <template x-if="availableMaterials.length > 0">
                    <div>
                        <span class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 block mb-3">Material</span>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="material in availableMaterials" :key="material">
                                <button type="button" @click="selectMaterial(material)" 
                                    :class="selectedMaterial === material ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 hover:border-slate-400'"
                                    class="h-10 px-4 border text-[12px] font-medium transition-colors">
                                    <span x-text="material"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                {{-- Selected Variant SKU --}}
                <template x-if="selectedVariant">
                    <div class="text-[11px] text-slate-400">
                        SKU: <span x-text="selectedVariant.sku" class="font-mono"></span>
                    </div>
                </template>
            </div>
            @endif

            <template x-if="canAddToCart">
            <div>
            {{-- Quantity --}}
            <div class="flex items-center gap-6 mb-8">
                <span class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Quantity</span>
                <div class="flex items-center border border-slate-200">
                    <button @click="decrement()" class="w-10 h-10 flex items-center justify-center hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                    <span class="w-12 h-10 flex items-center justify-center text-[13px] font-medium border-x border-slate-200" x-text="quantity"></span>
                    <button @click="increment()" class="w-10 h-10 flex items-center justify-center hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 mb-10">
                @auth
                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">
                    <input type="hidden" name="quantity" :value="quantity">
                    <button type="submit" :disabled="hasVariants && !selectedVariant" class="w-full h-14 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span x-text="hasVariants && !selectedVariant ? 'Select Options' : 'Add to Cart'"></span>
                    </button>
                </form>
                <form action="{{ route('wishlist.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="w-14 h-14 border border-slate-200 flex items-center justify-center hover:bg-slate-50 hover:border-slate-900 transition-all">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </form>
                <button onclick="addToCompare({{ $product->id }})" class="w-14 h-14 border border-slate-200 flex items-center justify-center hover:bg-slate-50 hover:border-slate-900 transition-all" title="Add to Compare">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                </button>
                @else
                <a href="{{ route('login') }}" class="flex-1 h-14 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors flex items-center justify-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Login to Add to Cart
                </a>
                @endauth
            </div>
            </div>
            </template>
            
            {{-- Needs Variant Selection Message --}}
            <template x-if="needsVariantSelection">
            <div class="bg-amber-50 border border-amber-200 p-6 mb-10">
                <div class="flex items-center gap-3 text-amber-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-[13px] font-medium">Please select your size and options above to add to cart</p>
                </div>
            </div>
            </template>
            
            {{-- Out of Stock - Notify Me Section (only when genuinely out of stock) --}}
            <template x-if="isOutOfStock">
            <div class="bg-slate-50 p-6 mb-10" x-data="{ 
                email: '{{ auth()->user()->email ?? '' }}', 
                loading: false, 
                success: false, 
                error: '',
                async subscribe() {
                    if (!this.email) { this.error = 'Please enter your email'; return; }
                    this.loading = true;
                    this.error = '';
                    try {
                        const response = await fetch('{{ route('stock-notification.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ product_id: {{ $product->id }}, email: this.email })
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.success = true;
                        } else {
                            this.error = data.message;
                        }
                    } catch (e) {
                        this.error = 'Something went wrong. Please try again.';
                    } finally {
                        this.loading = false;
                    }
                }
            }">
                <template x-if="!success">
                    <div>
                        <p class="text-[13px] text-slate-600 mb-4">This item is currently out of stock. Enter your email to be notified when it's back.</p>
                        <div class="flex gap-3">
                            <input type="email" x-model="email" placeholder="Your email" class="flex-1 h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                            <button @click="subscribe()" :disabled="loading" class="h-12 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors disabled:opacity-50">
                                <span x-show="!loading">Notify Me</span>
                                <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </button>
                        </div>
                        <p x-show="error" x-text="error" class="text-red-500 text-xs mt-2"></p>
                    </div>
                </template>
                <template x-if="success">
                    <div class="text-center py-4">
                        <svg class="w-12 h-12 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[13px] font-medium text-slate-900">You're on the list!</p>
                        <p class="text-[12px] text-slate-500 mt-1">We'll email you when this item is back in stock.</p>
                    </div>
                </template>
            </div>
            </template>

            {{-- Features --}}
            <div class="grid grid-cols-3 gap-6 py-8 border-t border-slate-100">
                <div class="text-center">
                    <svg class="w-6 h-6 mx-auto mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    <p class="text-[10px] font-bold tracking-widest uppercase text-slate-900">Free Shipping</p>
                    <p class="text-[10px] text-slate-400 mt-1">Orders over Rs. 250</p>
                </div>
                <div class="text-center">
                    <svg class="w-6 h-6 mx-auto mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <p class="text-[10px] font-bold tracking-widest uppercase text-slate-900">Easy Returns</p>
                    <p class="text-[10px] text-slate-400 mt-1">30 day returns</p>
                </div>
                <div class="text-center">
                    <svg class="w-6 h-6 mx-auto mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <p class="text-[10px] font-bold tracking-widest uppercase text-slate-900">Secure Payment</p>
                    <p class="text-[10px] text-slate-400 mt-1">100% protected</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Section --}}
    <div class="mt-20 border-t border-slate-100 pt-12">
        <div class="flex gap-12 border-b border-slate-100 mb-8">
            <button @click="activeTab = 'details'" :class="activeTab === 'details' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400'" class="pb-4 border-b-2 text-[11px] font-bold tracking-[0.2em] uppercase transition-colors">
                Details
            </button>
            <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400'" class="pb-4 border-b-2 text-[11px] font-bold tracking-[0.2em] uppercase transition-colors">
                Reviews ({{ $product->reviews_count }})
            </button>
            <button @click="activeTab = 'qa'" :class="activeTab === 'qa' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400'" class="pb-4 border-b-2 text-[11px] font-bold tracking-[0.2em] uppercase transition-colors">
                Q&A ({{ $product->approvedQuestions()->count() }})
            </button>
            <button @click="activeTab = 'shipping'" :class="activeTab === 'shipping' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-400'" class="pb-4 border-b-2 text-[11px] font-bold tracking-[0.2em] uppercase transition-colors">
                Shipping & Returns
            </button>
        </div>

        {{-- Details Tab --}}
        <div x-show="activeTab === 'details'" x-transition class="max-w-3xl">
            <p class="text-[14px] text-slate-600 leading-relaxed">{{ $product->description }}</p>
            <div class="mt-8 space-y-4">
                <div class="flex gap-8 py-3 border-b border-slate-100">
                    <span class="text-[11px] font-bold tracking-widest uppercase text-slate-400 w-32">Category</span>
                    <span class="text-[13px] text-slate-900">{{ $product->category_name }}</span>
                </div>
                @if($product->gender)
                <div class="flex gap-8 py-3 border-b border-slate-100">
                    <span class="text-[11px] font-bold tracking-widest uppercase text-slate-400 w-32">Gender</span>
                    <span class="text-[13px] text-slate-900 capitalize">{{ $product->gender }}</span>
                </div>
                @endif
                <div class="flex gap-8 py-3 border-b border-slate-100">
                    <span class="text-[11px] font-bold tracking-widest uppercase text-slate-400 w-32">Availability</span>
                    <span class="text-[13px] text-slate-900">{{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}</span>
                </div>
            </div>
        </div>

        {{-- Reviews Tab --}}
        <div x-show="activeTab === 'reviews'" x-cloak x-transition>
            <div class="grid lg:grid-cols-3 gap-12">
                {{-- Rating Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-slate-50 p-8 text-center">
                        <div class="text-5xl font-serif text-slate-900 mb-2">{{ $product->average_rating ?: '0.0' }}</div>
                        <div class="flex justify-center gap-1 mb-3">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($product->average_rating) ? 'text-yellow-400 fill-yellow-400' : 'text-slate-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="text-[12px] text-slate-500">Based on {{ $product->reviews_count }} reviews</p>
                    </div>

                    {{-- Write Review Form --}}
                    @auth
                    @php
                        $userReview = $product->reviews()->where('user_id', auth()->id())->first();
                    @endphp
                    @if(!$userReview)
                    <div class="mt-8" x-data="{ rating: 0, hoverRating: 0, submitting: false }">
                        <h4 class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-900 mb-4">Write a Review</h4>
                        <form action="{{ route('reviews.store', $product) }}" method="POST" @submit="submitting = true">
                            @csrf
                            <div class="mb-4">
                                <label class="text-[11px] font-bold tracking-widest uppercase text-slate-500 block mb-2">Rating</label>
                                <div class="flex gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                    <button type="button" @click="rating = {{ $i }}" @mouseenter="hoverRating = {{ $i }}" @mouseleave="hoverRating = 0">
                                        <svg class="w-7 h-7 transition-colors" :class="(hoverRating || rating) >= {{ $i }} ? 'text-yellow-400 fill-yellow-400' : 'text-slate-200'" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </button>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" x-model="rating" required>
                            </div>
                            <div class="mb-4">
                                <label class="text-[11px] font-bold tracking-widest uppercase text-slate-500 block mb-2">Title (Optional)</label>
                                <input type="text" name="title" maxlength="100" class="w-full h-10 px-4 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" placeholder="Summarize your review">
                            </div>
                            <div class="mb-4">
                                <label class="text-[11px] font-bold tracking-widest uppercase text-slate-500 block mb-2">Your Review</label>
                                <textarea name="comment" rows="4" required minlength="10" maxlength="1000" class="w-full px-4 py-3 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 resize-none" placeholder="Share your experience with this product..."></textarea>
                            </div>
                            <button type="submit" :disabled="!rating || submitting" class="w-full h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!submitting">Submit Review</span>
                                <span x-show="submitting">Submitting...</span>
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="mt-8 p-4 bg-green-50 border border-green-200">
                        <p class="text-[12px] text-green-700">You have already reviewed this product.</p>
                    </div>
                    @endif
                    @else
                    <div class="mt-8 p-6 bg-slate-50 text-center">
                        <p class="text-[13px] text-slate-600 mb-4">Login to write a review</p>
                        <a href="{{ route('login') }}" class="inline-flex h-10 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase items-center hover:bg-slate-800 transition-colors">Login</a>
                    </div>
                    @endauth
                </div>

                {{-- Reviews List --}}
                <div class="lg:col-span-2">
                    @forelse($product->approvedReviews()->with('user')->take(10)->get() as $review)
                    <div class="pb-8 mb-8 border-b border-slate-100 last:border-0">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="text-[14px] font-medium text-slate-900">{{ $review->user->name }}</span>
                                    @if($review->is_verified_purchase)
                                    <span class="text-[10px] font-bold tracking-wider uppercase text-green-600 bg-green-50 px-2 py-0.5">Verified Purchase</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="flex gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-slate-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span class="text-[11px] text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @auth
                            @if($review->user_id === auth()->id())
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] text-slate-400 hover:text-red-600 transition-colors">Delete</button>
                            </form>
                            @endif
                            @endauth
                        </div>
                        @if($review->title)
                        <h5 class="text-[14px] font-medium text-slate-900 mb-2">{{ $review->title }}</h5>
                        @endif
                        <p class="text-[13px] text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                        
                        {{-- Review Photos --}}
                        @if($review->photos->count() > 0)
                        <div class="flex gap-2 mt-3">
                            @foreach($review->photos as $photo)
                            <img src="{{ $photo->image_url }}" alt="Review photo" class="w-16 h-16 object-cover cursor-pointer hover:opacity-80 transition-opacity" onclick="window.open('{{ $photo->image_url }}', '_blank')">
                            @endforeach
                        </div>
                        @endif
                        
                        {{-- Helpful Voting --}}
                        <div class="flex items-center gap-4 mt-4 pt-4 border-t border-slate-100">
                            <span class="text-[11px] text-slate-400">Was this review helpful?</span>
                            @auth
                            <button onclick="voteReview({{ $review->id }}, true)" class="text-[11px] text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                Yes (<span id="helpful-{{ $review->id }}">{{ $review->helpful_count ?? 0 }}</span>)
                            </button>
                            <button onclick="voteReview({{ $review->id }}, false)" class="text-[11px] text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                No (<span id="not-helpful-{{ $review->id }}">{{ $review->not_helpful_count ?? 0 }}</span>)
                            </button>
                            @else
                            <span class="text-[11px] text-slate-400">{{ $review->helpful_count ?? 0 }} found this helpful</span>
                            @endauth
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <p class="text-[14px] text-slate-500">No reviews yet. Be the first to review this product!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Shipping Tab --}}
        <div x-show="activeTab === 'shipping'" x-cloak x-transition class="max-w-3xl">

        {{-- Q&A Tab --}}
        <div x-show="activeTab === 'qa'" x-cloak x-transition>
            @php
                $questions = $product->approvedQuestions()->with(['user', 'approvedAnswers.user'])->take(10)->get();
            @endphp
            
            <div class="grid lg:grid-cols-3 gap-12">
                {{-- Ask Question Form --}}
                <div class="lg:col-span-1">
                    @auth
                    <div class="bg-slate-50 p-6" x-data="{ submitting: false }">
                        <h4 class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-900 mb-4">Ask a Question</h4>
                        <form action="{{ route('product.question.store', $product) }}" method="POST" @submit="submitting = true">
                            @csrf
                            <div class="mb-4">
                                <textarea name="question" rows="4" required minlength="10" maxlength="500" class="w-full px-4 py-3 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 resize-none" placeholder="What would you like to know about this product?"></textarea>
                            </div>
                            <button type="submit" :disabled="submitting" class="w-full h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors disabled:opacity-50">
                                <span x-show="!submitting">Submit Question</span>
                                <span x-show="submitting">Submitting...</span>
                            </button>
                        </form>
                        <p class="text-[11px] text-slate-400 mt-3">Questions are reviewed before being published.</p>
                    </div>
                    @else
                    <div class="bg-slate-50 p-6 text-center">
                        <p class="text-[13px] text-slate-600 mb-4">Login to ask a question</p>
                        <a href="{{ route('login') }}" class="inline-flex h-10 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase items-center hover:bg-slate-800 transition-colors">Login</a>
                    </div>
                    @endauth
                </div>

                {{-- Questions List --}}
                <div class="lg:col-span-2">
                    @forelse($questions as $question)
                    <div class="pb-6 mb-6 border-b border-slate-100 last:border-0" x-data="{ showAnswerForm: false }">
                        <div class="flex gap-3 mb-3">
                            <span class="text-[11px] font-bold tracking-widest uppercase text-blue-600 mt-0.5">Q:</span>
                            <div class="flex-1">
                                <p class="text-[14px] text-slate-900">{{ $question->question }}</p>
                                <p class="text-[11px] text-slate-400 mt-1">Asked by {{ $question->user->name }} · {{ $question->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Answers --}}
                        @foreach($question->approvedAnswers as $answer)
                        <div class="flex gap-3 ml-6 mt-4 bg-slate-50 p-4">
                            <span class="text-[11px] font-bold tracking-widest uppercase {{ $answer->is_seller_answer ? 'text-green-600' : 'text-slate-400' }} mt-0.5">A:</span>
                            <div class="flex-1">
                                <p class="text-[13px] text-slate-700">{{ $answer->answer }}</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <p class="text-[11px] text-slate-400">
                                        @if($answer->is_seller_answer)
                                        <span class="text-green-600 font-medium">Seller</span>
                                        @else
                                        {{ $answer->user->name }}
                                        @endif
                                        · {{ $answer->created_at->diffForHumans() }}
                                    </p>
                                    @if($answer->helpful_count > 0)
                                    <span class="text-[11px] text-slate-400">{{ $answer->helpful_count }} found this helpful</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach

                        {{-- Answer Form --}}
                        @auth
                        <button @click="showAnswerForm = !showAnswerForm" class="ml-6 mt-3 text-[10px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors">
                            Answer this question
                        </button>
                        <div x-show="showAnswerForm" x-transition class="ml-6 mt-3">
                            <form action="{{ route('product.answer.store', $question) }}" method="POST">
                                @csrf
                                <textarea name="answer" rows="2" required minlength="10" maxlength="1000" placeholder="Share your answer..." class="w-full px-4 py-3 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 resize-none mb-2"></textarea>
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="showAnswerForm = false" class="px-4 py-1.5 text-[10px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-1.5 bg-slate-900 text-white text-[10px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endauth
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[14px] text-slate-500">No questions yet. Be the first to ask!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h4 class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-900 mb-4">Shipping</h4>
                    <ul class="space-y-3 text-[13px] text-slate-600">
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Free shipping on orders over Rs. 250
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Standard delivery: 3-5 business days
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Express delivery available at checkout
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-900 mb-4">Returns</h4>
                    <ul class="space-y-3 text-[13px] text-slate-600">
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            30-day return policy
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Items must be unused with tags
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Free returns on defective items
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Frequently Bought Together --}}
    @if(isset($frequentlyBought) && $frequentlyBought->isNotEmpty())
    <div class="mt-12 border-t border-slate-100 pt-12">
        <h2 class="text-xl font-serif tracking-wide mb-6">Frequently Bought Together</h2>
        <div class="bg-slate-50 p-6">
            <div class="flex flex-wrap items-center gap-4 mb-6">
                {{-- Current Product --}}
                <div class="flex items-center gap-3 p-3 bg-white border border-slate-200">
                    <input type="checkbox" checked disabled class="w-4 h-4 rounded border-slate-300">
                    <div class="w-16 h-16 bg-slate-100">
                        @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <p class="text-[12px] font-medium text-slate-900">{{ Str::limit($product->name, 20) }}</p>
                        <p class="text-[13px] font-semibold text-slate-900">₹{{ number_format($product->price) }}</p>
                    </div>
                </div>

                @foreach($frequentlyBought as $fbt)
                <span class="text-2xl text-slate-300">+</span>
                <div class="flex items-center gap-3 p-3 bg-white border border-slate-200 fbt-item" data-product-id="{{ $fbt->id }}" data-price="{{ $fbt->price }}">
                    <input type="checkbox" checked class="w-4 h-4 rounded border-slate-300 fbt-checkbox" onchange="updateFbtTotal()">
                    <div class="w-16 h-16 bg-slate-100">
                        @if($fbt->image_url)
                        <img src="{{ $fbt->image_url }}" alt="{{ $fbt->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('shop.show', $fbt) }}" class="text-[12px] font-medium text-slate-900 hover:underline">{{ Str::limit($fbt->name, 20) }}</a>
                        <p class="text-[13px] font-semibold text-slate-900">₹{{ number_format($fbt->price) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Total & Add All --}}
            <div class="flex items-center justify-between p-4 bg-white border border-slate-200">
                <div>
                    <p class="text-[12px] text-slate-500">Total for selected items:</p>
                    <p class="text-xl font-bold text-slate-900" id="fbtTotal">₹{{ number_format($product->price + $frequentlyBought->sum('price')) }}</p>
                </div>
                <button type="button" onclick="addFbtToCart()" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                    Add All to Cart
                </button>
            </div>
        </div>
    </div>

    <script>
    function updateFbtTotal() {
        const basePrice = {{ $product->price }};
        let total = basePrice;
        
        document.querySelectorAll('.fbt-checkbox:checked').forEach(checkbox => {
            const item = checkbox.closest('.fbt-item');
            total += parseFloat(item.dataset.price);
        });
        
        document.getElementById('fbtTotal').textContent = '₹' + total.toLocaleString('en-IN');
    }

    function addFbtToCart() {
        const productIds = [{{ $product->id }}];
        document.querySelectorAll('.fbt-checkbox:checked').forEach(checkbox => {
            const item = checkbox.closest('.fbt-item');
            productIds.push(parseInt(item.dataset.productId));
        });
        
        // Add each product to cart
        productIds.forEach(id => {
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: id, quantity: 1 })
            });
        });
        
        window.location.href = '{{ route("cart") }}';
    }
    </script>
    @endif

    {{-- Bundle Offers --}}
    @if(isset($productBundles) && $productBundles->isNotEmpty())
    <div class="mt-12 border-t border-slate-100 pt-12">
        <h2 class="text-xl font-serif tracking-wide mb-6">Bundle Offers</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($productBundles as $bundle)
            <div class="bg-slate-50 p-6 border border-slate-200">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-medium text-slate-900">{{ $bundle->name }}</h3>
                        <p class="text-[12px] text-slate-500">{{ $bundle->items->count() }} products included</p>
                    </div>
                    <span class="px-3 py-1 bg-red-500 text-white text-[11px] font-bold">SAVE {{ $bundle->savings_percentage }}%</span>
                </div>
                <div class="flex items-center gap-2 mb-4">
                    @foreach($bundle->items->take(4) as $item)
                    <div class="w-12 h-12 bg-white border border-slate-200">
                        @if($item->product->image_url)
                        <img src="{{ $item->product->image_url }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="flex items-baseline gap-3 mb-4">
                    <span class="text-xl font-bold text-slate-900">₹{{ number_format($bundle->bundle_price) }}</span>
                    <span class="text-sm text-slate-400 line-through">₹{{ number_format($bundle->original_price) }}</span>
                </div>
                <a href="{{ route('bundles.show', $bundle) }}" class="block w-full h-10 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors flex items-center justify-center">
                    View Bundle
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <div class="mt-20 border-t border-slate-100 pt-12">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl font-serif tracking-wide">You May Also Like</h2>
            <a href="{{ route('shop.index', ['category' => $product->category_name]) }}" class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors">
                View All →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts->take(4) as $related)
            <a href="{{ route('shop.show', $related) }}" class="group">
                <div class="aspect-[3/4] bg-[#f7f7f7] overflow-hidden mb-4">
                    @if($related->image_url)
                    <img src="{{ $related->image_url }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </div>
                <p class="text-[10px] tracking-widest uppercase text-slate-400 mb-1">{{ $related->category_name }}</p>
                <h3 class="text-[14px] font-medium text-slate-900 group-hover:text-slate-600 transition-colors mb-2">{{ $related->name }}</h3>
                <p class="text-[14px] font-semibold text-slate-900">Rs. {{ number_format($related->discount_price ?? $related->price, 2) }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recently Viewed Products --}}
    @if(isset($recentlyViewed) && $recentlyViewed->count() > 0)
    <div class="mt-20 border-t border-slate-100 pt-12">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl font-serif tracking-wide">Recently Viewed</h2>
            <a href="{{ route('shop.index') }}" class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors">
                Continue Shopping →
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($recentlyViewed as $viewed)
            <a href="{{ route('shop.show', $viewed) }}" class="group">
                <div class="aspect-[3/4] bg-[#f7f7f7] overflow-hidden mb-4">
                    @if($viewed->image_url)
                    <img src="{{ $viewed->image_url }}" alt="{{ $viewed->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </div>
                <p class="text-[10px] tracking-widest uppercase text-slate-400 mb-1">{{ $viewed->category_name }}</p>
                <h3 class="text-[14px] font-medium text-slate-900 group-hover:text-slate-600 transition-colors mb-2">{{ $viewed->name }}</h3>
                <p class="text-[14px] font-semibold text-slate-900">Rs. {{ number_format($viewed->discount_price ?? $viewed->price, 2) }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
// Review voting
function voteReview(reviewId, isHelpful) {
    fetch(`/reviews/${reviewId}/vote`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ is_helpful: isHelpful })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`helpful-${reviewId}`).textContent = data.helpful_count;
            document.getElementById(`not-helpful-${reviewId}`).textContent = data.not_helpful_count;
            showToast('Thanks for your feedback!');
        }
    })
    .catch(() => showToast('Something went wrong', 'error'));
}

// Add to compare
function addToCompare(productId) {
    fetch('{{ route('compare.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Something went wrong', 'error'));
}
</script>
@endpush
@endsection
