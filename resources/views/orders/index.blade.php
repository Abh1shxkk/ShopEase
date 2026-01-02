@extends('layouts.shop')

@section('title', 'My Orders')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-8">
        <a href="/" class="hover:text-slate-900">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-900 font-medium">My Orders</span>
    </nav>

    <h1 class="text-3xl font-bold text-slate-900 mb-8">My Orders</h1>

    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <a href="{{ route('orders.show', $order) }}" class="block bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg hover:border-gray-200 transition-all">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div>
                    <p class="font-semibold text-slate-900">{{ $order->order_number }}</p>
                    <p class="text-sm text-slate-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'processing' => 'bg-blue-100 text-blue-700',
                        'shipped' => 'bg-purple-100 text-purple-700',
                        'delivered' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                    ];
                    @endphp
                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="text-lg font-bold text-slate-900">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span>{{ $order->items->count() }} item(s)</span>
                <span>•</span>
                <span>{{ ucfirst($order->payment_method) }}</span>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @else
    <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <h3 class="text-xl font-semibold text-slate-900 mb-2">No orders yet</h3>
        <p class="text-slate-500 mb-6">Start shopping to see your orders here.</p>
        <a href="{{ route('shop.index') }}" class="inline-flex h-12 px-8 bg-blue-600 text-white font-semibold rounded-xl items-center justify-center hover:bg-blue-700 transition-colors">
            Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection
