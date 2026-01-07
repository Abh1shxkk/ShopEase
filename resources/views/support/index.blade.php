@extends('layouts.shop')

@section('title', __('messages.support.title'))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-16">
    {{-- Header --}}
    <div class="text-center mb-16">
        <h1 class="font-serif text-4xl md:text-5xl mb-4">{{ __('messages.support.how_can_we_help') }}</h1>
        <p class="text-slate-600 max-w-2xl mx-auto">{{ __('messages.support.help_description') }}</p>
    </div>

    {{-- Support Options --}}
    <div class="grid md:grid-cols-3 gap-8 mb-16">
        {{-- FAQ --}}
        <a href="{{ route('support.faq') }}" class="group border border-slate-200 p-8 hover:border-slate-900 transition-colors">
            <div class="w-14 h-14 bg-slate-100 flex items-center justify-center mb-6 group-hover:bg-slate-900 transition-colors">
                <svg class="w-7 h-7 text-slate-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="font-serif text-xl mb-2">{{ __('messages.support.faq') }}</h3>
            <p class="text-slate-600 text-sm">{{ __('messages.support.faq_description') }}</p>
        </a>

        {{-- Submit Ticket --}}
        <a href="{{ route('support.ticket.create') }}" class="group border border-slate-200 p-8 hover:border-slate-900 transition-colors">
            <div class="w-14 h-14 bg-slate-100 flex items-center justify-center mb-6 group-hover:bg-slate-900 transition-colors">
                <svg class="w-7 h-7 text-slate-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="font-serif text-xl mb-2">{{ __('messages.support.submit_ticket') }}</h3>
            <p class="text-slate-600 text-sm">{{ __('messages.support.submit_ticket_description') }}</p>
        </a>

        {{-- Live Chat --}}
        <div class="group border border-slate-200 p-8 hover:border-slate-900 transition-colors cursor-pointer" onclick="openLiveChat()">
            <div class="w-14 h-14 bg-slate-100 flex items-center justify-center mb-6 group-hover:bg-slate-900 transition-colors">
                <svg class="w-7 h-7 text-slate-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h3 class="font-serif text-xl mb-2">{{ __('messages.support.live_chat') }}</h3>
            <p class="text-slate-600 text-sm">{{ __('messages.support.live_chat_description') }}</p>
        </div>
    </div>

    {{-- Popular FAQs --}}
    @if($popularFaqs->count() > 0)
    <div class="mb-16">
        <h2 class="font-serif text-2xl mb-8 text-center">{{ __('messages.support.popular_questions') }}</h2>
        <div class="space-y-4 max-w-3xl mx-auto">
            @foreach($popularFaqs as $faq)
            <div x-data="{ open: false }" class="border border-slate-200 overflow-hidden">
                <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-slate-50 transition-colors">
                    <span class="font-medium text-sm">{{ $faq->question }}</span>
                    <svg class="w-5 h-5 text-slate-400 transition-transform duration-300 ease-out" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="grid transition-all duration-300 ease-out" :class="open ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                    <div class="overflow-hidden">
                        <div class="px-5 pb-5 text-sm text-slate-600 leading-relaxed">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('support.faq') }}" class="inline-flex items-center gap-2 text-sm font-medium hover:text-slate-600 transition-colors">
                {{ __('messages.support.view_all_faqs') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
    @endif

    {{-- Track Ticket --}}
    <div class="bg-slate-50 p-8 md:p-12 text-center">
        <h2 class="font-serif text-2xl mb-4">{{ __('messages.support.already_submitted') }}</h2>
        <p class="text-slate-600 mb-6">{{ __('messages.support.track_description') }}</p>
        <a href="{{ route('support.ticket.track') }}" class="inline-block bg-slate-900 text-white px-8 py-3 text-sm tracking-wider uppercase hover:bg-slate-800 transition-colors">
            {{ __('messages.support.track_ticket') }}
        </a>
    </div>

    {{-- Contact Info --}}
    <div class="mt-16 grid md:grid-cols-3 gap-8 text-center">
        <div>
            <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="font-medium mb-1">{{ __('messages.support.email_us') }}</h3>
            <p class="text-slate-600 text-sm">support@shopease.com</p>
        </div>
        <div>
            <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
            <h3 class="font-medium mb-1">{{ __('messages.support.call_us') }}</h3>
            <p class="text-slate-600 text-sm">+91 1800-123-4567</p>
        </div>
        <div>
            <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="font-medium mb-1">{{ __('messages.support.business_hours') }}</h3>
            <p class="text-slate-600 text-sm">Mon-Sat: 9AM - 8PM IST</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openLiveChat() {
    if (typeof Tawk_API !== 'undefined') {
        Tawk_API.maximize();
    } else if (typeof $crisp !== 'undefined') {
        $crisp.push(['do', 'chat:open']);
    } else {
        alert('{{ __('messages.support.chat_unavailable') }}');
    }
}
</script>
@endpush
@endsection
