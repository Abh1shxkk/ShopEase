@extends('layouts.shop')

@section('title', $flashSale->name)

@section('content')
<div class="min-h-screen bg-slate-50 py-12 pt-24">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-slate-900">Home</a>
            <span>/</span>
            <a href="{{ route('flash-sales.index') }}" class="hover:text-slate-900">Flash Sales</a>
            <span>/</span>
            <span class="text-slate-900">{{ $flashSale->name }}</span>
        </nav>

        {{-- Sale Header --}}
        <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white p-8 mb-8" x-data="countdown({{ $flashSale->time_remaining }})" x-init="startCountdown()">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-bold tracking-[0.2em] uppercase">Live Now</span>
                    </div>
                    <h1 class="text-3xl font-serif">{{ $flashSale->name }}</h1>
                    @if($flashSale->description)
                    <p class="text-white/80 mt-2">{{ $flashSale->description }}</p>
                    @endif
                </div>
                <div class="text-center lg:text-right">
                    <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-white/70 mb-3">Sale Ends In</p>
                    <div class="flex items-center gap-3">
                        @foreach(['hours', 'minutes', 'seconds'] as $unit)
                        <div class="bg-white/20 backdrop-blur px-4 py-3 min-w-[70px]">
                            <span class="text-3xl font-bold" x-text="{{ $unit }}.toString().padStart(2, '0')">00</span>
                            <p class="text-[10px] uppercase mt-1">{{ ucfirst($unit) }}</p>
                        </div>
                        @if($unit !== 'seconds')<span class="text-3xl font-bold">:</span>@endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Products Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($flashSale->products as $product)
            @php
                $flashProduct = $flashSale->flashSaleProducts->where('product_id', $product->id)->first();
                $discount = $flashProduct ? round((($product->price - $flashProduct->sale_price) / $product->price) * 100) : 0;
            @endphp
            <a href="{{ route('shop.show', $product) }}" class="group bg-white border border-slate-200 hover:shadow-lg transition-all">
                <div class="relative aspect-square overflow-hidden">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @if($discount > 0)
                    <div class="absolute top-3 left-3 bg-red-600 text-white px-3 py-1 text-[12px] font-bold">
                        -{{ $discount }}% OFF
                    </div>
                    @endif
                    @if($flashProduct && $flashProduct->quantity_limit)
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3">
                        <div class="flex items-center justify-between text-[11px] text-white mb-1">
                            <span>{{ $flashProduct->quantity_sold }} sold</span>
                            <span>{{ $flashProduct->remaining_quantity ?? 'Unlimited' }} left</span>
                        </div>
                        <div class="h-1.5 bg-white/30 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-red-500 to-orange-500 rounded-full transition-all" style="width: {{ $flashProduct->sold_percentage }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">{{ $product->category->name ?? 'Product' }}</p>
                    <h3 class="text-[13px] font-medium text-slate-900 mt-1 line-clamp-2">{{ $product->name }}</h3>
                    <div class="flex items-center gap-2 mt-3">
                        <span class="text-lg font-bold text-red-600">₹{{ number_format($flashProduct->sale_price ?? $product->price) }}</span>
                        @if($flashProduct && $flashProduct->sale_price < $product->price)
                        <span class="text-slate-400 text-[12px] line-through">₹{{ number_format($product->price) }}</span>
                        @endif
                    </div>
                    @if($flashProduct && $flashProduct->per_user_limit)
                    <p class="text-[10px] text-amber-600 mt-2">Limit {{ $flashProduct->per_user_limit }} per customer</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<script>
function countdown(totalSeconds) {
    return {
        totalSeconds: totalSeconds,
        hours: 0, minutes: 0, seconds: 0,
        startCountdown() {
            this.updateDisplay();
            setInterval(() => {
                if (this.totalSeconds > 0) { this.totalSeconds--; this.updateDisplay(); }
                else { location.reload(); }
            }, 1000);
        },
        updateDisplay() {
            this.hours = Math.floor(this.totalSeconds / 3600);
            this.minutes = Math.floor((this.totalSeconds % 3600) / 60);
            this.seconds = this.totalSeconds % 60;
        }
    }
}
</script>
@endsection
