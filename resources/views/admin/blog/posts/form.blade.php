@extends('layouts.admin')

@section('title', isset($post) ? 'Edit Post' : 'New Post')
@section('page-title', isset($post) ? 'Edit Post' : 'New Post')

@section('content')
<form action="{{ isset($post) ? route('admin.blog.posts.update', $post) : route('admin.blog.posts.store') }}" method="POST">
    @csrf
    @if(isset($post)) @method('PUT') @endif

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
                        <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $post->slug ?? '') }}" placeholder="auto-generated-from-title"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Excerpt</label>
                        <textarea name="excerpt" rows="2" placeholder="Brief summary of the post..."
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Content *</label>
                        <textarea name="content" rows="15" required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">{{ old('content', $post->content ?? '') }}</textarea>
                        @error('content')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">SEO Settings</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title ?? '') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Meta Description</label>
                        <textarea name="meta_description" rows="2"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Publish --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Publish</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                            <option value="draft" {{ old('status', $post->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="scheduled" {{ old('status', $post->status ?? '') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Publish Date</label>
                        <input type="datetime-local" name="published_at" 
                            value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }}
                                class="w-4 h-4 text-slate-600 border-slate-300 rounded focus:ring-slate-500">
                            <span class="text-sm text-slate-700">Featured</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="allow_comments" value="1" {{ old('allow_comments', $post->allow_comments ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 text-slate-600 border-slate-300 rounded focus:ring-slate-500">
                            <span class="text-sm text-slate-700">Comments</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit" class="flex-1 bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-700 transition">
                        {{ isset($post) ? 'Update' : 'Publish' }}
                    </button>
                    <a href="{{ route('admin.blog.posts') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                        Cancel
                    </a>
                </div>
            </div>

            {{-- Category --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Category</h3>
                <select name="category_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                    <option value="">No Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Featured Image --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Featured Image</h3>
                <input type="text" name="featured_image" value="{{ old('featured_image', $post->featured_image ?? '') }}" placeholder="Image URL"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                @if(isset($post) && $post->featured_image)
                <img src="{{ $post->featured_image }}" alt="" class="mt-3 w-full h-32 object-cover rounded-lg">
                @endif
            </div>

            {{-- Tags --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Tags</h3>
                <input type="text" name="tags" value="{{ old('tags', isset($post) && $post->tags ? implode(', ', $post->tags) : '') }}" 
                    placeholder="tag1, tag2, tag3"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent">
                <p class="text-xs text-slate-500 mt-1">Separate tags with commas</p>
            </div>
        </div>
    </div>
</form>
@endsection
