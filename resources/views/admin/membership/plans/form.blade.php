@extends('layouts.admin')

@section('title', isset($plan) ? 'Edit Plan' : 'Create Plan')
@section('page-title', isset($plan) ? 'Edit Membership Plan' : 'Create Membership Plan')

@section('content')
<div class="max-w-3xl">
    <form action="{{ isset($plan) ? route('admin.membership.plans.update', $plan) : route('admin.membership.plans.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($plan))
            @method('PUT')
        @endif

        {{-- Basic Info --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-6">Basic Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Plan Name</label>
                    <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" required
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $plan->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Price (₹)</label>
                    <input type="number" name="price" value="{{ old('price', $plan->price ?? '') }}" required step="0.01" min="0"
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Billing Cycle</label>
                    <select name="billing_cycle" required
                            class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="monthly" {{ old('billing_cycle', $plan->billing_cycle ?? '') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('billing_cycle', $plan->billing_cycle ?? '') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="yearly" {{ old('billing_cycle', $plan->billing_cycle ?? '') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Duration (Days)</label>
                    <input type="number" name="duration_days" value="{{ old('duration_days', $plan->duration_days ?? 30) }}" required min="1"
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? 0) }}" min="0"
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        {{-- Benefits --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-6">Benefits</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Discount Percentage (%)</label>
                    <input type="number" name="discount_percentage" value="{{ old('discount_percentage', $plan->discount_percentage ?? 0) }}" min="0" max="100" step="0.01"
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Early Access (Days)</label>
                    <input type="number" name="early_access_days" value="{{ old('early_access_days', $plan->early_access_days ?? 0) }}" min="0"
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-slate-500 mt-1">Days before public sale access</p>
                </div>

                <div class="md:col-span-2 space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="free_shipping" value="1" {{ old('free_shipping', $plan->free_shipping ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">Free Shipping on All Orders</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="priority_support" value="1" {{ old('priority_support', $plan->priority_support ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">Priority Customer Support</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="exclusive_products" value="1" {{ old('exclusive_products', $plan->exclusive_products ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700">Access to Exclusive Products</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-6">Status</h2>
            
            <div class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                    <span class="text-sm font-medium text-slate-700">Active (visible to customers)</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $plan->is_popular ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                    <span class="text-sm font-medium text-slate-700">Mark as Popular (highlighted on pricing page)</span>
                </label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white font-medium rounded hover:bg-slate-800 transition-colors">
                {{ isset($plan) ? 'Update Plan' : 'Create Plan' }}
            </button>
            <a href="{{ route('admin.membership.plans') }}" class="px-6 py-2 border border-slate-300 text-slate-700 font-medium rounded hover:bg-slate-50 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
