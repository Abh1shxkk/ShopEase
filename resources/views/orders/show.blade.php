@extends('layouts.shop')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="max-w-[1200px] mx-auto px-6 md:px-12 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-12">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('profile') }}" class="hover:text-slate-900 transition-colors">My Account</a>
        <span>/</span>
        <a href="{{ route('orders') }}" class="hover:text-slate-900 transition-colors">Orders</a>
        <span>/</span>
        <span class="text-slate-900">{{ $order->order_number }}</span>
    </nav>

    {{-- Order Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-12">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900 mb-2">Order {{ $order->order_number }}</h1>
            <p class="text-[12px] text-slate-500">Placed on {{ $order->created_at->format('d F Y \a\t h:i A') }}</p>
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
        <span class="px-4 py-2 text-[11px] font-bold tracking-widest uppercase border {{ $statusStyles[$order->status] ?? 'bg-slate-50 text-slate-700 border-slate-200' }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    {{-- Order Progress --}}
    @if($order->status !== 'cancelled')
    <div class="bg-slate-50 p-6 mb-8">
        <div class="flex items-center justify-between relative">
            @php
            $steps = ['pending', 'processing', 'shipped', 'delivered'];
            $currentIndex = array_search($order->status, $steps);
            @endphp
            
            {{-- Progress Line --}}
            <div class="absolute top-5 left-0 right-0 h-0.5 bg-slate-200">
                <div class="h-full bg-slate-900 transition-all" style="width: {{ ($currentIndex / 3) * 100 }}%"></div>
            </div>
            
            @foreach($steps as $index => $step)
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 flex items-center justify-center {{ $index <= $currentIndex ? 'bg-slate-900 text-white' : 'bg-white border-2 border-slate-200 text-slate-400' }}">
                    @if($index < $currentIndex)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @elseif($index == $currentIndex)
                    <div class="w-2 h-2 bg-white rounded-full"></div>
                    @else
                    <span class="text-[11px] font-bold">{{ $index + 1 }}</span>
                    @endif
                </div>
                <span class="text-[10px] tracking-widest uppercase mt-2 {{ $index <= $currentIndex ? 'text-slate-900 font-bold' : 'text-slate-400' }}">{{ ucfirst($step) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Order Items --}}
        <div class="lg:col-span-2">
            <div class="bg-slate-50 p-6 mb-6">
                <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-6">Order Items</h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4 pb-4 border-b border-slate-200 last:border-0 last:pb-0">
                        <div class="w-20 h-20 bg-white overflow-hidden flex-shrink-0">
                            @if($item->product)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1560393464-5c69a73c5770?w=100&h=100&fit=crop'">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-100">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <p class="font-medium text-slate-900 text-[14px]">{{ $item->product_name }}</p>
                                <p class="text-[12px] text-slate-500 mt-1">Qty: {{ $item->quantity }} × Rs. {{ number_format($item->price, 2) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-slate-900">Rs. {{ number_format($item->total, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Shipping & Payment Info --}}
            <div class="grid sm:grid-cols-2 gap-6">
                <div class="bg-slate-50 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-4">Shipping Address</h3>
                    <div class="text-[13px] text-slate-600 space-y-1">
                        <p class="font-medium text-slate-900">{{ $order->shipping_name }}</p>
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                        <p class="pt-2">{{ $order->shipping_phone }}</p>
                        <p>{{ $order->shipping_email }}</p>
                    </div>
                </div>
                <div class="bg-slate-50 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-4">Payment Information</h3>
                    <div class="text-[13px] text-slate-600 space-y-2">
                        <div class="flex justify-between">
                            <span>Method</span>
                            <span class="font-medium text-slate-900">{{ ucfirst($order->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status</span>
                            <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-slate-50 p-6 sticky top-28">
                <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-6">Order Summary</h2>
                <div class="space-y-3 text-[13px]">
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
                        <span class="font-semibold text-slate-900">Total</span>
                        <span class="text-lg font-bold text-slate-900">Rs. {{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-8 space-y-3">
                    <a href="{{ route('orders') }}" class="w-full h-12 bg-white text-slate-900 text-[11px] font-bold tracking-[0.2em] uppercase flex items-center justify-center border border-slate-200 hover:bg-slate-100 transition-colors">
                        ← Back to Orders
                    </a>
                    <a href="{{ route('shop.index') }}" class="w-full h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase flex items-center justify-center hover:bg-slate-800 transition-colors">
                        Continue Shopping
                    </a>
                </div>

                {{-- Need Help --}}
                <div class="mt-8 pt-6 border-t border-slate-200">
                    <p class="text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Need Help?</p>
                    <p class="text-[12px] text-slate-500">Contact our support team for any questions about your order.</p>
                    <a href="mailto:support@shopease.com" class="text-[12px] text-slate-900 font-medium hover:underline mt-2 inline-block">support@shopease.com</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
