@extends('layouts.shop')

@section('title', 'Flash Sales')

@section('content')
<div class="min-h-screen bg-slate-50 py-12 pt-24">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-serif tracking-wide text-slate-900">⚡ Flash Sales</h1>
            <p class="text-slate-500 mt-2">Limited time deals - Don't miss out!</p>
        </div>

        {{-- Live Sales --}}
        @if($liveSales->isNotEmpty())
        <div class="mb-16">
            <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase text-red-600 mb-6 flex items-center gap-2">
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                Live Now
            </h2>

            @foreach($liveSales as $sale)
            <div class="bg-white border border-slate-200 mb-8">
                {{-- Sale Header --}}
                <div class="bg-gradient-to-r from-red-600 to-orange-500 text-white p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-2xl font-serif">{{ $sale->name }}</h3>
                            @if($sale->description)
                            <p class="text-white/80 text-sm mt-1">{{ $sale->description }}</p>
                            @endif
                        </div>
                        <div class="text-center" x-data="countdown({{ $sale->time_remaining }})" x-init="startCountdown()">
                            <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-white/70 mb-2">Ends In</p>
                            <div class="flex items-center gap-2">
                                <div class="bg-white/20 px-3 py-2 min-w-[50px]">
                                    <span class="text-2xl font-bold" x-text="hours.toString().padStart(2, '0')">00</span>
                                    <p class="text-[9px] uppercase">Hours</p>
                                </div>
                                <span class="text-2xl">:</span>
                                <div class="bg-white/20 px-3 py-2 min-w-[50px]">
                                    <span class="text-2xl font-bold" x-text="minutes.toString().padStart(2, '0')">00</span>
                                    <p class="text-[9px] uppercase">Mins</p>
                                </div>
                                <span class="text-2xl">:</span>
                                <div class="bg-white/20 px-3 py-2 min-w-[50px]">
                                    <span class="text-2xl font-bold" x-text="seconds.toString().padStart(2, '0')">00</span>
                                    <p class="text-[9px] uppercase">Secs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Products Grid --}}
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($sale->products as $product)
                        @php
                            $flashProduct = $sale->flashSaleProducts->where('product_id', $product->id)->first();
                            $discount = $flashProduct ? round((($product->price - $flashProduct->sale_price) / $product->price) * 100) : 0;
                        @endphp
                        <a href="{{ route('shop.show', $product) }}" class="group bg-slate-50 border border-slate-100 hover:border-slate-300 transition-all">
                            <div class="relative aspect-square overflow-hidden">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @if($discount > 0)
                                <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 text-[11px] font-bold">
                                    -{{ $discount }}%
                                </div>
                                @endif
                                @if($flashProduct && $flashProduct->quantity_limit)
                                <div class="absolute bottom-0 left-0 right-0 bg-black/70 px-2 py-1">
                                    <div class="flex items-center justify-between text-[10px] text-white mb-1">
                                        <span>{{ $flashProduct->quantity_sold }}/{{ $flashProduct->quantity_limit }} sold</span>
                                        <span>{{ $flashProduct->sold_percentage }}%</span>
                                    </div>
                                    <div class="h-1 bg-white/30 rounded-full overflow-hidden">
                                        <div class="h-full bg-red-500 rounded-full" style="width: {{ $flashProduct->sold_percentage }}%"></div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <h4 class="text-[12px] font-medium text-slate-900 truncate">{{ $product->name }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-red-600 font-bold">₹{{ number_format($flashProduct->sale_price ?? $product->price) }}</span>
                                    @if($flashProduct && $flashProduct->sale_price < $product->price)
                                    <span class="text-slate-400 text-[11px] line-through">₹{{ number_format($product->price) }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <div class="px-6 pb-6">
                    <a href="{{ route('flash-sales.show', $sale) }}" class="block w-full h-11 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center justify-center hover:bg-slate-800 transition-colors">
                        View All {{ $sale->products->count() }} Products
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Upcoming Sales --}}
        @if($upcomingSales->isNotEmpty())
        <div>
            <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-500 mb-6">Coming Soon</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($upcomingSales as $sale)
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-lg font-serif text-slate-900">{{ $sale->name }}</h3>
                    <p class="text-slate-500 text-sm mt-1">{{ $sale->description }}</p>
                    <div class="mt-4 flex items-center gap-2 text-[12px] text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Starts {{ $sale->starts_at->format('M d, Y \a\t h:i A') }}
                    </div>
                    <div class="mt-4" x-data="countdown({{ $sale->time_until_start }})" x-init="startCountdown()">
                        <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Starts In</p>
                        <div class="flex items-center gap-2 text-slate-900">
                            <span x-text="days + 'd'">0d</span>
                            <span x-text="hours + 'h'">0h</span>
                            <span x-text="minutes + 'm'">0m</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- No Sales --}}
        @if($liveSales->isEmpty() && $upcomingSales->isEmpty())
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <h3 class="text-xl font-serif text-slate-900 mb-2">No Flash Sales Right Now</h3>
            <p class="text-slate-500">Check back soon for amazing deals!</p>
            <a href="{{ route('shop.index') }}" class="inline-block mt-6 h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center justify-center hover:bg-slate-800 transition-colors">
                Browse Shop
            </a>
        </div>
        @endif
    </div>
</div>

<script>
function countdown(totalSeconds) {
    return {
        totalSeconds: totalSeconds,
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        interval: null,
        
        startCountdown() {
            this.updateDisplay();
            this.interval = setInterval(() => {
                if (this.totalSeconds > 0) {
                    this.totalSeconds--;
                    this.updateDisplay();
                } else {
                    clearInterval(this.interval);
                    location.reload();
                }
            }, 1000);
        },
        
        updateDisplay() {
            this.days = Math.floor(this.totalSeconds / 86400);
            this.hours = Math.floor((this.totalSeconds % 86400) / 3600);
            this.minutes = Math.floor((this.totalSeconds % 3600) / 60);
            this.seconds = this.totalSeconds % 60;
        }
    }
}
</script>
@endsection
