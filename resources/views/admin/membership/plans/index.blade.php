@extends('layouts.admin')

@section('title', 'Membership Plans')
@section('page-title', 'Membership Plans')

@section('content')
<div class="space-y-6">
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Total Plans</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $stats['total_plans'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Active Plans</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['active_plans'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Active Subscribers</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['total_subscribers'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-amber-600">₹{{ number_format($stats['monthly_revenue']) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Plans Table --}}
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold">All Plans</h2>
            <a href="{{ route('admin.membership.plans.create') }}" class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded hover:bg-slate-800 transition-colors">
                Add New Plan
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Benefits</th>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Subscribers</th>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold tracking-wider uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($plans as $plan)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $plan->name }}</p>
                                    <p class="text-xs text-slate-500">{{ ucfirst($plan->billing_cycle) }}</p>
                                </div>
                                @if($plan->is_popular)
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-medium rounded">Popular</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold">₹{{ number_format($plan->price) }}</p>
                            <p class="text-xs text-slate-500">{{ $plan->duration_days }} days</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @if($plan->free_shipping)
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded">Free Ship</span>
                                @endif
                                @if($plan->discount_percentage > 0)
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded">{{ $plan->discount_percentage }}% Off</span>
                                @endif
                                @if($plan->early_access_days > 0)
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded">Early Access</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium">{{ $plan->active_subscriptions_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $plan->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.membership.plans.edit', $plan) }}" class="p-2 text-slate-400 hover:text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($plan->active_subscriptions_count === 0)
                                <form action="{{ route('admin.membership.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete this plan?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-400 hover:text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            No membership plans found. <a href="{{ route('admin.membership.plans.create') }}" class="text-blue-600 hover:underline">Create one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($plans->hasPages())
        <div class="p-6 border-t border-slate-200">
            {{ $plans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
