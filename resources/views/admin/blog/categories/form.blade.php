@extends('layouts.admin')

@section('title', isset($category) ? 'Edit Category' : 'New Category')
@section('page-title', isset($category) ? 'Edit Category' : 'New Category')

@section('content')
<div class="max-w-2xl">
    <form action="{{ isset($category) ? route('admin.blog.categories.update', $category) : route('admin.blog.categories.store') }}" method="POST">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug ?? '') }}" placeholder="auto-generated"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">{{ old('description', $category->description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Image URL</label>
                <input type="text" name="image" value="{{ old('image', $category->image ?? '') }}" placeholder="https://..."
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-slate-600 border-slate-300 rounded focus:ring-slate-500">
                        <span class="text-sm text-slate-700">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-slate-800 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-slate-700 transition">
                    {{ isset($category) ? 'Update' : 'Create' }} Category
                </button>
                <a href="{{ route('admin.blog.categories') }}" class="px-6 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
