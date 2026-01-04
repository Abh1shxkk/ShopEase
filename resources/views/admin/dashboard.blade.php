@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome -->
<div class="mb-8">
    <h1 class="text-2xl font-serif tracking-wide text-slate-900">Welcome back, {{ Auth::user()->name }}</h1>
    <p class="text-[12px] text-slate-500 mt-1">Here's what's happening with your store today.</p>
</div>

<!-- Stats Grid -->
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white border border-slate-200 p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Revenue</p>
                <p class="text-2xl font-serif text-slate-900 mt-2">₹{{ number_format($stats['revenue'], 0) }}</p>
                <p class="text-[11px] text-emerald-600 mt-2">₹{{ number_format($stats['today_revenue'], 0) }} today</p>
            </div>
            <div class="w-12 h-12 bg-slate-900 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Orders</p>
                <p class="text-2xl font-serif text-slate-900 mt-2">{{ number_format($stats['orders']) }}</p>
                <p class="text-[11px] text-amber-600 mt-2">{{ $stats['pending_orders'] }} pending</p>
            </div>
            <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Products</p>
                <p class="text-2xl font-serif text-slate-900 mt-2">{{ number_format($stats['products']) }}</p>
                @php 
                    $lowStock = \App\Services\InventoryService::getLowStockCount();
                    $outOfStock = \App\Services\InventoryService::getOutOfStockCount();
                @endphp
                @if($lowStock > 0 || $outOfStock > 0)
                <a href="{{ route('admin.inventory.index') }}" class="text-[11px] text-red-600 mt-2 inline-block">{{ $outOfStock }} out, {{ $lowStock }} low</a>
                @else
                <a href="{{ route('admin.products.create') }}" class="text-[11px] text-slate-500 hover:text-slate-900 mt-2 inline-block">+ Add new</a>
                @endif
            </div>
            <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Customers</p>
                <p class="text-2xl font-serif text-slate-900 mt-2">{{ number_format($stats['users']) }}</p>
                <a href="{{ route('admin.users.index') }}" class="text-[11px] text-slate-500 hover:text-slate-900 mt-2 inline-block">View all →</a>
            </div>
            <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
    </div>
</div>

<!-- Inventory & Coupons Alert Bar -->
@php 
    $inventoryAlerts = $lowStock + $outOfStock;
    $activeCoupons = \App\Models\Coupon::where('is_active', true)
        ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
        ->count();
@endphp
@if($inventoryAlerts > 0)
<div class="mb-6 bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-amber-800">Inventory Alert</p>
            <p class="text-xs text-amber-600">{{ $outOfStock }} products out of stock, {{ $lowStock }} running low</p>
        </div>
    </div>
    <a href="{{ route('admin.inventory.index') }}" class="px-4 py-2 bg-amber-600 text-white text-xs font-semibold rounded hover:bg-amber-700 transition-colors">
        Manage Inventory
    </a>
</div>
@endif

<!-- Main Content Grid -->
<div class="grid lg:grid-cols-3 gap-6">
    <!-- Recent Orders -->
    <div class="lg:col-span-2 bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-slate-900">View All</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentOrders as $order)
            <a href="{{ route('admin.orders.show', $order) }}" class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-100 flex items-center justify-center text-slate-600 font-medium text-[12px]">
                        {{ substr($order->user->name ?? 'G', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[13px] font-medium text-slate-900">{{ $order->order_number }}</p>
                        <p class="text-[11px] text-slate-400">{{ $order->user->name ?? 'Guest' }} • {{ $order->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[13px] font-semibold text-slate-900">₹{{ number_format($order->total, 0) }}</p>
                    <span class="text-[10px] tracking-widest uppercase px-2 py-0.5 {{ 
                        $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-700' : 
                        ($order->status === 'cancelled' ? 'bg-red-50 text-red-700' : 
                        ($order->status === 'shipped' ? 'bg-purple-50 text-purple-700' : 
                        ($order->status === 'processing' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700'))) 
                    }}">{{ $order->status }}</span>
                </div>
            </a>
            @empty
            <div class="px-6 py-12 text-center">
                <p class="text-[13px] text-slate-400">No orders yet</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">New Customers</h2>
            <a href="{{ route('admin.users.index') }}" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-slate-900">View All</a>
        </div>
        <div class="p-4 space-y-3">
            @forelse($recentUsers as $user)
            <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-3 p-2 hover:bg-slate-50 transition-colors -mx-2">
                <div class="w-10 h-10 bg-slate-100 flex items-center justify-center text-slate-600 font-medium text-[12px]">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[12px] font-medium text-slate-900 truncate">{{ $user->name }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ $user->email }}</p>
                </div>
                <span class="text-[10px] tracking-widest uppercase px-2 py-0.5 {{ $user->role === 'admin' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $user->role }}</span>
            </a>
            @empty
            <p class="text-[13px] text-slate-400 text-center py-8">No users yet</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-white border border-slate-200 p-6">
    <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-4">Quick Actions</h2>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.products.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </a>
        <a href="{{ route('admin.coupons.create') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Create Coupon
        </a>
        <a href="{{ route('admin.inventory.index') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Manage Inventory
        </a>
        <a href="{{ route('admin.orders.index') }}?status=pending" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Pending Orders ({{ $stats['pending_orders'] }})
        </a>
    </div>
</div>

<!-- Coupons & Inventory Summary -->
<div class="mt-6 grid md:grid-cols-2 gap-6">
    <!-- Active Coupons -->
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Active Coupons</h2>
            <a href="{{ route('admin.coupons.index') }}" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-slate-900">View All</a>
        </div>
        <div class="p-4">
            @php
                $recentCoupons = \App\Models\Coupon::where('is_active', true)
                    ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
                    ->latest()
                    ->take(4)
                    ->get();
            @endphp
            @forelse($recentCoupons as $coupon)
            <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                <div>
                    <p class="font-mono font-bold text-slate-900 text-sm">{{ $coupon->code }}</p>
                    <p class="text-xs text-slate-500">{{ $coupon->name }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $coupon->type === 'percentage' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $coupon->formatted_value }} OFF
                    </span>
                    <p class="text-[10px] text-slate-400 mt-1">{{ $coupon->used_count }} used</p>
                </div>
            </div>
            @empty
            <div class="py-8 text-center">
                <p class="text-sm text-slate-400 mb-3">No active coupons</p>
                <a href="{{ route('admin.coupons.create') }}" class="text-xs text-blue-600 hover:underline">Create your first coupon</a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Low Stock Alert</h2>
            <a href="{{ route('admin.inventory.index') }}?status=low_stock" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-slate-900">View All</a>
        </div>
        <div class="p-4">
            @php
                $lowStockProducts = \App\Models\Product::where('status', 'active')
                    ->where('stock', '>', 0)
                    ->whereColumn('stock', '<=', 'low_stock_threshold')
                    ->orderBy('stock', 'asc')
                    ->take(4)
                    ->get();
            @endphp
            @forelse($lowStockProducts as $product)
            <div class="flex items-center gap-3 py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                <div class="w-10 h-10 bg-slate-100 rounded overflow-hidden flex-shrink-0">
                    @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-900 truncate">{{ $product->name }}</p>
                    <p class="text-xs text-slate-500">Threshold: {{ $product->low_stock_threshold }}</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-bold {{ $product->stock <= 2 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $product->stock }} left
                </span>
            </div>
            @empty
            <div class="py-8 text-center">
                <svg class="w-10 h-10 mx-auto text-green-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-slate-400">All products well stocked!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
