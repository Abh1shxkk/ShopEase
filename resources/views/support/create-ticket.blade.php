@extends('layouts.shop')

@section('title', 'Submit Support Ticket')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16">
    {{-- Header --}}
    <div class="text-center mb-12">
        <nav class="text-sm text-slate-500 mb-4">
            <a href="{{ route('support.index') }}" class="hover:text-slate-900">Support</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900">Submit Ticket</span>
        </nav>
        <h1 class="font-serif text-4xl mb-4">Submit a Support Ticket</h1>
        <p class="text-slate-600">Fill out the form below and our team will respond within 24 hours.</p>
    </div>

    {{-- Form --}}
    <form action="{{ route('support.ticket.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid md:grid-cols-2 gap-6">
            {{-- Name --}}
            <div>
                <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Your Name *</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                    class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 @error('name') border-red-500 @enderror">
                @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                    class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 @error('email') border-red-500 @enderror">
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Subject --}}
        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Subject *</label>
            <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="Brief description of your issue"
                class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 @error('subject') border-red-500 @enderror">
            @error('subject')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            {{-- Category --}}
            <div>
                <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Category *</label>
                <select name="category" required class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 @error('category') border-red-500 @enderror">
                    <option value="">Select a category</option>
                    @foreach(\App\Models\SupportTicket::CATEGORIES as $key => $label)
                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Related Order --}}
            @auth
            @if($orders->count() > 0)
            <div>
                <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Related Order (Optional)</label>
                <select name="order_id" class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900">
                    <option value="">Select an order</option>
                    @foreach($orders as $order)
                    <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                        #{{ $order->order_number }} - ₹{{ number_format($order->total, 2) }} ({{ $order->created_at->format('M d, Y') }})
                    </option>
                    @endforeach
                </select>
            </div>
            @endif
            @endauth
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Description *</label>
            <textarea name="description" rows="6" required placeholder="Please describe your issue in detail..."
                class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900 resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-slate-500 mt-1">Minimum 20 characters</p>
        </div>

        {{-- Attachments --}}
        <div>
            <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Attachments (Optional)</label>
            <div class="border border-dashed border-slate-300 p-6 text-center" x-data="{ files: [] }">
                <input type="file" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx" 
                    class="hidden" id="attachments" @change="files = Array.from($event.target.files)">
                <label for="attachments" class="cursor-pointer">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm text-slate-600">Click to upload or drag and drop</p>
                    <p class="text-xs text-slate-400 mt-1">JPG, PNG, GIF, PDF, DOC up to 5MB each</p>
                </label>
                <template x-if="files.length > 0">
                    <div class="mt-4 text-left">
                        <template x-for="file in files" :key="file.name">
                            <div class="flex items-center gap-2 text-sm text-slate-600 py-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                <span x-text="file.name"></span>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
            @error('attachments.*')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('support.index') }}" class="text-sm text-slate-600 hover:text-slate-900">
                ← Back to Support
            </a>
            <button type="submit" class="bg-slate-900 text-white px-8 py-3 text-sm tracking-wider uppercase hover:bg-slate-800 transition-colors">
                Submit Ticket
            </button>
        </div>
    </form>
</div>
@endsection
