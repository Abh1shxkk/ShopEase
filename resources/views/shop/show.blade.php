@extends('layouts.shop')

@section('title', $product->name)

@section('content')
<div x-data="{ 
    quantity: 1,
    activeTab: 'details',
    maxStock: {{ min(10, $product->stock) }},
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
                @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-24 h-24 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif
            </div>
            @if($product->discount_price)
            <div class="absolute top-4 left-4 bg-slate-900 text-white text-[10px] font-bold tracking-widest uppercase px-3 py-2">
                Sale
            </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div class="lg:py-6">
            {{-- Category --}}
            <a href="{{ route('shop.index', ['category' => $product->category_name]) }}" class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-400 hover:text-slate-900 transition-colors">
                {{ $product->category_name }}
            </a>

            {{-- Title --}}
            <h1 class="text-3xl lg:text-4xl font-serif tracking-wide text-slate-900 mt-3 mb-6">{{ $product->name }}</h1>

            {{-- Price --}}
            <div class="flex items-baseline gap-4 mb-8">
                @if($product->discount_price)
                <span class="text-2xl font-semibold text-slate-900">Rs. {{ number_format($product->discount_price, 2) }}</span>
                <span class="text-lg text-slate-400 line-through">Rs. {{ number_format($product->price, 2) }}</span>
                <span class="text-[11px] font-bold tracking-wider uppercase text-red-600">
                    Save {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                </span>
                @else
                <span class="text-2xl font-semibold text-slate-900">Rs. {{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            {{-- Description --}}
            <p class="text-[14px] text-slate-600 leading-relaxed mb-8">{{ $product->description }}</p>

            {{-- Stock Status --}}
            <div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-100">
                @if($product->stock > 0)
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                <span class="text-[12px] font-medium text-green-700">In Stock</span>
                <span class="text-[12px] text-slate-400">{{ $product->stock }} available</span>
                @else
                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                <span class="text-[12px] font-medium text-red-700">Out of Stock</span>
                @endif
            </div>

            @if($product->stock > 0)
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
                    <input type="hidden" name="quantity" :value="quantity">
                    <button type="submit" class="w-full h-14 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors flex items-center justify-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Add to Cart
                    </button>
                </form>
                <form action="{{ route('wishlist.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="w-14 h-14 border border-slate-200 flex items-center justify-center hover:bg-slate-50 hover:border-slate-900 transition-all">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="flex-1 h-14 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors flex items-center justify-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Login to Add to Cart
                </a>
                @endauth
            </div>
            @else
            {{-- Out of Stock - Notify Me Section --}}
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
            @endif

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
</div>
@endsection
