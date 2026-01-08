@extends('layouts.admin')

@section('title', 'Points Transactions')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.loyalty.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Loyalty
            </a>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Points Transactions</h1>
            <p class="text-[12px] text-slate-500 mt-1">Complete history of all points activity</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-emerald-600">+{{ number_format($stats['total_earned']) }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Total Earned</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-red-600">-{{ number_format($stats['total_redeemed']) }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Total Redeemed</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-blue-600">{{ number_format($stats['total_adjusted']) }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Total Adjusted</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user name..." class="w-full h-10 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
            </div>
            <select name="type" class="h-10 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                <option value="">All Types</option>
                <option value="earned" {{ request('type') === 'earned' ? 'selected' : '' }}>Earned</option>
                <option value="redeemed" {{ request('type') === 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                <option value="adjusted" {{ request('type') === 'adjusted' ? 'selected' : '' }}>Adjusted</option>
                <option value="expired" {{ request('type') === 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <select name="source" class="h-10 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                <option value="">All Sources</option>
                <option value="order" {{ request('source') === 'order' ? 'selected' : '' }}>Order</option>
                <option value="referral" {{ request('source') === 'referral' ? 'selected' : '' }}>Referral</option>
                <option value="signup" {{ request('source') === 'signup' ? 'selected' : '' }}>Signup</option>
                <option value="review" {{ request('source') === 'review' ? 'selected' : '' }}>Review</option>
                <option value="admin" {{ request('source') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="redemption" {{ request('source') === 'redemption' ? 'selected' : '' }}>Redemption</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="h-10 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="h-10 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
            <button type="submit" class="h-10 px-5 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'type', 'source', 'date_from', 'date_to']))
            <a href="{{ route('admin.loyalty.transactions') }}" class="h-10 px-4 border border-slate-200 text-slate-600 text-[11px] font-bold tracking-[0.15em] uppercase flex items-center hover:bg-slate-50 transition-colors">
                Clear
            </a>
            @endif
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Date</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">User</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Type</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Source</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Description</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Points</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Balance After</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <p class="text-[12px] text-slate-900">{{ $transaction->created_at->format('M d, Y') }}</p>
                            <p class="text-[10px] text-slate-500">{{ $transaction->created_at->format('h:i A') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-medium text-slate-900">{{ $transaction->user->name ?? 'Deleted User' }}</p>
                            <p class="text-[11px] text-slate-500">{{ $transaction->user->email ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $typeColors = [
                                    'earned' => 'bg-emerald-100 text-emerald-700',
                                    'redeemed' => 'bg-red-100 text-red-700',
                                    'adjusted' => 'bg-blue-100 text-blue-700',
                                    'expired' => 'bg-slate-100 text-slate-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-[10px] font-bold tracking-wider uppercase {{ $typeColors[$transaction->type] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $transaction->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] text-slate-600">{{ ucfirst($transaction->source ?? '-') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[12px] text-slate-600 max-w-xs truncate">{{ $transaction->description ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[14px] font-semibold {{ $transaction->points > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[13px] text-slate-600">{{ number_format($transaction->balance_after) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">No transactions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $transactions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
