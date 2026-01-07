@extends('layouts.admin')

@section('title', isset($category) ? 'Edit Category' : 'Create Category')

@section('content')
<div class="max-w-2xl">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.support.faq.categories') }}" class="text-sm text-slate-500 hover:text-slate-900 mb-2 inline-block">← Back to Categories</a>
        <h1 class="font-serif text-2xl">{{ isset($category) ? 'Edit Category' : 'Create Category' }}</h1>
    </div>

    {{-- Form --}}
    <form action="{{ isset($category) ? route('admin.support.faq.categories.update', $category) : route('admin.support.faq.categories.store') }}" method="POST" class="bg-white border border-slate-200 p-6 space-y-6">
        @csrf
        @if(isset($category))
        @method('PUT')
        @endif

        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Name *</label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 @error('name') border-red-500 @enderror">
            @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Icon (Optional)</label>
            <input type="text" name="icon" value="{{ old('icon', $category->icon ?? '') }}" placeholder="e.g., shopping-bag"
                class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900">
            <p class="text-xs text-slate-500 mt-1">Icon name for display purposes</p>
        </div>

        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Description (Optional)</label>
            <textarea name="description" rows="3" class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 resize-none">{{ old('description', $category->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0"
                    class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900">
            </div>
            <div>
                <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Status</label>
                <select name="is_active" class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900">
                    <option value="1" {{ old('is_active', $category->is_active ?? true) ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !old('is_active', $category->is_active ?? true) ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.support.faq.categories') }}" class="px-6 py-2.5 text-sm text-slate-600 hover:text-slate-900">Cancel</a>
            <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 text-sm hover:bg-slate-800 transition-colors">
                {{ isset($category) ? 'Update Category' : 'Create Category' }}
            </button>
        </div>
    </form>
</div>
@endsection
