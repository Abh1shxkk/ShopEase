@extends('layouts.shop')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-8">
        <a href="/" class="hover:text-slate-900">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('orders') }}" class="hover:text-slate-900">My Orders</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-900 font-medium">{{ $order->order_number }}</span>
    </nav>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Order {{ $order->order_number }}</h1>
            <p class="text-slate-500">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>
        @php
        $statusColors = [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'processing' => 'bg-blue-100 text-blue-700',
            'shipped' => 'bg-purple-100 text-purple-700',
            'delivered' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
        ];
        @endphp
        <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Shipping Address</h3>
            <p class="text-slate-600">
                {{ $order->shipping_name }}<br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                {{ $order->shipping_phone }}<br>
                {{ $order->shipping_email }}
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Payment Information</h3>
            <p class="text-slate-600">
                <span class="font-medium">Method:</span> {{ ucfirst($order->payment_method) }}<br>
                <span class="font-medium">Status:</span> 
                <span class="{{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ ucfirst($order->payment_status) }}</span>
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-8">
        <h3 class="font-semibold text-slate-900 mb-6">Order Items</h3>
        <div class="space-y-4">
            @foreach($order->items as $item)
            <div class="flex items-center gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                <div class="w-20 h-20 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0">
                    @if($item->product && $item->product->image)
                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-medium text-slate-900">{{ $item->product_name }}</p>
                    <p class="text-sm text-slate-500">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                </div>
                <p class="font-semibold text-slate-900">${{ number_format($item->total, 2) }}</p>
            </div>
            @endforeach
        </div>

        <div class="border-t border-gray-100 mt-6 pt-6 space-y-2">
            <div class="flex justify-between text-sm"><span class="text-slate-600">Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-slate-600">Shipping</span><span>{{ $order->shipping == 0 ? 'FREE' : '$' . number_format($order->shipping, 2) }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-slate-600">Tax</span><span>${{ number_format($order->tax, 2) }}</span></div>
            <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-100"><span>Total</span><span>${{ number_format($order->total, 2) }}</span></div>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('orders') }}" class="h-12 px-6 bg-white text-slate-700 font-medium rounded-xl flex items-center justify-center border border-gray-200 hover:bg-gray-50 transition-colors">
            ← Back to Orders
        </a>
        <a href="{{ route('shop.index') }}" class="h-12 px-6 bg-blue-600 text-white font-semibold rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
