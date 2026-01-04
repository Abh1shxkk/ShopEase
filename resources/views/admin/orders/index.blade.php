@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">Orders</h1>
        <p class="text-[12px] text-slate-500 mt-1">Manage customer orders</p>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-white border border-slate-200 p-4 text-center">
        <p class="text-xl font-serif text-slate-900">{{ $stats['total'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-slate-400 mt-1">Total</p>
    </div>
    <div class="bg-amber-50 border border-amber-200 p-4 text-center">
        <p class="text-xl font-serif text-amber-700">{{ $stats['pending'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-amber-600 mt-1">Pending</p>
    </div>
    <div class="bg-blue-50 border border-blue-200 p-4 text-center">
        <p class="text-xl font-serif text-blue-700">{{ $stats['processing'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-blue-600 mt-1">Processing</p>
    </div>
    <div class="bg-purple-50 border border-purple-200 p-4 text-center">
        <p class="text-xl font-serif text-purple-700">{{ $stats['shipped'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-purple-600 mt-1">Shipped</p>
    </div>
    <div class="bg-emerald-50 border border-emerald-200 p-4 text-center">
        <p class="text-xl font-serif text-emerald-700">{{ $stats['delivered'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-emerald-600 mt-1">Delivered</p>
    </div>
    <div class="bg-red-50 border border-red-200 p-4 text-center">
        <p class="text-xl font-serif text-red-700">{{ $stats['cancelled'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-red-600 mt-1">Cancelled</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white border border-slate-200 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order # or customer..." class="flex-1 min-w-[200px] h-11 px-4 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
        <select name="status" class="h-11 px-4 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <select name="payment_status" class="h-11 px-4 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
            <option value="">All Payments</option>
            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
        <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">Reset</a>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white border border-slate-200">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-200">
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Order</th>
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Customer</th>
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Total</th>
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Payment</th>
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Date</th>
                <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($orders as $order)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-[13px] font-medium text-slate-900 hover:underline">{{ $order->order_number }}</a>
                    <p class="text-[11px] text-slate-400">{{ $order->items->count() }} items</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-[13px] font-medium text-slate-900">{{ $order->user->name ?? 'Guest' }}</p>
                    <p class="text-[11px] text-slate-400">{{ $order->shipping_email }}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-[13px] font-semibold text-slate-900">₹{{ number_format($order->total, 0) }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-[10px] tracking-widest uppercase px-2 py-1 {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($order->payment_status === 'failed' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">
                        {{ $order->payment_status }}
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase">{{ $order->payment_method }}</p>
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="h-8 px-2 text-[11px] border border-slate-200 focus:outline-none focus:ring-1 focus:ring-slate-900 {{ 
                            $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-700' : 
                            ($order->status === 'cancelled' ? 'bg-red-50 text-red-700' : 
                            ($order->status === 'shipped' ? 'bg-purple-50 text-purple-700' : 
                            ($order->status === 'processing' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700'))) 
                        }}">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </td>
                <td class="px-6 py-4 text-[12px] text-slate-500">{{ $order->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-slate-900">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-[13px] text-slate-400">No orders found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $orders->links('vendor.pagination.admin') }}
</div>
@endsection
