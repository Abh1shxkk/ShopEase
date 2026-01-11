@extends('layouts.admin')

@section('title', 'Ethos Sections')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif text-slate-900">Ethos Sections</h1>
            <p class="text-sm text-slate-500 mt-1">Manage content sections for the Our Ethos page</p>
        </div>
        <a href="{{ route('admin.brand.ethos.sections.create') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Section
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Order</th>
                    <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Title</th>
                    <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Layout</th>
                    <th class="px-6 py-3 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                    <th class="px-6 py-3 text-right text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($sections as $section)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $section->sort_order }}</td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-slate-900">{{ $section->title }}</p>
                        @if($section->subtitle)
                        <p class="text-xs text-slate-500">{{ $section->subtitle }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-[10px] font-medium tracking-wide uppercase bg-slate-100 text-slate-600">
                            {{ $section->image_position }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($section->is_active)
                        <span class="inline-flex px-2 py-1 text-[10px] font-medium tracking-wide uppercase bg-emerald-100 text-emerald-700">Active</span>
                        @else
                        <span class="inline-flex px-2 py-1 text-[10px] font-medium tracking-wide uppercase bg-slate-100 text-slate-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.brand.ethos.sections.edit', $section) }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.brand.ethos.sections.delete', $section) }}" method="POST" onsubmit="return confirm('Delete this section?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        No sections found. <a href="{{ route('admin.brand.ethos.sections.create') }}" class="text-slate-900 underline">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
