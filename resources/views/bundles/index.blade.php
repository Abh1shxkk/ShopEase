@extends('layouts.shop')

@section('title', 'Bundle Offers')

@section('content')
<div class="min-h-screen bg-slate-50 py-12 pt-24">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl font-serif tracking-wide text-slate-900">Bundle Offers</h1>
            <p class="text-slate-500 mt-2">Save more with our curated product bundles</p>
        </div>

        @if($bundles->isEmpty())
        <div class="bg-white border border-slate-200 p-12 text-center">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-slate-500 mb-4">No bundle offers available at the moment</p>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 text-slate-900 font-medium hover:underline">
                Continue Shopping
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($bundles as $bundle)
            <div class="bg-white border border-slate-200 overflow-hidden group">
                {{-- Bundle Image --}}
                <div class="relative">
                    @if($bundle->image)
                    <img src="{{ Storage::url($bundle->image) }}" alt="{{ $bundle->name }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-slate-100 flex items-center justify-center">
                        <div class="flex -space-x-4">
                            @foreach($bundle->items->take(3) as $item)
                            <div class="w-16 h-16 bg-white border border-slate-200 rounded-full overflow-hidden">
                                @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" alt="" class="w-full h-full object-cover">
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    {{-- Discount Badge --}}
                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1">
                        <span class="text-[12px] font-bold">SAVE {{ $bundle->savings_percentage }}%</span>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-lg font-serif text-slate-900 mb-2">{{ $bundle->name }}</h3>
                    
                    @if($bundle->description)
                    <p class="text-[13px] text-slate-500 mb-4 line-clamp-2">{{ $bundle->description }}</p>
                    @endif

                    {{-- Products Preview --}}
                    <div class="mb-4">
                        <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Includes {{ $bundle->items->count() }} Products</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($bundle->items as $item)
                            <div class="flex items-center gap-2 bg-slate-50 px-2 py-1">
                                @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" alt="" class="w-6 h-6 object-cover">
                                @endif
                                <span class="text-[11px] text-slate-600">{{ Str::limit($item->product->name, 15) }}</span>
                                @if($item->quantity > 1)
                                <span class="text-[10px] text-slate-400">x{{ $item->quantity }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Pricing --}}
                    <div class="flex items-baseline gap-3 mb-4">
                        <span class="text-2xl font-bold text-slate-900">₹{{ number_format($bundle->bundle_price) }}</span>
                        <span class="text-lg text-slate-400 line-through">₹{{ number_format($bundle->original_price) }}</span>
                    </div>

                    <p class="text-[12px] text-emerald-600 font-medium mb-4">You save ₹{{ number_format($bundle->savings) }}</p>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <a href="{{ route('bundles.show', $bundle) }}" class="flex-1 h-11 border border-slate-900 text-slate-900 text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-900 hover:text-white transition-colors flex items-center justify-center">
                            View Details
                        </a>
                        <form method="POST" action="{{ route('bundles.add-to-cart', $bundle) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full h-11 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
