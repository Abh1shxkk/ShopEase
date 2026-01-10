@extends('layouts.shop')

@section('title', 'Become a Seller')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-white">
    {{-- Hero Section --}}
    <div class="relative bg-slate-900 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="max-w-6xl mx-auto px-6 text-center relative">
            <div class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-300 px-4 py-2 text-xs font-bold tracking-wider uppercase mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Seller Program
            </div>
            <h1 class="text-4xl md:text-5xl font-serif mb-6">Become a Seller</h1>
            <p class="text-slate-300 max-w-2xl mx-auto text-lg">Start selling your products on ShopEase marketplace and reach millions of customers.</p>
        </div>
    </div>

    {{-- Benefits Overview --}}
    <div class="max-w-6xl mx-auto px-6 -mt-10 relative z-10">
        <div class="grid md:grid-cols-3 gap-4">
            <div class="bg-white p-6 shadow-lg border border-slate-100 text-center">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold mb-1">Reach Millions</h3>
                <p class="text-sm text-slate-500">Access our large customer base</p>
            </div>
            <div class="bg-white p-6 shadow-lg border border-slate-100 text-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold mb-1">Low Commission</h3>
                <p class="text-sm text-slate-500">Competitive rates, more profit</p>
            </div>
            <div class="bg-white p-6 shadow-lg border border-slate-100 text-center">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-semibold mb-1">Secure Payments</h3>
                <p class="text-sm text-slate-500">Fast & reliable payouts</p>
            </div>
        </div>
    </div>

    {{-- Registration Form --}}
    <div class="max-w-3xl mx-auto px-6 py-16">
        <form action="{{ route('seller.register.store') }}" method="POST" class="bg-white shadow-lg border border-slate-200 overflow-hidden">
            @csrf
            
            {{-- Store Information --}}
            <div class="p-8 border-b border-slate-200">
                <h2 class="text-xl font-serif text-slate-900 mb-6">Store Information</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Store Name *</label>
                        <input type="text" name="store_name" value="{{ old('store_name') }}" required
                            placeholder="Your store name"
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition @error('store_name') border-red-500 @enderror">
                        @error('store_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-slate-500">This will be displayed to customers</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Store Description</label>
                        <textarea name="store_description" rows="3" placeholder="Tell customers about your store..."
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">{{ old('store_description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Business Details --}}
            <div class="p-8 border-b border-slate-200">
                <h2 class="text-xl font-serif text-slate-900 mb-6">Business Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Business/Legal Name</label>
                        <input type="text" name="business_name" value="{{ old('business_name') }}"
                            placeholder="Registered business name"
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Business Email *</label>
                        <input type="email" name="business_email" value="{{ old('business_email', auth()->user()->email) }}" required
                            placeholder="business@example.com"
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition @error('business_email') border-red-500 @enderror">
                        @error('business_email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Business Phone *</label>
                        <input type="tel" name="business_phone" value="{{ old('business_phone', auth()->user()->phone) }}" required
                            placeholder="+91 9876543210"
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition @error('business_phone') border-red-500 @enderror">
                        @error('business_phone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">GST Number</label>
                        <input type="text" name="gst_number" value="{{ old('gst_number') }}"
                            placeholder="22AAAAA0000A1Z5"
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                        <p class="mt-1 text-xs text-slate-500">Optional, but required for GST invoicing</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Business Address</label>
                        <textarea name="business_address" rows="2" placeholder="Full business address"
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">{{ old('business_address') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Bank Details --}}
            <div class="p-8 border-b border-slate-200">
                <h2 class="text-xl font-serif text-slate-900 mb-2">Bank Details</h2>
                <p class="text-sm text-slate-500 mb-6">Required for receiving payouts (can be added later)</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                            placeholder="e.g., State Bank of India"
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Account Holder Name</label>
                        <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder') }}"
                            placeholder="Name as per bank records"
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Account Number</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                            placeholder="Your bank account number"
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">IFSC Code</label>
                        <input type="text" name="bank_ifsc_code" value="{{ old('bank_ifsc_code') }}"
                            placeholder="e.g., SBIN0001234"
                            class="w-full px-4 py-3 border border-slate-300 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                    </div>
                </div>
            </div>

            {{-- Terms & Submit --}}
            <div class="p-8 bg-slate-50">
                <div class="mb-6">
                    <label class="flex items-start space-x-3">
                        <input type="checkbox" name="terms" required class="mt-1 w-4 h-4 text-slate-900 border-slate-300 rounded focus:ring-slate-900 @error('terms') border-red-500 @enderror">
                        <span class="text-sm text-slate-600">
                            I agree to the <a href="#" class="text-slate-900 underline hover:no-underline">Seller Terms & Conditions</a> 
                            and <a href="#" class="text-slate-900 underline hover:no-underline">Marketplace Policies</a>. 
                            I understand that my application will be reviewed before approval.
                        </span>
                    </label>
                    @error('terms')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-slate-600 hover:text-slate-900 transition">
                        ← Back to Home
                    </a>
                    <button type="submit" class="px-8 py-3 bg-slate-900 text-white font-medium hover:bg-slate-800 transition">
                        Submit Application
                    </button>
                </div>
            </div>
        </form>

        {{-- Already a seller? --}}
        <p class="text-center mt-8 text-slate-600">
            Already have a seller account? 
            <a href="{{ route('seller.dashboard') }}" class="text-slate-900 underline hover:no-underline">Go to Dashboard</a>
        </p>
    </div>
</div>
@endsection
