@extends('layouts.admin')

@section('title', 'Customer Acquisition')

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
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">Customer Acquisition</h1>
        <p class="text-[12px] text-slate-500 mt-1">Track new customer signups and retention metrics</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">New Customers</p>
                    <p class="text-3xl font-light text-slate-900 mt-2">{{ number_format($customerStats['summary']['total_new_customers']) }}</p>
                    <p class="text-[11px] {{ $customerStats['summary']['customer_growth'] >= 0 ? 'text-emerald-600' : 'text-red-600' }} mt-1">
                        {{ $customerStats['summary']['customer_growth'] >= 0 ? '+' : '' }}{{ $customerStats['summary']['customer_growth'] }}% vs previous
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Retention Rate</p>
                    <p class="text-3xl font-light text-emerald-600 mt-2">{{ $customerStats['summary']['retention_rate'] }}%</p>
                    <p class="text-[11px] text-slate-500 mt-1">{{ $customerStats['summary']['returning_customers'] }} returning</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">New Ordering</p>
                    <p class="text-3xl font-light text-purple-600 mt-2">{{ number_format($customerStats['summary']['new_ordering_customers']) }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">first-time buyers</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Social Signups</p>
                    <p class="text-3xl font-light text-amber-600 mt-2">{{ number_format($customerStats['summary']['social_signups']) }}</p>
                    <p class="text-[11px] text-slate-500 mt-1">Google/Facebook</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Daily Signups Chart --}}
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Daily Customer Signups</h3>
        </div>
        <div class="p-6">
            @if($customerStats['daily_signups']->count() > 0)
            <div class="h-64 flex items-end justify-between gap-2">
                @foreach($customerStats['daily_signups'] as $data)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full bg-slate-100 rounded-t relative" style="height: {{ max(($data->count / max($customerStats['daily_signups']->max('count'), 1)) * 200, 4) }}px">
                        <div class="absolute inset-0 bg-blue-500 rounded-t opacity-80"></div>
                    </div>
                    <span class="text-[9px] text-slate-400 truncate w-full text-center">{{ \Carbon\Carbon::parse($data->date)->format('d') }}</span>
                </div>
                @endforeach
            </div>
            <div class="mt-4 flex items-center justify-center gap-4 text-[11px] text-slate-500">
                <span>Total: {{ $customerStats['daily_signups']->sum('count') }} signups</span>
                <span>•</span>
                <span>Avg: {{ round($customerStats['daily_signups']->avg('count'), 1) }}/day</span>
            </div>
            @else
            <div class="h-64 flex items-center justify-center">
                <p class="text-[13px] text-slate-500">No signup data available for this period</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Customer Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Customer Types --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Customer Breakdown</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-[13px] text-slate-700">New Customers</span>
                        </div>
                        <span class="text-[14px] font-semibold text-slate-900">{{ $customerStats['summary']['new_ordering_customers'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                            <span class="text-[13px] text-slate-700">Returning Customers</span>
                        </div>
                        <span class="text-[14px] font-semibold text-slate-900">{{ $customerStats['summary']['returning_customers'] }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                            <span class="text-[13px] text-slate-700">Social Signups</span>
                        </div>
                        <span class="text-[14px] font-semibold text-slate-900">{{ $customerStats['summary']['social_signups'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Retention Metrics --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Retention Metrics</h3>
            </div>
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-32 h-32 rounded-full border-8 border-emerald-100">
                        <div class="text-center">
                            <p class="text-3xl font-light text-emerald-600">{{ $customerStats['summary']['retention_rate'] }}%</p>
                            <p class="text-[10px] text-slate-500 uppercase tracking-wider">Retention</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-slate-500">Total Ordering Customers</span>
                        <span class="font-medium text-slate-900">{{ $customerStats['summary']['new_ordering_customers'] + $customerStats['summary']['returning_customers'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-slate-500">Repeat Purchase Rate</span>
                        <span class="font-medium text-slate-900">{{ $customerStats['summary']['retention_rate'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-[13px]">
                        <span class="text-slate-500">Growth vs Previous Period</span>
                        <span class="font-medium {{ $customerStats['summary']['customer_growth'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $customerStats['summary']['customer_growth'] >= 0 ? '+' : '' }}{{ $customerStats['summary']['customer_growth'] }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
