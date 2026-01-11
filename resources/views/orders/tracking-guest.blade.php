@extends('layouts.shop')

@section('title', 'Track Order #' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-6 md:px-12 py-12">
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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
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
            <p class="text-slate-500 text-sm">No tracking updates yet. Check back soon!</p>
        </div>
        @endif
    </div>

    <div class="mt-10">
        <a href="{{ route('orders.track.form') }}" class="text-[11px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors">
            ← Track Another Order
        </a>
    </div>
</div>
@endsection
