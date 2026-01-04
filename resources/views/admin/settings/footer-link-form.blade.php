@extends('layouts.admin')

@section('title', $link ? 'Edit Footer Link' : 'Add Footer Link')

@section('content')
<div class="max-w-2xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.settings.footer') }}" class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-600 mb-2 inline-block">← Back to Footer Settings</a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ $link ? 'Edit Footer Link' : 'Add Footer Link' }}</h1>
    </div>

    <!-- Form -->
    <form action="{{ $link ? route('admin.settings.footer-links.update', $link) : route('admin.settings.footer-links.store') }}" method="POST" class="bg-white border border-slate-200 p-8">
        @csrf
        @if($link)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Link Title *</label>
                <input type="text" name="title" value="{{ old('title', $link?->title) }}" required class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="About Us">
                @error('title')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- URL -->
            <div>
                <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">URL *</label>
                <input type="text" name="url" value="{{ old('url', $link?->url) }}" required class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="/about or https://example.com">
                <p class="text-[11px] text-slate-400 mt-1">Use relative paths (e.g., /shop) or full URLs</p>
                @error('url')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Group -->
            <div>
                <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Link Group *</label>
                <select name="group" required class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors bg-white">
                    <option value="shop" {{ old('group', $link?->group) == 'shop' ? 'selected' : '' }}>Shop Links</option>
                    <option value="account" {{ old('group', $link?->group) == 'account' ? 'selected' : '' }}>Account Links</option>
                    <option value="info" {{ old('group', $link?->group) == 'info' ? 'selected' : '' }}>Info Links</option>
                </select>
                @error('group')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Sort Order -->
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $link?->sort_order ?? 0) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors">
                </div>

                <!-- Active -->
                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $link?->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 border-slate-300 text-slate-900 focus:ring-slate-900">
                        <span class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-8 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.settings.footer') }}" class="h-12 px-8 border border-slate-200 text-[11px] font-bold tracking-[0.15em] uppercase flex items-center justify-center hover:bg-slate-50 transition-colors">Cancel</a>
            <button type="submit" class="flex-1 h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">{{ $link ? 'Update Link' : 'Create Link' }}</button>
        </div>
    </form>
</div>
@endsection
