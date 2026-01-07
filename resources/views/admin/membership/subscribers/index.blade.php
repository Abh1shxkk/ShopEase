@extends('layouts.admin')

@section('title', 'Subscribers')
@section('page-title', 'Membership Subscribers')

@section('content')
<div class="space-y-6">
    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..."
                       class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-40">
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div class="w-48">
                <label class="block text-sm font-medium text-slate-700 mb-2">Plan</label>
                <select name="plan" class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Plans</option>
                    @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded hover:bg-slate-800 transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'plan']))
            <a href="{{ route('admin.membership.subscribers') }}" class="px-4 py-2 border border-slate-300 text-slate-700 rounded hover:bg-slate-50 transition-colors">
                Clear
            </a>
            @endif
        </form>
    </div>

    {{-- Subscribers Table --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Subscriber</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Plan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold tracking-wider uppercase text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($subscriptions as $subscription)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $subscription->user->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <p class="font-medium text-slate-900">{{ $subscription->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $subscription->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium">{{ $subscription->plan->name }}</p>
                        <p class="text-xs text-slate-500">{{ ucfirst($subscription->plan->billing_cycle) }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $subscription->starts_at?->format('M d, Y') }} - {{ $subscription->ends_at?->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 font-medium">
                        ₹{{ number_format($subscription->amount_paid, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                            @if($subscription->status === 'active') bg-emerald-100 text-emerald-700
                            @elseif($subscription->status === 'cancelled') bg-red-100 text-red-700
                            @elseif($subscription->status === 'expired') bg-slate-100 text-slate-700
                            @else bg-amber-100 text-amber-700 @endif">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.membership.subscribers.show', $subscription) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        No subscribers found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($subscriptions->hasPages())
        <div class="p-6 border-t border-slate-200">
            {{ $subscriptions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
