@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<!-- Header -->
<div class="mb-8">
    <h1 class="text-2xl font-serif tracking-wide text-slate-900">Site Settings</h1>
    <p class="text-[12px] text-slate-500 mt-1">Manage your website content and appearance</p>
</div>

<!-- Settings Grid -->
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- General Settings -->
    <a href="{{ route('admin.settings.general') }}" class="bg-white border border-slate-200 p-6 hover:border-slate-400 transition-colors group">
        <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mb-4 group-hover:bg-slate-900 transition-colors">
            <svg class="w-6 h-6 text-slate-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">General Settings</h3>
        <p class="text-[13px] text-slate-600">Site name, contact info, social links</p>
    </a>

    <!-- Hero Banners -->
    <a href="{{ route('admin.settings.hero-banners') }}" class="bg-white border border-slate-200 p-6 hover:border-slate-400 transition-colors group">
        <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mb-4 group-hover:bg-slate-900 transition-colors">
            <svg class="w-6 h-6 text-slate-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Hero Banners</h3>
        <p class="text-[13px] text-slate-600">Homepage slider images and text</p>
    </a>

    <!-- Shop Banners -->
    <a href="{{ route('admin.settings.shop-banners') }}" class="bg-white border border-slate-200 p-6 hover:border-slate-400 transition-colors group">
        <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mb-4 group-hover:bg-slate-900 transition-colors">
            <svg class="w-6 h-6 text-slate-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Shop Banners</h3>
        <p class="text-[13px] text-slate-600">Shop page slider images and text</p>
    </a>

    <!-- Featured Sections -->
    <a href="{{ route('admin.settings.featured-sections') }}" class="bg-white border border-slate-200 p-6 hover:border-slate-400 transition-colors group">
        <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mb-4 group-hover:bg-slate-900 transition-colors">
            <svg class="w-6 h-6 text-slate-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Featured Sections</h3>
        <p class="text-[13px] text-slate-600">Category showcase, heritage, journal</p>
    </a>

    <!-- Footer Settings -->
    <a href="{{ route('admin.settings.footer') }}" class="bg-white border border-slate-200 p-6 hover:border-slate-400 transition-colors group">
        <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mb-4 group-hover:bg-slate-900 transition-colors">
            <svg class="w-6 h-6 text-slate-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Footer Settings</h3>
        <p class="text-[13px] text-slate-600">Contact info, social links, footer links</p>
    </a>
</div>
@endsection
