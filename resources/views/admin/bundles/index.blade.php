@extends('layouts.admin')

@section('title', 'Product Bundles')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Product Bundles</h1>
            <p class="text-[12px] text-slate-500 mt-1">Create combo offers and bundle deals</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bundles.frequently-bought') }}" class="h-11 px-6 bg-slate-100 text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-200 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Frequently Bought
            </a>
            <a href="{{ route('admin.bundles.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create Bundle
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Bundles</p>
            <p class="text-3xl font-light text-slate-900 mt-2">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Active</p>
            <p class="text-3xl font-light text-emerald-600 mt-2">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Inactive</p>
            <p class="text-3xl font-light text-slate-400 mt-2">{{ $stats['inactive'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search bundles..." class="h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 w-64">
            <select name="status" onchange="this.form.submit()" class="h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase">Filter</button>
        </form>
    </div>

    {{-- Bundles Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bundles as $bundle)
        <div class="bg-white border border-slate-200 overflow-hidden">
            @if($bundle->image)
            <img src="{{ Storage::url($bundle->image) }}" alt="{{ $bundle->name }}" class="w-full h-40 object-cover">
            @else
            <div class="w-full h-40 bg-slate-100 flex items-center justify-center">
                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            @endif
            
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="font-medium text-slate-900">{{ $bundle->name }}</h3>
                    <span class="px-2 py-1 text-[10px] font-bold tracking-wider uppercase {{ $bundle->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                        {{ $bundle->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <p class="text-[12px] text-slate-500 mb-3">{{ $bundle->items->count() }} products</p>
                
                <div class="flex items-baseline gap-2 mb-3">
                    <span class="text-lg font-semibold text-slate-900">₹{{ number_format($bundle->bundle_price) }}</span>
                    <span class="text-sm text-slate-400 line-through">₹{{ number_format($bundle->original_price) }}</span>
                    <span class="text-[11px] font-bold text-emerald-600">{{ $bundle->savings_percentage }}% OFF</span>
                </div>

                <div class="flex items-center gap-2 mb-4">
                    @foreach($bundle->items->take(3) as $item)
                    <div class="w-10 h-10 bg-slate-100 rounded overflow-hidden">
                        @if($item->product->image)
                        <img src="{{ Storage::url($item->product->image) }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    @endforeach
                    @if($bundle->items->count() > 3)
                    <span class="text-[11px] text-slate-400">+{{ $bundle->items->count() - 3 }} more</span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.bundles.edit', $bundle) }}" class="flex-1 h-9 bg-slate-100 text-slate-700 text-[11px] font-bold tracking-[0.1em] uppercase hover:bg-slate-200 transition-colors flex items-center justify-center">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.bundles.toggle', $bundle) }}" class="flex-1">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full h-9 {{ $bundle->is_active ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }} text-[11px] font-bold tracking-[0.1em] uppercase transition-colors">
                            {{ $bundle->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.bundles.destroy', $bundle) }}" onsubmit="return confirm('Delete this bundle?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="h-9 w-9 bg-red-100 text-red-600 hover:bg-red-200 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white border border-slate-200 p-12 text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-slate-500 mb-4">No bundles created yet</p>
            <a href="{{ route('admin.bundles.create') }}" class="inline-flex items-center gap-2 text-slate-900 font-medium hover:underline">
                Create your first bundle
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @endforelse
    </div>

    @if($bundles->hasPages())
    <div class="mt-6">
        {{ $bundles->links('vendor.pagination.admin') }}
    </div>
    @endif
</div>
@endsection
