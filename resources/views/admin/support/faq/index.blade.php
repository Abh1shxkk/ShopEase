@extends('layouts.admin')

@section('title', 'FAQs')

@section('content')
{{-- Header --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-serif text-2xl">FAQs</h1>
        <p class="text-sm text-slate-500 mt-1">Manage frequently asked questions</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.support.faq.categories') }}" class="border border-slate-200 px-6 py-2.5 text-[12px] tracking-wider uppercase hover:bg-slate-50 transition-colors">
            Categories
        </a>
        <a href="{{ route('admin.support.faqs.create') }}" class="bg-slate-900 text-white px-6 py-2.5 text-[12px] tracking-wider uppercase hover:bg-slate-800 transition-colors">
            Add FAQ
        </a>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white border border-slate-200 p-4 mb-6">
    <form action="{{ route('admin.support.faqs') }}" method="GET" class="flex gap-4">
        <select name="category" class="border border-slate-200 py-2 px-3 text-sm focus:outline-none focus:border-slate-900">
            <option value="">All Categories</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-slate-900 text-white px-4 py-2 text-sm hover:bg-slate-800 transition-colors">
            Filter
        </button>
        @if(request('category'))
        <a href="{{ route('admin.support.faqs') }}" class="border border-slate-200 px-4 py-2 text-sm hover:bg-slate-50 transition-colors">
            Clear
        </a>
        @endif
    </form>
</div>

{{-- FAQs --}}
<div class="bg-white border border-slate-200">
    @if($faqs->count() > 0)
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500">Question</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500 hidden md:table-cell">Category</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500 hidden lg:table-cell">Helpful</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500">Status</th>
                <th class="py-4 px-6"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($faqs as $faq)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="py-4 px-6">
                    <p class="font-medium text-sm">{{ Str::limit($faq->question, 60) }}</p>
                </td>
                <td class="py-4 px-6 hidden md:table-cell">
                    <span class="text-sm text-slate-600">{{ $faq->category->name }}</span>
                </td>
                <td class="py-4 px-6 hidden lg:table-cell">
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-green-600">{{ $faq->helpful_count }}</span>
                        <span class="text-slate-300">/</span>
                        <span class="text-red-600">{{ $faq->not_helpful_count }}</span>
                    </div>
                </td>
                <td class="py-4 px-6">
                    <span class="inline-block px-2 py-1 text-[10px] font-medium {{ $faq->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $faq->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.support.faqs.edit', $faq) }}" class="text-sm text-slate-600 hover:text-slate-900">Edit</a>
                        <form action="{{ route('admin.support.faqs.destroy', $faq) }}" method="POST" class="inline" onsubmit="return confirm('Delete this FAQ?')">
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-slate-600">No FAQs yet.</p>
        <a href="{{ route('admin.support.faqs.create') }}" class="inline-block mt-4 text-sm text-slate-900 hover:underline">Create your first FAQ</a>
    </div>
    @endif
</div>

<div class="mt-6">
    {{ $faqs->withQueryString()->links('vendor.pagination.admin') }}
</div>
@endsection
