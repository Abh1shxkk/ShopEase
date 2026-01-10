@extends('layouts.shop')

@section('title', 'Account Suspended')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-white">
    {{-- Hero Section --}}
    <div class="relative bg-slate-900 text-white py-16 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="max-w-6xl mx-auto px-6 text-center relative">
            <div class="inline-flex items-center gap-2 bg-red-500/20 text-red-300 px-4 py-2 text-xs font-bold tracking-wider uppercase mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Account Status
            </div>
            <h1 class="text-4xl md:text-5xl font-serif mb-4">Account Suspended</h1>
            <p class="text-slate-300 max-w-2xl mx-auto">Your seller account has been suspended.</p>
        </div>
    </div>

    {{-- Status Card --}}
    <div class="max-w-xl mx-auto px-6 -mt-8 relative z-10">
        <div class="bg-white shadow-lg border border-slate-200 p-8 text-center">
            {{-- Icon --}}
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <h2 class="text-2xl font-serif text-slate-900 mb-3">Seller Account Suspended</h2>
            <p class="text-slate-600 mb-6">
                Your seller account has been suspended. You cannot access the seller dashboard at this time.
            </p>

            @if(auth()->user()->seller && auth()->user()->seller->rejection_reason)
            <div class="bg-red-50 border border-red-200 p-4 mb-6 text-left">
                <p class="text-sm font-semibold text-red-800 mb-1">Reason:</p>
                <p class="text-sm text-red-700">{{ auth()->user()->seller->rejection_reason }}</p>
            </div>
            @endif

            <div class="space-y-3">
                <a href="{{ route('support.ticket.create') }}" class="block w-full px-6 py-3 bg-slate-900 text-white font-medium hover:bg-slate-800 transition">
                    Contact Support
                </a>
                <a href="{{ route('home') }}" class="block w-full px-6 py-3 border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
                    Back to Home
                </a>
            </div>
        </div>
    </div>

    {{-- Help Section --}}
    <div class="max-w-xl mx-auto px-6 py-16">
        <div class="bg-slate-50 border border-slate-200 p-6">
            <h3 class="text-lg font-serif text-slate-900 mb-4">What can you do?</h3>
            <ul class="space-y-3 text-sm text-slate-600">
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span>Contact our support team to understand the reason for suspension</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Provide any required documentation or clarification</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Appeal the decision if you believe it was made in error</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
