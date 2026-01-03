@extends('layouts.shop')

@section('title', 'Order Confirmed')

@section('content')
<div class="max-w-[800px] mx-auto px-6 md:px-12 py-16">
    {{-- Success Header --}}
    <div class="text-center mb-12">
        <div class="w-20 h-20 bg-emerald-50 border border-emerald-200 flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-3xl font-serif tracking-wide text-slate-900 mb-3">Order Confirmed</h1>
        <p class="text-[13px] text-slate-500">Thank you for your purchase. Your order has been placed successfully.</p>
    </div>

    {{-- Order Details Card --}}
    <div class="bg-slate-50 p-8 mb-8">
        {{-- Order Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-200">
            <div>
                <p class="text-[10px] tracking-widest uppercase text-slate-400 mb-1">Order Number</p>
                <p class="text-lg font-semibold text-slate-900">{{ $order->order_number }}</p>
            </div>
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
        </div>

        {{-- Order Items --}}
        <div class="space-y-4 mb-8">
            @foreach($order->items as $item)
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white overflow-hidden flex-shrink-0">
                    @if($item->product)
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=100&h=100&fit=crop'">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-slate-100">
                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-[13px] font-medium text-slate-900">{{ $item->product_name }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Qty: {{ $item->quantity }} × Rs. {{ number_format($item->price, 2) }}</p>
                </div>
                <p class="text-[13px] font-semibold text-slate-900">Rs. {{ number_format($item->total, 2) }}</p>
            </div>
            @endforeach
        </div>

        {{-- Order Totals --}}
        <div class="border-t border-slate-200 pt-6 space-y-2 text-[13px]">
            <div class="flex justify-between">
                <span class="text-slate-500">Subtotal</span>
                <span class="font-medium text-slate-900">Rs. {{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Shipping</span>
                <span class="font-medium {{ $order->shipping == 0 ? 'text-emerald-600' : 'text-slate-900' }}">
                    {{ $order->shipping == 0 ? 'FREE' : 'Rs. ' . number_format($order->shipping, 2) }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Tax</span>
                <span class="font-medium text-slate-900">Rs. {{ number_format($order->tax, 2) }}</span>
            </div>
            <div class="border-t border-slate-200 pt-3 flex justify-between">
                <span class="font-semibold text-slate-900">Total Paid</span>
                <span class="text-lg font-bold text-slate-900">Rs. {{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Shipping Address --}}
    <div class="bg-slate-50 p-8 mb-8">
        <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-4">Shipping Address</h3>
        <div class="text-[13px] text-slate-600 space-y-1">
            <p class="font-medium text-slate-900">{{ $order->shipping_name }}</p>
            <p>{{ $order->shipping_address }}</p>
            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
            <p class="pt-2">{{ $order->shipping_phone }}</p>
            <p>{{ $order->shipping_email }}</p>
        </div>
    </div>

    {{-- What's Next --}}
    <div class="bg-slate-900 text-white p-8 mb-8">
        <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-4">What's Next?</h3>
        <div class="space-y-4 text-[13px]">
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 bg-white/10 flex items-center justify-center flex-shrink-0">
                    <span class="text-[11px] font-bold">1</span>
                </div>
                <div>
                    <p class="font-medium">Order Confirmation Email</p>
                    <p class="text-slate-400 text-[12px] mt-0.5">You'll receive an email with your order details shortly.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 bg-white/10 flex items-center justify-center flex-shrink-0">
                    <span class="text-[11px] font-bold">2</span>
                </div>
                <div>
                    <p class="font-medium">Order Processing</p>
                    <p class="text-slate-400 text-[12px] mt-0.5">We'll prepare your items for shipment within 1-2 business days.</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 bg-white/10 flex items-center justify-center flex-shrink-0">
                    <span class="text-[11px] font-bold">3</span>
                </div>
                <div>
                    <p class="font-medium">Shipping Updates</p>
                    <p class="text-slate-400 text-[12px] mt-0.5">Track your order from your account or via email updates.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('orders.show', $order) }}" class="flex-1 h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase flex items-center justify-center hover:bg-slate-800 transition-colors">
            View Order Details
        </a>
        <a href="{{ route('shop.index') }}" class="flex-1 h-12 bg-white text-slate-900 text-[11px] font-bold tracking-[0.2em] uppercase flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition-colors">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
