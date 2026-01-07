@extends('layouts.shop')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    {{-- Header --}}
    <div class="mb-8">
        <nav class="text-sm text-slate-500 mb-4">
            <a href="{{ route('support.index') }}" class="hover:text-slate-900">Support</a>
            <span class="mx-2">/</span>
            @auth
            <a href="{{ route('support.tickets') }}" class="hover:text-slate-900">My Tickets</a>
            <span class="mx-2">/</span>
            @endauth
            <span class="text-slate-900">{{ $ticket->ticket_number }}</span>
        </nav>
        
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <h1 class="font-serif text-2xl md:text-3xl mb-2">{{ $ticket->subject }}</h1>
                <p class="text-sm text-slate-500">Ticket #{{ $ticket->ticket_number }} • Created {{ $ticket->created_at->format('M d, Y \a\t h:i A') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $statusColors = [
                        'open' => 'bg-blue-100 text-blue-700',
                        'in_progress' => 'bg-yellow-100 text-yellow-700',
                        'waiting' => 'bg-orange-100 text-orange-700',
                        'resolved' => 'bg-green-100 text-green-700',
                        'closed' => 'bg-slate-100 text-slate-600',
                    ];
                    $priorityColors = [
                        'low' => 'bg-green-100 text-green-700',
                        'medium' => 'bg-yellow-100 text-yellow-700',
                        'high' => 'bg-orange-100 text-orange-700',
                        'urgent' => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <span class="px-3 py-1 text-xs font-medium {{ $priorityColors[$ticket->priority] ?? 'bg-slate-100' }}">
                    {{ ucfirst($ticket->priority) }}
                </span>
                <span class="px-3 py-1 text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-slate-100' }}">
                    {{ \App\Models\SupportTicket::STATUSES[$ticket->status] ?? $ticket->status }}
                </span>
            </div>
        </div>
    </div>

    {{-- Ticket Info --}}
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="border border-slate-200 p-4">
            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Category</p>
            <p class="font-medium text-sm">{{ \App\Models\SupportTicket::CATEGORIES[$ticket->category] ?? $ticket->category }}</p>
        </div>
        @if($ticket->order)
        <div class="border border-slate-200 p-4">
            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Related Order</p>
            <a href="{{ route('orders.show', $ticket->order) }}" class="font-medium text-sm hover:text-slate-600">#{{ $ticket->order->order_number }}</a>
        </div>
        @endif
        <div class="border border-slate-200 p-4">
            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Last Updated</p>
            <p class="font-medium text-sm">{{ $ticket->updated_at->diffForHumans() }}</p>
        </div>
    </div>

    {{-- Original Message --}}
    <div class="border border-slate-200 mb-8">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-900 text-white flex items-center justify-center text-sm font-medium">
                    {{ substr($ticket->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-medium text-sm">{{ $ticket->name }}</p>
                    <p class="text-xs text-slate-500">{{ $ticket->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="prose prose-sm max-w-none text-slate-600">
                {!! nl2br(e($ticket->description)) !!}
            </div>
            @if($ticket->attachments->count() > 0)
            <div class="mt-6 pt-6 border-t border-slate-100">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-3">Attachments</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($ticket->attachments as $attachment)
                    <a href="{{ Storage::url($attachment->path) }}" target="_blank" 
                        class="flex items-center gap-2 px-3 py-2 bg-slate-50 text-sm hover:bg-slate-100 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        {{ $attachment->filename }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Replies --}}
    @if($ticket->replies->count() > 0)
    <div class="space-y-6 mb-8">
        @foreach($ticket->replies as $reply)
        <div class="border {{ $reply->is_staff_reply ? 'border-blue-200 bg-blue-50/30' : 'border-slate-200' }}">
            <div class="px-6 py-4 border-b {{ $reply->is_staff_reply ? 'border-blue-200 bg-blue-50' : 'border-slate-200 bg-slate-50' }} flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $reply->is_staff_reply ? 'bg-blue-600' : 'bg-slate-900' }} text-white flex items-center justify-center text-sm font-medium">
                        {{ substr($reply->user->name ?? 'S', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-sm">
                            {{ $reply->user->name ?? 'Support Team' }}
                            @if($reply->is_staff_reply)
                            <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs">Staff</span>
                            @endif
                        </p>
                        <p class="text-xs text-slate-500">{{ $reply->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="prose prose-sm max-w-none text-slate-600">
                    {!! nl2br(e($reply->message)) !!}
                </div>
                @if($reply->attachments->count() > 0)
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-3">Attachments</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($reply->attachments as $attachment)
                        <a href="{{ Storage::url($attachment->path) }}" target="_blank" 
                            class="flex items-center gap-2 px-3 py-2 bg-slate-50 text-sm hover:bg-slate-100 transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            {{ $attachment->filename }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Reply Form --}}
    @if(!in_array($ticket->status, ['closed', 'resolved']))
    @auth
    <div class="border border-slate-200">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
            <h3 class="font-medium">Add a Reply</h3>
        </div>
        <form action="{{ route('support.ticket.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <textarea name="message" rows="4" required placeholder="Type your reply here..."
                class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 resize-none mb-4 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
            @error('message')
            <p class="text-red-500 text-xs mb-4">{{ $message }}</p>
            @enderror
            
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                    <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx" class="hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    Attach files
                </label>
                <button type="submit" class="bg-slate-900 text-white px-6 py-2 text-sm tracking-wider uppercase hover:bg-slate-800 transition-colors">
                    Send Reply
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="bg-slate-50 p-6 text-center">
        <p class="text-slate-600 mb-4">Please log in to reply to this ticket.</p>
        <a href="{{ route('login') }}" class="inline-block bg-slate-900 text-white px-6 py-2 text-sm tracking-wider uppercase hover:bg-slate-800 transition-colors">
            Log In
        </a>
    </div>
    @endauth
    @else
    <div class="bg-slate-50 p-6 text-center">
        <p class="text-slate-600">This ticket has been {{ $ticket->status }}. If you need further assistance, please create a new ticket.</p>
        <a href="{{ route('support.ticket.create') }}" class="inline-block mt-4 bg-slate-900 text-white px-6 py-2 text-sm tracking-wider uppercase hover:bg-slate-800 transition-colors">
            Create New Ticket
        </a>
    </div>
    @endif
</div>
@endsection
