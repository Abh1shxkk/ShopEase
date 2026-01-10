@extends('layouts.seller')

@section('title', 'Payouts')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-serif text-slate-900">Payouts</h1>
        <p class="text-[13px] text-slate-500 mt-1">Manage your earnings and request payouts</p>
    </div>

    <!-- Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-slate-900 p-6">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Available Balance</p>
            <p class="text-3xl font-serif text-white mt-2">₹{{ number_format($seller->wallet_balance, 2) }}</p>
            <p class="text-[11px] text-slate-400 mt-2">Ready for withdrawal</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Pending Earnings</p>
            <p class="text-3xl font-serif text-slate-900 mt-2">₹{{ number_format($pendingEarnings, 2) }}</p>
            <p class="text-[11px] text-slate-500 mt-2">From undelivered orders</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Total Withdrawn</p>
            <p class="text-3xl font-serif text-slate-900 mt-2">₹{{ number_format($seller->total_withdrawn, 2) }}</p>
            <p class="text-[11px] text-slate-500 mt-2">All time</p>
        </div>
    </div>

    <!-- Request Payout -->
    @if($seller->wallet_balance >= $minimumPayout)
    <div class="bg-white border border-slate-200 p-6">
        <h2 class="text-lg font-serif text-slate-900 mb-4">Request Payout</h2>
        <form action="{{ route('seller.payouts.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
            @csrf
            <div class="flex-1">
                <label class="block text-[12px] font-medium text-slate-700 mb-2">Amount (₹)</label>
                <input type="number" name="amount" min="{{ $minimumPayout }}" max="{{ $seller->wallet_balance }}" step="0.01" value="{{ old('amount', $seller->wallet_balance) }}" required
                    class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                <p class="text-[10px] text-slate-500 mt-1">Minimum: ₹{{ number_format($minimumPayout, 2) }}</p>
            </div>
            <div class="sm:self-end">
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 text-white text-[12px] font-medium hover:bg-slate-800">Request Payout</button>
            </div>
        </form>
        @if(!$seller->bank_account_number)
        <div class="mt-4 p-3 bg-amber-50 border border-amber-100">
            <p class="text-[12px] text-amber-700">⚠️ Please add your bank details in <a href="{{ route('seller.profile.index') }}" class="underline">Store Settings</a> before requesting a payout.</p>
        </div>
        @endif
    </div>
    @else
    <div class="bg-slate-50 border-2 border-dashed border-slate-200 p-8 text-center">
        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-[13px] text-slate-600">Minimum balance of ₹{{ number_format($minimumPayout, 2) }} required for payout</p>
        <p class="text-[12px] text-slate-500 mt-1">Current balance: ₹{{ number_format($seller->wallet_balance, 2) }}</p>
    </div>
    @endif

    <!-- Payout History -->
    <div class="bg-white border border-slate-200">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-serif text-slate-900">Payout History</h2>
        </div>
        @if($payouts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Payout ID</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Amount</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Method</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Requested</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Processed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($payouts as $payout)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-[12px] font-medium text-slate-900">{{ $payout->payout_id }}</td>
                        <td class="px-6 py-4 text-[12px] text-slate-900">₹{{ number_format($payout->amount, 2) }}</td>
                        <td class="px-6 py-4 text-[12px] text-slate-600">{{ ucfirst(str_replace('_', ' ', $payout->payment_method)) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-[10px] font-medium
                                @if($payout->status === 'completed') bg-emerald-50 text-emerald-700
                                @elseif($payout->status === 'processing') bg-blue-50 text-blue-700
                                @elseif($payout->status === 'failed') bg-red-50 text-red-700
                                @else bg-amber-50 text-amber-700 @endif">
                                {{ ucfirst($payout->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[12px] text-slate-500">{{ $payout->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-[12px] text-slate-500">{{ $payout->processed_at ? $payout->processed_at->format('M d, Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $payouts->links() }}</div>
        @else
        <div class="p-16 text-center">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <h3 class="text-lg font-serif text-slate-900 mb-2">No payouts yet</h3>
            <p class="text-[13px] text-slate-500">Your payout history will appear here.</p>
        </div>
        @endif
    </div>
</div>
@endsection
