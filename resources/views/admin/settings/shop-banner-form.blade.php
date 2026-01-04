@extends('layouts.admin')

@section('title', $banner ? 'Edit Shop Banner' : 'Add Shop Banner')

@section('content')
<div class="max-w-2xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.settings.shop-banners') }}" class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-600 mb-2 inline-block">← Back to Shop Banners</a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ $banner ? 'Edit Shop Banner' : 'Add Shop Banner' }}</h1>
    </div>

    <!-- Form -->
    <form action="{{ $banner ? route('admin.settings.shop-banners.update', $banner) : route('admin.settings.shop-banners.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 p-8">
        @csrf
        @if($banner)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $banner?->title) }}" required class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="New Season Arrivals">
                @error('title')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Subtitle -->
            <div>
                <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Subtitle</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $banner?->subtitle) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="Discover the latest trends">
                @error('subtitle')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Image URL -->
            <div>
                <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Image URL</label>
                <input type="url" name="image" value="{{ old('image', $banner?->image && str_starts_with($banner->image, 'http') ? $banner->image : '') }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="https://images.unsplash.com/...">
                <p class="text-[11px] text-slate-400 mt-1">Enter an image URL or upload a file below</p>
                @error('image')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Or Upload Image</label>
                <input type="file" name="image_file" accept="image/*" class="w-full text-[13px] text-slate-600 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[11px] file:font-bold file:tracking-wider file:uppercase file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:cursor-pointer">
                @if($banner && $banner->image && !str_starts_with($banner->image, 'http'))
                <p class="text-[11px] text-slate-500 mt-2">Current: {{ basename($banner->image) }}</p>
                @endif
                @error('image_file')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Preview -->
            @if($banner && $banner->image)
            <div>
                <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Current Image</label>
                <div class="w-full h-40 bg-slate-100 overflow-hidden">
                    <img src="{{ $banner->image_url }}" alt="Preview" class="w-full h-full object-cover">
                </div>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-6">
                <!-- Sort Order -->
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner?->sort_order ?? 0) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors">
                </div>

                <!-- Active -->
                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner?->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 border-slate-300 text-slate-900 focus:ring-slate-900">
                        <span class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 mt-8 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.settings.shop-banners') }}" class="h-12 px-8 border border-slate-200 text-[11px] font-bold tracking-[0.15em] uppercase flex items-center justify-center hover:bg-slate-50 transition-colors">Cancel</a>
            <button type="submit" class="flex-1 h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">{{ $banner ? 'Update Banner' : 'Create Banner' }}</button>
        </div>
    </form>
</div>
@endsection
