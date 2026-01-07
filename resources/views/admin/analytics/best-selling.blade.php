@extends('layouts.admin')

@section('title', 'Best Selling Products')

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
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">Best Selling Products</h1>
        <p class="text-[12px] text-slate-500 mt-1">Top performing products by sales volume</p>
    </div>

    {{-- Summary --}}
    <div class="bg-white border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Units Sold</p>
                <p class="text-3xl font-light text-slate-900 mt-2">{{ number_format($bestSelling['total_sold']) }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Product Rankings</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Rank</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Product</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Units Sold</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Orders</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Revenue</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Share</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bestSelling['products'] as $index => $product)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="w-8 h-8 bg-{{ $index < 3 ? 'amber' : 'slate' }}-100 flex items-center justify-center text-[12px] font-bold text-{{ $index < 3 ? 'amber-600' : 'slate-500' }}">
                                {{ $index + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-slate-100 flex-shrink-0 overflow-hidden">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[13px] font-medium text-slate-900">{{ $product->name }}</p>
                                    <p class="text-[11px] text-slate-500">₹{{ number_format($product->price) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[14px] font-semibold text-slate-900 text-right">{{ number_format($product->total_sold) }}</td>
                        <td class="px-6 py-4 text-[13px] text-slate-600 text-right">{{ $product->order_count }}</td>
                        <td class="px-6 py-4 text-[13px] font-semibold text-emerald-600 text-right">₹{{ number_format($product->total_revenue) }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <div class="w-16 h-2 bg-slate-100 overflow-hidden">
                                    <div class="h-full bg-emerald-500" style="width: {{ $product->percentage }}%"></div>
                                </div>
                                <span class="text-[12px] text-slate-600">{{ $product->percentage }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-[13px] text-slate-500">No sales data yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
