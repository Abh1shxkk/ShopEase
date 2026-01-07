@extends('layouts.admin')

@section('title', 'Sales Report')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.analytics.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Analytics
            </a>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Sales Report</h1>
            <p class="text-[12px] text-slate-500 mt-1">Detailed sales performance analysis</p>
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
                Export CSV
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Revenue</p>
            <p class="text-3xl font-light text-slate-900 mt-2">₹{{ number_format($salesReport['summary']['total_revenue']) }}</p>
            <p class="text-[11px] {{ $salesReport['summary']['revenue_growth'] >= 0 ? 'text-emerald-600' : 'text-red-600' }} mt-1">
                {{ $salesReport['summary']['revenue_growth'] >= 0 ? '+' : '' }}{{ $salesReport['summary']['revenue_growth'] }}% vs previous period
            </p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Orders</p>
            <p class="text-3xl font-light text-blue-600 mt-2">{{ number_format($salesReport['summary']['total_orders']) }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Avg Order Value</p>
            <p class="text-3xl font-light text-purple-600 mt-2">₹{{ number_format($salesReport['summary']['avg_order_value']) }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Growth</p>
            <p class="text-3xl font-light {{ $salesReport['summary']['revenue_growth'] >= 0 ? 'text-emerald-600' : 'text-red-600' }} mt-2">
                {{ $salesReport['summary']['revenue_growth'] >= 0 ? '+' : '' }}{{ $salesReport['summary']['revenue_growth'] }}%
            </p>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Sales Data</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Period</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Revenue</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Orders</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Avg Order Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($salesReport['data'] as $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-[13px] font-medium text-slate-900">{{ $row->period }}</td>
                        <td class="px-6 py-4 text-[13px] text-slate-900 text-right">₹{{ number_format($row->revenue) }}</td>
                        <td class="px-6 py-4 text-[13px] text-slate-600 text-right">{{ $row->orders }}</td>
                        <td class="px-6 py-4 text-[13px] text-slate-600 text-right">₹{{ number_format($row->avg_order_value) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-[13px] text-slate-500">No sales data for this period</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
