@extends('layouts.admin')

@section('title', 'Subscriber Details')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.membership.subscribers') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Subscribers
            </a>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ $subscription->user->name ?? 'Unknown User' }}</h1>
            <p class="text-[12px] text-slate-500 mt-1">{{ $subscription->user->email ?? '' }}</p>
        </div>
        @php
            $statusConfig = [
                'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'expired' => 'bg-amber-50 text-amber-700 border-amber-200',
                'cancelled' => 'bg-red-50 text-red-700 border-red-200',
            ];
        @endphp
        <span class="inline-flex px-3 py-1.5 text-[10px] font-bold tracking-[0.1em] uppercase border {{ $statusConfig[$subscription->status] ?? 'bg-slate-50 text-slate-700 border-slate-200' }}">
            {{ ucfirst($subscription->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Subscription Details --}}
            <div class="bg-white border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Subscription Details</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-1">Plan</p>
                            <p class="text-[14px] font-medium text-slate-900">{{ $subscription->plan->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-1">Price</p>
                            <p class="text-[14px] font-medium text-slate-900">₹{{ number_format($subscription->plan->price ?? 0) }}/{{ $subscription->plan->billing_cycle ?? 'month' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-1">Started</p>
                            <p class="text-[14px] text-slate-900">{{ $subscription->starts_at ? $subscription->starts_at->format('M d, Y') : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-1">Expires</p>
                            <p class="text-[14px] text-slate-900">{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-1">Auto Renew</p>
                            <p class="text-[14px] text-slate-900">{{ $subscription->auto_renew ? 'Yes' : 'No' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-1">Member Discount</p>
                            <p class="text-[14px] text-slate-900">{{ $subscription->plan->discount_percentage ?? 0 }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment History --}}
            <div class="bg-white border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Payment History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50">
                                <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Date</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Amount</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Method</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($subscription->payments ?? [] as $payment)
                            <tr>
                                <td class="px-6 py-3 text-[12px] text-slate-900">{{ $payment->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-3 text-[12px] text-slate-900">₹{{ number_format($payment->amount) }}</td>
                                <td class="px-6 py-3 text-[12px] text-slate-500">{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-medium {{ $payment->status === 'completed' ? 'text-emerald-700' : 'text-amber-700' }}">
                                        <span class="w-1.5 h-1.5 {{ $payment->status === 'completed' ? 'bg-emerald-500' : 'bg-amber-500' }} rounded-full"></span>
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-[13px] text-slate-500">No payment history</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Member Info --}}
            <div class="bg-white border border-slate-200 p-6">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-6">Member Info</h3>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-xl font-medium text-slate-600">
                        {{ substr($subscription->user->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[14px] font-medium text-slate-900">{{ $subscription->user->name ?? 'Unknown' }}</p>
                        <p class="text-[12px] text-slate-500">{{ $subscription->user->email ?? '' }}</p>
                    </div>
                </div>
                <div class="space-y-3 text-[13px]">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Member Since</span>
                        <span class="text-slate-900">{{ $subscription->user->created_at ? $subscription->user->created_at->format('M Y') : '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Total Orders</span>
                        <span class="text-slate-900">{{ $subscription->user->orders->count() ?? 0 }}</span>
                    </div>
                </div>
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.users.show', $subscription->user) }}" class="text-[11px] font-bold tracking-[0.15em] uppercase text-blue-600 hover:text-blue-700">
                        View Full Profile →
                    </a>
                </div>
            </div>

            {{-- Plan Features --}}
            @if($subscription->plan && $subscription->plan->features && is_array($subscription->plan->features))
            <div class="bg-white border border-slate-200 p-6">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-4">Plan Features</h3>
                <ul class="space-y-2">
                    @foreach($subscription->plan->features as $feature)
                    <li class="flex items-center gap-2 text-[13px] text-slate-600">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
