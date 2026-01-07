@extends('layouts.admin')

@section('title', isset($campaign) ? 'Edit Campaign' : 'Create Campaign')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.newsletter.campaigns') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Campaigns
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ isset($campaign) ? 'Edit Campaign' : 'Create Campaign' }}</h1>
        <p class="text-[12px] text-slate-500 mt-1">
            @if(isset($campaign))
                Update your email campaign
            @else
                Send to {{ $subscriberCount }} active subscribers
            @endif
        </p>
    </div>

    <form action="{{ isset($campaign) ? route('admin.newsletter.campaigns.update', $campaign) : route('admin.newsletter.campaigns.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($campaign))
            @method('PUT')
        @endif

        {{-- Campaign Type --}}
        <div class="bg-white border border-slate-200 p-6">
            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-4">Campaign Type</label>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                @foreach(['promotional' => 'Promotional', 'new_arrivals' => 'New Arrivals', 'sale' => 'Sale', 'announcement' => 'Announcement', 'custom' => 'Custom'] as $value => $label)
                <label class="relative cursor-pointer group">
                    <input type="radio" name="type" value="{{ $value }}" class="peer sr-only" {{ old('type', $campaign->type ?? 'custom') == $value ? 'checked' : '' }}>
                    <div class="px-4 py-3 text-center border border-slate-200 text-[11px] font-bold tracking-[0.1em] uppercase text-slate-600 peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white group-hover:border-slate-300 transition-all">
                        {{ $label }}
                    </div>
                </label>
                @endforeach
            </div>
            @error('type')
            <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Subject --}}
        <div class="bg-white border border-slate-200 p-6">
            <label for="subject" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Email Subject</label>
            <input type="text" name="subject" id="subject" value="{{ old('subject', $campaign->subject ?? '') }}" 
                class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors"
                placeholder="e.g., 🎉 New Arrivals Just Dropped!" required>
            @error('subject')
            <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Content --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <label for="content" class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Email Content</label>
                <span class="text-[10px] text-slate-400">HTML supported</span>
            </div>
            <textarea name="content" id="content" rows="16" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 font-mono transition-colors"
                placeholder="Write your email content here..." required>{{ old('content', $campaign->content ?? '') }}</textarea>
            @error('content')
            <p class="mt-2 text-[12px] text-red-600">{{ $message }}</p>
            @enderror
            
            {{-- Quick Templates --}}
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-3">Quick Templates</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="insertTemplate('sale')" class="px-3 py-1.5 text-[10px] font-bold tracking-[0.1em] uppercase text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Sale Announcement
                    </button>
                    <button type="button" onclick="insertTemplate('newArrivals')" class="px-3 py-1.5 text-[10px] font-bold tracking-[0.1em] uppercase text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                        New Arrivals
                    </button>
                    <button type="button" onclick="insertTemplate('welcome')" class="px-3 py-1.5 text-[10px] font-bold tracking-[0.1em] uppercase text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Welcome Email
                    </button>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('admin.newsletter.campaigns') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">
                Cancel
            </a>
            <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                {{ isset($campaign) ? 'Update Campaign' : 'Create Campaign' }}
            </button>
        </div>
    </form>
</div>

<script>
const templates = {
    sale: `<p>Hi there! 👋</p>

<p>We're excited to announce our <strong>BIGGEST SALE OF THE SEASON!</strong></p>

<p>Get up to <strong>50% OFF</strong> on selected items. This is your chance to grab your favorites at unbeatable prices.</p>

<p>🔥 <strong>Sale Highlights:</strong></p>
<ul>
    <li>Up to 50% off on Women's Collection</li>
    <li>Up to 40% off on Men's Collection</li>
    <li>Free shipping on orders above ₹999</li>
</ul>

<p>Hurry! This offer is valid for a limited time only.</p>

<p>Happy Shopping!<br>Team ShopEase</p>`,

    newArrivals: `<p>Hi there! 👋</p>

<p>Fresh styles have just landed! Check out our <strong>NEW ARRIVALS</strong> collection.</p>

<p>We've curated the latest trends just for you. From elegant dresses to casual wear, there's something for everyone.</p>

<p>✨ <strong>What's New:</strong></p>
<ul>
    <li>Spring/Summer Collection 2026</li>
    <li>Exclusive Designer Pieces</li>
    <li>Limited Edition Items</li>
</ul>

<p>Be the first to shop these new styles before they sell out!</p>

<p>With love,<br>Team ShopEase</p>`,

    welcome: `<p>Welcome to the ShopEase family! 🎉</p>

<p>Thank you for subscribing to our newsletter. We're thrilled to have you with us!</p>

<p>As a subscriber, you'll be the first to know about:</p>
<ul>
    <li>Exclusive sales and promotions</li>
    <li>New product launches</li>
    <li>Style tips and trends</li>
    <li>Special member-only offers</li>
</ul>

<p>To get you started, here's a special <strong>10% OFF</strong> on your first order. Use code: <strong>WELCOME10</strong></p>

<p>Happy Shopping!<br>Team ShopEase</p>`
};

function insertTemplate(type) {
    if (templates[type]) {
        document.getElementById('content').value = templates[type];
    }
}
</script>
@endsection
