@extends('layouts.admin')

@section('title', $section ? 'Edit Section' : 'Add Section')

@section('content')
@php
$typeLabels = [
    'category_showcase' => 'Category Showcase',
    'heritage' => 'Heritage Section',
    'journal' => 'Journal Post',
    'marquee' => 'Marquee Product',
];
@endphp

<div x-data="{ imagePreview: '{{ $section ? $section->image_url : '' }}' }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ $section ? 'Edit' : 'Add' }} {{ $typeLabels[$type] ?? 'Section' }}</h1>
            <p class="text-[12px] text-slate-500 mt-1">{{ $section ? 'Update section details' : 'Create a new featured section' }}</p>
        </div>
        <a href="{{ route('admin.settings.featured-sections') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <form action="{{ $section ? route('admin.settings.featured-sections.update', $section) : route('admin.settings.featured-sections.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($section) @method('PUT') @endif
        <input type="hidden" name="section_type" value="{{ $type }}">
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Section Content</h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Name (Internal) <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $section?->name) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors" required>
                            @error('name')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $section?->title) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors" required>
                            @error('title')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Description</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors resize-none">{{ old('description', $section?->description) }}</textarea>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Link URL</label>
                                <input type="text" name="link" value="{{ old('link', $section?->link) }}" placeholder="/shop or https://..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Link Text</label>
                                <input type="text" name="link_text" value="{{ old('link_text', $section?->link_text) }}" placeholder="Shop Now" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Settings</h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $section?->sort_order ?? 0) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                            @if($type === 'marquee')
                            <p class="text-[10px] text-slate-400 mt-2">1-8 = Top row (scrolls left), 9+ = Bottom row (scrolls right)</p>
                            @endif
                        </div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $section?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-slate-900 border-slate-300 focus:ring-slate-900">
                            <span class="text-[13px] text-slate-600">Active</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Image</h3>
                    
                    <div class="space-y-4">
                        <div class="{{ in_array($type, ['category_showcase', 'marquee']) ? 'aspect-[3/4]' : 'aspect-video' }} bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <div class="text-center p-4">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-[11px] text-slate-400">No image</p>
                                </div>
                            </template>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Image URL</label>
                            <input type="text" name="image" value="{{ old('image', $section?->image) }}" placeholder="https://..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors" @input="imagePreview = $event.target.value">
                        </div>
                        <p class="text-[10px] text-slate-400 text-center">— OR —</p>
                        <input type="file" name="image_file" accept="image/*" class="w-full text-[12px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[11px] file:font-bold file:tracking-[0.1em] file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 file:cursor-pointer" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.settings.featured-sections') }}" class="flex-1 h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">Cancel</a>
                    <button type="submit" class="flex-1 h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">{{ $section ? 'Update' : 'Create' }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
