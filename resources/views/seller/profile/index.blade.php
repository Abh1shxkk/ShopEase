@extends('layouts.seller')

@section('title', 'Store Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-serif text-slate-900">Store Settings</h1>
        <p class="text-[13px] text-slate-500 mt-1">Manage your store profile and bank details</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Store Profile -->
        <div class="lg:col-span-2">
            <form action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200">
                @csrf
                @method('PUT')
                
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-serif text-slate-900">Store Profile</h2>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Store Logo & Banner -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[12px] font-medium text-slate-700 mb-2">Store Logo</label>
                            <div class="flex items-center space-x-4">
                                <img src="{{ $seller->logo_url }}" alt="Store Logo" class="w-16 h-16 rounded-full object-cover border border-slate-200">
                                <input type="file" name="store_logo" accept="image/*" class="text-[12px] text-slate-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-slate-700 mb-2">Store Banner</label>
                            <input type="file" name="store_banner" accept="image/*" class="text-[12px] text-slate-500">
                            @if($seller->store_banner)<p class="text-[10px] text-slate-500 mt-1">Current banner uploaded</p>@endif
                        </div>
                    </div>

                    <!-- Store Name -->
                    <div>
                        <label class="block text-[12px] font-medium text-slate-700 mb-2">Store Name *</label>
                        <input type="text" name="store_name" value="{{ old('store_name', $seller->store_name) }}" required
                            class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900 @error('store_name') border-red-500 @enderror">
                        @error('store_name')<p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <!-- Store Description -->
                    <div>
                        <label class="block text-[12px] font-medium text-slate-700 mb-2">Store Description</label>
                        <textarea name="store_description" rows="4" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">{{ old('store_description', $seller->store_description) }}</textarea>
                    </div>

                    <!-- Business Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[12px] font-medium text-slate-700 mb-2">Business Name</label>
                            <input type="text" name="business_name" value="{{ old('business_name', $seller->business_name) }}" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-slate-700 mb-2">Business Email *</label>
                            <input type="email" name="business_email" value="{{ old('business_email', $seller->business_email) }}" required class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[12px] font-medium text-slate-700 mb-2">Business Phone *</label>
                            <input type="tel" name="business_phone" value="{{ old('business_phone', $seller->business_phone) }}" required class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-slate-700 mb-2">GST Number</label>
                            <input type="text" name="gst_number" value="{{ old('gst_number', $seller->gst_number) }}" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[12px] font-medium text-slate-700 mb-2">Business Address</label>
                        <textarea name="business_address" rows="2" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">{{ old('business_address', $seller->business_address) }}</textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white text-[12px] font-medium hover:bg-slate-800">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Store Stats -->
            <div class="bg-white border border-slate-200 p-6">
                <h2 class="text-lg font-serif text-slate-900 mb-4">Store Stats</h2>
                <div class="space-y-3 text-[13px]">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Status</span>
                        <span class="px-2 py-1 text-[10px] font-medium {{ $seller->status === 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ ucfirst($seller->status) }}</span>
                    </div>
                    <div class="flex justify-between"><span class="text-slate-600">Commission Rate</span><span class="text-slate-900">{{ $seller->commission_rate }}%</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Total Products</span><span class="text-slate-900">{{ $seller->total_products }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Total Orders</span><span class="text-slate-900">{{ $seller->total_orders }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-600">Rating</span><span class="text-slate-900">{{ number_format($seller->average_rating, 1) }} ⭐</span></div>
                </div>
            </div>

            <!-- Bank Details -->
            <form action="{{ route('seller.profile.bank') }}" method="POST" class="bg-white border border-slate-200">
                @csrf
                @method('PUT')
                
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-serif text-slate-900">Bank Details</h2>
                    <p class="text-[11px] text-slate-500 mt-1">Required for payouts</p>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[12px] font-medium text-slate-700 mb-2">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $seller->bank_name) }}" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                    </div>
                    <div>
                        <label class="block text-[12px] font-medium text-slate-700 mb-2">Account Holder Name</label>
                        <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder', $seller->bank_account_holder) }}" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                    </div>
                    <div>
                        <label class="block text-[12px] font-medium text-slate-700 mb-2">Account Number</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $seller->bank_account_number) }}" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                    </div>
                    <div>
                        <label class="block text-[12px] font-medium text-slate-700 mb-2">IFSC Code</label>
                        <input type="text" name="bank_ifsc_code" value="{{ old('bank_ifsc_code', $seller->bank_ifsc_code) }}" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <button type="submit" class="w-full px-4 py-2.5 bg-slate-900 text-white text-[12px] font-medium hover:bg-slate-800">Update Bank Details</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
