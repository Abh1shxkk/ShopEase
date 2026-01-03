@extends('layouts.shop')

@section('title', $product->name)

@section('content')
<div x-data="{ 
    quantity: 1,
    inWishlist: false,
    activeTab: 'details',
    selectedImage: 0,
    maxStock: {{ $product->stock }},
    increment() { if (this.quantity < this.maxStock) this.quantity++ },
    decrement() { if (this.quantity > 1) this.quantity-- }
}">
    <!-- Breadcrumb -->
    <div class="bg-gray-50/50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center gap-2 text-sm">
                <a href="/" class="text-slate-500 hover:text-slate-900 transition-colors">Home</a>
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('shop.index') }}" class="text-slate-500 hover:text-slate-900 transition-colors">Shop</a>
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('shop.index', ['category' => $product->category]) }}" class="text-slate-500 hover:text-slate-900 transition-colors">{{ $product->category }}</a>
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-900 font-medium">{{ Str::limit($product->name, 25) }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <!-- Product Section -->
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-16">
            <!-- Images -->
            <div class="space-y-4">
                <div class="aspect-square bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 relative group">
                    @if($product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                        <svg class="w-32 h-32 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                    
                    @if($product->discount_price)
                    <div class="absolute top-4 left-4 bg-red-500 text-white text-sm font-bold px-3 py-1.5 rounded-full">
                        -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="lg:py-4">
                <!-- Category & Title -->
                <div class="mb-6">
                    <a href="{{ route('shop.index', ['category' => $product->category]) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 font-medium hover:text-blue-700 transition-colors mb-3">
                        {{ $product->category }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <h1 class="text-2xl lg:text-4xl font-bold text-slate-900 leading-tight">{{ $product->name }}</h1>
                </div>

                <!-- Rating -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex items-center gap-1">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 {{ $i < 4 ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <span class="ml-2 text-sm font-medium text-slate-900">4.5</span>
                    </div>
                    <span class="text-slate-300">|</span>
                    <span class="text-sm text-slate-500">{{ rand(50, 300) }} reviews</span>
                    <span class="text-slate-300">|</span>
                    <span class="text-sm text-slate-500">{{ rand(100, 500) }}+ sold</span>
                </div>

                <!-- Price -->
                <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                    <div class="flex items-baseline gap-3 mb-2">
                        @if($product->discount_price)
                        <span class="text-4xl font-bold text-slate-900">${{ number_format($product->discount_price, 2) }}</span>
                        <span class="text-xl text-slate-400 line-through">${{ number_format($product->price, 2) }}</span>
                        <span class="ml-2 px-3 py-1 bg-red-100 text-red-600 text-sm font-semibold rounded-full">Save ${{ number_format($product->price - $product->discount_price, 2) }}</span>
                        @else
                        <span class="text-4xl font-bold text-slate-900">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-500">Inclusive of all taxes • Free shipping on orders over $50</p>
                </div>

                <!-- Stock Status -->
                <div class="flex items-center gap-3 mb-6">
                    @if($product->stock > 0)
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-green-50 rounded-full">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-sm font-medium text-green-700">In Stock</span>
                    </div>
                    <span class="text-sm text-slate-500">{{ $product->stock }} units available</span>
                    @else
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-red-50 rounded-full">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        <span class="text-sm font-medium text-red-700">Out of Stock</span>
                    </div>
                    @endif
                </div>

                <!-- Description -->
                <p class="text-slate-600 leading-relaxed mb-8">{{ Str::limit($product->description, 250) }}</p>

                @if($product->stock > 0)
                <!-- Quantity Selector -->
                <div class="flex items-center gap-6 mb-6">
                    <span class="text-sm font-medium text-slate-700">Quantity</span>
                    <div class="flex items-center bg-gray-50 rounded-xl border border-gray-200">
                        <button @click="decrement()" class="w-12 h-12 flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-gray-100 rounded-l-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <input type="number" x-model="quantity" min="1" :max="maxStock" class="w-16 h-12 text-center bg-transparent border-x border-gray-200 font-semibold text-slate-900 focus:outline-none">
                        <button @click="increment()" class="w-12 h-12 flex items-center justify-center text-slate-600 hover:text-slate-900 hover:bg-gray-100 rounded-r-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 mb-8">
                    @auth
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" :value="quantity">
                        <button type="submit" class="w-full h-14 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Add to Cart
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="flex-1 h-14 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Login to Add to Cart
                    </a>
                    @endauth
                    @auth
                    <a href="{{ route('checkout') }}" class="flex-1 h-14 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 active:scale-[0.98] transition-all flex items-center justify-center">
                        Buy Now
                    </a>
                    <form action="{{ route('wishlist.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-14 h-14 border-2 border-gray-200 rounded-xl flex items-center justify-center hover:border-red-300 hover:bg-red-50 transition-all">
                            <svg class="w-6 h-6 text-slate-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="flex-1 h-14 bg-slate-900 text-white font-semibold rounded-xl hover:bg-slate-800 active:scale-[0.98] transition-all flex items-center justify-center">
                        Buy Now
                    </a>
                    @endauth
                </div>
                @else
                <!-- Notify Me -->
                <div class="bg-slate-50 rounded-2xl p-6 mb-8">
                    <h4 class="font-semibold text-slate-900 mb-2">Get notified when back in stock</h4>
                    <p class="text-sm text-slate-500 mb-4">We'll send you an email when this product is available again.</p>
                    <div class="flex gap-3">
                        <input type="email" placeholder="Enter your email" class="flex-1 h-12 px-4 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <button class="h-12 px-6 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">Notify Me</button>
                    </div>
                </div>
                @endif

                <!-- Features -->
                <div class="grid grid-cols-3 gap-4 p-4 bg-gray-50 rounded-2xl">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mx-auto mb-2 shadow-sm">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        </div>
                        <p class="text-xs font-medium text-slate-900">Free Delivery</p>
                        <p class="text-xs text-slate-500">Orders $50+</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mx-auto mb-2 shadow-sm">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <p class="text-xs font-medium text-slate-900">Easy Returns</p>
                        <p class="text-xs text-slate-500">30 Days</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mx-auto mb-2 shadow-sm">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <p class="text-xs font-medium text-slate-900">Secure</p>
                        <p class="text-xs text-slate-500">100% Safe</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="mt-16">
            <div class="border-b border-gray-200">
                <nav class="flex gap-8 -mb-px">
                    <button @click="activeTab = 'details'" :class="activeTab === 'details' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Product Details
                    </button>
                    <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Reviews ({{ rand(50, 300) }})
                    </button>
                    <button @click="activeTab = 'shipping'" :class="activeTab === 'shipping' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-gray-300'" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Shipping & Returns
                    </button>
                </nav>
            </div>

            <div class="py-8">
                <!-- Details Tab -->
                <div x-show="activeTab === 'details'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="prose prose-slate max-w-none">
                        <p class="text-slate-600 leading-relaxed text-lg">{{ $product->description }}</p>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div x-show="activeTab === 'reviews'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 text-center">
                            <div class="text-6xl font-bold text-slate-900 mb-3">4.5</div>
                            <div class="flex justify-center gap-1 mb-3">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-6 h-6 {{ $i < 4 ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 fill-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <p class="text-slate-500">Based on {{ rand(50, 300) }} reviews</p>
                        </div>
                        <div class="md:col-span-2 space-y-3">
                            @foreach([5, 4, 3, 2, 1] as $star)
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-medium text-slate-600 w-12">{{ $star }} star</span>
                                <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-yellow-400 rounded-full transition-all duration-500" style="width: {{ $star == 5 ? 60 : ($star == 4 ? 25 : ($star == 3 ? 10 : 5)) }}%"></div>
                                </div>
                                <span class="text-sm text-slate-500 w-12 text-right">{{ $star == 5 ? 60 : ($star == 4 ? 25 : ($star == 3 ? 10 : 5)) }}%</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Shipping Tab -->
                <div x-show="activeTab === 'shipping'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                </div>
                                <h4 class="font-semibold text-slate-900">Shipping Information</h4>
                            </div>
                            <ul class="space-y-2 text-slate-600">
                                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Free standard shipping on orders over $50</li>
                                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Express shipping available at checkout</li>
                                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Delivery within 3-5 business days</li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </div>
                                <h4 class="font-semibold text-slate-900">Return Policy</h4>
                            </div>
                            <ul class="space-y-2 text-slate-600">
                                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 30-day return policy</li>
                                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Items must be unused and in original packaging</li>
                                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Free returns on defective items</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-slate-900">You May Also Like</h2>
                <a href="{{ route('shop.index', ['category' => $product->category]) }}" class="text-blue-600 font-medium hover:text-blue-700 transition-colors flex items-center gap-1">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @foreach($relatedProducts as $related)
                <a href="{{ route('shop.show', $related) }}" class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg hover:border-gray-200 transition-all duration-300">
                    <div class="aspect-square bg-gray-50 overflow-hidden">
                        @if($related->image)
                        <img src="{{ Storage::url($related->image) }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-sm font-medium text-slate-900 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $related->name }}</p>
                        <p class="text-sm font-bold text-slate-900 mt-1">${{ number_format($related->discount_price ?? $related->price, 2) }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
