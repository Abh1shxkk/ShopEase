@extends('layouts.admin')

@section('title', 'Seller Payouts')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-serif text-slate-900">Seller Payouts</h1>
        <p class="text-[13px] text-slate-500 mt-1">Manage payout requests from sellers</p>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-slate-200 p-4">
        <form action="{{ route('admin.sellers.payouts') }}" method="GET" class="flex gap-4">
            <select name="status" class="px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-[12px] font-medium hover:bg-slate-200">Filter</button>
        </form>
    </div>

    <!-- Payouts Table -->
    <div class="bg-white border border-slate-200">
        @if($payouts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Payout ID</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Seller</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Amount</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Method</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Requested</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold tracking-widest uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($payouts as $payout)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-[12px] font-medium text-slate-900">{{ $payout->payout_id }}</td>
                        <td class="px-6 py-4">
                            <p class="text-[12px] text-slate-900">{{ $payout->seller->store_name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $payout->seller->user->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-[12px] font-medium text-slate-900">₹{{ number_format($payout->amount, 2) }}</td>
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
                        <td class="px-6 py-4 text-right">
                            @if($payout->status === 'pending')
                            <div class="flex justify-end items-center space-x-2">
                                <form action="{{ route('admin.sellers.payouts.process', $payout) }}" method="POST" class="inline flex items-center space-x-2">
                                    @csrf
                                    <input type="text" name="transaction_id" placeholder="Transaction ID" class="px-2 py-1.5 text-[11px] border border-slate-200 w-28">
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white text-[11px] font-medium hover:bg-emerald-700">Process</button>
                                </form>
                                <form action="{{ route('admin.sellers.payouts.reject', $payout) }}" method="POST" class="inline" onsubmit="return confirm('Reject this payout?')">
                                    @csrf
                                    <input type="hidden" name="notes" value="Rejected by admin">
                                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-[11px] font-medium hover:bg-red-700">Reject</button>
                                </form>
                            </div>
                            @else
                            <span class="text-[12px] text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $payouts->links() }}</div>
        @else
        <div class="p-16 text-center">
            <p class="text-[13px] text-slate-500">No payout requests found.</p>
        </div>
        @endif
    </div>
</div>
@endsection
