@extends('layouts.shop')

@section('title', 'My Support Tickets')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-16">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <nav class="text-sm text-slate-500 mb-2">
                <a href="{{ route('support.index') }}" class="hover:text-slate-900">Support</a>
                <span class="mx-2">/</span>
                <span class="text-slate-900">My Tickets</span>
            </nav>
            <h1 class="font-serif text-3xl">My Support Tickets</h1>
        </div>
        <a href="{{ route('support.ticket.create') }}" class="bg-slate-900 text-white px-6 py-3 text-sm tracking-wider uppercase hover:bg-slate-800 transition-colors">
            New Ticket
        </a>
    </div>

    @if($tickets->count() > 0)
    <div class="border border-slate-200">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left py-4 px-6 text-xs font-medium tracking-wider uppercase text-slate-600">Ticket</th>
                    <th class="text-left py-4 px-6 text-xs font-medium tracking-wider uppercase text-slate-600 hidden md:table-cell">Category</th>
                    <th class="text-left py-4 px-6 text-xs font-medium tracking-wider uppercase text-slate-600">Status</th>
                    <th class="text-left py-4 px-6 text-xs font-medium tracking-wider uppercase text-slate-600 hidden sm:table-cell">Date</th>
                    <th class="py-4 px-6"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($tickets as $ticket)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-4 px-6">
                        <p class="font-medium text-sm">{{ $ticket->subject }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $ticket->ticket_number }}</p>
                    </td>
                    <td class="py-4 px-6 hidden md:table-cell">
                        <span class="text-sm text-slate-600">{{ \App\Models\SupportTicket::CATEGORIES[$ticket->category] ?? $ticket->category }}</span>
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
                        <span class="inline-block px-3 py-1 text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ \App\Models\SupportTicket::STATUSES[$ticket->status] ?? $ticket->status }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm text-slate-600 hidden sm:table-cell">
                        {{ $ticket->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="{{ route('support.ticket.show', $ticket) }}" class="text-sm font-medium hover:text-slate-600 transition-colors">
                            View →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $tickets->links('vendor.pagination.luxury') }}
    </div>
    @else
    <div class="text-center py-16 border border-slate-200">
        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="font-serif text-xl mb-2">No Tickets Yet</h3>
        <p class="text-slate-600 mb-6">You haven't submitted any support tickets.</p>
        <a href="{{ route('support.ticket.create') }}" class="inline-block bg-slate-900 text-white px-8 py-3 text-sm tracking-wider uppercase hover:bg-slate-800 transition-colors">
            Create Your First Ticket
        </a>
    </div>
    @endif
</div>
@endsection
