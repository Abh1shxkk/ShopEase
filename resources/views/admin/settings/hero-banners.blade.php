@extends('layouts.admin')

@section('title', 'Hero Banners')

@section('content')
<div x-data="{ showDeleteModal: false, deleteId: null }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Hero Banners</h1>
            <p class="text-[12px] text-slate-500 mt-1">Manage homepage slider banners</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.settings.index') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
            <a href="{{ route('admin.settings.hero-banners.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Banner
            </a>
        </div>
    </div>

    <!-- Banners Grid -->
    @if($banners->count() > 0)
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($banners as $banner)
        <div class="bg-white border border-slate-200 overflow-hidden group">
            <div class="aspect-video bg-slate-100 relative overflow-hidden">
                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                @if(!$banner->is_active)
                <div class="absolute top-3 left-3 px-2 py-1 bg-amber-500 text-white text-[10px] font-bold tracking-widest uppercase">Inactive</div>
                @endif
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                    <a href="{{ route('admin.settings.hero-banners.edit', $banner) }}" class="w-10 h-10 bg-white flex items-center justify-center text-slate-900 hover:bg-slate-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <button @click="showDeleteModal = true; deleteId = {{ $banner->id }}" class="w-10 h-10 bg-red-600 flex items-center justify-center text-white hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-4">
                <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400 mb-1">Order: {{ $banner->sort_order }}</p>
                <h3 class="text-[14px] font-medium text-slate-900">{{ $banner->title }}</h3>
                @if($banner->subtitle)
                <p class="text-[12px] text-slate-500 mt-1">{{ Str::limit($banner->subtitle, 50) }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white border border-slate-200 p-16 text-center">
        <div class="w-16 h-16 bg-slate-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-lg font-serif text-slate-900 mb-2">No banners yet</h3>
        <p class="text-[13px] text-slate-500 mb-6">Create your first hero banner for the homepage slider.</p>
        <a href="{{ route('admin.settings.hero-banners.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase inline-flex items-center gap-2 hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Banner
        </a>
    </div>
    @endif

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showDeleteModal" @click="showDeleteModal = false" class="fixed inset-0 bg-black/40"></div>
            <div x-show="showDeleteModal" x-transition class="relative bg-white shadow-xl max-w-md w-full p-8">
                <div class="text-center">
                    <div class="w-14 h-14 bg-red-50 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="text-xl font-serif text-slate-900 mb-2">Delete Banner</h3>
                    <p class="text-[13px] text-slate-500 mb-8">Are you sure you want to delete this banner? This action cannot be undone.</p>
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false" class="flex-1 h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                        <form :action="'{{ url('admin/settings/hero-banners') }}/' + deleteId" method="POST" class="flex-1">
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
