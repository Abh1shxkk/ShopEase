@extends('layouts.seller')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="bg-slate-900 p-8">
        <div class="flex items-center gap-2 text-amber-400 text-[10px] font-bold tracking-[0.2em] uppercase mb-4">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            Seller Dashboard
        </div>
        <h1 class="text-3xl font-serif text-white">Welcome back, {{ $seller->store_name }}!</h1>
        <p class="mt-2 text-slate-400 text-[13px]">Here's what's happening with your store today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium tracking-widest uppercase text-slate-400">Wallet Balance</p>
                    <p class="text-2xl font-serif text-slate-900 mt-1">₹{{ number_format($stats['wallet_balance'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] text-slate-500">Pending: ₹{{ number_format($stats['pending_earnings'], 2) }}</p>
        </div>

        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium tracking-widest uppercase text-slate-400">Total Products</p>
                    <p class="text-2xl font-serif text-slate-900 mt-1">{{ $stats['total_products'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] text-slate-500">Active: {{ $stats['active_products'] }} | Pending: {{ $stats['pending_products'] }}</p>
        </div>

        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium tracking-widest uppercase text-slate-400">Total Orders</p>
                    <p class="text-2xl font-serif text-slate-900 mt-1">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] text-slate-500">Today: {{ $stats['today_orders'] }} | This Month: {{ $stats['month_orders'] }}</p>
        </div>

        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-medium tracking-widest uppercase text-slate-400">Store Rating</p>
                    <p class="text-2xl font-serif text-slate-900 mt-1">{{ number_format($stats['average_rating'], 1) }} ⭐</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] text-slate-500">{{ $stats['total_reviews'] }} reviews</p>
        </div>
    </div>

    <!-- Earnings Overview & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-slate-200">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-serif text-slate-900">Earnings Overview</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center p-4 bg-slate-50">
                    <span class="text-[12px] text-slate-600">Today's Earnings</span>
                    <span class="font-semibold text-slate-900">₹{{ number_format($stats['today_earnings'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-slate-50">
                    <span class="text-[12px] text-slate-600">This Month</span>
                    <span class="font-semibold text-slate-900">₹{{ number_format($stats['month_earnings'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-4 bg-slate-50">
                    <span class="text-[12px] text-slate-600">Total Earnings</span>
                    <span class="font-semibold text-slate-900">₹{{ number_format($stats['total_earnings'], 2) }}</span>
                </div>
            </div>
            <div class="px-6 pb-6">
                <a href="{{ route('seller.payouts.index') }}" class="inline-flex items-center text-[12px] text-slate-900 font-medium hover:underline">
                    Request Payout
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="bg-white border border-slate-200">
            <div class="p-6 border-b border-slate-100">
                <h2 class="text-lg font-serif text-slate-900">Quick Actions</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('seller.products.create') }}" class="flex flex-col items-center p-6 bg-slate-50 hover:bg-slate-100 transition group">
                        <svg class="w-8 h-8 text-slate-400 group-hover:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="text-[12px] font-medium text-slate-700">Add Product</span>
                    </a>
                    <a href="{{ route('seller.orders.index') }}" class="flex flex-col items-center p-6 bg-slate-50 hover:bg-slate-100 transition group">
                        <svg class="w-8 h-8 text-slate-400 group-hover:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="text-[12px] font-medium text-slate-700">View Orders</span>
                    </a>
                    <a href="{{ route('seller.profile.index') }}" class="flex flex-col items-center p-6 bg-slate-50 hover:bg-slate-100 transition group">
                        <svg class="w-8 h-8 text-slate-400 group-hover:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-[12px] font-medium text-slate-700">Store Settings</span>
                    </a>
                    <a href="{{ route('seller.payouts.index') }}" class="flex flex-col items-center p-6 bg-slate-50 hover:bg-slate-100 transition group">
                        <svg class="w-8 h-8 text-slate-400 group-hover:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-[12px] font-medium text-slate-700">Payouts</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    @if($recentOrders->count() > 0)
    <div class="bg-white border border-slate-200">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-serif text-slate-900">Recent Orders</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Order</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Customer</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Amount</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($recentOrders as $earning)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('seller.orders.show', $earning->order) }}" class="text-[12px] font-medium text-slate-900 hover:underline">
                                #{{ $earning->order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-[12px] text-slate-600">
                            {{ $earning->order->user->name ?? 'Guest' }}
                        </td>
                        <td class="px-6 py-4 text-[12px] font-medium text-slate-900">
                            ₹{{ number_format($earning->seller_amount, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-[10px] font-medium
                                @if($earning->order->status === 'delivered') bg-emerald-50 text-emerald-700
                                @elseif($earning->order->status === 'processing') bg-blue-50 text-blue-700
                                @elseif($earning->order->status === 'shipped') bg-purple-50 text-purple-700
                                @else bg-amber-50 text-amber-700 @endif">
                                {{ ucfirst($earning->order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[12px] text-slate-500">
                            {{ $earning->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            <a href="{{ route('seller.orders.index') }}" class="text-[12px] font-medium text-slate-900 hover:underline">View all orders →</a>
        </div>
    </div>
    @endif
</div>
@endsection
