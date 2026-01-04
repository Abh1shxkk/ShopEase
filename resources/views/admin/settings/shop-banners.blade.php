@extends('layouts.admin')

@section('title', 'Shop Page Banners')

@section('content')
<div x-data="{ showDeleteModal: false, deleteId: null }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('admin.settings.index') }}" class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-600 mb-2 inline-block">← Back to Settings</a>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Shop Page Banners</h1>
            <p class="text-[12px] text-slate-500 mt-1">Manage the slider banners on the shop page</p>
        </div>
        <a href="{{ route('admin.settings.shop-banners.create') }}" class="h-10 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Banner
        </a>
    </div>

    <!-- Banners List -->
    <div class="bg-white border border-slate-200">
        @forelse($banners as $banner)
        <div class="flex items-center gap-6 p-6 border-b border-slate-100 last:border-b-0 hover:bg-slate-50 transition-colors">
            <!-- Image Preview -->
            <div class="w-32 h-20 bg-slate-100 flex-shrink-0 overflow-hidden">
                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
            </div>
            
            <!-- Info -->
            <div class="flex-1 min-w-0">
                <h3 class="font-medium text-slate-900 truncate">{{ $banner->title }}</h3>
                @if($banner->subtitle)
                <p class="text-[12px] text-slate-500 mt-1">{{ $banner->subtitle }}</p>
                @endif
                <div class="flex items-center gap-4 mt-2">
                    <span class="text-[10px] font-bold tracking-[0.1em] uppercase {{ $banner->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                        {{ $banner->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="text-[10px] text-slate-400">Order: {{ $banner->sort_order }}</span>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.settings.shop-banners.edit', $banner) }}" class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <button @click="showDeleteModal = true; deleteId = {{ $banner->id }}" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-slate-500 mb-4">No shop banners yet</p>
            <a href="{{ route('admin.settings.shop-banners.create') }}" class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-900 hover:underline">Add your first banner</a>
        </div>
        @endforelse
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div @click="showDeleteModal = false" class="absolute inset-0 bg-black/50"></div>
        <div class="relative bg-white p-8 max-w-md w-full mx-4">
            <h3 class="text-lg font-serif mb-2">Delete Banner</h3>
            <p class="text-[13px] text-slate-600 mb-6">Are you sure you want to delete this banner? This action cannot be undone.</p>
            <div class="flex gap-3">
                <button @click="showDeleteModal = false" class="flex-1 h-10 border border-slate-200 text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-50 transition-colors">Cancel</button>
                <form :action="'{{ url('admin/settings/shop-banners') }}/' + deleteId" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full h-10 bg-red-600 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-red-700 transition-colors">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
