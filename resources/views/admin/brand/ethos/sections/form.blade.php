@extends('layouts.admin')

@section('title', isset($section) ? 'Edit Ethos Section' : 'Add Ethos Section')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.brand.ethos.sections') }}" class="p-2 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-serif text-slate-900">{{ isset($section) ? 'Edit Section' : 'Add Section' }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ isset($section) ? 'Update ethos section details' : 'Create a new ethos section' }}</p>
        </div>
    </div>

    <form action="{{ isset($section) ? route('admin.brand.ethos.sections.update', $section) : route('admin.brand.ethos.sections.store') }}" method="POST" class="bg-white border border-slate-200 p-6 space-y-6">
        @csrf
        @if(isset($section))
        @method('PUT')
        @endif

        <div class="grid gap-6">
            <div>
                <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $section->title ?? '') }}" required class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
                @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Subtitle</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $section->subtitle ?? '') }}" class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Content *</label>
                <textarea name="content" rows="5" required class="w-full px-4 py-3 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors resize-none">{{ old('content', $section->content ?? '') }}</textarea>
                @error('content')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Image URL</label>
                <input type="url" name="image_url" value="{{ old('image_url', $section->image_url ?? '') }}" placeholder="https://..." class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Image Position *</label>
                <select name="image_position" required class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors bg-white">
                    <option value="right" {{ old('image_position', $section->image_position ?? 'right') === 'right' ? 'selected' : '' }}>Right</option>
                    <option value="left" {{ old('image_position', $section->image_position ?? '') === 'left' ? 'selected' : '' }}>Left</option>
                    <option value="background" {{ old('image_position', $section->image_position ?? '') === 'background' ? 'selected' : '' }}>Background (Full Width)</option>
                    <option value="full" {{ old('image_position', $section->image_position ?? '') === 'full' ? 'selected' : '' }}>Full Width Image</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Button Text</label>
                    <input type="text" name="button_text" value="{{ old('button_text', $section->button_text ?? '') }}" class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Button Link</label>
                    <input type="text" name="button_link" value="{{ old('button_link', $section->button_link ?? '') }}" class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.1em] uppercase text-slate-700 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $section->sort_order ?? 0) }}" class="w-full h-11 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 border-slate-300 text-slate-900 focus:ring-slate-900">
                        <span class="text-sm text-slate-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 text-sm font-medium hover:bg-slate-800 transition-colors">
                {{ isset($section) ? 'Update Section' : 'Create Section' }}
            </button>
            <a href="{{ route('admin.brand.ethos.sections') }}" class="px-6 py-2.5 text-sm text-slate-600 hover:text-slate-900 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
