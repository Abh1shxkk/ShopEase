@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->ticket_number)

@section('content')
<div class="max-w-5xl">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('admin.support.tickets') }}" class="text-sm text-slate-500 hover:text-slate-900 mb-2 inline-block">← Back to Tickets</a>
            <h1 class="font-serif text-2xl">{{ $ticket->subject }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $ticket->ticket_number }} • {{ $ticket->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Original Message --}}
            <div class="bg-white border border-slate-200">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                    <div class="w-10 h-10 bg-slate-900 text-white flex items-center justify-center text-sm font-medium">
                        {{ substr($ticket->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-sm">{{ $ticket->name }}</p>
                        <p class="text-xs text-slate-500">{{ $ticket->email }}</p>
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
            @foreach($ticket->replies as $reply)
            <div class="bg-white border {{ $reply->is_staff_reply ? 'border-blue-200' : 'border-slate-200' }} {{ $reply->is_internal_note ? 'border-yellow-200 bg-yellow-50/30' : '' }}">
                <div class="px-6 py-4 border-b {{ $reply->is_staff_reply ? 'border-blue-200 bg-blue-50' : 'border-slate-200 bg-slate-50' }} {{ $reply->is_internal_note ? 'border-yellow-200 bg-yellow-100' : '' }} flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 {{ $reply->is_staff_reply ? 'bg-blue-600' : 'bg-slate-900' }} text-white flex items-center justify-center text-sm font-medium">
                            {{ substr($reply->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-sm">
                                {{ $reply->user->name ?? 'Unknown' }}
                                @if($reply->is_staff_reply)
                                <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs">Staff</span>
                                @endif
                                @if($reply->is_internal_note)
                                <span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs">Internal Note</span>
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

            {{-- Reply Form --}}
            <div class="bg-white border border-slate-200">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="font-medium text-sm">Add Reply</h3>
                </div>
                <form action="{{ route('admin.support.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <textarea name="message" rows="4" required placeholder="Type your reply..."
                        class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 resize-none mb-4">{{ old('message') }}</textarea>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                                <input type="file" name="attachments[]" multiple class="hidden">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                Attach
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="checkbox" name="is_internal_note" value="1" class="rounded border-slate-300">
                                Internal note (not visible to customer)
                            </label>
                        </div>
                        <button type="submit" class="bg-slate-900 text-white px-6 py-2 text-sm hover:bg-slate-800 transition-colors">
                            Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Ticket Details --}}
            <div class="bg-white border border-slate-200">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="font-medium text-sm">Ticket Details</h3>
                </div>
                <form action="{{ route('admin.support.tickets.update', $ticket) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Status</label>
                        <select name="status" class="w-full border border-slate-200 py-2 px-3 text-sm focus:outline-none focus:border-slate-900">
                            @foreach(\App\Models\SupportTicket::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ $ticket->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Priority</label>
                        <select name="priority" class="w-full border border-slate-200 py-2 px-3 text-sm focus:outline-none focus:border-slate-900">
                            @foreach(\App\Models\SupportTicket::PRIORITIES as $key => $label)
                            <option value="{{ $key }}" {{ $ticket->priority === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Assigned To</label>
                        <select name="assigned_to" class="w-full border border-slate-200 py-2 px-3 text-sm focus:outline-none focus:border-slate-900">
                            <option value="">Unassigned</option>
                            @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white py-2 text-sm hover:bg-slate-800 transition-colors">
                        Update Ticket
                    </button>
                </form>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white border border-slate-200">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="font-medium text-sm">Customer</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Name</p>
                        <p class="text-sm font-medium">{{ $ticket->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Email</p>
                        <p class="text-sm">{{ $ticket->email }}</p>
                    </div>
                    @if($ticket->user)
                    <div>
                        <a href="{{ route('admin.users.show', $ticket->user) }}" class="text-sm text-blue-600 hover:underline">View Customer Profile →</a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Related Order --}}
            @if($ticket->order)
            <div class="bg-white border border-slate-200">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h3 class="font-medium text-sm">Related Order</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Order Number</p>
                        <p class="text-sm font-medium">#{{ $ticket->order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Total</p>
                        <p class="text-sm">₹{{ number_format($ticket->order->total, 2) }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.orders.show', $ticket->order) }}" class="text-sm text-blue-600 hover:underline">View Order →</a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Category --}}
            <div class="bg-white border border-slate-200 p-6">
                <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Category</p>
                <p class="text-sm font-medium">{{ \App\Models\SupportTicket::CATEGORIES[$ticket->category] ?? $ticket->category }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
