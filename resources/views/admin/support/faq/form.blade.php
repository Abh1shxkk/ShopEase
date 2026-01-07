@extends('layouts.admin')

@section('title', isset($faq) ? 'Edit FAQ' : 'Create FAQ')

@section('content')
<div class="max-w-2xl">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.support.faqs') }}" class="text-sm text-slate-500 hover:text-slate-900 mb-2 inline-block">← Back to FAQs</a>
        <h1 class="font-serif text-2xl">{{ isset($faq) ? 'Edit FAQ' : 'Create FAQ' }}</h1>
    </div>

    {{-- Form --}}
    <form action="{{ isset($faq) ? route('admin.support.faqs.update', $faq) : route('admin.support.faqs.store') }}" method="POST" class="bg-white border border-slate-200 p-6 space-y-6">
        @csrf
        @if(isset($faq))
        @method('PUT')
        @endif

        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Category *</label>
            <select name="category_id" required class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 @error('category_id') border-red-500 @enderror">
                <option value="">Select a category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $faq->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Question *</label>
            <input type="text" name="question" value="{{ old('question', $faq->question ?? '') }}" required
                class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 @error('question') border-red-500 @enderror">
            @error('question')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Answer *</label>
            <textarea name="answer" rows="6" required class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 resize-none @error('answer') border-red-500 @enderror">{{ old('answer', $faq->answer ?? '') }}</textarea>
            @error('answer')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" min="0"
                    class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900">
            </div>
            <div>
                <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Status</label>
                <select name="is_active" class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900">
                    <option value="1" {{ old('is_active', $faq->is_active ?? true) ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !old('is_active', $faq->is_active ?? true) ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.support.faqs') }}" class="px-6 py-2.5 text-sm text-slate-600 hover:text-slate-900">Cancel</a>
            <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 text-sm hover:bg-slate-800 transition-colors">
                {{ isset($faq) ? 'Update FAQ' : 'Create FAQ' }}
            </button>
        </div>
    </form>
</div>
@endsection
