@extends('layouts.admin')

@section('title', 'Inventory Alerts')

@section('content')
<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center gap-2 text-[12px] text-slate-500 hover:text-slate-900 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Inventory
            </a>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Inventory Alerts</h1>
            <p class="text-[12px] text-slate-500 mt-1">Monitor stock level notifications</p>
        </div>
        @if($stats['unread'] > 0)
        <form action="{{ route('admin.inventory.alerts.mark-all-read') }}" method="POST">
            @csrf
            <button type="submit" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Mark All Read
            </button>
        </form>
        @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Total Alerts</p>
            <p class="text-2xl font-serif text-slate-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Unread</p>
            <p class="text-2xl font-serif text-blue-600">{{ $stats['unread'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Low Stock</p>
            <p class="text-2xl font-serif text-amber-600">{{ $stats['low_stock'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Out of Stock</p>
            <p class="text-2xl font-serif text-red-600">{{ $stats['out_of_stock'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-slate-200 p-6 mb-6">
        <form action="{{ route('admin.inventory.alerts') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-48">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Type</label>
                <select name="type" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <option value="">All Types</option>
                    <option value="low_stock" {{ request('type') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('type') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="restocked" {{ request('type') === 'restocked' ? 'selected' : '' }}>Restocked</option>
                </select>
            </div>
            <div class="w-full sm:w-48">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Status</label>
                <select name="read" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <option value="">All Status</option>
                    <option value="unread" {{ request('read') === 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="read" {{ request('read') === 'read' ? 'selected' : '' }}>Read</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Filter</button>
                @if(request()->hasAny(['type', 'read']))
                <a href="{{ route('admin.inventory.alerts') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Alerts Table -->
    <div class="bg-white border border-slate-200">
        @if($alerts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Alert</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Product</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Stock Level</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Time</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($alerts as $alert)
                    <tr class="hover:bg-slate-50 transition-colors {{ !$alert->is_read ? 'bg-blue-50/30' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 flex items-center justify-center flex-shrink-0
                                    {{ $alert->type === 'out_of_stock' ? 'bg-red-50' : '' }}
                                    {{ $alert->type === 'low_stock' ? 'bg-amber-50' : '' }}
                                    {{ $alert->type === 'restocked' ? 'bg-emerald-50' : '' }}">
                                    @if($alert->type === 'out_of_stock')
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    @elseif($alert->type === 'low_stock')
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>
                                <span class="text-[10px] tracking-widest uppercase px-2 py-1
                                    {{ $alert->type === 'out_of_stock' ? 'bg-red-50 text-red-700' : '' }}
                                    {{ $alert->type === 'low_stock' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ $alert->type === 'restocked' ? 'bg-emerald-50 text-emerald-700' : '' }}">
                                    {{ str_replace('_', ' ', $alert->type) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-medium text-slate-900">{{ $alert->product->name ?? 'Unknown Product' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[13px] {{ $alert->stock_level == 0 ? 'text-red-600 font-semibold' : ($alert->stock_level < 10 ? 'text-amber-600' : 'text-slate-600') }}">
                                {{ $alert->stock_level }} units
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] text-slate-500">{{ $alert->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                @if(!$alert->is_read)
                                <form action="{{ route('admin.inventory.alerts.mark-read', $alert) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors" title="Mark as read">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                                @endif
                                @if($alert->product)
                                <a href="{{ route('admin.products.edit', $alert->product) }}" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors" title="Edit Product">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($alerts->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $alerts->links('vendor.pagination.admin') }}
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <h3 class="text-lg font-serif text-slate-900 mb-2">No alerts found</h3>
            <p class="text-[13px] text-slate-500">All inventory levels are healthy.</p>
        </div>
        @endif
    </div>
</div>
@endsection