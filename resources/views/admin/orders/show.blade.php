@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.orders.index') }}" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-slate-900 flex items-center gap-2 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Orders
    </a>
</div>

<div class="flex flex-wrap items-start justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ $order->order_number }}</h1>
        <p class="text-[12px] text-slate-500 mt-1">Placed on {{ $order->created_at->format('d F Y, h:i A') }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('orders.invoice.download', $order) }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Invoice
        </a>
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="flex items-center gap-2">
            @csrf
            @method('PATCH')
            <select name="status" class="h-11 px-4 bg-white border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Update</button>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Order Items -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Order Items</h2>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($order->items as $item)
                <div class="px-6 py-4 flex gap-4">
                    <div class="w-16 h-16 bg-slate-100 overflow-hidden flex-shrink-0">
                        @if($item->product && $item->product->image)
                        <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-[13px] font-medium text-slate-900">{{ $item->product_name }}</p>
                        <p class="text-[11px] text-slate-400 mt-1">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 0) }}</p>
                    </div>
                    <p class="text-[13px] font-semibold text-slate-900">₹{{ number_format($item->total, 0) }}</p>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-slate-50 space-y-2">
                <div class="flex justify-between text-[12px]">
                    <span class="text-slate-500">Subtotal</span>
                    <span class="text-slate-900">₹{{ number_format($order->subtotal, 0) }}</span>
                </div>
                <div class="flex justify-between text-[12px]">
                    <span class="text-slate-500">Shipping</span>
                    <span class="{{ $order->shipping == 0 ? 'text-emerald-600' : 'text-slate-900' }}">{{ $order->shipping == 0 ? 'FREE' : '₹' . number_format($order->shipping, 0) }}</span>
                </div>
                <div class="flex justify-between text-[12px]">
                    <span class="text-slate-500">Tax (GST)</span>
                    <span class="text-slate-900">₹{{ number_format($order->tax, 0) }}</span>
                </div>
                <div class="flex justify-between text-[14px] font-semibold pt-2 border-t border-slate-200">
                    <span>Total</span>
                    <span>₹{{ number_format($order->total, 0) }}</span>
                </div>
            </div>
        </div>

        @if($order->notes)
        <div class="bg-white border border-slate-200 p-6">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-3">Order Notes</h2>
            <p class="text-[13px] text-slate-600">{{ $order->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Customer -->
        <div class="bg-white border border-slate-200 p-6">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-4">Customer</h2>
            @if($order->user)
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-slate-100 flex items-center justify-center text-slate-600 font-medium">
                    {{ substr($order->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-[13px] font-medium text-slate-900">{{ $order->user->name }}</p>
                    <p class="text-[11px] text-slate-400">{{ $order->user->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.users.show', $order->user) }}" class="w-full h-10 bg-slate-100 text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase flex items-center justify-center hover:bg-slate-200 transition-colors">View Profile</a>
            @else
            <p class="text-[13px] text-slate-400">Guest checkout</p>
            @endif
        </div>

        <!-- Shipping -->
        <div class="bg-white border border-slate-200 p-6">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-4">Shipping Address</h2>
            <div class="text-[12px] text-slate-600 space-y-1">
                <p class="font-medium text-slate-900">{{ $order->shipping_name }}</p>
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                <p class="pt-2">{{ $order->shipping_phone }}</p>
                <p>{{ $order->shipping_email }}</p>
            </div>
        </div>

        <!-- Payment -->
        <div class="bg-white border border-slate-200 p-6">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-4">Payment</h2>
            <div class="space-y-3 text-[12px]">
                <div class="flex justify-between">
                    <span class="text-slate-500">Method</span>
                    <span class="font-medium text-slate-900 uppercase">{{ $order->payment_method }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Status</span>
                    <span class="px-2 py-0.5 text-[10px] tracking-widest uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($order->payment_status === 'failed' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">
                        {{ $order->payment_status }}
                    </span>
                </div>
                @if($order->razorpay_payment_id)
                <div class="flex justify-between">
                    <span class="text-slate-500">Payment ID</span>
                    <span class="font-mono text-[10px] text-slate-600">{{ $order->razorpay_payment_id }}</span>
                </div>
                @endif
                @if($order->paid_at)
                <div class="flex justify-between">
                    <span class="text-slate-500">Paid At</span>
                    <span>{{ $order->paid_at->format('d M Y, h:i A') }}</span>
                </div>
                @endif
            </div>
            
            <form action="{{ route('admin.orders.updatePaymentStatus', $order) }}" method="POST" class="mt-4 pt-4 border-t border-slate-200">
                @csrf
                @method('PATCH')
                <select name="payment_status" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900 mb-2">
                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
                <button type="submit" class="w-full h-10 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Update Payment</button>
            </form>
        </div>

        <!-- Status Timeline -->
        <div class="bg-white border border-slate-200 p-6">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-4">Order Progress</h2>
            @php
                $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                $currentIndex = array_search($order->status, $statuses);
                if ($order->status === 'cancelled') $currentIndex = -1;
            @endphp
            <div class="space-y-4">
                @foreach($statuses as $index => $status)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center {{ $index <= $currentIndex ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-400' }}">
                        @if($index < $currentIndex)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                        <span class="text-[11px] font-bold">{{ $index + 1 }}</span>
                        @endif
                    </div>
                    <span class="text-[12px] {{ $index <= $currentIndex ? 'text-slate-900 font-medium' : 'text-slate-400' }}">{{ ucfirst($status) }}</span>
                </div>
                @endforeach
                @if($order->status === 'cancelled')
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <span class="text-[12px] text-red-600 font-medium">Cancelled</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
