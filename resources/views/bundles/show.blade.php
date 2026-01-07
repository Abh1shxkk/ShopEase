@extends('layouts.shop')

@section('title', $bundle->name)

@section('content')
<div class="min-h-screen bg-slate-50 py-12 pt-24">
    <div class="max-w-6xl mx-auto px-4">
        {{-- Breadcrumb --}}
        <nav class="mb-8">
            <ol class="flex items-center gap-2 text-[12px]">
                <li><a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-900">Home</a></li>
                <li class="text-slate-300">/</li>
                <li><a href="{{ route('bundles.index') }}" class="text-slate-500 hover:text-slate-900">Bundles</a></li>
                <li class="text-slate-300">/</li>
                <li class="text-slate-900">{{ $bundle->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- Left: Bundle Image & Products --}}
            <div>
                @if($bundle->image)
                <div class="bg-white border border-slate-200 mb-6">
                    <img src="{{ Storage::url($bundle->image) }}" alt="{{ $bundle->name }}" class="w-full h-80 object-cover">
                </div>
                @endif

                {{-- Products in Bundle --}}
                <div class="bg-white border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">What's Included</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($bundle->items as $item)
                        <div class="p-4 flex items-center gap-4">
                            <div class="w-20 h-20 bg-slate-100 flex-shrink-0">
                                @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('shop.show', $item->product) }}" class="text-[14px] font-medium text-slate-900 hover:underline">
                                    {{ $item->product->name }}
                                </a>
                                <p class="text-[12px] text-slate-500 mt-1">Qty: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[14px] font-semibold text-slate-900">₹{{ number_format($item->product->price * $item->quantity) }}</p>
                                @if($item->quantity > 1)
                                <p class="text-[11px] text-slate-400">₹{{ number_format($item->product->price) }} each</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right: Bundle Details --}}
            <div>
                <div class="bg-white border border-slate-200 p-8 sticky top-24">
                    {{-- Badge --}}
                    <div class="inline-block bg-red-500 text-white px-4 py-2 mb-6">
                        <span class="text-[12px] font-bold tracking-wider">BUNDLE OFFER - SAVE {{ $bundle->savings_percentage }}%</span>
                    </div>

                    <h1 class="text-2xl font-serif tracking-wide text-slate-900 mb-4">{{ $bundle->name }}</h1>
                    
                    @if($bundle->description)
                    <p class="text-[14px] text-slate-600 mb-6 leading-relaxed">{{ $bundle->description }}</p>
                    @endif

                    {{-- Pricing Breakdown --}}
                    <div class="bg-slate-50 p-6 mb-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-[14px]">
                                <span class="text-slate-500">Original Price ({{ $bundle->items->count() }} items)</span>
                                <span class="text-slate-400 line-through">₹{{ number_format($bundle->original_price) }}</span>
                            </div>
                            <div class="flex justify-between text-[14px]">
                                <span class="text-slate-500">Bundle Discount</span>
                                <span class="text-red-600">-₹{{ number_format($bundle->savings) }}</span>
                            </div>
                            <div class="border-t border-slate-200 pt-3 flex justify-between">
                                <span class="text-lg font-medium text-slate-900">Bundle Price</span>
                                <span class="text-2xl font-bold text-slate-900">₹{{ number_format($bundle->bundle_price) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Savings Highlight --}}
                    <div class="bg-emerald-50 border border-emerald-200 p-4 mb-6 text-center">
                        <p class="text-emerald-700 font-medium">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            You save ₹{{ number_format($bundle->savings) }} with this bundle!
                        </p>
                    </div>

                    {{-- Validity --}}
                    @if($bundle->ends_at)
                    <div class="bg-amber-50 border border-amber-200 p-4 mb-6 text-center">
                        <p class="text-amber-700 text-[13px]">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Offer valid till {{ $bundle->ends_at->format('M d, Y') }}
                        </p>
                    </div>
                    @endif

                    {{-- Add to Cart --}}
                    <form method="POST" action="{{ route('bundles.add-to-cart', $bundle) }}">
                        @csrf
                        <button type="submit" class="w-full h-14 bg-slate-900 text-white text-[12px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Add Bundle to Cart
                        </button>
                    </form>

                    <p class="text-[11px] text-slate-400 text-center mt-4">All {{ $bundle->items->count() }} products will be added to your cart</p>
                </div>
            </div>
        </div>

        {{-- Related Bundles --}}
        @if($relatedBundles->isNotEmpty())
        <div class="mt-16">
            <h2 class="text-xl font-serif text-slate-900 mb-6">More Bundle Offers</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedBundles as $related)
                <a href="{{ route('bundles.show', $related) }}" class="bg-white border border-slate-200 p-4 hover:border-slate-400 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="flex -space-x-2">
                            @foreach($related->items->take(2) as $item)
                            <div class="w-12 h-12 bg-slate-100 border border-white rounded-full overflow-hidden">
                                @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" alt="" class="w-full h-full object-cover">
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <div class="flex-1">
                            <h4 class="text-[14px] font-medium text-slate-900">{{ $related->name }}</h4>
                            <p class="text-[12px] text-emerald-600">Save {{ $related->savings_percentage }}%</p>
                        </div>
                        <span class="text-lg font-bold text-slate-900">₹{{ number_format($related->bundle_price) }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
