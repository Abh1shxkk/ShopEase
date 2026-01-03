@extends('layouts.shop')

@section('title', 'My Orders')

@section('content')
<div class="max-w-[1200px] mx-auto px-6 md:px-12 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-12">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('profile') }}" class="hover:text-slate-900 transition-colors">My Account</a>
        <span>/</span>
        <span class="text-slate-900">Orders</span>
    </nav>

    <h1 class="text-3xl font-serif tracking-wide mb-12">My Orders</h1>

    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <a href="{{ route('orders.show', $order) }}" class="block bg-slate-50 p-6 hover:bg-slate-100 transition-colors group">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div>
                    <p class="text-[11px] tracking-widest uppercase text-slate-400 mb-1">Order Number</p>
                    <p class="font-medium text-slate-900">{{ $order->order_number }}</p>
                    <p class="text-[12px] text-slate-500 mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    @php
                    $statusStyles = [
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'shipped' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                    ];
                    @endphp
                    <span class="px-3 py-1.5 text-[10px] font-bold tracking-widest uppercase border {{ $statusStyles[$order->status] ?? 'bg-slate-50 text-slate-700 border-slate-200' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="text-lg font-semibold text-slate-900">Rs. {{ number_format($order->total, 2) }}</span>
                </div>
            </div>
            
            {{-- Order Items Preview --}}
            <div class="flex items-center gap-4 pt-4 border-t border-slate-200">
                <div class="flex -space-x-3">
                    @foreach($order->items->take(3) as $item)
                    <div class="w-12 h-12 bg-white border border-slate-200 overflow-hidden">
                        @if($item->product)
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=100&h=100&fit=crop'">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-slate-100">
                            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @if($order->items->count() > 3)
                    <div class="w-12 h-12 bg-slate-900 flex items-center justify-center text-white text-[11px] font-bold">
                        +{{ $order->items->count() - 3 }}
                    </div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-[12px] text-slate-500">
                        {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                        <span class="mx-2">•</span>
                        {{ ucfirst($order->payment_method) }}
                    </p>
                </div>
                <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-900 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-12">
        {{ $orders->links('vendor.pagination.luxury') }}
    </div>
    @else
    <div class="text-center py-24 bg-slate-50">
        <div class="w-20 h-20 bg-white border border-slate-200 flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <h3 class="text-xl font-serif tracking-wide text-slate-900 mb-3">No orders yet</h3>
        <p class="text-[13px] text-slate-500 mb-8">Start shopping to see your orders here.</p>
        <a href="{{ route('shop.index') }}" class="inline-flex h-12 px-10 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase items-center justify-center hover:bg-slate-800 transition-colors">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection
