@extends('layouts.admin')

@section('title', 'Blog Categories')
@section('page-title', 'Blog Categories')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="p-6 border-b border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Categories</h2>
            <p class="text-sm text-slate-500">Organize your blog posts</p>
        </div>
        <a href="{{ route('admin.blog.categories.create') }}" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-700 transition">
            <i class="fas fa-plus mr-2"></i>New Category
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Posts</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($categories as $category)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($category->image)
                            <img src="{{ $category->image }}" alt="" class="w-10 h-10 object-cover rounded-lg">
                            @else
                            <div class="w-10 h-10 bg-slate-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder text-slate-400"></i>
                            </div>
                            @endif
                            <span class="font-medium text-slate-800">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $category->slug }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $category->posts_count }}</td>
                    <td class="px-6 py-4">
                        @if($category->is_active)
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Active</span>
                        @else
                        <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.blog.categories.edit', $category) }}" class="p-2 text-slate-400 hover:text-blue-600">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.blog.categories.delete', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button class="p-2 text-slate-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        <i class="fas fa-folder-open text-4xl mb-4 text-slate-300"></i>
                        <p>No categories yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
