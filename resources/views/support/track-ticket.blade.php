@extends('layouts.shop')

@section('title', 'Track Your Ticket')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16">
    {{-- Header --}}
    <div class="text-center mb-12">
        <nav class="text-sm text-slate-500 mb-4">
            <a href="{{ route('support.index') }}" class="hover:text-slate-900">Support</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900">Track Ticket</span>
        </nav>
        <h1 class="font-serif text-4xl mb-4">Track Your Ticket</h1>
        <p class="text-slate-600">Enter your ticket number and email to view the status of your support request.</p>
    </div>

    {{-- Error Message --}}
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 mb-6 text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('support.ticket.track') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Ticket Number *</label>
            <input type="text" name="ticket_number" value="{{ old('ticket_number') }}" required placeholder="e.g., TKT-ABC123"
                class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 @error('ticket_number') border-red-500 @enderror">
            @error('ticket_number')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Email Address *</label>
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email used when creating the ticket"
                class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 @error('email') border-red-500 @enderror">
            @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-slate-900 text-white py-3 text-sm tracking-wider uppercase hover:bg-slate-800 transition-colors">
            Track Ticket
        </button>
    </form>

    <div class="mt-8 text-center">
        <p class="text-sm text-slate-600">
            Don't have a ticket yet? 
            <a href="{{ route('support.ticket.create') }}" class="text-slate-900 font-medium hover:underline">Create one now</a>
        </p>
    </div>
</div>
@endsection
