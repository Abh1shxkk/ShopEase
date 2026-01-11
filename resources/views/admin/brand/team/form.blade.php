@extends('layouts.admin')

@section('title', isset($member) ? 'Edit Team Member' : 'Add Team Member')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.brand.team') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Team Members
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ isset($member) ? 'Edit Team Member' : 'Add Team Member' }}</h1>
        <p class="text-[12px] text-slate-500 mt-1">{{ isset($member) ? 'Update team member information' : 'Add a new member to your team' }}</p>
    </div>

    <form action="{{ isset($member) ? route('admin.brand.team.update', $member) : route('admin.brand.team.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($member))
            @method('PUT')
        @endif

        {{-- Name & Position --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $member->name ?? '') }}" required
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           placeholder="e.g., John Doe">
                    @error('name')
                    <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="position" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Position *</label>
                    <input type="text" name="position" id="position" value="{{ old('position', $member->position ?? '') }}" required
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           placeholder="e.g., Founder & CEO">
                    @error('position')
                    <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Bio --}}
        <div class="bg-white border border-slate-200 p-6">
            <label for="bio" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Bio</label>
            <textarea name="bio" id="bio" rows="4"
                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                      placeholder="A brief description about this team member...">{{ old('bio', $member->bio ?? '') }}</textarea>
        </div>

        {{-- Photo --}}
        <div class="bg-white border border-slate-200 p-6">
            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Photo</label>
            <div class="flex items-start gap-6">
                @if(isset($member) && $member->image_url)
                <div class="flex-shrink-0">
                    <img src="{{ $member->image_url }}" alt="" class="w-24 h-24 object-cover border border-slate-200">
                </div>
                @endif
                <div class="flex-1">
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-600 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[11px] file:font-bold file:tracking-[0.1em] file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                    <p class="mt-2 text-[11px] text-slate-400">Max 2MB. Recommended: Square image, 400x400px</p>
                </div>
            </div>
        </div>

        {{-- Social Links --}}
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-4">Social Links</p>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="linkedin" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">LinkedIn URL</label>
                    <input type="url" name="linkedin" id="linkedin" value="{{ old('linkedin', $member->linkedin ?? '') }}"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           placeholder="https://linkedin.com/in/...">
                </div>
                <div>
                    <label for="twitter" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Twitter URL</label>
                    <input type="url" name="twitter" id="twitter" value="{{ old('twitter', $member->twitter ?? '') }}"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           placeholder="https://twitter.com/...">
                </div>
            </div>
        </div>

        {{-- Sort Order & Status --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="sort_order" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $member->sort_order ?? 0) }}"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
                    <p class="mt-2 text-[11px] text-slate-400">Lower numbers appear first</p>
                </div>
                <div class="flex items-center pt-8">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-900"></div>
                        <span class="ml-3 text-[12px] font-medium text-slate-700">Active</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('admin.brand.team') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">
                Cancel
            </a>
            <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                {{ isset($member) ? 'Update Member' : 'Add Member' }}
            </button>
        </div>
    </form>
</div>
@endsection
