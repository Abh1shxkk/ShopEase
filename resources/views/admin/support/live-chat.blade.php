@extends('layouts.admin')

@section('title', 'Live Chat Settings')

@section('content')
<div class="max-w-2xl" x-data="{ provider: '{{ $settings['live_chat_provider'] ?? 'none' }}' }">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-serif text-2xl">Live Chat Settings</h1>
        <p class="text-sm text-slate-500 mt-1">Configure Tawk.to or Crisp live chat integration</p>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.support.live-chat.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Enable/Disable --}}
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-medium">Enable Live Chat</h3>
                    <p class="text-sm text-slate-500 mt-1">Show live chat widget on your store</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="live_chat_enabled" value="1" {{ $settings['live_chat_enabled'] ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-900"></div>
                </label>
            </div>
        </div>

        {{-- Provider Selection --}}
        <div class="bg-white border border-slate-200 p-6">
            <h3 class="font-medium mb-4">Chat Provider</h3>
            <div class="space-y-4">
                <label class="flex items-start gap-4 p-4 border cursor-pointer transition-colors"
                       :class="provider === 'tawk' ? 'border-slate-900 bg-slate-50' : 'border-slate-200 hover:border-slate-400'">
                    <input type="radio" name="live_chat_provider" value="tawk" x-model="provider" class="mt-1">
                    <div>
                        <p class="font-medium">Tawk.to</p>
                        <p class="text-sm text-slate-500 mt-1">Free live chat software with unlimited agents and chat history</p>
                    </div>
                </label>
                <label class="flex items-start gap-4 p-4 border cursor-pointer transition-colors"
                       :class="provider === 'crisp' ? 'border-slate-900 bg-slate-50' : 'border-slate-200 hover:border-slate-400'">
                    <input type="radio" name="live_chat_provider" value="crisp" x-model="provider" class="mt-1">
                    <div>
                        <p class="font-medium">Crisp</p>
                        <p class="text-sm text-slate-500 mt-1">Modern customer messaging platform with chatbot capabilities</p>
                    </div>
                </label>
                <label class="flex items-start gap-4 p-4 border cursor-pointer transition-colors"
                       :class="provider === 'none' ? 'border-slate-900 bg-slate-50' : 'border-slate-200 hover:border-slate-400'">
                    <input type="radio" name="live_chat_provider" value="none" x-model="provider" class="mt-1">
                    <div>
                        <p class="font-medium">None</p>
                        <p class="text-sm text-slate-500 mt-1">Disable live chat integration</p>
                    </div>
                </label>
            </div>
        </div>

        {{-- Tawk.to Settings --}}
        <div x-show="provider === 'tawk'" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white border border-slate-200 p-6">
            <h3 class="font-medium mb-4">Tawk.to Configuration</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Property ID *</label>
                    <input type="text" name="tawk_property_id" value="{{ $settings['tawk_property_id'] ?? '' }}" placeholder="e.g., 1234567890abcdef12345678"
                        class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900">
                    <p class="text-xs text-slate-500 mt-1">Found in Tawk.to Dashboard → Administration → Chat Widget</p>
                </div>
                <div>
                    <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Widget ID</label>
                    <input type="text" name="tawk_widget_id" value="{{ $settings['tawk_widget_id'] ?? '1i' }}" placeholder="e.g., 1i or default"
                        class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900">
                    <p class="text-xs text-slate-500 mt-1">Usually "1i" - found in the widget embed code after the property ID</p>
                </div>
            </div>
            <div class="mt-4 p-4 bg-blue-50 border border-blue-100 text-sm">
                <p class="font-medium mb-2 text-blue-900">How to get your Tawk.to credentials:</p>
                <ol class="list-decimal list-inside space-y-1 text-blue-800">
                    <li>Sign up at <a href="https://www.tawk.to" target="_blank" class="underline">tawk.to</a></li>
                    <li>Go to Administration → Channels → Chat Widget</li>
                    <li>Look at the embed code: <code class="bg-blue-100 px-1">embed.tawk.to/<strong>PROPERTY_ID</strong>/<strong>WIDGET_ID</strong></code></li>
                    <li>Copy the Property ID and Widget ID from the URL</li>
                </ol>
            </div>
        </div>

        {{-- Crisp Settings --}}
        <div x-show="provider === 'crisp'" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white border border-slate-200 p-6">
            <h3 class="font-medium mb-4">Crisp Configuration</h3>
            <div>
                <label class="block text-xs font-medium tracking-wider uppercase text-slate-600 mb-2">Website ID *</label>
                <input type="text" name="crisp_website_id" value="{{ $settings['crisp_website_id'] ?? '' }}" placeholder="e.g., 12345678-1234-1234-1234-123456789012"
                    class="w-full border border-slate-200 py-3 px-4 text-sm focus:outline-none focus:border-slate-900">
                <p class="text-xs text-slate-500 mt-1">Found in Crisp Dashboard → Settings → Website Settings</p>
            </div>
            <div class="mt-4 p-4 bg-purple-50 border border-purple-100 text-sm">
                <p class="font-medium mb-2 text-purple-900">How to get your Crisp Website ID:</p>
                <ol class="list-decimal list-inside space-y-1 text-purple-800">
                    <li>Sign up at <a href="https://crisp.chat" target="_blank" class="underline">crisp.chat</a></li>
                    <li>Go to Settings → Website Settings → Setup Instructions</li>
                    <li>Find <code class="bg-purple-100 px-1">CRISP_WEBSITE_ID</code> in the code snippet</li>
                    <li>Copy the UUID (looks like: 12345678-1234-1234-1234-123456789012)</li>
                </ol>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.support.tickets') }}" class="px-6 py-3 text-sm text-slate-600 hover:text-slate-900">Cancel</a>
            <button type="submit" class="bg-slate-900 text-white px-8 py-3 text-sm tracking-wider uppercase hover:bg-slate-800 transition-colors">
                Save Settings
            </button>
        </div>
    </form>

    {{-- Test Section --}}
    <div class="mt-8 bg-slate-50 border border-slate-200 p-6">
        <h3 class="font-medium mb-2">Test Your Live Chat</h3>
        <p class="text-sm text-slate-600 mb-4">After saving your settings, visit your store's frontend to see the live chat widget in action.</p>
        <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800">
            <span>Open Store Frontend</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>
</div>
@endsection
