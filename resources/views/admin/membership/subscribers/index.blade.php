@extends('layouts.admin')

@section('title', 'Membership Subscribers')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Membership Subscribers</h1>
            <p class="text-[12px] text-slate-500 mt-1">Manage your premium members</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total</p>
                    <p class="text-3xl font-light text-slate-900 mt-2">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Active</p>
                    <p class="text-3xl font-light text-emerald-600 mt-2">{{ $stats['active'] ?? 0 }}</p>
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
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Expired</p>
                    <p class="text-3xl font-light text-amber-600 mt-2">{{ $stats['expired'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Cancelled</p>
                    <p class="text-3xl font-light text-red-600 mt-2">{{ $stats['cancelled'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 p-6">
        <form method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                    class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
            </div>
            <div class="w-full lg:w-48">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Status</label>
                <select name="status" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="w-full lg:w-48">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Plan</label>
                <select name="plan" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <option value="">All Plans</option>
                    @foreach(\App\Models\MembershipPlan::all() as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Filter</button>
                @if(request()->hasAny(['search', 'status', 'plan']))
                <a href="{{ route('admin.membership.subscribers') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Member</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Plan</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Started</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Expires</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subscriptions as $subscription)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-100 flex items-center justify-center text-[13px] font-medium text-slate-600">
                                    {{ substr($subscription->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-[13px] font-medium text-slate-900">{{ $subscription->user->name ?? 'Unknown' }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $subscription->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[13px] text-slate-900">{{ $subscription->plan->name ?? 'N/A' }}</span>
                            <p class="text-[11px] text-slate-400">₹{{ number_format($subscription->plan->price ?? 0) }}/{{ $subscription->plan->billing_cycle ?? 'month' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'active' => ['color' => 'emerald', 'label' => 'Active'],
                                    'expired' => ['color' => 'amber', 'label' => 'Expired'],
                                    'cancelled' => ['color' => 'red', 'label' => 'Cancelled'],
                                ];
                                $config = $statusConfig[$subscription->status] ?? ['color' => 'slate', 'label' => ucfirst($subscription->status)];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-{{ $config['color'] }}-700">
                                <span class="w-1.5 h-1.5 bg-{{ $config['color'] }}-500 rounded-full"></span>
                                {{ $config['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] text-slate-500">{{ $subscription->starts_at ? $subscription->starts_at->format('M d, Y') : '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] text-slate-500">{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : '—' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.membership.subscribers.show', $subscription) }}" class="text-[12px] font-medium text-slate-600 hover:text-slate-900 transition-colors">
                                View →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-[13px] text-slate-500">No subscribers found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($subscriptions->hasPages())
    <div>
        {{ $subscriptions->withQueryString()->links('vendor.pagination.admin') }}
    </div>
    @endif
</div>
@endsection
