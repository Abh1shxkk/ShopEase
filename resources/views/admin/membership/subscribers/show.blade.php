@extends('layouts.admin')

@section('title', 'Subscriber Details')
@section('page-title', 'Subscriber Details')

@section('content')
<div class="space-y-6">
    {{-- Back Button --}}
    <div>
        <a href="{{ route('admin.membership.subscribers') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Subscribers
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- User Info --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Subscriber Info</h3>
            <div class="flex items-center gap-4 mb-6">
                <img src="{{ $subscription->user->avatar_url }}" alt="" class="w-16 h-16 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-slate-900">{{ $subscription->user->name }}</p>
                    <p class="text-sm text-slate-500">{{ $subscription->user->email }}</p>
                    @if($subscription->user->phone)
                    <p class="text-sm text-slate-500">{{ $subscription->user->phone }}</p>
                    @endif
                </div>
            </div>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Member Since</span>
                    <span class="font-medium">{{ $subscription->user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Total Orders</span>
                    <span class="font-medium">{{ $subscription->user->orders()->count() }}</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('admin.users.show', $subscription->user) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    View Full Profile →
                </a>
            </div>
        </div>

        {{-- Subscription Details --}}
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Subscription Details</h3>
                <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full
                    @if($subscription->status === 'active') bg-emerald-100 text-emerald-700
                    @elseif($subscription->status === 'cancelled') bg-red-100 text-red-700
                    @elseif($subscription->status === 'expired') bg-slate-100 text-slate-700
                    @else bg-amber-100 text-amber-700 @endif">
                    {{ ucfirst($subscription->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Plan</p>
                    <p class="font-semibold text-slate-900">{{ $subscription->plan->name }}</p>
                    <p class="text-sm text-slate-500">{{ ucfirst($subscription->plan->billing_cycle) }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">Amount Paid</p>
                    <p class="font-semibold text-slate-900">₹{{ number_format($subscription->amount_paid, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">Start Date</p>
                    <p class="font-medium text-slate-900">{{ $subscription->starts_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">End Date</p>
                    <p class="font-medium text-slate-900">{{ $subscription->ends_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">Auto Renew</p>
                    <p class="font-medium {{ $subscription->auto_renew ? 'text-emerald-600' : 'text-slate-600' }}">
                        {{ $subscription->auto_renew ? 'Enabled' : 'Disabled' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">Payment Method</p>
                    <p class="font-medium text-slate-900">{{ ucfirst($subscription->payment_method ?? 'N/A') }}</p>
                </div>
            </div>

            @if($subscription->status === 'active' && $subscription->days_remaining <= 7)
            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                <p class="text-sm text-amber-800">
                    <span class="font-medium">Expiring Soon:</span> {{ $subscription->days_remaining }} days remaining
                </p>
            </div>
            @endif

            @if($subscription->cancelled_at)
            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800">
                    <span class="font-medium">Cancelled:</span> {{ $subscription->cancelled_at->format('M d, Y h:i A') }}
                </p>
                @if($subscription->cancellation_reason)
                <p class="text-sm text-red-700 mt-1">Reason: {{ $subscription->cancellation_reason }}</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Plan Benefits --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Plan Benefits</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-slate-50 rounded-lg text-center">
                <div class="w-10 h-10 mx-auto mb-2 rounded-full flex items-center justify-center {{ $subscription->plan->free_shipping ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-900">Free Shipping</p>
                <p class="text-xs text-slate-500">{{ $subscription->plan->free_shipping ? 'Included' : 'Not included' }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-lg text-center">
                <div class="w-10 h-10 mx-auto mb-2 rounded-full flex items-center justify-center {{ $subscription->plan->discount_percentage > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-900">Member Discount</p>
                <p class="text-xs text-slate-500">{{ $subscription->plan->discount_percentage > 0 ? $subscription->plan->discount_percentage . '%' : 'None' }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-lg text-center">
                <div class="w-10 h-10 mx-auto mb-2 rounded-full flex items-center justify-center {{ $subscription->plan->early_access_days > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-900">Early Access</p>
                <p class="text-xs text-slate-500">{{ $subscription->plan->early_access_days > 0 ? $subscription->plan->early_access_days . ' days' : 'None' }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-lg text-center">
                <div class="w-10 h-10 mx-auto mb-2 rounded-full flex items-center justify-center {{ $subscription->plan->priority_support ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-900">Priority Support</p>
                <p class="text-xs text-slate-500">{{ $subscription->plan->priority_support ? 'Included' : 'Not included' }}</p>
            </div>
        </div>
    </div>

    {{-- Payment History --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Payment History</h3>
        </div>
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Transaction ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($subscription->payments as $payment)
                <tr>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $payment->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 font-medium">
                        ₹{{ number_format($payment->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ ucfirst($payment->payment_method ?? 'N/A') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-mono">
                        {{ $payment->razorpay_payment_id ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                            @if($payment->status === 'completed') bg-emerald-100 text-emerald-700
                            @elseif($payment->status === 'failed') bg-red-100 text-red-700
                            @elseif($payment->status === 'refunded') bg-amber-100 text-amber-700
                            @else bg-slate-100 text-slate-700 @endif">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                        No payment records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
