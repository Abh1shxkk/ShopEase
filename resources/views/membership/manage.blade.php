@extends('layouts.shop')

@section('title', 'Manage Membership')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-12">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('profile') }}" class="hover:text-slate-900 transition-colors">Account</a>
        <span>/</span>
        <span class="text-slate-900">Membership</span>
    </nav>

    <h1 class="text-3xl font-serif mb-8">Manage Membership</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- Current Subscription --}}
    @if($currentSubscription)
    <div class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 p-8 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-amber-500 text-white rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-amber-900">{{ $currentSubscription->plan->name }}</h2>
                    <p class="text-amber-700 text-sm mt-1">
                        @if($currentSubscription->status === 'active')
                            Active until {{ $currentSubscription->ends_at->format('M d, Y') }}
                            @if($currentSubscription->is_expiring_soon)
                                <span class="text-red-600 font-medium">(Expiring soon!)</span>
                            @endif
                        @else
                            Status: {{ ucfirst($currentSubscription->status) }}
                        @endif
                    </p>
                    <div class="flex items-center gap-4 mt-3 text-sm">
                        <span class="text-amber-600">{{ $currentSubscription->days_remaining }} days remaining</span>
                        <span class="text-amber-400">•</span>
                        <span class="text-amber-600">Auto-renew: {{ $currentSubscription->auto_renew ? 'On' : 'Off' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <a href="{{ route('membership.index') }}" class="px-6 py-2 bg-amber-600 text-white text-sm font-medium text-center hover:bg-amber-700 transition-colors">
                    Upgrade Plan
                </a>
                <form action="{{ route('membership.toggle-auto-renew', $currentSubscription) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-6 py-2 border border-amber-300 text-amber-700 text-sm font-medium hover:bg-amber-200 transition-colors">
                        {{ $currentSubscription->auto_renew ? 'Disable' : 'Enable' }} Auto-Renew
                    </button>
                </form>
            </div>
        </div>

        {{-- Benefits --}}
        <div class="mt-6 pt-6 border-t border-amber-200">
            <h3 class="text-sm font-medium text-amber-800 mb-3">Your Benefits</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if($currentSubscription->plan->free_shipping)
                <div class="flex items-center gap-2 text-sm text-amber-700">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Free Shipping
                </div>
                @endif
                @if($currentSubscription->plan->discount_percentage > 0)
                <div class="flex items-center gap-2 text-sm text-amber-700">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $currentSubscription->plan->discount_percentage }}% Discount
                </div>
                @endif
                @if($currentSubscription->plan->early_access_days > 0)
                <div class="flex items-center gap-2 text-sm text-amber-700">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Early Access
                </div>
                @endif
                @if($currentSubscription->plan->priority_support)
                <div class="flex items-center gap-2 text-sm text-amber-700">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Priority Support
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Cancel Subscription --}}
    @if($currentSubscription->status === 'active')
    <div x-data="{ showCancel: false }" class="mb-8">
        <button @click="showCancel = !showCancel" class="text-sm text-red-600 hover:text-red-700">
            Cancel Subscription
        </button>
        
        <div x-show="showCancel" x-cloak class="mt-4 p-6 bg-red-50 border border-red-200">
            <h3 class="font-medium text-red-800 mb-2">Are you sure you want to cancel?</h3>
            <p class="text-sm text-red-600 mb-4">
                You'll continue to have access until {{ $currentSubscription->ends_at->format('M d, Y') }}, but your membership won't renew.
            </p>
            <form action="{{ route('membership.cancel', $currentSubscription) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-red-700 mb-1">Reason for cancellation (optional)</label>
                    <textarea name="reason" rows="2" class="w-full px-3 py-2 border border-red-200 text-sm focus:outline-none focus:ring-1 focus:ring-red-500"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition-colors">
                        Confirm Cancellation
                    </button>
                    <button type="button" @click="showCancel = false" class="px-4 py-2 border border-red-200 text-red-700 text-sm font-medium hover:bg-red-100 transition-colors">
                        Keep Membership
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @else
    {{-- No Active Subscription --}}
    <div class="bg-slate-50 p-8 text-center mb-8">
        <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
        </div>
        <h2 class="text-xl font-serif mb-2">No Active Membership</h2>
        <p class="text-slate-600 mb-6">Join our premium membership to unlock exclusive benefits.</p>
        <a href="{{ route('membership.index') }}" class="inline-block px-8 py-3 bg-slate-900 text-white font-medium hover:bg-slate-800 transition-colors">
            View Plans
        </a>
    </div>
    @endif

    {{-- Subscription History --}}
    <div class="mb-8">
        <h2 class="text-xl font-serif mb-6">Subscription History</h2>
        
        @if($subscriptionHistory->count() > 0)
        <div class="bg-white border border-slate-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($subscriptionHistory as $subscription)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium">{{ $subscription->plan->name }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $subscription->starts_at?->format('M d, Y') }} - {{ $subscription->ends_at?->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">₹{{ number_format($subscription->amount_paid, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                @if($subscription->status === 'active') bg-emerald-100 text-emerald-700
                                @elseif($subscription->status === 'cancelled') bg-red-100 text-red-700
                                @elseif($subscription->status === 'expired') bg-slate-100 text-slate-700
                                @else bg-amber-100 text-amber-700 @endif">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $subscriptionHistory->links() }}
        @else
        <p class="text-slate-500 text-sm">No subscription history found.</p>
        @endif
    </div>

    {{-- Payment History --}}
    <div>
        <h2 class="text-xl font-serif mb-6">Payment History</h2>
        
        @if($paymentHistory->count() > 0)
        <div class="bg-white border border-slate-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($paymentHistory as $payment)
                    <tr>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $payment->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm font-medium">{{ $payment->subscription->plan->name ?? 'Membership' }}</td>
                        <td class="px-6 py-4 text-sm">₹{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                @if($payment->status === 'completed') bg-emerald-100 text-emerald-700
                                @elseif($payment->status === 'failed') bg-red-100 text-red-700
                                @else bg-amber-100 text-amber-700 @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $paymentHistory->links() }}
        @else
        <p class="text-slate-500 text-sm">No payment history found.</p>
        @endif
    </div>
</div>
@endsection
