@extends('layouts.admin')

@section('title', 'Blog Posts')
@section('page-title', 'Blog Posts')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6 border-b border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">All Posts</h2>
            <p class="text-sm text-slate-500">Manage your blog posts</p>
        </div>
        <a href="{{ route('admin.blog.posts.create') }}" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-700 transition">
            <i class="fas fa-plus mr-2"></i>New Post
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Post</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Views</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Date</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($posts as $post)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            @if($post->featured_image)
                            <img src="{{ $post->featured_image }}" alt="" class="w-16 h-12 object-cover rounded-lg">
                            @else
                            <div class="w-16 h-12 bg-slate-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-slate-400"></i>
                            </div>
                            @endif
                            <div>
                                <p class="font-medium text-slate-800">{{ Str::limit($post->title, 40) }}</p>
                                <p class="text-xs text-slate-500">{{ $post->author?->name ?? 'Unknown' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-600">{{ $post->category?->name ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($post->status === 'published')
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Published</span>
                        @elseif($post->status === 'draft')
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Draft</span>
                        @else
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Scheduled</span>
                        @endif
                        @if($post->is_featured)
                        <span class="ml-1 px-2 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">Featured</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ number_format($post->views) }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $post->formatted_date }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="p-2 text-slate-400 hover:text-slate-600">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="{{ route('admin.blog.posts.edit', $post) }}" class="p-2 text-slate-400 hover:text-blue-600">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.blog.posts.delete', $post) }}" method="POST" class="inline" onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button class="p-2 text-slate-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <i class="fas fa-newspaper text-4xl mb-4 text-slate-300"></i>
                        <p>No posts yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($posts->hasPages())
    <div class="p-6 border-t border-slate-200">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection
