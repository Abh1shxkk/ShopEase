@extends('layouts.admin')

@section('title', 'Flash Sales')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Flash Sales</h1>
            <p class="text-[12px] text-slate-500 mt-1">Create and manage limited-time deals</p>
        </div>
        <a href="{{ route('admin.flash-sales.create') }}" class="h-10 px-5 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
            </svg>
            Create Sale
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-slate-900">{{ $stats['total_sales'] }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Total Sales</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-red-600">{{ $stats['active_sales'] }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Live Now</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-amber-600">{{ $stats['upcoming_sales'] }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Upcoming</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-emerald-600">{{ number_format($stats['total_products_sold']) }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Products Sold</p>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Sale</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Products</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Duration</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Status</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-medium text-slate-900">{{ $sale->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ Str::limit($sale->description, 50) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-[13px] text-slate-900">{{ $sale->products_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-[11px] text-slate-600">{{ $sale->starts_at->format('M d, h:i A') }}</p>
                            <p class="text-[11px] text-slate-400">to {{ $sale->ends_at->format('M d, h:i A') }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($sale->isLive())
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span> LIVE
                            </span>
                            @elseif($sale->isUpcoming())
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold">UPCOMING</span>
                            @elseif($sale->hasEnded())
                            <span class="px-2 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold">ENDED</span>
                            @else
                            <span class="px-2 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold">INACTIVE</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.flash-sales.edit', $sale) }}" class="p-2 text-slate-400 hover:text-slate-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.flash-sales.toggle', $sale) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-2 {{ $sale->is_active ? 'text-emerald-600' : 'text-slate-400' }} hover:text-slate-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.flash-sales.destroy', $sale) }}" onsubmit="return confirm('Delete this flash sale?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">No flash sales yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sales->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">{{ $sales->links() }}</div>
        @endif
    </div>
</div>
@endsection
