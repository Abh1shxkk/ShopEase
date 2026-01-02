@extends('layouts.shop')

@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-8">
        <a href="/" class="hover:text-slate-900">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-900 font-medium">Shopping Cart</span>
    </nav>

    <h1 class="text-3xl font-bold text-slate-900 mb-8">Shopping Cart</h1>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">{{ session('error') }}</div>
    @endif

    @if($cartItems->count() > 0)
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
            <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-6 flex flex-col sm:flex-row gap-4">
                <div class="w-full sm:w-28 h-28 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0">
                    @if($item->product->image)
                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <a href="{{ route('shop.show', $item->product) }}" class="font-semibold text-slate-900 hover:text-blue-600 transition-colors">{{ $item->product->name }}</a>
                        <p class="text-sm text-slate-500 mt-1">{{ $item->product->category }}</p>
                        <p class="text-lg font-bold text-slate-900 mt-2">${{ number_format($item->product->discount_price ?? $item->product->price, 2) }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                            @csrf
                            @method('PATCH')
                            <select name="quantity" onchange="this.form.submit()" class="h-10 px-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @for($i = 1; $i <= min(10, $item->product->stock); $i++)
                                <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </form>
                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-28">
                <h2 class="text-lg font-semibold text-slate-900 mb-6">Order Summary</h2>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Subtotal ({{ $cartItems->count() }} items)</span>
                        <span class="font-medium text-slate-900">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Shipping</span>
                        <span class="font-medium {{ $shipping == 0 ? 'text-green-600' : 'text-slate-900' }}">{{ $shipping == 0 ? 'FREE' : '$' . number_format($shipping, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Tax (8%)</span>
                        <span class="font-medium text-slate-900">${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="border-t border-gray-100 pt-4 flex justify-between">
                        <span class="font-semibold text-slate-900">Total</span>
                        <span class="text-xl font-bold text-slate-900">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
                @if($subtotal < 50)
                <p class="text-sm text-slate-500 mt-4 p-3 bg-blue-50 rounded-lg">Add ${{ number_format(50 - $subtotal, 2) }} more for free shipping!</p>
                @endif
                <a href="{{ route('checkout') }}" class="mt-6 w-full h-12 bg-blue-600 text-white font-semibold rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors">
                    Proceed to Checkout
                </a>
                <a href="{{ route('shop.index') }}" class="mt-3 w-full h-12 bg-white text-slate-700 font-medium rounded-xl flex items-center justify-center border border-gray-200 hover:bg-gray-50 transition-colors">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <h3 class="text-xl font-semibold text-slate-900 mb-2">Your cart is empty</h3>
        <p class="text-slate-500 mb-6">Looks like you haven't added anything to your cart yet.</p>
        <a href="{{ route('shop.index') }}" class="inline-flex h-12 px-8 bg-blue-600 text-white font-semibold rounded-xl items-center justify-center hover:bg-blue-700 transition-colors">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection
