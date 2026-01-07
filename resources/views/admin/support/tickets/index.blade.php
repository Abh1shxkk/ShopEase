@extends('layouts.admin')

@section('title', 'Support Tickets')

@section('content')
{{-- Header --}}
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="font-serif text-2xl">Support Tickets</h1>
        <p class="text-sm text-slate-500 mt-1">Manage customer support requests</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white border border-slate-200 p-4">
        <p class="text-xs text-slate-500 uppercase tracking-wider">Open</p>
        <p class="text-2xl font-semibold text-blue-600 mt-1">{{ $stats['open'] }}</p>
    </div>
    <div class="bg-white border border-slate-200 p-4">
        <p class="text-xs text-slate-500 uppercase tracking-wider">In Progress</p>
        <p class="text-2xl font-semibold text-yellow-600 mt-1">{{ $stats['in_progress'] }}</p>
    </div>
    <div class="bg-white border border-slate-200 p-4">
        <p class="text-xs text-slate-500 uppercase tracking-wider">Waiting</p>
        <p class="text-2xl font-semibold text-orange-600 mt-1">{{ $stats['waiting'] }}</p>
    </div>
    <div class="bg-white border border-slate-200 p-4">
        <p class="text-xs text-slate-500 uppercase tracking-wider">Resolved</p>
        <p class="text-2xl font-semibold text-green-600 mt-1">{{ $stats['resolved'] }}</p>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white border border-slate-200 p-4 mb-6">
    <form action="{{ route('admin.support.tickets') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tickets..."
                class="w-full border border-slate-200 py-2 px-3 text-sm focus:outline-none focus:border-slate-900">
        </div>
        <select name="status" class="border border-slate-200 py-2 px-3 text-sm focus:outline-none focus:border-slate-900">
            <option value="">All Status</option>
            @foreach(\App\Models\SupportTicket::STATUSES as $key => $label)
            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="category" class="border border-slate-200 py-2 px-3 text-sm focus:outline-none focus:border-slate-900">
            <option value="">All Categories</option>
            @foreach(\App\Models\SupportTicket::CATEGORIES as $key => $label)
            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="priority" class="border border-slate-200 py-2 px-3 text-sm focus:outline-none focus:border-slate-900">
            <option value="">All Priorities</option>
            @foreach(\App\Models\SupportTicket::PRIORITIES as $key => $label)
            <option value="{{ $key }}" {{ request('priority') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-slate-900 text-white px-4 py-2 text-sm hover:bg-slate-800 transition-colors">
            Filter
        </button>
        @if(request()->hasAny(['search', 'status', 'category', 'priority']))
        <a href="{{ route('admin.support.tickets') }}" class="border border-slate-200 px-4 py-2 text-sm hover:bg-slate-50 transition-colors">
            Clear
        </a>
        @endif
    </form>
</div>

{{-- Tickets Table --}}
<div class="bg-white border border-slate-200">
    @if($tickets->count() > 0)
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500">Ticket</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500 hidden md:table-cell">Customer</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500 hidden lg:table-cell">Category</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500">Priority</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500">Status</th>
                <th class="text-left py-4 px-6 text-[10px] font-bold tracking-wider uppercase text-slate-500 hidden sm:table-cell">Date</th>
                <th class="py-4 px-6"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($tickets as $ticket)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="py-4 px-6">
                    <p class="font-medium text-sm">{{ Str::limit($ticket->subject, 40) }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $ticket->ticket_number }}</p>
                </td>
                <td class="py-4 px-6 hidden md:table-cell">
                    <p class="text-sm">{{ $ticket->name }}</p>
                    <p class="text-xs text-slate-500">{{ $ticket->email }}</p>
                </td>
                <td class="py-4 px-6 hidden lg:table-cell">
                    <span class="text-sm text-slate-600">{{ \App\Models\SupportTicket::CATEGORIES[$ticket->category] ?? $ticket->category }}</span>
                </td>
                <td class="py-4 px-6">
                    @php
                        $priorityColors = [
                            'low' => 'bg-green-100 text-green-700',
                            'medium' => 'bg-yellow-100 text-yellow-700',
                            'high' => 'bg-orange-100 text-orange-700',
                            'urgent' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <span class="inline-block px-2 py-1 text-[10px] font-medium {{ $priorityColors[$ticket->priority] ?? 'bg-slate-100' }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </td>
                <td class="py-4 px-6">
                    @php
                        $statusColors = [
                            'open' => 'bg-blue-100 text-blue-700',
                            'in_progress' => 'bg-yellow-100 text-yellow-700',
                            'waiting' => 'bg-orange-100 text-orange-700',
                            'resolved' => 'bg-green-100 text-green-700',
                            'closed' => 'bg-slate-100 text-slate-600',
                        ];
                    @endphp
                    <span class="inline-block px-2 py-1 text-[10px] font-medium {{ $statusColors[$ticket->status] ?? 'bg-slate-100' }}">
                        {{ \App\Models\SupportTicket::STATUSES[$ticket->status] ?? $ticket->status }}
                    </span>
                </td>
                <td class="py-4 px-6 text-sm text-slate-600 hidden sm:table-cell">
                    {{ $ticket->created_at->format('M d, Y') }}
                </td>
                <td class="py-4 px-6 text-right">
                    <a href="{{ route('admin.support.tickets.show', $ticket) }}" class="text-sm font-medium hover:text-slate-600 transition-colors">
                        View →
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="text-center py-12">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-slate-600">No tickets found.</p>
    </div>
    @endif
</div>

<div class="mt-6">
    {{ $tickets->withQueryString()->links('vendor.pagination.admin') }}
</div>
@endsection
