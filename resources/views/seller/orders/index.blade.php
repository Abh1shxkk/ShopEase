@extends('layouts.seller')

@section('title', 'Orders')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-serif text-slate-900">Orders</h1>
        <p class="text-[13px] text-slate-500 mt-1">View and manage orders containing your products</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Total Orders</p>
            <p class="text-2xl font-serif text-slate-900 mt-1">{{ $stats['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Pending</p>
            <p class="text-2xl font-serif text-amber-600 mt-1">{{ $stats['pending'] ?? 0 }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Processing</p>
            <p class="text-2xl font-serif text-blue-600 mt-1">{{ $stats['processing'] ?? 0 }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Delivered</p>
            <p class="text-2xl font-serif text-emerald-600 mt-1">{{ $stats['delivered'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-slate-200 p-4">
        <form action="{{ route('seller.orders.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order number..." 
                    class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
            </div>
            <select name="status" class="px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-[12px] font-medium hover:bg-slate-200">Filter</button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-slate-200">
        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Order</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Customer</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Products</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Your Earnings</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Date</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold tracking-widest uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($orders as $earning)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-[12px] font-medium text-slate-900">#{{ $earning->order->order_number }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[12px] text-slate-900">{{ $earning->order->user->name ?? 'Guest' }}</p>
                            <p class="text-[11px] text-slate-500">{{ $earning->order->user->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-[12px] text-slate-600">
                            {{ $earning->order->items->where('product.seller_id', auth()->user()->seller->id)->count() }} item(s)
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[12px] font-medium text-slate-900">₹{{ number_format($earning->seller_amount, 2) }}</p>
                            <p class="text-[10px] text-slate-500">Commission: ₹{{ number_format($earning->commission_amount, 2) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-[10px] font-medium
                                @if($earning->order->status === 'delivered') bg-emerald-50 text-emerald-700
                                @elseif($earning->order->status === 'processing') bg-blue-50 text-blue-700
                                @elseif($earning->order->status === 'shipped') bg-purple-50 text-purple-700
                                @elseif($earning->order->status === 'cancelled') bg-red-50 text-red-700
                                @else bg-amber-50 text-amber-700 @endif">
                                {{ ucfirst($earning->order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[12px] text-slate-500">{{ $earning->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('seller.orders.show', $earning->order) }}" class="text-[12px] font-medium text-slate-900 hover:underline">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $orders->links() }}</div>
        @else
        <div class="p-16 text-center">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <h3 class="text-lg font-serif text-slate-900 mb-2">No orders yet</h3>
            <p class="text-[13px] text-slate-500">Orders containing your products will appear here.</p>
        </div>
        @endif
    </div>
</div>
@endsection
