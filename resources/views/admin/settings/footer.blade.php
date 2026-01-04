@extends('layouts.admin')

@section('title', 'Footer Settings')

@section('content')
<div x-data="{ showDeleteModal: false, deleteId: null, activeTab: 'settings' }">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.settings.index') }}" class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-600 mb-2 inline-block">← Back to Settings</a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">Footer Settings</h1>
        <p class="text-[12px] text-slate-500 mt-1">Manage footer content, contact info, and links</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 mb-6 border-b border-slate-200">
        <button @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3 text-[11px] font-bold tracking-[0.15em] uppercase border-b-2 -mb-px transition-colors">Contact & Social</button>
        <button @click="activeTab = 'links'" :class="activeTab === 'links' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'" class="px-6 py-3 text-[11px] font-bold tracking-[0.15em] uppercase border-b-2 -mb-px transition-colors">Footer Links</button>
    </div>

    <!-- Settings Tab -->
    <div x-show="activeTab === 'settings'" class="max-w-2xl">
        <form action="{{ route('admin.settings.footer.update') }}" method="POST" class="bg-white border border-slate-200 p-8">
            @csrf
            
            <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Contact Information</h3>
            <div class="space-y-6 mb-8">
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Email</label>
                    <input type="email" name="footer_email" value="{{ old('footer_email', $settings['footer_email']) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="support@shopease.com">
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Phone</label>
                    <input type="text" name="footer_phone" value="{{ old('footer_phone', $settings['footer_phone']) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="+91 98765 43210">
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Address</label>
                    <textarea name="footer_address" rows="3" class="w-full px-4 py-3 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors resize-none" placeholder="123 Commerce Street, Mumbai, MH 400001">{{ old('footer_address', $settings['footer_address']) }}</textarea>
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Copyright Text</label>
                    <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings['footer_copyright']) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="© 2026 ShopEase. All rights reserved.">
                </div>
            </div>

            <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6 pt-6 border-t border-slate-100">Social Media Links</h3>
            <div class="space-y-6">
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="https://instagram.com/shopease">
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Facebook URL</label>
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="https://facebook.com/shopease">
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Twitter/X URL</label>
                    <input type="url" name="twitter_url" value="{{ old('twitter_url', $settings['twitter_url']) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="https://twitter.com/shopease">
                </div>
                <div>
                    <label class="block text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">YouTube URL</label>
                    <input type="url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url']) }}" class="w-full h-12 px-4 border border-slate-200 text-[14px] focus:outline-none focus:border-slate-900 transition-colors" placeholder="https://youtube.com/@shopease">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100">
                <button type="submit" class="h-12 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Save Settings</button>
            </div>
        </form>
    </div>

    <!-- Links Tab -->
    <div x-show="activeTab === 'links'">
        <div class="flex justify-end mb-6">
            <a href="{{ route('admin.settings.footer-links.create') }}" class="h-10 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Link
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach(['shop' => 'Shop Links', 'account' => 'Account Links', 'info' => 'Info Links'] as $group => $title)
            <div class="bg-white border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">{{ $title }}</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($links[$group] ?? [] as $link)
                    <div class="flex items-center justify-between px-6 py-3 hover:bg-slate-50 transition-colors">
                        <div>
                            <p class="text-[13px] font-medium text-slate-900">{{ $link->title }}</p>
                            <p class="text-[11px] text-slate-400 truncate max-w-[150px]">{{ $link->url }}</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.settings.footer-links.edit', $link) }}" class="p-1.5 text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <button @click="showDeleteModal = true; deleteId = {{ $link->id }}" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-[12px] text-slate-400">No links in this group</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        <div @click="showDeleteModal = false" class="absolute inset-0 bg-black/50"></div>
        <div class="relative bg-white p-8 max-w-md w-full mx-4">
            <h3 class="text-lg font-serif mb-2">Delete Link</h3>
            <p class="text-[13px] text-slate-600 mb-6">Are you sure you want to delete this footer link?</p>
            <div class="flex gap-3">
                <button @click="showDeleteModal = false" class="flex-1 h-10 border border-slate-200 text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-50 transition-colors">Cancel</button>
                <form :action="'{{ url('admin/settings/footer-links') }}/' + deleteId" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full h-10 bg-red-600 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-red-700 transition-colors">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
