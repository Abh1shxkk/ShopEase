@extends('layouts.admin')

@section('title', $banner ? 'Edit Banner' : 'Add Banner')

@section('content')
<div x-data="{ imagePreview: '{{ $banner ? $banner->image_url : '' }}' }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ $banner ? 'Edit Banner' : 'Add New Banner' }}</h1>
            <p class="text-[12px] text-slate-500 mt-1">{{ $banner ? 'Update banner details' : 'Create a new hero banner' }}</p>
        </div>
        <a href="{{ route('admin.settings.hero-banners') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <form action="{{ $banner ? route('admin.settings.hero-banners.update', $banner) : route('admin.settings.hero-banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($banner) @method('PUT') @endif
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Banner Content</h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $banner?->title) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors" required>
                            @error('title')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Subtitle</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle', $banner?->subtitle) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Buttons</h3>
                    
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Button 1 Text</label>
                            <input type="text" name="button_text" value="{{ old('button_text', $banner?->button_text ?? 'SHOP HIM') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Button 1 Link</label>
                            <input type="text" name="button_link" value="{{ old('button_link', $banner?->button_link ?? '/shop?gender=men') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Button 2 Text</label>
                            <input type="text" name="button_text_2" value="{{ old('button_text_2', $banner?->button_text_2 ?? 'SHOP HER') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Button 2 Link</label>
                            <input type="text" name="button_link_2" value="{{ old('button_link_2', $banner?->button_link_2 ?? '/shop?gender=women') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
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
                            <input type="number" name="sort_order" value="{{ old('sort_order', $banner?->sort_order ?? 0) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                        </div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-slate-900 border-slate-300 focus:ring-slate-900">
                            <span class="text-[13px] text-slate-600">Active</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Banner Image</h3>
                    
                    <div class="space-y-4">
                        <div class="aspect-video bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center">
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
                            <input type="text" name="image" value="{{ old('image', $banner?->image) }}" placeholder="https://..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors" @input="imagePreview = $event.target.value">
                        </div>
                        <p class="text-[10px] text-slate-400 text-center">— OR —</p>
                        <input type="file" name="image_file" accept="image/*" class="w-full text-[12px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[11px] file:font-bold file:tracking-[0.1em] file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 file:cursor-pointer" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.settings.hero-banners') }}" class="flex-1 h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">Cancel</a>
                    <button type="submit" class="flex-1 h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">{{ $banner ? 'Update' : 'Create' }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
