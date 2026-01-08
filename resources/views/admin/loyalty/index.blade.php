@extends('layouts.admin')

@section('title', 'Loyalty Program')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Loyalty Program</h1>
            <p class="text-[12px] text-slate-500 mt-1">Manage customer loyalty points and rewards</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.loyalty.settings') }}" class="h-10 px-5 bg-white border border-slate-200 text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-light text-slate-900">{{ number_format($stats['total_points_issued']) }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Total Points Issued</p>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-light text-slate-900">{{ number_format($stats['total_points_redeemed']) }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Total Points Redeemed</p>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-light text-slate-900">{{ number_format($stats['total_active_points']) }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Active Points (₹{{ number_format($stats['points_value_outstanding']) }})</p>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-light text-slate-900">{{ number_format($stats['total_members']) }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Members with Points</p>
        </div>
    </div>

    {{-- Tier Distribution --}}
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Tier Distribution</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-4 gap-4">
                <div class="text-center p-4 bg-orange-50 border border-orange-200">
                    <span class="text-3xl">🥉</span>
                    <p class="text-2xl font-light text-slate-900 mt-2">{{ $tierDistribution['bronze'] }}</p>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Bronze</p>
                </div>
                <div class="text-center p-4 bg-slate-100 border border-slate-300">
                    <span class="text-3xl">🥈</span>
                    <p class="text-2xl font-light text-slate-900 mt-2">{{ $tierDistribution['silver'] }}</p>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Silver</p>
                </div>
                <div class="text-center p-4 bg-amber-50 border border-amber-200">
                    <span class="text-3xl">🥇</span>
                    <p class="text-2xl font-light text-slate-900 mt-2">{{ $tierDistribution['gold'] }}</p>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Gold</p>
                </div>
                <div class="text-center p-4 bg-purple-50 border border-purple-200">
                    <span class="text-3xl">💎</span>
                    <p class="text-2xl font-light text-slate-900 mt-2">{{ $tierDistribution['platinum'] }}</p>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Platinum</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        {{-- Top Earners --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Top Earners</h3>
                <a href="{{ route('admin.loyalty.members') }}" class="text-[11px] text-slate-600 hover:text-slate-900">View All →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($topEarners as $index => $earner)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="w-6 h-6 flex items-center justify-center text-[11px] font-bold {{ $index < 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">{{ $index + 1 }}</span>
                        <div>
                            <p class="text-[13px] font-medium text-slate-900">{{ $earner->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $earner->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[13px] font-semibold text-slate-900">{{ number_format($earner->total_earned_points) }}</p>
                        <p class="text-[10px] text-slate-400">lifetime points</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-slate-500 text-sm">No data yet</div>
                @endforelse
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Recent Transactions</h3>
                <a href="{{ route('admin.loyalty.transactions') }}" class="text-[11px] text-slate-600 hover:text-slate-900">View All →</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentTransactions as $transaction)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center {{ $transaction->points > 0 ? 'bg-emerald-100' : 'bg-red-100' }}">
                            @if($transaction->points > 0)
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            @else
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-[12px] text-slate-900">{{ $transaction->user->name ?? 'Unknown' }}</p>
                            <p class="text-[10px] text-slate-500">{{ ucfirst($transaction->source) }} • {{ $transaction->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <span class="text-[13px] font-semibold {{ $transaction->points > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-slate-500 text-sm">No transactions yet</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Quick Actions</h3>
        </div>
        <div class="p-6 grid sm:grid-cols-3 gap-4">
            <a href="{{ route('admin.loyalty.members') }}" class="p-4 border border-slate-200 hover:border-slate-900 transition-colors flex items-center gap-4">
                <div class="w-10 h-10 bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[13px] font-medium text-slate-900">Manage Members</p>
                    <p class="text-[11px] text-slate-500">View and adjust user points</p>
                </div>
            </a>
            <a href="{{ route('admin.loyalty.transactions') }}" class="p-4 border border-slate-200 hover:border-slate-900 transition-colors flex items-center gap-4">
                <div class="w-10 h-10 bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[13px] font-medium text-slate-900">All Transactions</p>
                    <p class="text-[11px] text-slate-500">View complete history</p>
                </div>
            </a>
            <a href="{{ route('admin.loyalty.settings') }}" class="p-4 border border-slate-200 hover:border-slate-900 transition-colors flex items-center gap-4">
                <div class="w-10 h-10 bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[13px] font-medium text-slate-900">Program Settings</p>
                    <p class="text-[11px] text-slate-500">Configure points & tiers</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
