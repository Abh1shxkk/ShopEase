@extends('layouts.admin')

@section('title', isset($section) ? 'Edit Story Section' : 'Create Story Section')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.brand.story-sections') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Story Sections
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ isset($section) ? 'Edit Story Section' : 'Create Story Section' }}</h1>
        <p class="text-[12px] text-slate-500 mt-1">{{ isset($section) ? 'Update this section of your brand story' : 'Add a new section to your brand story page' }}</p>
    </div>

    <form action="{{ isset($section) ? route('admin.brand.story-sections.update', $section) : route('admin.brand.story-sections.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($section))
            @method('PUT')
        @endif

        {{-- Title & Subtitle --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $section->title ?? '') }}" required
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           placeholder="e.g., Our Beginning">
                    @error('title')
                    <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="subtitle" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Subtitle</label>
                    <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $section->subtitle ?? '') }}"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           placeholder="e.g., A passion for quality">
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="bg-white border border-slate-200 p-6">
            <label for="content" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Content *</label>
            <textarea name="content" id="content" rows="6" required
                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                      placeholder="Write your story content here...">{{ old('content', $section->content ?? '') }}</textarea>
            @error('content')
            <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image & Layout --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Section Image</label>
                    @if(isset($section) && $section->image_url)
                    <div class="mb-4">
                        <img src="{{ $section->image_url }}" alt="" class="w-full h-40 object-cover border border-slate-200">
                    </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-600 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[11px] file:font-bold file:tracking-[0.1em] file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                    <p class="mt-2 text-[11px] text-slate-400">Max 2MB. Recommended: 1200x800px</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Image Position *</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['right' => 'Right', 'left' => 'Left', 'full' => 'Full Width', 'background' => 'Background'] as $value => $label)
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="image_position" value="{{ $value }}" class="peer sr-only" {{ old('image_position', $section->image_position ?? 'right') == $value ? 'checked' : '' }}>
                            <div class="px-4 py-3 text-center border border-slate-200 text-[11px] font-bold tracking-[0.1em] uppercase text-slate-600 peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white group-hover:border-slate-300 transition-all">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Button Settings --}}
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-4">Call to Action Button (Optional)</p>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="button_text" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Button Text</label>
                    <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $section->button_text ?? '') }}"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           placeholder="e.g., Learn More">
                </div>
                <div>
                    <label for="button_link" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Button Link</label>
                    <input type="text" name="button_link" id="button_link" value="{{ old('button_link', $section->button_link ?? '') }}"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           placeholder="e.g., /shop or https://...">
                </div>
            </div>
        </div>

        {{-- Sort Order & Status --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="sort_order" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $section->sort_order ?? 0) }}"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
                    <p class="mt-2 text-[11px] text-slate-400">Lower numbers appear first</p>
                </div>
                <div class="flex items-center pt-8">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-900"></div>
                        <span class="ml-3 text-[12px] font-medium text-slate-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('admin.brand.story-sections') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">
                Cancel
            </a>
            <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                {{ isset($section) ? 'Update Section' : 'Create Section' }}
            </button>
        </div>
    </form>
</div>
@endsection
