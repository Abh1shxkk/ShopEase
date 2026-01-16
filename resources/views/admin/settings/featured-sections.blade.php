@extends('layouts.admin')

@section('title', 'Featured Sections')

@section('content')
<div x-data="{ showDeleteModal: false, deleteId: null }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Featured Sections</h1>
            <p class="text-[12px] text-slate-500 mt-1">Manage homepage content sections</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.settings.index') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
        </div>
    </div>

    <!-- Section Types -->
    <div class="space-y-8">
        <!-- Category Showcase -->
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Category Showcase</h2>
                    <p class="text-[12px] text-slate-400 mt-1">Featured category cards on homepage</p>
                </div>
                <a href="{{ route('admin.settings.featured-sections.create', ['type' => 'category_showcase']) }}" class="h-9 px-4 bg-slate-900 text-white text-[10px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add
                </a>
            </div>
            @if(isset($sections['category_showcase']) && $sections['category_showcase']->count() > 0)
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
                @foreach($sections['category_showcase'] as $section)
                <div class="border border-slate-200 overflow-hidden group relative">
                    <div class="aspect-[3/4] bg-slate-100">
                        <img src="{{ $section->image_url }}" alt="{{ $section->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-3">
                        <p class="text-[12px] font-medium text-slate-900">{{ $section->title }}</p>
                        <p class="text-[10px] text-slate-400">{{ $section->is_active ? 'Active' : 'Inactive' }}</p>
                    </div>
                    <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.settings.featured-sections.edit', $section) }}" class="w-8 h-8 bg-white flex items-center justify-center text-slate-600 hover:text-slate-900 shadow">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <button @click="showDeleteModal = true; deleteId = {{ $section->id }}" class="w-8 h-8 bg-red-600 flex items-center justify-center text-white hover:bg-red-700 shadow">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-8 text-center text-[13px] text-slate-400">No category sections yet</div>
            @endif
        </div>

        <!-- Heritage Section -->
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Heritage Section</h2>
                    <p class="text-[12px] text-slate-400 mt-1">Brand story and sustainability message</p>
                </div>
                <a href="{{ route('admin.settings.featured-sections.create', ['type' => 'heritage']) }}" class="h-9 px-4 bg-slate-900 text-white text-[10px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add
                </a>
            </div>
            @if(isset($sections['heritage']) && $sections['heritage']->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($sections['heritage'] as $section)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-slate-100 overflow-hidden">
                            <img src="{{ $section->image_url }}" alt="{{ $section->title }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-[13px] font-medium text-slate-900">{{ $section->title }}</p>
                            <p class="text-[11px] text-slate-400">{{ Str::limit($section->description, 60) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.settings.featured-sections.edit', $section) }}" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:bg-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <button @click="showDeleteModal = true; deleteId = {{ $section->id }}" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-8 text-center text-[13px] text-slate-400">No heritage sections yet</div>
            @endif
        </div>

        <!-- Journal Posts -->
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Journal Posts</h2>
                    <p class="text-[12px] text-slate-400 mt-1">Blog/journal entries on homepage</p>
                </div>
                <a href="{{ route('admin.settings.featured-sections.create', ['type' => 'journal']) }}" class="h-9 px-4 bg-slate-900 text-white text-[10px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add
                </a>
            </div>
            @if(isset($sections['journal']) && $sections['journal']->count() > 0)
            <div class="grid sm:grid-cols-3 gap-4 p-4">
                @foreach($sections['journal'] as $section)
                <div class="border border-slate-200 overflow-hidden group relative">
                    <div class="aspect-video bg-slate-100">
                        <img src="{{ $section->image_url }}" alt="{{ $section->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-3">
                        <p class="text-[12px] font-medium text-slate-900">{{ Str::limit($section->title, 40) }}</p>
                    </div>
                    <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.settings.featured-sections.edit', $section) }}" class="w-8 h-8 bg-white flex items-center justify-center text-slate-600 hover:text-slate-900 shadow">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <button @click="showDeleteModal = true; deleteId = {{ $section->id }}" class="w-8 h-8 bg-red-600 flex items-center justify-center text-white hover:bg-red-700 shadow">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-8 text-center text-[13px] text-slate-400">No journal posts yet</div>
            @endif
        </div>

        <!-- Product Marquee -->
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Product Marquee</h2>
                    <p class="text-[12px] text-slate-400 mt-1">Auto-scrolling products on homepage (two rows)</p>
                </div>
                <a href="{{ route('admin.settings.featured-sections.create', ['type' => 'marquee']) }}" class="h-9 px-4 bg-slate-900 text-white text-[10px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add
                </a>
            </div>
            @if(isset($sections['marquee']) && $sections['marquee']->count() > 0)
            <div class="grid sm:grid-cols-4 lg:grid-cols-6 gap-3 p-4">
                @foreach($sections['marquee'] as $section)
                <div class="border border-slate-200 overflow-hidden group relative">
                    <div class="aspect-[3/4] bg-slate-100">
                        <img src="{{ $section->image_url }}" alt="{{ $section->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-2">
                        <p class="text-[11px] font-medium text-slate-900 truncate">{{ $section->title }}</p>
                        <p class="text-[10px] text-slate-400">{{ $section->is_active ? 'Active' : 'Inactive' }}</p>
                    </div>
                    <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('admin.settings.featured-sections.edit', $section) }}" class="w-7 h-7 bg-white flex items-center justify-center text-slate-600 hover:text-slate-900 shadow">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <button @click="showDeleteModal = true; deleteId = {{ $section->id }}" class="w-7 h-7 bg-red-600 flex items-center justify-center text-white hover:bg-red-700 shadow">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-4 pb-4">
                <p class="text-[10px] text-slate-400 bg-slate-50 p-3 border border-slate-100">
                    <strong>Tip:</strong> Add at least 8 products for best results. Products will auto-scroll in two rows (top moves left, bottom moves right). Use "Sort Order" to control which row: 1-8 for top row, 9-16 for bottom row.
                </p>
            </div>
            @else
            <div class="p-8 text-center text-[13px] text-slate-400">
                No marquee products yet. Add products to create an auto-scrolling showcase.
            </div>
            @endif
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showDeleteModal" @click="showDeleteModal = false" class="fixed inset-0 bg-black/40"></div>
            <div x-show="showDeleteModal" x-transition class="relative bg-white shadow-xl max-w-md w-full p-8">
                <div class="text-center">
                    <div class="w-14 h-14 bg-red-50 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="text-xl font-serif text-slate-900 mb-2">Delete Section</h3>
                    <p class="text-[13px] text-slate-500 mb-8">Are you sure you want to delete this section?</p>
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false" class="flex-1 h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                        <form :action="'{{ url('admin/settings/featured-sections') }}/' + deleteId" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-11 px-6 bg-red-600 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-red-700 transition-colors">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
