@extends('layouts.admin')

@section('title', isset($sale) ? 'Edit Sale' : 'Create Sale')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.membership.sales') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Sales
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ isset($sale) ? 'Edit Sale' : 'Create Early Access Sale' }}</h1>
        <p class="text-[12px] text-slate-500 mt-1">{{ isset($sale) ? 'Update sale details' : 'Create an exclusive sale for premium members' }}</p>
    </div>

    <form action="{{ isset($sale) ? route('admin.membership.sales.update', $sale) : route('admin.membership.sales.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($sale))
            @method('PUT')
        @endif

        {{-- Basic Info --}}
        <div class="bg-white border border-slate-200 p-6 space-y-5">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Sale Information</h3>
            
            <div>
                <label for="name" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Sale Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $sale->name ?? '') }}" 
                    class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                    placeholder="e.g., Summer Flash Sale" required>
                @error('name')
                <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="description" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Description</label>
                <textarea name="description" id="description" rows="3" 
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                    placeholder="Brief description of the sale">{{ old('description', $sale->description ?? '') }}</textarea>
                @error('description')
                <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Discount --}}
        <div class="bg-white border border-slate-200 p-6 space-y-5">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Discount</h3>
            
            <div>
                <label for="member_discount" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Member Discount Percentage</label>
                <div class="relative">
                    <input type="number" name="member_discount" id="member_discount" value="{{ old('member_discount', $sale->member_discount ?? '') }}" 
                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors pr-12"
                        placeholder="20" min="1" max="100" step="0.01" required>
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[14px] text-slate-400">%</span>
                </div>
                @error('member_discount')
                <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Duration --}}
        <div class="bg-white border border-slate-200 p-6 space-y-5">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Duration</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="member_access_at" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Member Access Start</label>
                    <input type="datetime-local" name="member_access_at" id="member_access_at" value="{{ old('member_access_at', isset($sale) ? $sale->member_access_at->format('Y-m-d\TH:i') : '') }}" 
                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors" required>
                    @error('member_access_at')
                    <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="public_access_at" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Public Access Start</label>
                    <input type="datetime-local" name="public_access_at" id="public_access_at" value="{{ old('public_access_at', isset($sale) ? $sale->public_access_at->format('Y-m-d\TH:i') : '') }}" 
                        class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors" required>
                    @error('public_access_at')
                    <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label for="ends_at" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Sale End Date & Time</label>
                <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', isset($sale) && $sale->ends_at ? $sale->ends_at->format('Y-m-d\TH:i') : '') }}" 
                    class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                <p class="mt-1 text-[11px] text-slate-400">Leave empty for no end date</p>
                @error('ends_at')
                <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <p class="text-[11px] text-slate-400">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Members get early access before public. Set member access before public access date.
            </p>
        </div>

        {{-- Settings --}}
        <div class="bg-white border border-slate-200 p-6 space-y-5">
            <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Settings</h3>
            
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $sale->is_active ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 border-slate-300 text-slate-900 focus:ring-slate-900">
                <span class="text-[13px] text-slate-700">Active</span>
            </label>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('admin.membership.sales') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">
                Cancel
            </a>
            <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                {{ isset($sale) ? 'Update Sale' : 'Create Sale' }}
            </button>
        </div>
    </form>
</div>
@endsection
