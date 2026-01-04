@extends('layouts.admin')

@section('title', isset($coupon) ? 'Edit Coupon' : 'Create Coupon')

@section('content')
<div class="max-w-4xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.coupons.index') }}" class="inline-flex items-center gap-2 text-[12px] text-slate-500 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Coupons
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ isset($coupon) ? 'Edit Coupon' : 'Create Coupon' }}</h1>
        <p class="text-[12px] text-slate-500 mt-1">{{ isset($coupon) ? 'Update coupon details' : 'Add a new discount coupon' }}</p>
    </div>

    <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" method="POST">
        @csrf
        @if(isset($coupon))
            @method('PUT')
        @endif

        <!-- Basic Info -->
        <div class="bg-white border border-slate-200 p-6 mb-6">
            <h2 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-6">Basic Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Coupon Code *</label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" 
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors uppercase font-mono"
                           placeholder="SAVE20" required>
                    @error('code')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Coupon Name *</label>
                    <input type="text" name="name" value="{{ old('name', $coupon->name ?? '') }}" 
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           placeholder="Summer Sale 20% Off" required>
                    @error('name')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Description</label>
                    <textarea name="description" rows="2" 
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors resize-none"
                              placeholder="Optional description for internal use">{{ old('description', $coupon->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Discount Settings -->
        <div class="bg-white border border-slate-200 p-6 mb-6">
            <h2 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-6">Discount Settings</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Discount Type *</label>
                    <select name="type" id="discount-type" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                        <option value="percentage" {{ old('type', $coupon->type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('type', $coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Discount Value *</label>
                    <div class="relative">
                        <span id="value-prefix" class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] text-slate-500">%</span>
                        <input type="number" name="value" value="{{ old('value', $coupon->value ?? '') }}" 
                               class="w-full h-11 pl-10 pr-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors"
                               step="0.01" min="0" required>
                    </div>
                    @error('value')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Minimum Order Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] text-slate-500">₹</span>
                        <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount ?? 0) }}" 
                               class="w-full h-11 pl-10 pr-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors"
                               step="0.01" min="0">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Set to 0 for no minimum</p>
                </div>

                <div id="max-discount-field">
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Maximum Discount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] text-slate-500">₹</span>
                        <input type="number" name="max_discount" value="{{ old('max_discount', $coupon->max_discount ?? '') }}" 
                               class="w-full h-11 pl-10 pr-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors"
                               step="0.01" min="0">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Cap for percentage discounts (optional)</p>
                </div>
            </div>
        </div>

        <!-- Usage Limits -->
        <div class="bg-white border border-slate-200 p-6 mb-6">
            <h2 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-6">Usage Limits</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Total Usage Limit</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}" 
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           min="1" placeholder="Unlimited">
                    <p class="text-[11px] text-slate-400 mt-1">Leave empty for unlimited usage</p>
                </div>

                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Usage Limit Per User *</label>
                    <input type="number" name="usage_limit_per_user" value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user ?? 1) }}" 
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors"
                           min="1" required>
                </div>
            </div>
        </div>

        <!-- Validity Period -->
        <div class="bg-white border border-slate-200 p-6 mb-6">
            <h2 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-6">Validity Period</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Start Date</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at', isset($coupon) && $coupon->starts_at ? $coupon->starts_at->format('Y-m-d') : '') }}" 
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <p class="text-[11px] text-slate-400 mt-1">Leave empty to start immediately</p>
                </div>

                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Expiry Date</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at', isset($coupon) && $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" 
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <p class="text-[11px] text-slate-400 mt-1">Leave empty for no expiry</p>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white border border-slate-200 p-6 mb-6">
            <label class="flex items-center gap-4 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}
                       class="w-5 h-5 border-slate-300 text-slate-900 focus:ring-slate-900">
                <div>
                    <p class="text-[13px] font-medium text-slate-900">Active</p>
                    <p class="text-[11px] text-slate-500">Enable this coupon for use</p>
                </div>
            </label>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-3">
            <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                {{ isset($coupon) ? 'Update Coupon' : 'Create Coupon' }}
            </button>
            <a href="{{ route('admin.coupons.index') }}" class="h-11 px-8 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('discount-type');
    const valuePrefix = document.getElementById('value-prefix');
    const maxDiscountField = document.getElementById('max-discount-field');

    function updateUI() {
        if (typeSelect.value === 'percentage') {
            valuePrefix.textContent = '%';
            maxDiscountField.style.display = 'block';
        } else {
            valuePrefix.textContent = '₹';
            maxDiscountField.style.display = 'none';
        }
    }

    typeSelect.addEventListener('change', updateUI);
    updateUI();
});
</script>
@endsection