@extends('layouts.admin')

@section('title', 'Abandoned Carts')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.analytics.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Analytics
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">Abandoned Cart Analytics</h1>
        <p class="text-[12px] text-slate-500 mt-1">Track cart abandonment and potential revenue recovery</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Abandoned Carts</p>
                    <p class="text-3xl font-light text-red-600 mt-2">{{ number_format($abandonedCarts['summary']['total_abandoned_carts']) }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">in selected period</p>
                </div>
                <div class="w-12 h-12 bg-red-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Potential Revenue</p>
                    <p class="text-3xl font-light text-amber-600 mt-2">₹{{ number_format($abandonedCarts['summary']['total_abandoned_value']) }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">lost revenue</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Conversion Rate</p>
                    <p class="text-3xl font-light text-emerald-600 mt-2">{{ $abandonedCarts['summary']['cart_conversion_rate'] }}%</p>
                    <p class="text-[11px] text-slate-500 mt-1">carts converted</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Avg Cart Value</p>
                    <p class="text-3xl font-light text-slate-900 mt-2">₹{{ number_format($abandonedCarts['summary']['avg_cart_value']) }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">per abandoned cart</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Daily Abandoned Carts Chart --}}
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Daily Abandoned Carts</h3>
        </div>
        <div class="p-6">
            @if($abandonedCarts['daily_abandoned']->count() > 0)
            <div class="h-64 flex items-end justify-between gap-2">
                @foreach($abandonedCarts['daily_abandoned'] as $data)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full bg-slate-100 rounded-t relative" style="height: {{ max(($data->count / max($abandonedCarts['daily_abandoned']->max('count'), 1)) * 200, 4) }}px">
                        <div class="absolute inset-0 bg-red-500 rounded-t opacity-80"></div>
                    </div>
                    <span class="text-[9px] text-slate-400 truncate w-full text-center">{{ \Carbon\Carbon::parse($data->date)->format('d') }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="h-64 flex items-center justify-center">
                <p class="text-[13px] text-slate-500">No abandoned cart data available</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Most Abandoned Products --}}
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Most Abandoned Products</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Product</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Abandon Count</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Total Quantity</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Potential Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($abandonedCarts['abandoned_by_product'] as $product)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-slate-100 flex-shrink-0 overflow-hidden">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[13px] font-medium text-slate-900">{{ Str::limit($product->name, 40) }}</p>
                                    <p class="text-[11px] text-slate-500">₹{{ number_format($product->price) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[14px] font-semibold text-red-600 text-right">{{ $product->abandon_count }}</td>
                        <td class="px-6 py-4 text-[13px] text-slate-600 text-right">{{ $product->total_quantity }}</td>
                        <td class="px-6 py-4 text-[13px] font-semibold text-amber-600 text-right">₹{{ number_format($product->potential_revenue) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-[13px] text-slate-500">No abandoned cart data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
