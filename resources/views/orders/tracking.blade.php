@extends('layouts.shop')

@section('title', 'Track Order #' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-6 md:px-12 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('orders') }}" class="hover:text-slate-900 transition-colors">Orders</a>
        <span>/</span>
        <span class="text-slate-900">Track Order</span>
    </nav>

    <h1 class="text-3xl font-serif tracking-wide text-slate-900 mb-2">Track Your Order</h1>
    <p class="text-slate-500 text-sm mb-10">Order #{{ $order->order_number }}</p>

    {{-- Order Status Card --}}
    <div class="bg-slate-50 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mb-1">Current Status</p>
                <p class="text-xl font-semibold text-slate-900 capitalize">{{ str_replace('_', ' ', $order->status) }}</p>
            </div>
            @if($order->tracking_number)
            <div class="text-right">
                <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mb-1">Tracking Number</p>
                <p class="text-sm font-mono text-slate-900">{{ $order->tracking_number }}</p>
                @if($order->carrier)
                <p class="text-xs text-slate-500">via {{ $order->carrier }}</p>
                @endif
                @if($order->getTrackingUrl())
                <a href="{{ $order->getTrackingUrl() }}" target="_blank" class="text-xs text-blue-600 hover:underline">Track on carrier website →</a>
                @endif
            </div>
            @endif
        </div>
        @if($order->estimated_delivery)
        <div class="mt-4 pt-4 border-t border-slate-200">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mb-1">Estimated Delivery</p>
            <p class="text-sm text-slate-900">{{ $order->estimated_delivery->format('l, F j, Y') }}</p>
        </div>
        @endif
    </div>

    {{-- Tracking Timeline --}}
    <div class="mb-10">
        <h2 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-6">Tracking History</h2>
        
        @if($order->trackingEvents->count() > 0)
        <div class="relative">
            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-slate-200"></div>
            
            @foreach($order->trackingEvents as $index => $event)
            <div class="relative flex gap-6 pb-8 last:pb-0">
                <div class="relative z-10 w-8 h-8 rounded-full flex items-center justify-center {{ $index === 0 ? 'bg-slate-900 text-white' : 'bg-white border-2 border-slate-200 text-slate-400' }}">
                    @php
                        $icon = $event->getStatusIcon();
                    @endphp
                    @if($icon === 'check-circle')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($icon === 'truck')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    @elseif($icon === 'cube')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>
                <div class="flex-1 pt-1">
                    <p class="font-medium text-slate-900">{{ $event->getStatusLabel() }}</p>
                    @if($event->description)
                    <p class="text-sm text-slate-600 mt-1">{{ $event->description }}</p>
                    @endif
                    @if($event->location)
                    <p class="text-xs text-slate-500 mt-1">{{ $event->location }}</p>
                    @endif
                    <p class="text-xs text-slate-400 mt-2">{{ $event->event_time->format('M j, Y \a\t g:i A') }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-10 bg-slate-50">
            <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-slate-500 text-sm">No tracking updates yet. Check back soon!</p>
        </div>
        @endif
    </div>

    {{-- Order Items --}}
    <div class="border-t border-slate-100 pt-8">
        <h2 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-6">Order Items</h2>
        <div class="space-y-4">
            @foreach($order->items as $item)
            <div class="flex gap-4">
                <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/80' }}" alt="{{ $item->product_name }}" class="w-20 h-24 object-cover bg-slate-100">
                <div class="flex-1">
                    <h3 class="font-medium text-slate-900">{{ $item->product_name }}</h3>
                    <p class="text-sm text-slate-500">Qty: {{ $item->quantity }}</p>
                    <p class="text-sm font-medium text-slate-900 mt-1">Rs. {{ number_format($item->price * $item->quantity, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Shipping Address --}}
    <div class="border-t border-slate-100 pt-8 mt-8">
        <h2 class="text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-4">Shipping Address</h2>
        <p class="text-sm text-slate-600">
            {{ $order->shipping_name }}<br>
            {{ $order->shipping_address }}<br>
            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
            {{ $order->shipping_phone }}
        </p>
    </div>

    <div class="mt-10">
        <a href="{{ route('orders') }}" class="text-[11px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors">
            ← Back to Orders
        </a>
    </div>
</div>
@endsection
