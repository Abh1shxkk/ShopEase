@extends('layouts.shop')

@section('title', 'Loyalty Points')

@section('content')
<div class="min-h-screen bg-slate-50 py-12 pt-24">
    <div class="max-w-5xl mx-auto px-4">
        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl font-serif tracking-wide text-slate-900">Loyalty Rewards</h1>
            <p class="text-slate-500 mt-2">Earn points on every purchase and redeem for discounts</p>
        </div>

        {{-- Points Overview Card --}}
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-8 mb-8">
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Current Points --}}
                <div class="text-center md:border-r md:border-white/20">
                    <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-400 mb-2">Available Points</p>
                    <p class="text-5xl font-light">{{ number_format($stats['current_points']) }}</p>
                    <p class="text-slate-400 text-sm mt-2">Worth ₹{{ number_format($stats['points_value'], 2) }}</p>
                </div>
                
                {{-- Tier Status --}}
                <div class="text-center md:border-r md:border-white/20">
                    <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-400 mb-2">Your Tier</p>
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-4xl">{{ $stats['tier']['icon'] }}</span>
                        <span class="text-3xl font-light">{{ $stats['tier']['name'] }}</span>
                    </div>
                    <p class="text-slate-400 text-sm mt-2">{{ $stats['tier']['multiplier'] }}x points multiplier</p>
                </div>
                
                {{-- Total Earned --}}
                <div class="text-center">
                    <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-400 mb-2">Lifetime Earned</p>
                    <p class="text-5xl font-light">{{ number_format($stats['total_earned']) }}</p>
                    <p class="text-slate-400 text-sm mt-2">{{ number_format($stats['total_redeemed']) }} redeemed</p>
                </div>
            </div>

            {{-- Tier Progress --}}
            @if($stats['tier']['next_tier'])
            <div class="mt-8 pt-6 border-t border-white/20">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-slate-400">Progress to {{ $stats['tier']['next_tier'] }}</span>
                    <span class="text-white">{{ number_format($stats['tier']['points_to_next']) }} points to go</span>
                </div>
                <div class="h-2 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-500" style="width: {{ $tierProgress }}%"></div>
                </div>
            </div>
            @else
            <div class="mt-8 pt-6 border-t border-white/20 text-center">
                <p class="text-amber-400 font-medium">🎉 You've reached the highest tier!</p>
            </div>
            @endif
        </div>

        {{-- Quick Actions --}}
        <div class="grid md:grid-cols-2 gap-4 mb-8">
            <a href="{{ route('shop.index') }}" class="bg-white border border-slate-200 p-6 flex items-center gap-4 hover:border-slate-900 transition-colors group">
                <div class="w-12 h-12 bg-slate-100 flex items-center justify-center group-hover:bg-slate-900 transition-colors">
                    <svg class="w-6 h-6 text-slate-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-900">Shop Now</p>
                    <p class="text-[12px] text-slate-500">Earn {{ $settings['points_per_rupee'] }} point per ₹1 spent</p>
                </div>
            </a>
            
            @if($stats['can_redeem'])
            <a href="{{ route('cart') }}" class="bg-white border border-slate-200 p-6 flex items-center gap-4 hover:border-slate-900 transition-colors group">
                <div class="w-12 h-12 bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-600 transition-colors">
                    <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-900">Redeem Points</p>
                    <p class="text-[12px] text-slate-500">Use points at checkout for discounts</p>
                </div>
            </a>
            @else
            <div class="bg-slate-100 border border-slate-200 p-6 flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-500">Redeem Points</p>
                    <p class="text-[12px] text-slate-400">Earn {{ $stats['min_to_redeem'] - $stats['current_points'] }} more points to unlock</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Tier Benefits --}}
        <div class="bg-white border border-slate-200 mb-8">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Tier Benefits</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Tier</th>
                            <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Points Required</th>
                            <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Points Multiplier</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $tiers = [
                                ['name' => 'Bronze', 'icon' => '🥉', 'min' => 0, 'mult' => $settings['tier_bronze_multiplier']],
                                ['name' => 'Silver', 'icon' => '🥈', 'min' => $settings['tier_silver_min'], 'mult' => $settings['tier_silver_multiplier']],
                                ['name' => 'Gold', 'icon' => '🥇', 'min' => $settings['tier_gold_min'], 'mult' => $settings['tier_gold_multiplier']],
                                ['name' => 'Platinum', 'icon' => '💎', 'min' => $settings['tier_platinum_min'], 'mult' => $settings['tier_platinum_multiplier']],
                            ];
                        @endphp
                        @foreach($tiers as $tier)
                        <tr class="{{ $stats['tier']['name'] === $tier['name'] ? 'bg-amber-50' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">{{ $tier['icon'] }}</span>
                                    <span class="text-[13px] font-medium text-slate-900">{{ $tier['name'] }}</span>
                                    @if($stats['tier']['name'] === $tier['name'])
                                    <span class="px-2 py-0.5 bg-amber-200 text-amber-800 text-[9px] font-bold tracking-wider uppercase">Current</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-[13px] text-slate-600">{{ number_format($tier['min']) }}+</td>
                            <td class="px-6 py-4 text-center text-[13px] font-semibold text-slate-900">{{ $tier['mult'] }}x</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Earning Opportunities --}}
        <div class="bg-white border border-slate-200 mb-8">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Ways to Earn</h3>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                @foreach($opportunities as $opp)
                <div class="p-6 text-center">
                    <span class="text-3xl mb-3 block">{{ $opp['icon'] }}</span>
                    <h4 class="font-medium text-slate-900 mb-1">{{ $opp['title'] }}</h4>
                    <p class="text-[11px] text-slate-500 mb-2">{{ $opp['description'] }}</p>
                    <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 text-[11px] font-bold">{{ $opp['points'] }} pts</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Points History --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Points History</h3>
                <div class="flex gap-2">
                    <select id="filter-type" class="h-8 px-3 bg-white border border-slate-200 text-[11px] focus:outline-none">
                        <option value="">All Types</option>
                        <option value="earned">Earned</option>
                        <option value="redeemed">Redeemed</option>
                        <option value="adjusted">Adjusted</option>
                    </select>
                </div>
            </div>
            <div class="divide-y divide-slate-100" id="transactions-list">
                @forelse($transactions as $transaction)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 flex items-center justify-center {{ $transaction->points > 0 ? 'bg-emerald-100' : 'bg-red-100' }}">
                            @if($transaction->points > 0)
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            @else
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-[13px] text-slate-900">{{ $transaction->description ?? ucfirst($transaction->source) }}</p>
                            <p class="text-[11px] text-slate-500">{{ $transaction->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[15px] font-semibold {{ $transaction->points > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                        </p>
                        <p class="text-[10px] text-slate-400">Balance: {{ number_format($transaction->balance_after) }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-slate-500">No transactions yet</p>
                    <p class="text-[12px] text-slate-400 mt-1">Start shopping to earn points!</p>
                </div>
                @endforelse
            </div>
            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>

        {{-- How Points Work --}}
        <div class="mt-8 bg-slate-100 border border-slate-200 p-8">
            <h3 class="text-xl font-serif text-slate-900 mb-6 text-center">How Points Work</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 bg-white flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl font-bold text-slate-900">1</span>
                    </div>
                    <h4 class="font-medium text-slate-900 mb-2">Earn Points</h4>
                    <p class="text-sm text-slate-500">Get {{ $settings['points_per_rupee'] }} point for every ₹1 you spend. Higher tiers earn more!</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-white flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl font-bold text-slate-900">2</span>
                    </div>
                    <h4 class="font-medium text-slate-900 mb-2">Collect Points</h4>
                    <p class="text-sm text-slate-500">Accumulate at least {{ $settings['min_points_to_redeem'] }} points to unlock redemption.</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-white flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl font-bold text-slate-900">3</span>
                    </div>
                    <h4 class="font-medium text-slate-900 mb-2">Redeem & Save</h4>
                    <p class="text-sm text-slate-500">Use points at checkout. 1 point = ₹{{ $settings['points_value_in_rupees'] }} discount.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
