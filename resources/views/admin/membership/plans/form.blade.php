@extends('layouts.admin')

@section('title', isset($plan) ? 'Edit Plan' : 'Create Plan')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.membership.plans') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Plans
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ isset($plan) ? 'Edit Plan' : 'Create Plan' }}</h1>
        <p class="text-[12px] text-slate-500 mt-1">{{ isset($plan) ? 'Update membership plan details' : 'Add a new membership plan' }}</p>
    </div>

    <form action="{{ isset($plan) ? route('admin.membership.plans.update', $plan) : route('admin.membership.plans.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($plan))
            @method('PUT')
        @endif

        {{-- Basic Info --}}
        <div class="bg-white border border-slate-200 p-6 space-y-5">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Basic Information</h3>
            
            <div>
                <label for="name" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Plan Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $plan->name ?? '') }}" 
                    class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                    placeholder="e.g., Premium, Gold, Platinum" required>
                @error('name')
                <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="description" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                    placeholder="Brief description of the plan">{{ old('description', $plan->description ?? '') }}</textarea>
                @error('description')
                <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Pricing --}}
        <div class="bg-white border border-slate-200 p-6 space-y-5">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Pricing</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Price (₹)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $plan->price ?? '') }}" 
                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                        placeholder="999" min="0" step="0.01" required>
                    @error('price')
                    <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="billing_cycle" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Billing Cycle</label>
                    <select name="billing_cycle" id="billing_cycle" 
                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                        <option value="monthly" {{ old('billing_cycle', $plan->billing_cycle ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ old('billing_cycle', $plan->billing_cycle ?? '') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="yearly" {{ old('billing_cycle', $plan->billing_cycle ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                    @error('billing_cycle')
                    <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label for="discount_percentage" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Member Discount (%)</label>
                <input type="number" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage', $plan->discount_percentage ?? 0) }}" 
                    class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                    placeholder="10" min="0" max="100">
                <p class="mt-1 text-[11px] text-slate-400">Discount on all purchases for members</p>
                @error('discount_percentage')
                <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Features --}}
        <div class="bg-white border border-slate-200 p-6 space-y-5">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Features</h3>
            
            <div>
                <label for="features" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Plan Features</label>
                <textarea name="features" id="features" rows="5" 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 font-mono transition-colors"
                    placeholder="Enter one feature per line">{{ old('features', isset($plan) && $plan->features ? implode("\n", $plan->features) : '') }}</textarea>
                <p class="mt-1 text-[11px] text-slate-400">Enter one feature per line</p>
                @error('features')
                <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Settings --}}
        <div class="bg-white border border-slate-200 p-6 space-y-5">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Settings</h3>
            
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}
                        class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900">
                    <span class="text-[13px] text-slate-700">Active</span>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $plan->is_featured ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900">
                    <span class="text-[13px] text-slate-700">Featured (Most Popular)</span>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="early_access" value="1" {{ old('early_access', $plan->early_access ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900">
                    <span class="text-[13px] text-slate-700">Early Access to Sales</span>
                </label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('admin.membership.plans') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">
                Cancel
            </a>
            <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                {{ isset($plan) ? 'Update Plan' : 'Create Plan' }}
            </button>
        </div>
    </form>
</div>
@endsection
