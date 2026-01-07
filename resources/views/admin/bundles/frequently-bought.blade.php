@extends('layouts.admin')

@section('title', 'Frequently Bought Together')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.bundles.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Bundles
            </a>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Frequently Bought Together</h1>
            <p class="text-[12px] text-slate-500 mt-1">Auto-generated product pairs based on purchase history</p>
        </div>
        <form method="POST" action="{{ route('admin.bundles.regenerate-fbt') }}">
            @csrf
            <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Regenerate Data
            </button>
        </form>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product..." class="h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 w-64">
            <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase">Search</button>
        </form>
    </div>

    {{-- Pairs Table --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Product</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Frequently Bought With</th>
                        <th class="text-center px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Times Bought</th>
                        <th class="text-center px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Discount %</th>
                        <th class="text-center px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pairs as $pair)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($pair->product->image)
                                <img src="{{ Storage::url($pair->product->image) }}" alt="" class="w-10 h-10 object-cover bg-slate-100">
                                @else
                                <div class="w-10 h-10 bg-slate-100"></div>
                                @endif
                                <div>
                                    <p class="text-[13px] font-medium text-slate-900">{{ Str::limit($pair->product->name, 30) }}</p>
                                    <p class="text-[11px] text-slate-500">₹{{ number_format($pair->product->price) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($pair->relatedProduct->image)
                                <img src="{{ Storage::url($pair->relatedProduct->image) }}" alt="" class="w-10 h-10 object-cover bg-slate-100">
                                @else
                                <div class="w-10 h-10 bg-slate-100"></div>
                                @endif
                                <div>
                                    <p class="text-[13px] font-medium text-slate-900">{{ Str::limit($pair->relatedProduct->name, 30) }}</p>
                                    <p class="text-[11px] text-slate-500">₹{{ number_format($pair->relatedProduct->price) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-[14px] font-semibold text-slate-900">{{ $pair->purchase_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.bundles.update-fbt', $pair) }}" class="flex items-center justify-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="discount_percentage" value="{{ $pair->discount_percentage }}" min="0" max="100" step="0.5" class="w-16 h-8 px-2 bg-slate-50 border border-slate-200 text-[12px] text-center focus:outline-none focus:border-slate-900">
                                <span class="text-[12px] text-slate-400">%</span>
                                <input type="hidden" name="is_active" value="{{ $pair->is_active ? '1' : '0' }}">
                                <button type="submit" class="text-[10px] text-slate-500 hover:text-slate-900">Save</button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-[10px] font-bold tracking-wider uppercase {{ $pair->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $pair->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form method="POST" action="{{ route('admin.bundles.update-fbt', $pair) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="discount_percentage" value="{{ $pair->discount_percentage }}">
                                <input type="hidden" name="is_active" value="{{ $pair->is_active ? '0' : '1' }}">
                                <button type="submit" class="text-[11px] font-medium {{ $pair->is_active ? 'text-amber-600 hover:text-amber-700' : 'text-emerald-600 hover:text-emerald-700' }}">
                                    {{ $pair->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-[13px] text-slate-500">
                            No frequently bought together data yet. This is auto-generated from order history.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pairs->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $pairs->links('vendor.pagination.admin') }}
        </div>
        @endif
    </div>
</div>
@endsection
