@extends('layouts.shop')

@section('title', 'Welcome to Premium!')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-6 py-16">
    <div class="max-w-lg text-center">
        {{-- Success Animation --}}
        <div class="w-24 h-24 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce">
            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        </div>

        <h1 class="text-3xl font-serif mb-4">Welcome to Premium!</h1>
        <p class="text-slate-600 mb-8">
            Congratulations! You're now a {{ $subscription->plan->name }} member. Your exclusive benefits are now active.
        </p>

        {{-- Subscription Details --}}
        <div class="bg-slate-50 p-6 mb-8 text-left">
            <h3 class="font-medium mb-4">Your Membership Details</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Plan</span>
                    <span class="font-medium">{{ $subscription->plan->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Started</span>
                    <span>{{ $subscription->starts_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Expires</span>
                    <span>{{ $subscription->ends_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Amount Paid</span>
                    <span class="font-medium">₹{{ number_format($subscription->amount_paid, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Benefits Reminder --}}
        <div class="bg-emerald-50 border border-emerald-200 p-6 mb-8 text-left">
            <h3 class="font-medium text-emerald-800 mb-3">Your Benefits</h3>
            <ul class="space-y-2 text-sm text-emerald-700">
                @if($subscription->plan->free_shipping)
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Free shipping on all orders
                </li>
                @endif
                @if($subscription->plan->discount_percentage > 0)
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $subscription->plan->discount_percentage }}% off on all purchases
                </li>
                @endif
                @if($subscription->plan->early_access_days > 0)
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $subscription->plan->early_access_days }}-day early access to sales
                </li>
                @endif
                @if($subscription->plan->priority_support)
                <li class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Priority customer support
                </li>
                @endif
            </ul>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop.index') }}" class="px-8 py-3 bg-slate-900 text-white font-medium hover:bg-slate-800 transition-colors">
                Start Shopping
            </a>
            <a href="{{ route('membership.manage') }}" class="px-8 py-3 border border-slate-200 font-medium hover:bg-slate-50 transition-colors">
                Manage Membership
            </a>
        </div>
    </div>
</div>
@endsection
