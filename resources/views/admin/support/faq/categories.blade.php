@extends('layouts.admin')

@section('title', 'FAQ Categories')

@section('content')
{{-- Header --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-serif text-2xl">FAQ Categories</h1>
        <p class="text-sm text-slate-500 mt-1">Organize your frequently asked questions</p>
    </div>
    <a href="{{ route('admin.support.faq.categories.create') }}" class="bg-slate-900 text-white px-6 py-2.5 text-[12px] tracking-wider uppercase hover:bg-slate-800 transition-colors">
        Add Category
    </a>
</div>

{{-- Categories --}}
<div class="bg-white border border-slate-200">
    @if($categories->count() > 0)
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500">Category</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500">FAQs</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500">Order</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500">Status</th>
                <th class="py-4 px-6"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($categories as $category)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="py-4 px-6">
                    <p class="font-medium text-sm">{{ $category->name }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $category->slug }}</p>
                </td>
                <td class="py-4 px-6">
                    <span class="text-sm">{{ $category->faqs_count }}</span>
                </td>
                <td class="py-4 px-6">
                    <span class="text-sm text-slate-600">{{ $category->sort_order }}</span>
                </td>
                <td class="py-4 px-6">
                    <span class="inline-block px-2 py-1 text-[10px] font-medium {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.support.faq.categories.edit', $category) }}" class="text-sm text-slate-600 hover:text-slate-900">Edit</a>
                        <form action="{{ route('admin.support.faq.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category and all its FAQs?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-700">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="text-center py-12">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <p class="text-slate-600">No categories yet.</p>
        <a href="{{ route('admin.support.faq.categories.create') }}" class="inline-block mt-4 text-sm text-slate-900 hover:underline">Create your first category</a>
    </div>
    @endif
</div>
@endsection
