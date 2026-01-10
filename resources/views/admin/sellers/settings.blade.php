@extends('layouts.admin')

@section('title', 'Seller Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-serif text-slate-900">Seller Settings</h1>
        <p class="text-[13px] text-slate-500 mt-1">Configure marketplace seller settings</p>
    </div>

    <form action="{{ route('admin.sellers.settings.update') }}" method="POST" class="bg-white border border-slate-200">
        @csrf
        
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[12px] font-medium text-slate-700 mb-2">Default Commission Rate (%)</label>
                    <input type="number" name="default_commission_rate" value="{{ old('default_commission_rate', $settings->default_commission_rate) }}" step="0.01" min="0" max="100"
                        class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                    <p class="text-[10px] text-slate-500 mt-1">Applied to new sellers</p>
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-slate-700 mb-2">Minimum Payout Amount (₹)</label>
                    <input type="number" name="minimum_payout_amount" value="{{ old('minimum_payout_amount', $settings->minimum_payout_amount) }}" step="0.01" min="0"
                        class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                </div>

                <div>
                    <label class="block text-[12px] font-medium text-slate-700 mb-2">Payout Frequency (Days)</label>
                    <input type="number" name="payout_frequency_days" value="{{ old('payout_frequency_days', $settings->payout_frequency_days) }}" min="1"
                        class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                </div>
            </div>

            <div class="space-y-4">
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="auto_approve_sellers" value="1" {{ $settings->auto_approve_sellers ? 'checked' : '' }}
                        class="w-4 h-4 text-slate-900 border-slate-300 focus:ring-slate-900">
                    <span class="text-[13px] text-slate-700">Auto-approve new sellers</span>
                </label>

                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="auto_approve_products" value="1" {{ $settings->auto_approve_products ? 'checked' : '' }}
                        class="w-4 h-4 text-slate-900 border-slate-300 focus:ring-slate-900">
                    <span class="text-[13px] text-slate-700">Auto-approve seller products</span>
                </label>
            </div>

            <div>
                <label class="block text-[12px] font-medium text-slate-700 mb-2">Seller Terms & Conditions</label>
                <textarea name="seller_terms" rows="6"
                    class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">{{ old('seller_terms', $settings->seller_terms) }}</textarea>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white text-[12px] font-medium hover:bg-slate-800">Save Settings</button>
        </div>
    </form>
</div>
@endsection
