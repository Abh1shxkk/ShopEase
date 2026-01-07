@extends('layouts.admin')

@section('title', 'Early Access Sales')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Early Access Sales</h1>
            <p class="text-[12px] text-slate-500 mt-1">Manage exclusive sales for premium members</p>
        </div>
        <a href="{{ route('admin.membership.sales.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
            </svg>
            Create Sale
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Sales</p>
                    <p class="text-3xl font-light text-slate-900 mt-2">{{ \App\Models\EarlyAccessSale::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Active Now</p>
                    <p class="text-3xl font-light text-emerald-600 mt-2">{{ \App\Models\EarlyAccessSale::where('is_active', true)->where('member_access_at', '<=', now())->where('ends_at', '>=', now())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Upcoming</p>
                    <p class="text-3xl font-light text-blue-600 mt-2">{{ \App\Models\EarlyAccessSale::where('member_access_at', '>', now())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Ended</p>
                    <p class="text-3xl font-light text-slate-400 mt-2">{{ \App\Models\EarlyAccessSale::where('ends_at', '<', now())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Sale</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Discount</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Duration</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-medium text-slate-900">{{ $sale->name }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ Str::limit($sale->description, 50) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[14px] font-semibold text-emerald-600">{{ $sale->member_discount }}% OFF</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[12px] text-slate-900">{{ $sale->member_access_at->format('M d, Y H:i') }}</p>
                            <p class="text-[11px] text-slate-500">to {{ $sale->ends_at ? $sale->ends_at->format('M d, Y H:i') : 'No end date' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $now = now();
                                if (!$sale->is_active) {
                                    $status = ['color' => 'slate', 'label' => 'Inactive'];
                                } elseif ($sale->member_access_at > $now) {
                                    $status = ['color' => 'blue', 'label' => 'Upcoming'];
                                } elseif ($sale->ends_at && $sale->ends_at < $now) {
                                    $status = ['color' => 'slate', 'label' => 'Ended'];
                                } else {
                                    $status = ['color' => 'emerald', 'label' => 'Active'];
                                }
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-{{ $status['color'] }}-700">
                                <span class="w-1.5 h-1.5 bg-{{ $status['color'] }}-500 rounded-full"></span>
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.membership.sales.edit', $sale) }}" class="text-[12px] font-medium text-slate-600 hover:text-slate-900 transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('admin.membership.sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Delete this sale?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[12px] font-medium text-red-600 hover:text-red-700 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-[13px] text-slate-500 mb-4">No early access sales yet</p>
                                <a href="{{ route('admin.membership.sales.create') }}" class="text-[12px] font-medium text-blue-600 hover:text-blue-700">
                                    Create your first sale →
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($sales->hasPages())
    <div>
        {{ $sales->withQueryString()->links('vendor.pagination.admin') }}
    </div>
    @endif
</div>
@endsection
