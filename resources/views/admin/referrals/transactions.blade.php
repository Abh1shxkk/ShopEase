@extends('layouts.admin')

@section('title', 'Reward Transactions')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Reward Transactions</h1>
            <p class="text-[12px] text-slate-500 mt-1">Track all points earned and redeemed</p>
        </div>
        <a href="{{ route('admin.referrals.index') }}" class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-600 hover:text-slate-900">← Back to Referrals</a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Earned</p>
            <p class="text-3xl font-light text-emerald-600 mt-2">{{ number_format($stats['total_earned']) }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Redeemed</p>
            <p class="text-3xl font-light text-red-600 mt-2">{{ number_format($stats['total_redeemed']) }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Active Points</p>
            <p class="text-3xl font-light text-blue-600 mt-2">{{ number_format($stats['active_points']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user..." class="h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 w-64">
            <select name="type" onchange="this.form.submit()" class="h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                <option value="">All Types</option>
                <option value="earned" {{ request('type') == 'earned' ? 'selected' : '' }}>Earned</option>
                <option value="redeemed" {{ request('type') == 'redeemed' ? 'selected' : '' }}>Redeemed</option>
                <option value="adjusted" {{ request('type') == 'adjusted' ? 'selected' : '' }}>Adjusted</option>
            </select>
            <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase">Filter</button>
        </form>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">User</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Type</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Points</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Balance</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Source</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-medium text-slate-900">{{ $transaction->user->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $transaction->user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-[10px] font-bold tracking-wider uppercase {{ $transaction->type === 'earned' ? 'bg-emerald-100 text-emerald-700' : ($transaction->type === 'redeemed' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $transaction->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[14px] font-semibold {{ $transaction->points > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-[13px] text-slate-600">{{ number_format($transaction->balance_after) }}</td>
                        <td class="px-6 py-4 text-[13px] text-slate-600">{{ ucfirst($transaction->source) }}</td>
                        <td class="px-6 py-4 text-[13px] text-slate-600">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-[13px] text-slate-500">No transactions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $transactions->links('vendor.pagination.admin') }}
        </div>
        @endif
    </div>
</div>
@endsection
