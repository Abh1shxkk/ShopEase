@extends('layouts.admin')

@section('title', 'Newsletter Subscribers')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Newsletter Subscribers</h1>
            <p class="text-[12px] text-slate-500 mt-1">Manage your email subscribers</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.newsletter.campaigns') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                Campaigns
            </a>
            <a href="{{ route('admin.newsletter.export') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Subscribers</p>
                    <p class="text-3xl font-light text-slate-900 mt-2">{{ number_format($stats['total']) }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Active</p>
                    <p class="text-3xl font-light text-emerald-600 mt-2">{{ number_format($stats['active']) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Unsubscribed</p>
                    <p class="text-3xl font-light text-slate-400 mt-2">{{ number_format($stats['unsubscribed']) }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 p-6">
        <form method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by email..." 
                    class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
            </div>
            <div class="w-full lg:w-48">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Status</label>
                <select name="status" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Filter</button>
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.newsletter.index') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Email</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Name</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Subscribed</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subscribers as $subscriber)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-[13px] text-slate-900">{{ $subscriber->email }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[13px] text-slate-500">{{ $subscriber->name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($subscriber->status === 'active')
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-emerald-700">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-400">
                                <span class="w-1.5 h-1.5 bg-slate-300 rounded-full"></span>
                                Unsubscribed
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] text-slate-500">{{ $subscriber->subscribed_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.newsletter.destroy', $subscriber) }}" method="POST" class="inline" onsubmit="return confirm('Delete this subscriber?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[12px] font-medium text-red-600 hover:text-red-700 transition-colors">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-[13px] text-slate-500">No subscribers found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($subscribers->hasPages())
    <div>
        {{ $subscribers->withQueryString()->links('vendor.pagination.admin') }}
    </div>
    @endif
</div>
@endsection
