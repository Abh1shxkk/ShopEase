@extends('layouts.admin')

@section('title', 'Referral Settings')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.referrals.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Referrals
            </a>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Referral Settings</h1>
            <p class="text-[12px] text-slate-500 mt-1">Configure referral program rewards and rules</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.referrals.settings.update') }}" class="space-y-8">
        @csrf

        {{-- Enable/Disable --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Program Status</h3>
                    <p class="text-[13px] text-slate-600">Enable or disable the referral program</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_enabled" value="0">
                    <input type="checkbox" name="is_enabled" value="1" {{ $settings['is_enabled'] ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-checked:bg-emerald-600 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                </label>
            </div>
        </div>

        {{-- Reward Points --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Reward Points</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Referrer Reward Points</label>
                    <input type="number" name="referrer_reward_points" value="{{ $settings['referrer_reward_points'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Points given to the person who refers</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Referred User Reward Points</label>
                    <input type="number" name="referred_reward_points" value="{{ $settings['referred_reward_points'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Signup bonus for new user using referral code</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Min Order for Completion (₹)</label>
                    <input type="number" name="min_order_for_completion" value="{{ $settings['min_order_for_completion'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Minimum order value to complete referral</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Referral Expiry (Days)</label>
                    <input type="number" name="referral_expiry_days" value="{{ $settings['referral_expiry_days'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Days before pending referral expires</p>
                </div>
            </div>
        </div>

        {{-- Points Earning & Redemption --}}
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Points Earning & Redemption</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Points Per Rupee Spent</label>
                    <input type="number" step="0.01" name="points_per_rupee" value="{{ $settings['points_per_rupee'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Points earned per rupee on orders</p>
                </div>
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
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Max Points Per Order</label>
                    <input type="number" name="max_points_per_order" value="{{ $settings['max_points_per_order'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Maximum points that can be earned per order</p>
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
