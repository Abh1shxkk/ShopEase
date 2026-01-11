@extends('layouts.admin')

@section('title', isset($step) ? 'Edit Process Step' : 'Create Process Step')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.brand.process-steps') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Process Steps
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ isset($step) ? 'Edit Process Step' : 'Create Process Step' }}</h1>
        <p class="text-[12px] text-slate-500 mt-1">{{ isset($step) ? 'Update this step in your process' : 'Add a new step to your process page' }}</p>
    </div>

    <form action="{{ isset($step) ? route('admin.brand.process-steps.update', $step) : route('admin.brand.process-steps.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($step))
            @method('PUT')
        @endif

        {{-- Step Number & Title --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label for="step_number" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Step Number *</label>
                    <input type="number" name="step_number" id="step_number" value="{{ old('step_number', $step->step_number ?? 1) }}" required min="1"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
                    @error('step_number')
                    <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="title" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $step->title ?? '') }}" required
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                           placeholder="e.g., Sourcing Materials">
                    @error('title')
                    <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="bg-white border border-slate-200 p-6">
            <label for="description" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Description *</label>
            <textarea name="description" id="description" rows="5" required
                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                      placeholder="Describe this step in detail...">{{ old('description', $step->description ?? '') }}</textarea>
            @error('description')
            <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Image --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="grid md:grid-cols-1 gap-6">
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Step Image</label>
                    @if(isset($step) && $step->image_url)
                    <div class="mb-4">
                        <img src="{{ $step->image_url }}" alt="" class="w-full h-40 object-cover border border-slate-200">
                    </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-600 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[11px] file:font-bold file:tracking-[0.1em] file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                    <p class="mt-2 text-[11px] text-slate-400">Max 2MB. Recommended: 800x600px</p>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $step->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-900"></div>
                    <span class="ml-3 text-[12px] font-medium text-slate-700">Active</span>
                </label>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('admin.brand.process-steps') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">
                Cancel
            </a>
            <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                {{ isset($step) ? 'Update Step' : 'Create Step' }}
            </button>
        </div>
    </form>
</div>
@endsection
