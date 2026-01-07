@extends('layouts.admin')

@section('title', isset($sale) ? 'Edit Sale' : 'Create Sale')
@section('page-title', isset($sale) ? 'Edit Early Access Sale' : 'Create Early Access Sale')

@section('content')
<div class="max-w-3xl">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.membership.sales') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Sales
        </a>
    </div>

    <form action="{{ isset($sale) ? route('admin.membership.sales.update', $sale) : route('admin.membership.sales.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($sale)) @method('PUT') @endif

        {{-- Basic Info --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Sale Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Sale Name *</label>
                    <input type="text" name="name" value="{{ old('name', $sale->name ?? '') }}" required
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $sale->description ?? '') }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Timing --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Sale Timing</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Member Access Date *</label>
                    <input type="datetime-local" name="member_access_at" required
                           value="{{ old('member_access_at', isset($sale) ? $sale->member_access_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-slate-500 mt-1">When members can start shopping</p>
                    @error('member_access_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Public Access Date *</label>
                    <input type="datetime-local" name="public_access_at" required
                           value="{{ old('public_access_at', isset($sale) ? $sale->public_access_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-slate-500 mt-1">When everyone can access the sale</p>
                    @error('public_access_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Sale Ends</label>
                    <input type="datetime-local" name="ends_at"
                           value="{{ old('ends_at', isset($sale) && $sale->ends_at ? $sale->ends_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-slate-500 mt-1">Leave empty for no end date</p>
                    @error('ends_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Extra Member Discount (%)</label>
                    <input type="number" name="member_discount" step="0.01" min="0" max="100"
                           value="{{ old('member_discount', $sale->member_discount ?? 0) }}"
                           class="w-full px-4 py-2 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-slate-500 mt-1">Additional discount for members during this sale</p>
                    @error('member_discount') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $sale->is_active ?? true) ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <div>
                    <span class="font-medium text-slate-900">Active</span>
                    <p class="text-sm text-slate-500">Enable this sale</p>
                </div>
            </label>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded hover:bg-slate-800 transition-colors">
                {{ isset($sale) ? 'Update Sale' : 'Create Sale' }}
            </button>
            <a href="{{ route('admin.membership.sales') }}" class="px-6 py-2 border border-slate-300 text-slate-700 rounded hover:bg-slate-50 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
