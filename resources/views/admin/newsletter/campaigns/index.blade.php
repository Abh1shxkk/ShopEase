@extends('layouts.admin')

@section('title', 'Email Campaigns')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Email Campaigns</h1>
            <p class="text-[12px] text-slate-500 mt-1">Create and send newsletters to your subscribers</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.newsletter.index') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Subscribers
            </a>
            <a href="{{ route('admin.newsletter.campaigns.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                </svg>
                New Campaign
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total</p>
            <p class="text-2xl font-light text-slate-900 mt-1">{{ $stats['total_campaigns'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Sent</p>
            <p class="text-2xl font-light text-emerald-600 mt-1">{{ $stats['sent_campaigns'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Drafts</p>
            <p class="text-2xl font-light text-amber-600 mt-1">{{ $stats['draft_campaigns'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Subscribers</p>
            <p class="text-2xl font-light text-blue-600 mt-1">{{ $stats['total_subscribers'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Emails Sent</p>
            <p class="text-2xl font-light text-purple-600 mt-1">{{ number_format($stats['total_emails_sent']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 p-6">
        <form method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="w-full lg:w-48">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Status</label>
                <select name="status" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="sending" {{ request('status') == 'sending' ? 'selected' : '' }}>Sending</option>
                </select>
            </div>
            <div class="w-full lg:w-48">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Type</label>
                <select name="type" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <option value="">All Types</option>
                    <option value="promotional" {{ request('type') == 'promotional' ? 'selected' : '' }}>Promotional</option>
                    <option value="new_arrivals" {{ request('type') == 'new_arrivals' ? 'selected' : '' }}>New Arrivals</option>
                    <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Sale</option>
                    <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                    <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Filter</button>
                @if(request()->hasAny(['status', 'type']))
                <a href="{{ route('admin.newsletter.campaigns') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Campaigns List --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Campaign</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Type</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Recipients</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Date</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($campaigns as $campaign)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.newsletter.campaigns.show', $campaign) }}" class="text-[13px] font-medium text-slate-900 hover:text-blue-600 transition-colors">
                                {{ $campaign->subject }}
                            </a>
                            <p class="text-[11px] text-slate-400 mt-0.5">by {{ $campaign->creator->name ?? 'Unknown' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[11px] font-medium text-slate-600 bg-slate-100 px-2 py-1">
                                {{ ucfirst(str_replace('_', ' ', $campaign->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'draft' => ['color' => 'amber', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                                    'sending' => ['color' => 'blue', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                    'sent' => ['color' => 'emerald', 'icon' => 'M5 13l4 4L19 7'],
                                    'failed' => ['color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                ];
                                $config = $statusConfig[$campaign->status] ?? $statusConfig['draft'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-{{ $config['color'] }}-700">
                                <span class="w-1.5 h-1.5 bg-{{ $config['color'] }}-500 rounded-full"></span>
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($campaign->status === 'sent')
                            <span class="text-[12px] text-slate-600">{{ $campaign->sent_count }}/{{ $campaign->total_recipients }}</span>
                            <span class="text-[10px] text-slate-400 ml-1">({{ $campaign->success_rate }}%)</span>
                            @else
                            <span class="text-[12px] text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] text-slate-500">
                                {{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y') : $campaign->created_at->format('M d, Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.newsletter.campaigns.show', $campaign) }}" class="text-[12px] font-medium text-slate-600 hover:text-slate-900 transition-colors">
                                View →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                                <p class="text-[13px] text-slate-500 mb-4">No campaigns yet</p>
                                <a href="{{ route('admin.newsletter.campaigns.create') }}" class="text-[12px] font-medium text-blue-600 hover:text-blue-700">
                                    Create your first campaign →
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
    @if($campaigns->hasPages())
    <div>
        {{ $campaigns->withQueryString()->links('vendor.pagination.admin') }}
    </div>
    @endif
</div>
@endsection
