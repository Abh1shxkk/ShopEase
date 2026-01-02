@extends('layouts.shop')

@section('title', 'Order Confirmed')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Order Confirmed!</h1>
        <p class="text-slate-600 mb-8">Thank you for your purchase. Your order has been placed successfully.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
            <div>
                <p class="text-sm text-slate-500">Order Number</p>
                <p class="text-lg font-bold text-slate-900">{{ $order->order_number }}</p>
            </div>
            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-full">{{ ucfirst($order->status) }}</span>
        </div>

        <div class="space-y-4 mb-6">
            @foreach($order->items as $item)
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gray-50 rounded-lg overflow-hidden">
                    @if($item->product && $item->product->image)
                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-medium text-slate-900">{{ $item->product_name }}</p>
                    <p class="text-sm text-slate-500">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                </div>
                <p class="font-medium text-slate-900">${{ number_format($item->total, 2) }}</p>
            </div>
            @endforeach
        </div>

        <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-slate-600">Subtotal</span><span>${{ number_format($order->subtotal, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-600">Shipping</span><span>{{ $order->shipping == 0 ? 'FREE' : '$' . number_format($order->shipping, 2) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-600">Tax</span><span>${{ number_format($order->tax, 2) }}</span></div>
            <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-100"><span>Total</span><span>${{ number_format($order->total, 2) }}</span></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-8">
        <h3 class="font-semibold text-slate-900 mb-4">Shipping Address</h3>
        <p class="text-slate-600">
            {{ $order->shipping_name }}<br>
            {{ $order->shipping_address }}<br>
            {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
            {{ $order->shipping_phone }}
        </p>
    </div>

    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('orders') }}" class="flex-1 h-12 bg-blue-600 text-white font-semibold rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors">
            View My Orders
        </a>
        <a href="{{ route('shop.index') }}" class="flex-1 h-12 bg-white text-slate-700 font-medium rounded-xl flex items-center justify-center border border-gray-200 hover:bg-gray-50 transition-colors">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
