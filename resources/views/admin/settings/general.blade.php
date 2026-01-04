@extends('layouts.admin')

@section('title', 'General Settings')

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">General Settings</h1>
        <p class="text-[12px] text-slate-500 mt-1">Configure your site's basic information</p>
    </div>
    <a href="{{ route('admin.settings.index') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back
    </a>
</div>

<form action="{{ route('admin.settings.general.update') }}" method="POST">
    @csrf
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Site Info -->
        <div class="bg-white border border-slate-200 p-6">
            <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Site Information</h3>
            
            <div class="space-y-5">
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Site Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                </div>
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Tagline</label>
                    <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                </div>
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Description</label>
                    <textarea name="site_description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors resize-none">{{ $settings['site_description'] }}</textarea>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white border border-slate-200 p-6">
            <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Contact Information</h3>
            
            <div class="space-y-5">
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Email</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                </div>
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Phone</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                </div>
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Address</label>
                    <textarea name="contact_address" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors resize-none">{{ $settings['contact_address'] }}</textarea>
                </div>
            </div>
        </div>

        <!-- Social Links -->
        <div class="bg-white border border-slate-200 p-6 lg:col-span-2">
            <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Social Media Links</h3>
            
            <div class="grid sm:grid-cols-3 gap-5">
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] }}" placeholder="https://instagram.com/..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
                </div>
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Facebook URL</label>
                    <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] }}" placeholder="https://facebook.com/..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
                </div>
                <div>
                    <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Twitter URL</label>
                    <input type="url" name="twitter_url" value="{{ $settings['twitter_url'] }}" placeholder="https://twitter.com/..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Save Settings</button>
    </div>
</form>
@endsection
