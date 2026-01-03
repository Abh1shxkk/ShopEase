@extends('layouts.shop')

@section('title', 'Checkout')

@section('content')
<div class="max-w-[1200px] mx-auto px-6 md:px-12 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-12">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('cart') }}" class="hover:text-slate-900 transition-colors">Cart</a>
        <span>/</span>
        <span class="text-slate-900">Checkout</span>
    </nav>

    <h1 class="text-3xl font-serif tracking-wide mb-12">Checkout</h1>

    @if($errors->any())
    <div class="mb-8 p-4 bg-red-50 border border-red-200">
        <ul class="text-[12px] text-red-700 space-y-1">
            @foreach($errors->all() as $error)
            <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid lg:grid-cols-3 gap-12">
            {{-- Shipping & Payment --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Shipping Information --}}
                <div class="bg-slate-50 p-8">
                    <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-8">Shipping Information</h2>
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Full Name</label>
                            <input type="text" name="shipping_name" value="{{ old('shipping_name', auth()->user()->name) }}" required 
                                class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Email</label>
                            <input type="email" name="shipping_email" value="{{ old('shipping_email', auth()->user()->email) }}" required 
                                class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Phone</label>
                            <input type="tel" name="shipping_phone" value="{{ old('shipping_phone', auth()->user()->phone) }}" required placeholder="+91 9876543210"
                                class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Address</label>
                            <textarea name="shipping_address" required rows="2" placeholder="House/Flat No., Building, Street, Area"
                                class="w-full px-4 py-3 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900 resize-none">{{ old('shipping_address') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">City</label>
                            <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" required 
                                class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">State</label>
                            <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" required 
                                class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">PIN Code</label>
                            <input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}" required 
                                class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-slate-50 p-8">
                    <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-8">Payment Method</h2>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 bg-white border border-slate-200 cursor-pointer hover:border-slate-900 transition-colors group">
                            <input type="radio" name="payment_method" value="cod" checked class="w-4 h-4 text-slate-900 border-slate-300 focus:ring-slate-900">
                            <div class="ml-4 flex-1">
                                <span class="text-[13px] font-medium text-slate-900">Cash on Delivery</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Pay when you receive your order</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-300 group-hover:text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </label>
                        <label class="flex items-center p-4 bg-white border border-slate-200 cursor-pointer hover:border-slate-900 transition-colors group">
                            <input type="radio" name="payment_method" value="upi" class="w-4 h-4 text-slate-900 border-slate-300 focus:ring-slate-900">
                            <div class="ml-4 flex-1">
                                <span class="text-[13px] font-medium text-slate-900">UPI Payment</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Pay using Google Pay, PhonePe, Paytm</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-300 group-hover:text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </label>
                        <label class="flex items-center p-4 bg-white border border-slate-200 cursor-pointer hover:border-slate-900 transition-colors group">
                            <input type="radio" name="payment_method" value="card" class="w-4 h-4 text-slate-900 border-slate-300 focus:ring-slate-900">
                            <div class="ml-4 flex-1">
                                <span class="text-[13px] font-medium text-slate-900">Credit/Debit Card</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Visa, Mastercard, RuPay accepted</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-300 group-hover:text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </label>
                    </div>
                </div>

                {{-- Order Notes --}}
                <div class="bg-slate-50 p-8">
                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Order Notes (Optional)</label>
                    <textarea name="notes" rows="3" placeholder="Any special instructions for delivery..."
                        class="w-full px-4 py-3 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900 resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-slate-50 p-8 sticky top-28">
                    <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-8">Order Summary</h2>
                    
                    {{-- Cart Items --}}
                    <div class="space-y-4 mb-8">
                        @foreach($cartItems as $item)
                        <div class="flex gap-4">
                            <div class="w-16 h-16 bg-white overflow-hidden flex-shrink-0">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=100&h=100&fit=crop'">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[12px] font-medium text-slate-900 truncate">{{ $item->product->name }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="text-[13px] font-semibold text-slate-900">Rs. {{ number_format($item->subtotal, 2) }}</p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="border-t border-slate-200 pt-6 space-y-3 text-[13px]">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-medium text-slate-900">Rs. {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Shipping</span>
                            <span class="font-medium {{ $shipping == 0 ? 'text-emerald-600' : 'text-slate-900' }}">
                                {{ $shipping == 0 ? 'FREE' : 'Rs. ' . number_format($shipping, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tax (8%)</span>
                            <span class="font-medium text-slate-900">Rs. {{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between">
                            <span class="font-semibold text-slate-900">Total</span>
                            <span class="text-lg font-bold text-slate-900">Rs. {{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    {{-- Place Order Button --}}
                    <button type="submit" class="mt-8 w-full h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase flex items-center justify-center hover:bg-slate-800 transition-colors">
                        Place Order
                    </button>

                    {{-- Back to Cart --}}
                    <a href="{{ route('cart') }}" class="mt-3 w-full h-12 bg-white text-slate-900 text-[11px] font-bold tracking-[0.2em] uppercase flex items-center justify-center border border-slate-200 hover:bg-slate-100 transition-colors">
                        ← Back to Cart
                    </a>

                    {{-- Security Badge --}}
                    <div class="mt-8 pt-6 border-t border-slate-200 text-center">
                        <div class="flex items-center justify-center gap-2 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span class="text-[10px] tracking-widest uppercase">Secure Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
