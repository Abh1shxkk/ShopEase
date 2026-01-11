@extends('layouts.shop')

@section('title', 'Share Your Wishlist')

@section('content')
<div class="max-w-lg mx-auto px-6 md:px-12 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('wishlist') }}" class="hover:text-slate-900 transition-colors">Wishlist</a>
        <span>/</span>
        <span class="text-slate-900">Share</span>
    </nav>

    <h1 class="text-3xl font-serif tracking-wide text-slate-900 mb-3">Share Your Wishlist</h1>
    <p class="text-slate-500 text-sm mb-10">Create a shareable link to your wishlist that friends and family can view.</p>

    @if($existingShare)
    <div class="bg-green-50 border border-green-200 p-6 mb-8">
        <h3 class="text-sm font-medium text-green-900 mb-2">You already have an active share link!</h3>
        <div class="flex items-center gap-2 bg-white p-3 border border-green-200 mb-4">
            <input type="text" value="{{ $existingShare->getShareUrl() }}" readonly class="flex-1 text-sm text-slate-600 bg-transparent border-none focus:outline-none" id="share-url">
            <button onclick="copyShareUrl()" class="text-[10px] font-bold tracking-widest uppercase text-green-700 hover:text-green-900 transition-colors">Copy</button>
        </div>
        <div class="flex items-center justify-between text-xs text-green-700">
            <span>{{ $existingShare->view_count }} views</span>
            @if($existingShare->expires_at)
            <span>Expires {{ $existingShare->expires_at->diffForHumans() }}</span>
            @else
            <span>Never expires</span>
            @endif
        </div>
        <form action="{{ route('wishlist.share.destroy', $existingShare) }}" method="POST" class="mt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-[10px] font-bold tracking-widest uppercase text-red-600 hover:text-red-800 transition-colors">
                Delete Share Link
            </button>
        </form>
    </div>
    @else
    <form action="{{ route('wishlist.share.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="text-[11px] font-bold tracking-widest uppercase text-slate-500 block mb-2">Title (Optional)</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g., My Birthday Wishlist" class="w-full h-12 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
        </div>

        <div>
            <label class="text-[11px] font-bold tracking-widest uppercase text-slate-500 block mb-2">Description (Optional)</label>
            <textarea name="description" rows="3" placeholder="Add a note for viewers..." class="w-full px-4 py-3 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors resize-none">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="text-[11px] font-bold tracking-widest uppercase text-slate-500 block mb-2">Link Expiration</label>
            <select name="expires_in" class="w-full h-12 px-4 border border-slate-200 text-sm focus:outline-none focus:border-slate-900 transition-colors">
                <option value="never">Never expires</option>
                <option value="7">Expires in 7 days</option>
                <option value="30">Expires in 30 days</option>
                <option value="90">Expires in 90 days</option>
            </select>
        </div>

        <button type="submit" class="w-full h-14 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
            Create Share Link
        </button>
    </form>
    @endif

    <div class="mt-10">
        <a href="{{ route('wishlist') }}" class="text-[11px] font-bold tracking-widest uppercase text-slate-500 hover:text-slate-900 transition-colors">
            ← Back to Wishlist
        </a>
    </div>
</div>

@push('scripts')
<script>
function copyShareUrl() {
    const input = document.getElementById('share-url');
    input.select();
    document.execCommand('copy');
    showToast('Link copied to clipboard!');
}
</script>
@endpush
@endsection
