@extends('layouts.shop')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-16">
    {{-- Header --}}
    <div class="text-center mb-12">
        <nav class="text-sm text-slate-500 mb-4">
            <a href="{{ route('support.index') }}" class="hover:text-slate-900">Support</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900">FAQ</span>
        </nav>
        <h1 class="font-serif text-4xl md:text-5xl mb-4">Frequently Asked Questions</h1>
        <p class="text-slate-600 max-w-2xl mx-auto">Find answers to common questions about our products, orders, shipping, and more.</p>
    </div>

    {{-- Search --}}
    <div class="max-w-2xl mx-auto mb-12">
        <form action="{{ route('support.faq') }}" method="GET" class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for answers..." 
                class="w-full border border-slate-200 py-4 px-6 pr-12 text-sm focus:outline-none focus:border-slate-900">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </form>
    </div>

    <div class="grid lg:grid-cols-4 gap-12">
        {{-- Categories Sidebar --}}
        <div class="lg:col-span-1">
            <h3 class="font-medium text-sm tracking-wider uppercase mb-4">Categories</h3>
            <nav class="space-y-2">
                <a href="{{ route('support.faq') }}" 
                    class="block py-2 px-3 text-sm {{ !$selectedCategory ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} transition-colors">
                    All Questions
                </a>
                @foreach($categories as $category)
                <a href="{{ route('support.faq', ['category' => $category->slug]) }}" 
                    class="block py-2 px-3 text-sm {{ $selectedCategory && $selectedCategory->id === $category->id ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }} transition-colors">
                    {{ $category->name }}
                    <span class="text-xs opacity-60">({{ $category->activeFaqs->count() }})</span>
                </a>
                @endforeach
            </nav>
        </div>

        {{-- FAQs --}}
        <div class="lg:col-span-3">
            @if(request('search'))
            <p class="text-sm text-slate-600 mb-6">
                Showing results for "<strong>{{ request('search') }}</strong>"
                <a href="{{ route('support.faq') }}" class="text-slate-900 underline ml-2">Clear</a>
            </p>
            @endif

            @if($faqs->count() > 0)
            <div class="space-y-4">
                @foreach($faqs as $faq)
                <div x-data="{ open: false }" class="border border-slate-200 overflow-hidden">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-slate-50 transition-colors">
                        <span class="font-medium text-sm pr-4">{{ $faq->question }}</span>
                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0 transition-transform duration-300 ease-out" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="grid transition-all duration-300 ease-out" :class="open ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                        <div class="overflow-hidden">
                            <div class="px-5 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-4">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                            {{-- Feedback --}}
                            <div class="px-5 pb-5 flex items-center gap-4 text-xs text-slate-500" x-data="{ voted: false }">
                                <span>Was this helpful?</span>
                                <template x-if="!voted">
                                    <div class="flex items-center gap-2">
                                        <button @click="fetch('{{ route('support.faq.feedback', $faq) }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ helpful: true }) }); voted = true" 
                                            class="flex items-center gap-1 px-2 py-1 border border-slate-200 hover:border-green-500 hover:text-green-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                            Yes
                                        </button>
                                        <button @click="fetch('{{ route('support.faq.feedback', $faq) }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ helpful: false }) }); voted = true" 
                                            class="flex items-center gap-1 px-2 py-1 border border-slate-200 hover:border-red-500 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/></svg>
                                            No
                                        </button>
                                    </div>
                                </template>
                                <template x-if="voted">
                                    <span class="text-green-600">Thanks for your feedback!</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 border border-slate-200">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-slate-600">No FAQs found matching your criteria.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Still Need Help --}}
    <div class="mt-16 bg-slate-50 p-8 md:p-12 text-center">
        <h2 class="font-serif text-2xl mb-4">Still Need Help?</h2>
        <p class="text-slate-600 mb-6">Can't find what you're looking for? Our support team is here to help.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('support.ticket.create') }}" class="inline-block bg-slate-900 text-white px-8 py-3 text-sm tracking-wider uppercase hover:bg-slate-800 transition-colors">
                Submit a Ticket
            </a>
            <button onclick="openLiveChat()" class="inline-block border border-slate-900 text-slate-900 px-8 py-3 text-sm tracking-wider uppercase hover:bg-slate-900 hover:text-white transition-colors">
                Start Live Chat
            </button>
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
        alert('Live chat is currently unavailable. Please submit a ticket instead.');
    }
}
</script>
@endpush
@endsection
