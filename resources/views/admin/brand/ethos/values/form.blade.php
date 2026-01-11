@extends('layouts.admin')

@section('title', isset($value) ? 'Edit Ethos Value' : 'Add Ethos Value')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.brand.ethos.values') }}" class="p-2 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-serif text-slate-900">{{ isset($value) ? 'Edit Value' : 'Add Value' }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ isset($value) ? 'Update core value details' : 'Create a new core value' }}</p>
        </div>
    </div>

    <form action="{{ isset($value) ? route('admin.brand.ethos.values.update', $value) : route('admin.brand.ethos.values.store') }}" method="POST" class="bg-white border border-slate-200 p-6 space-y-6">
        @csrf
        @if(isset($value))
        @method('PUT')
        @endif

        <div class="grid gap-6">
            <div>
                <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $value->title ?? '') }}" required class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
                @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Description *</label>
                <textarea name="description" rows="4" required class="w-full px-4 py-3 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors resize-none">{{ old('description', $value->description ?? '') }}</textarea>
                @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Icon</label>
                <select name="icon" class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors bg-white">
                    <option value="">Default</option>
                    <option value="leaf" {{ old('icon', $value->icon ?? '') === 'leaf' ? 'selected' : '' }}>Leaf (Sustainability)</option>
                    <option value="heart" {{ old('icon', $value->icon ?? '') === 'heart' ? 'selected' : '' }}>Heart (Craftsmanship)</option>
                    <option value="globe" {{ old('icon', $value->icon ?? '') === 'globe' ? 'selected' : '' }}>Globe (Community)</option>
                </select>
                <p class="mt-1 text-xs text-slate-500">Choose an icon or leave empty for default</p>
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Image URL (Optional)</label>
                <input type="url" name="image_url" value="{{ old('image_url', $value->image_url ?? '') }}" placeholder="https://..." class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
                <p class="mt-1 text-xs text-slate-500">If provided, image will be shown instead of icon</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $value->sort_order ?? 0) }}" class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $value->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 border-slate-300 text-slate-900 focus:ring-slate-900">
                        <span class="text-sm text-slate-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 text-sm font-medium hover:bg-slate-800 transition-colors">
                {{ isset($value) ? 'Update Value' : 'Create Value' }}
            </button>
            <a href="{{ route('admin.brand.ethos.values') }}" class="px-6 py-2.5 text-sm text-slate-600 hover:text-slate-900 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
