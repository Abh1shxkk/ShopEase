@extends('layouts.shop')

@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-8">
        <a href="/" class="hover:text-slate-900">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('cart') }}" class="hover:text-slate-900">Cart</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-900 font-medium">Checkout</span>
    </nav>

    <h1 class="text-3xl font-bold text-slate-900 mb-8">Checkout</h1>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Shipping Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-6">Shipping Information</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                            <input type="email" name="shipping_email" value="{{ old('shipping_email', auth()->user()->email) }}" required class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                            <input type="tel" name="shipping_phone" value="{{ old('shipping_phone') }}" required class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Address</label>
                            <textarea name="shipping_address" required rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('shipping_address') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">City</label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">State</label>
                            <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" required class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">ZIP Code</label>
                            <input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}" required class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-slate-900 mb-6">Payment Method</h2>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="radio" name="payment_method" value="cod" checked class="w-5 h-5 text-blue-600">
                            <span class="ml-3 font-medium text-slate-900">Cash on Delivery</span>
                        </label>
                        <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="radio" name="payment_method" value="card" class="w-5 h-5 text-blue-600">
                            <span class="ml-3 font-medium text-slate-900">Credit/Debit Card (Demo)</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Order Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-28">
                    <h2 class="text-lg font-semibold text-slate-900 mb-6">Order Summary</h2>
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                        <div class="flex gap-3">
                            <div class="w-16 h-16 bg-gray-50 rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $item->product->name }}</p>
                                <p class="text-sm text-slate-500">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-medium text-slate-900">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-100 pt-4 space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-slate-600">Subtotal</span><span class="font-medium">${{ number_format($subtotal, 2) }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-600">Shipping</span><span class="font-medium {{ $shipping == 0 ? 'text-green-600' : '' }}">{{ $shipping == 0 ? 'FREE' : '$' . number_format($shipping, 2) }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-600">Tax</span><span class="font-medium">${{ number_format($tax, 2) }}</span></div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between">
                            <span class="font-semibold text-slate-900">Total</span>
                            <span class="text-xl font-bold text-slate-900">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                    <button type="submit" class="mt-6 w-full h-12 bg-blue-600 text-white font-semibold rounded-xl flex items-center justify-center hover:bg-blue-700 transition-colors">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
