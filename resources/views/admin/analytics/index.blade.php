@extends('layouts.admin')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Analytics Dashboard</h1>
            <p class="text-[12px] text-slate-500 mt-1">Track your store performance and insights</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <select name="period" onchange="this.form.submit()" class="h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </form>
            <a href="{{ route('admin.analytics.export-sales', ['period' => $period]) }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export
            </a>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Today's Revenue</p>
                    <p class="text-3xl font-light text-slate-900 mt-2">₹{{ number_format($overview['today']['revenue']) }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">{{ $overview['today']['orders'] }} orders</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">This Week</p>
                    <p class="text-3xl font-light text-blue-600 mt-2">₹{{ number_format($overview['this_week']['revenue']) }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">{{ $overview['this_week']['orders'] }} orders</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">This Month</p>
                    <p class="text-3xl font-light text-purple-600 mt-2">₹{{ number_format($overview['this_month']['revenue']) }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">{{ $overview['this_month']['orders'] }} orders</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">New Customers</p>
                    <p class="text-3xl font-light text-amber-600 mt-2">{{ $overview['this_month']['new_customers'] }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">this month</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales Report Summary --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Sales Overview</h3>
                <div class="flex items-center gap-4 text-[11px]">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                        Revenue
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                        Orders
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="h-64 flex items-end justify-between gap-2">
                    @foreach($salesReport['data']->take(14) as $index => $data)
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full bg-slate-100 rounded-t relative" style="height: {{ max(($data->revenue / max($salesReport['data']->max('revenue'), 1)) * 200, 4) }}px">
                            <div class="absolute inset-0 bg-emerald-500 rounded-t opacity-80"></div>
                        </div>
                        <span class="text-[9px] text-slate-400 truncate w-full text-center">{{ \Carbon\Carbon::parse($data->period)->format('d') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-6">Period Summary</h3>
            <div class="space-y-5">
                <div>
                    <p class="text-[11px] text-slate-500">Total Revenue</p>
                    <p class="text-2xl font-light text-slate-900">₹{{ number_format($salesReport['summary']['total_revenue']) }}</p>
                    <p class="text-[11px] {{ $salesReport['summary']['revenue_growth'] >= 0 ? 'text-emerald-600' : 'text-red-600' }} mt-1">
                        {{ $salesReport['summary']['revenue_growth'] >= 0 ? '+' : '' }}{{ $salesReport['summary']['revenue_growth'] }}% vs previous
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-[11px] text-slate-500">Total Orders</p>
                    <p class="text-2xl font-light text-slate-900">{{ number_format($salesReport['summary']['total_orders']) }}</p>
                </div>
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-[11px] text-slate-500">Avg Order Value</p>
                    <p class="text-2xl font-light text-slate-900">₹{{ number_format($salesReport['summary']['avg_order_value']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Best Selling Products --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Best Selling Products</h3>
                <a href="{{ route('admin.analytics.best-selling') }}" class="text-[11px] font-bold tracking-[0.1em] uppercase text-blue-600 hover:text-blue-700">View All</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($bestSelling['products']->take(5) as $index => $product)
                <div class="px-6 py-4 flex items-center gap-4">
                    <span class="w-6 h-6 bg-slate-100 flex items-center justify-center text-[11px] font-bold text-slate-500">{{ $index + 1 }}</span>
                    <div class="w-12 h-12 bg-slate-100 flex-shrink-0 overflow-hidden">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-medium text-slate-900 truncate">{{ $product->name }}</p>
                        <p class="text-[11px] text-slate-500">{{ $product->total_sold }} sold</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[13px] font-semibold text-slate-900">₹{{ number_format($product->total_revenue) }}</p>
                        <p class="text-[11px] text-slate-400">{{ $product->percentage }}%</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-[13px] text-slate-500">No sales data yet</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Customer Acquisition --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Customer Acquisition</h3>
                <a href="{{ route('admin.analytics.customers') }}" class="text-[11px] font-bold tracking-[0.1em] uppercase text-blue-600 hover:text-blue-700">Details</a>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="text-center p-4 bg-slate-50">
                        <p class="text-3xl font-light text-slate-900">{{ $customerStats['summary']['total_new_customers'] }}</p>
                        <p class="text-[11px] text-slate-500 mt-1">New Customers</p>
                        <p class="text-[11px] {{ $customerStats['summary']['customer_growth'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $customerStats['summary']['customer_growth'] >= 0 ? '+' : '' }}{{ $customerStats['summary']['customer_growth'] }}%
                        </p>
                    </div>
                    <div class="text-center p-4 bg-slate-50">
                        <p class="text-3xl font-light text-slate-900">{{ $customerStats['summary']['retention_rate'] }}%</p>
                        <p class="text-[11px] text-slate-500 mt-1">Retention Rate</p>
                        <p class="text-[11px] text-slate-400">{{ $customerStats['summary']['returning_customers'] }} returning</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-slate-500">New Ordering Customers</span>
                        <span class="font-medium text-slate-900">{{ $customerStats['summary']['new_ordering_customers'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-slate-500">Social Signups</span>
                        <span class="font-medium text-slate-900">{{ $customerStats['summary']['social_signups'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Abandoned Carts --}}
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Abandoned Cart Analytics</h3>
            <a href="{{ route('admin.analytics.abandoned-carts') }}" class="text-[11px] font-bold tracking-[0.1em] uppercase text-blue-600 hover:text-blue-700">View Details</a>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="text-center p-4 border border-slate-200">
                    <p class="text-3xl font-light text-red-600">{{ $abandonedCarts['summary']['total_abandoned_carts'] }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">Abandoned Carts</p>
                </div>
                <div class="text-center p-4 border border-slate-200">
                    <p class="text-3xl font-light text-amber-600">₹{{ number_format($abandonedCarts['summary']['total_abandoned_value']) }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">Potential Revenue Lost</p>
                </div>
                <div class="text-center p-4 border border-slate-200">
                    <p class="text-3xl font-light text-emerald-600">{{ $abandonedCarts['summary']['cart_conversion_rate'] }}%</p>
                    <p class="text-[11px] text-slate-500 mt-1">Cart Conversion Rate</p>
                </div>
                <div class="text-center p-4 border border-slate-200">
                    <p class="text-3xl font-light text-slate-900">₹{{ number_format($abandonedCarts['summary']['avg_cart_value']) }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">Avg Cart Value</p>
                </div>
            </div>
            
            @if($abandonedCarts['abandoned_by_product']->count() > 0)
            <div>
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-4">Most Abandoned Products</p>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50">
                                <th class="text-left px-4 py-3 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Product</th>
                                <th class="text-left px-4 py-3 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Abandon Count</th>
                                <th class="text-left px-4 py-3 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Quantity</th>
                                <th class="text-right px-4 py-3 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Potential Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($abandonedCarts['abandoned_by_product']->take(5) as $product)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-slate-100 flex-shrink-0 overflow-hidden">
                                            @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <span class="text-[13px] text-slate-900">{{ Str::limit($product->name, 30) }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-[13px] text-slate-600">{{ $product->abandon_count }}</td>
                                <td class="px-4 py-3 text-[13px] text-slate-600">{{ $product->total_quantity }}</td>
                                <td class="px-4 py-3 text-[13px] font-semibold text-red-600 text-right">₹{{ number_format($product->potential_revenue) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
