@extends('layouts.seller')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('seller.orders.index') }}" class="p-2 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-serif text-slate-900">Order #{{ $order->order_number }}</h1>
                <p class="text-[12px] text-slate-500 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
        </div>
        <span class="px-3 py-1.5 text-[11px] font-medium
            @if($order->status === 'delivered') bg-emerald-50 text-emerald-700
            @elseif($order->status === 'processing') bg-blue-50 text-blue-700
            @elseif($order->status === 'shipped') bg-purple-50 text-purple-700
            @elseif($order->status === 'cancelled') bg-red-50 text-red-700
            @else bg-amber-50 text-amber-700 @endif">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-serif text-slate-900">Your Products in This Order</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($sellerItems as $item)
                    <div class="p-6 flex items-center space-x-4">
                        <img src="{{ $item->product->image_url ?? '/images/placeholder.jpg' }}" alt="{{ $item->product->name ?? 'Product' }}" class="w-20 h-20 object-cover border border-slate-200">
                        <div class="flex-1">
                            <h3 class="text-[13px] font-medium text-slate-900">{{ $item->product->name ?? 'Product Deleted' }}</h3>
                            <p class="text-[12px] text-slate-500">Qty: {{ $item->quantity }}</p>
                            @if($item->variant)<p class="text-[12px] text-slate-500">{{ $item->variant->name }}</p>@endif
                        </div>
                        <div class="text-right">
                            <p class="text-[13px] font-medium text-slate-900">₹{{ number_format($item->price * $item->quantity, 2) }}</p>
                            <p class="text-[11px] text-slate-500">₹{{ number_format($item->price, 2) }} each</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white border border-slate-200 p-6">
                <h2 class="text-lg font-serif text-slate-900 mb-4">Shipping Address</h2>
                <div class="text-[13px] text-slate-600 space-y-1">
                    <p class="font-medium text-slate-900">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_pincode }}</p>
                    <p class="mt-3">Phone: {{ $order->shipping_phone }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Earnings Summary -->
            <div class="bg-white border border-slate-200 p-6">
                <h2 class="text-lg font-serif text-slate-900 mb-4">Your Earnings</h2>
                <div class="space-y-3 text-[13px]">
                    <div class="flex justify-between"><span class="text-slate-600">Order Amount</span><span class="text-slate-900">₹{{ number_format($earning->order_amount, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Commission ({{ $earning->commission_rate }}%)</span><span class="text-red-600">-₹{{ number_format($earning->commission_amount, 2) }}</span></div>
                    <hr class="border-slate-100">
                    <div class="flex justify-between"><span class="font-medium text-slate-900">Your Earnings</span><span class="font-semibold text-emerald-600">₹{{ number_format($earning->seller_amount, 2) }}</span></div>
                </div>
                <div class="mt-4 p-3 {{ $earning->status === 'paid' ? 'bg-emerald-50 border border-emerald-100' : 'bg-amber-50 border border-amber-100' }}">
                    <p class="text-[12px] {{ $earning->status === 'paid' ? 'text-emerald-700' : 'text-amber-700' }}">
                        @if($earning->status === 'paid') ✓ Earnings credited to wallet
                        @elseif($earning->status === 'processed') Processed, pending payout
                        @else Credited after delivery @endif
                    </p>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white border border-slate-200 p-6">
                <h2 class="text-lg font-serif text-slate-900 mb-4">Customer</h2>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-slate-100 flex items-center justify-center"><span class="text-slate-600 font-medium text-[13px]">{{ substr($order->user->name ?? 'G', 0, 1) }}</span></div>
                    <div>
                        <p class="text-[13px] font-medium text-slate-900">{{ $order->user->name ?? 'Guest' }}</p>
                        <p class="text-[12px] text-slate-500">{{ $order->user->email ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white border border-slate-200 p-6">
                <h2 class="text-lg font-serif text-slate-900 mb-4">Timeline</h2>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 mt-2 rounded-full bg-emerald-500"></div>
                        <div><p class="text-[12px] font-medium text-slate-900">Order Placed</p><p class="text-[11px] text-slate-500">{{ $order->created_at->format('M d, Y h:i A') }}</p></div>
                    </div>
                    @if($order->status !== 'pending')
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 mt-2 rounded-full {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                        <div><p class="text-[12px] font-medium text-slate-900">Processing</p></div>
                    </div>
                    @endif
                    @if(in_array($order->status, ['shipped', 'delivered']))
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 mt-2 rounded-full bg-emerald-500"></div>
                        <div><p class="text-[12px] font-medium text-slate-900">Shipped</p></div>
                    </div>
                    @endif
                    @if($order->status === 'delivered')
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 mt-2 rounded-full bg-emerald-500"></div>
                        <div><p class="text-[12px] font-medium text-slate-900">Delivered</p></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
