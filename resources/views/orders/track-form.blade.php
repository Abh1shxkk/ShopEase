@extends('layouts.shop')

@section('title', 'Track Your Order')

@section('content')
<div class="max-w-lg mx-auto px-6 md:px-12 py-20">
    <div class="text-center mb-10">
        <svg class="w-16 h-16 mx-auto text-slate-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        <h1 class="text-3xl font-serif tracking-wide text-slate-900 mb-3">Track Your Order</h1>
        <p class="text-slate-500 text-sm">Enter your order number and email to track your shipment.</p>
    </div>

    <form action="{{ route('orders.track') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="text-[11px] font-bold tracking-widest uppercase text-slate-500 block mb-2">Order Number</label>
            <input type="text" name="order_number" value="{{ old('order_number') }}" required placeholder="e.g., ORD-ABC123" class="w-full h-12 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors @error('order_number') border-red-500 @enderror">
            @error('order_number')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-[11px] font-bold tracking-widest uppercase text-slate-500 block mb-2">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email used for order" class="w-full h-12 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors @error('email') border-red-500 @enderror">
            @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full h-14 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
            Track Order
        </button>
    </form>

    @auth
    <div class="mt-8 text-center">
        <p class="text-sm text-slate-500">Or <a href="{{ route('orders') }}" class="text-slate-900 hover:underline">view all your orders</a></p>
    </div>
    @else
    <div class="mt-8 text-center">
        <p class="text-sm text-slate-500">Have an account? <a href="{{ route('login') }}" class="text-slate-900 hover:underline">Sign in</a> to view all your orders.</p>
    </div>
    @endauth
</div>
@endsection
