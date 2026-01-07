@extends('layouts.admin')

@section('title', 'Membership Plans')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Membership Plans</h1>
            <p class="text-[12px] text-slate-500 mt-1">Manage subscription plans for your members</p>
        </div>
        <a href="{{ route('admin.membership.plans.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add Plan
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Plans</p>
                    <p class="text-3xl font-light text-slate-900 mt-2">{{ \App\Models\MembershipPlan::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Active Plans</p>
                    <p class="text-3xl font-light text-emerald-600 mt-2">{{ \App\Models\MembershipPlan::where('is_active', true)->count() }}</p>
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
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Subscribers</p>
                    <p class="text-3xl font-light text-blue-600 mt-2">{{ \App\Models\UserSubscription::where('status', 'active')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Revenue</p>
                    <p class="text-3xl font-light text-purple-600 mt-2">₹{{ number_format(\App\Models\SubscriptionPayment::where('status', 'completed')->sum('amount')) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Plans Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($plans as $plan)
        <div class="bg-white border border-slate-200 {{ $plan->is_featured ? 'ring-2 ring-slate-900' : '' }}">
            @if($plan->is_featured)
            <div class="bg-slate-900 text-white text-center py-2">
                <span class="text-[10px] font-semibold tracking-wider uppercase">Most Popular</span>
            </div>
            @endif
            
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $plan->name }}</h3>
                        <p class="text-[12px] text-slate-500 mt-1">{{ ucfirst($plan->billing_cycle) }} billing</p>
                    </div>
                    @if($plan->is_active)
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-emerald-700">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                        Active
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                        <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                        Inactive
                    </span>
                    @endif
                </div>
                
                <div class="mb-6">
                    <span class="text-3xl font-light text-slate-900">₹{{ number_format($plan->price) }}</span>
                    <span class="text-[13px] text-slate-500">/{{ $plan->billing_cycle }}</span>
                </div>
                
                @if($plan->features && is_array($plan->features))
                <ul class="space-y-2 mb-6">
                    @foreach($plan->features as $feature)
                    <li class="flex items-center gap-2 text-[13px] text-slate-600">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                @endif
                
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-[12px] text-slate-500">{{ $plan->subscriptions_count ?? 0 }} subscribers</span>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.membership.plans.edit', $plan) }}" class="text-[12px] font-medium text-slate-600 hover:text-slate-900 transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('admin.membership.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Delete this plan?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[12px] font-medium text-red-600 hover:text-red-700 transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white border border-slate-200 p-16 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <p class="text-[13px] text-slate-500 mb-4">No membership plans yet</p>
            <a href="{{ route('admin.membership.plans.create') }}" class="text-[12px] font-medium text-blue-600 hover:text-blue-700">
                Create your first plan →
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
