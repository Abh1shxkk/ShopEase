@extends('layouts.shop')

@section('title', 'Refer & Earn')

@section('content')
<div class="min-h-screen bg-slate-50 py-12 pt-24">
    <div class="max-w-4xl mx-auto px-4">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl font-serif tracking-wide text-slate-900">Refer & Earn</h1>
            <p class="text-slate-500 mt-2">Share your code with friends and earn rewards</p>
        </div>

        {{-- Referral Code Card --}}
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-8 mb-8">
            <div class="text-center">
                <p class="text-[10px] font-bold tracking-[0.2em] uppercase text-slate-400 mb-2">Your Referral Code</p>
                <div class="flex items-center justify-center gap-4 mb-4">
                    <span class="text-4xl font-bold tracking-[0.3em]" id="referralCode">{{ $user->referral_code }}</span>
                    <button onclick="copyCode()" class="p-2 bg-white/10 hover:bg-white/20 transition-colors" title="Copy Code">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
                <p class="text-slate-300 text-sm mb-6">Share this code with friends. When they sign up and make their first purchase, you both earn rewards!</p>
                
                {{-- Share Buttons --}}
                <div class="flex items-center justify-center gap-3">
                    <a href="https://wa.me/?text=Join%20ShopEase%20using%20my%20referral%20code%20{{ $user->referral_code }}%20and%20get%20bonus%20points!%20{{ urlencode(route('register', ['ref' => $user->referral_code])) }}" target="_blank" class="p-3 bg-green-500 hover:bg-green-600 transition-colors" title="Share on WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=Join%20ShopEase%20using%20my%20referral%20code%20{{ $user->referral_code }}%20and%20get%20bonus%20points!&url={{ urlencode(route('register', ['ref' => $user->referral_code])) }}" target="_blank" class="p-3 bg-sky-500 hover:bg-sky-600 transition-colors" title="Share on Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('register', ['ref' => $user->referral_code])) }}" target="_blank" class="p-3 bg-blue-600 hover:bg-blue-700 transition-colors" title="Share on Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <button onclick="copyLink()" class="p-3 bg-slate-600 hover:bg-slate-500 transition-colors" title="Copy Link">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Referral Link --}}
        <div class="bg-white border border-slate-200 p-6 mb-8">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Your Referral Link</p>
            <div class="flex items-center gap-2">
                <input type="text" readonly value="{{ route('register', ['ref' => $user->referral_code]) }}" id="referralLink" class="flex-1 h-11 px-4 bg-slate-50 border border-slate-200 text-[12px] text-slate-600">
                <button onclick="copyLink()" class="h-11 px-4 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Copy</button>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white border border-slate-200 p-6 text-center">
                <p class="text-3xl font-light text-slate-900">{{ $stats['total_referrals'] }}</p>
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Total Referrals</p>
            </div>
            <div class="bg-white border border-slate-200 p-6 text-center">
                <p class="text-3xl font-light text-emerald-600">{{ $stats['completed_referrals'] }}</p>
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Completed</p>
            </div>
            <div class="bg-white border border-slate-200 p-6 text-center">
                <p class="text-3xl font-light text-amber-600">{{ number_format($user->reward_points) }}</p>
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Available Points</p>
            </div>
            <div class="bg-white border border-slate-200 p-6 text-center">
                <p class="text-3xl font-light text-slate-900">₹{{ number_format($pointsValue) }}</p>
                <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Points Value</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Recent Referrals --}}
            <div class="bg-white border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Your Referrals</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($referrals as $referral)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-medium text-slate-900">{{ $referral->referred->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $referral->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="px-3 py-1 text-[10px] font-bold tracking-wider uppercase {{ $referral->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $referral->status }}
                        </span>
                    </div>
                    @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-slate-500 text-sm">No referrals yet. Share your code to get started!</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Points History --}}
            <div class="bg-white border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Points History</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($transactions as $transaction)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-[13px] text-slate-900">{{ $transaction->description ?? ucfirst($transaction->source) }}</p>
                            <p class="text-[11px] text-slate-500">{{ $transaction->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="text-[14px] font-semibold {{ $transaction->points > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                        </span>
                    </div>
                    @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-slate-500 text-sm">No transactions yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- How It Works --}}
        <div class="mt-12 bg-white border border-slate-200 p-8">
            <h3 class="text-xl font-serif text-slate-900 mb-6 text-center">How It Works</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl font-bold text-slate-900">1</span>
                    </div>
                    <h4 class="font-medium text-slate-900 mb-2">Share Your Code</h4>
                    <p class="text-sm text-slate-500">Share your unique referral code with friends and family</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl font-bold text-slate-900">2</span>
                    </div>
                    <h4 class="font-medium text-slate-900 mb-2">Friend Signs Up</h4>
                    <p class="text-sm text-slate-500">They sign up using your code and get bonus points</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <span class="text-xl font-bold text-slate-900">3</span>
                    </div>
                    <h4 class="font-medium text-slate-900 mb-2">Earn Rewards</h4>
                    <p class="text-sm text-slate-500">When they make their first purchase, you earn points too!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCode() {
    const code = document.getElementById('referralCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
        showToast('Referral code copied!');
    });
}

function copyLink() {
    const link = document.getElementById('referralLink').value;
    navigator.clipboard.writeText(link).then(() => {
        showToast('Referral link copied!');
    });
}

function showToast(message) {
    // Remove existing toast if any
    const existingToast = document.getElementById('copy-toast');
    if (existingToast) existingToast.remove();
    
    // Create toast
    const toast = document.createElement('div');
    toast.id = 'copy-toast';
    toast.className = 'fixed top-24 right-6 z-50 px-6 py-4 bg-slate-900 text-white shadow-lg flex items-center gap-3 animate-fade-in';
    toast.innerHTML = `
        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span class="text-[13px]">${message}</span>
    `;
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateX(100%); }
    to { opacity: 1; transform: translateX(0); }
}
.animate-fade-in {
    animation: fade-in 0.3s ease forwards;
}
</style>
@endsection
