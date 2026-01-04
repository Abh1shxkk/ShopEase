@extends('layouts.admin')

@section('title', 'Edit Category')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.categories.index') }}" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-slate-900 flex items-center gap-2 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Categories
    </a>
</div>

<div class="max-w-2xl">
    <h1 class="text-2xl font-serif tracking-wide text-slate-900 mb-8">Edit Category</h1>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="bg-slate-50 p-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div>
                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Category Name</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900 @error('name') border-red-500 @enderror">
                @error('name')
                <p class="text-red-500 text-[11px] mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900 resize-none">{{ old('description', $category->description) }}</textarea>
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Image</label>
                @if($category->image)
                <div class="mb-3">
                    <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-24 h-24 object-cover">
                </div>
                @endif
                <input type="file" name="image" accept="image/*" class="text-[12px] text-slate-600">
                <p class="text-[11px] text-slate-400 mt-2">Leave empty to keep current image</p>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Status</label>
                    <select name="is_active" class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                        <option value="1" {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex gap-4 mt-8">
            <button type="submit" class="h-12 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                Update Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="h-12 px-8 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-100 transition-colors flex items-center">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
