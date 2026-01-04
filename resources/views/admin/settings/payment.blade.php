@extends('layouts.admin')

@section('title', 'Payment Settings')

@section('content')
<div class="max-w-3xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.settings.index') }}" class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-600 mb-2 inline-block">← Back to Settings</a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">Payment Settings</h1>
        <p class="text-[12px] text-slate-500 mt-1">Configure payment gateway, tax, and shipping settings</p>
    </div>

    <form action="{{ route('admin.settings.payment.update') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Currency Settings -->
        <div class="bg-white border border-slate-200 p-8">
            <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Currency Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Currency Code</label>
                    <select name="currency" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors bg-white appearance-none" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%23475569%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-position: right 1rem center; background-repeat: no-repeat; background-size: 1rem;">
                        <option value="INR" {{ $settings['currency'] == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                        <option value="USD" {{ $settings['currency'] == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ $settings['currency'] == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        <option value="GBP" {{ $settings['currency'] == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Currency Symbol</label>
                    <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol']) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="₹">
                    @error('currency_symbol')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Tax Settings -->
        <div class="bg-white border border-slate-200 p-8">
            <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Tax Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Tax Name</label>
                    <input type="text" name="tax_name" value="{{ old('tax_name', $settings['tax_name']) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="GST">
                    @error('tax_name')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" value="{{ old('tax_rate', $settings['tax_rate']) }}" step="0.01" min="0" max="100" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="18">
                    @error('tax_rate')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Shipping Settings -->
        <div class="bg-white border border-slate-200 p-8">
            <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Shipping Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Shipping Charge</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">{{ $settings['currency_symbol'] }}</span>
                        <input type="number" name="shipping_charge" value="{{ old('shipping_charge', $settings['shipping_charge']) }}" step="0.01" min="0" class="w-full h-12 pl-8 pr-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="50">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Free Shipping Above</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">{{ $settings['currency_symbol'] }}</span>
                        <input type="number" name="free_shipping_threshold" value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold']) }}" step="0.01" min="0" class="w-full h-12 pl-8 pr-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="999">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Set to 0 to disable free shipping</p>
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Min Order Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">{{ $settings['currency_symbol'] }}</span>
                        <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $settings['min_order_amount']) }}" step="0.01" min="0" class="w-full h-12 pl-8 pr-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white border border-slate-200 p-8">
            <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Payment Methods</h3>
            
            <!-- Razorpay -->
            <div class="border border-slate-100 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-[13px] font-medium text-slate-900">Razorpay</h4>
                            <p class="text-[11px] text-slate-500">Accept cards, UPI, wallets, and more</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="razorpay_enabled" value="1" {{ $settings['razorpay_enabled'] == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-checked:bg-slate-900 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Key ID</label>
                        <input type="text" name="razorpay_key_id" value="{{ old('razorpay_key_id', $settings['razorpay_key_id']) }}" class="w-full h-10 px-4 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="rzp_test_xxxxx">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Key Secret</label>
                        <input type="password" name="razorpay_key_secret" value="{{ old('razorpay_key_secret', $settings['razorpay_key_secret']) }}" class="w-full h-10 px-4 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="••••••••••••">
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-3">Get your API keys from <a href="https://dashboard.razorpay.com/app/keys" target="_blank" class="text-blue-600 hover:underline">Razorpay Dashboard</a></p>
            </div>

            <!-- Cash on Delivery -->
            <div class="border border-slate-100 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-[13px] font-medium text-slate-900">Cash on Delivery</h4>
                            <p class="text-[11px] text-slate-500">Allow customers to pay on delivery</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="cod_enabled" value="1" {{ $settings['cod_enabled'] == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-checked:bg-slate-900 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
            <button type="submit" class="h-12 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Save Settings</button>
        </div>
    </form>
</div>
@endsection
