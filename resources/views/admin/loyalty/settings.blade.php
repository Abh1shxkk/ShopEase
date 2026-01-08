@extends('layouts.admin')

@section('title', 'Loyalty Settings')

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
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Loyalty Settings</h1>
            <p class="text-[12px] text-slate-500 mt-1">Configure points earning, redemption, and tier rules</p>
        </div>
        <form method="POST" action="{{ route('admin.loyalty.toggle') }}">
            @csrf
            <button type="submit" class="h-10 px-5 {{ $settings['loyalty_enabled'] ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-slate-400 hover:bg-slate-500' }} text-white text-[11px] font-bold tracking-[0.15em] uppercase transition-colors">
                {{ $settings['loyalty_enabled'] ? '✓ Program Active' : 'Program Disabled' }}
            </button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.loyalty.settings.update') }}" class="space-y-8">
        @csrf

        {{-- Points Earning --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Points Earning</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Points Per Rupee Spent</label>
                    <input type="number" step="0.1" name="points_per_rupee" value="{{ $settings['points_per_rupee'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Base points earned per ₹1 spent</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Max Points Per Order</label>
                    <input type="number" name="max_points_per_order" value="{{ $settings['max_points_per_order'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Maximum points that can be earned per order</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Signup Bonus Points</label>
                    <input type="number" name="signup_bonus_points" value="{{ $settings['signup_bonus_points'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Points awarded on new user registration</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Review Bonus Points</label>
                    <input type="number" name="review_bonus_points" value="{{ $settings['review_bonus_points'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Points awarded for writing a product review</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Birthday Bonus Points</label>
                    <input type="number" name="birthday_bonus_points" value="{{ $settings['birthday_bonus_points'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Points awarded on user's birthday</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Points Expiry (Days)</label>
                    <input type="number" name="points_expiry_days" value="{{ $settings['points_expiry_days'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Days before unused points expire</p>
                </div>
            </div>
        </div>

        {{-- Points Redemption --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Points Redemption</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Points Value (₹ per point)</label>
                    <input type="number" step="0.01" name="points_value_in_rupees" value="{{ $settings['points_value_in_rupees'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Rupee value when redeeming points</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Min Points to Redeem</label>
                    <input type="number" name="min_points_to_redeem" value="{{ $settings['min_points_to_redeem'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Minimum points required for redemption</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Max Redemption % Per Order</label>
                    <input type="number" name="max_redemption_percent" value="{{ $settings['max_redemption_percent'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Max % of order that can be paid with points</p>
                </div>
            </div>
        </div>

        {{-- Tier Settings --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Tier Settings</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Tier</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Min Points Required</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Points Multiplier</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl">🥉</span>
                                        <span class="text-[13px] font-medium text-slate-900">Bronze</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" value="0" disabled class="w-32 h-10 px-3 bg-slate-100 border border-slate-200 text-[13px] text-slate-500">
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" step="0.1" name="tier_bronze_multiplier" value="{{ $settings['tier_bronze_multiplier'] }}" class="w-32 h-10 px-3 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl">🥈</span>
                                        <span class="text-[13px] font-medium text-slate-900">Silver</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" name="tier_silver_min" value="{{ $settings['tier_silver_min'] }}" class="w-32 h-10 px-3 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" step="0.1" name="tier_silver_multiplier" value="{{ $settings['tier_silver_multiplier'] }}" class="w-32 h-10 px-3 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl">🥇</span>
                                        <span class="text-[13px] font-medium text-slate-900">Gold</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" name="tier_gold_min" value="{{ $settings['tier_gold_min'] }}" class="w-32 h-10 px-3 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" step="0.1" name="tier_gold_multiplier" value="{{ $settings['tier_gold_multiplier'] }}" class="w-32 h-10 px-3 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl">💎</span>
                                        <span class="text-[13px] font-medium text-slate-900">Platinum</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" name="tier_platinum_min" value="{{ $settings['tier_platinum_min'] }}" class="w-32 h-10 px-3 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                                </td>
                                <td class="px-4 py-4">
                                    <input type="number" step="0.1" name="tier_platinum_multiplier" value="{{ $settings['tier_platinum_multiplier'] }}" class="w-32 h-10 px-3 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
