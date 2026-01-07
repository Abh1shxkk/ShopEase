@extends('layouts.admin')

@section('title', 'Referrals')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Referral Management</h1>
            <p class="text-[12px] text-slate-500 mt-1">Track referrals and rewards</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openAdjustPointsModal()" class="h-11 px-6 bg-emerald-600 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-emerald-700 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Adjust Points
            </button>
            <a href="{{ route('admin.referrals.settings') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Referrals</p>
            <p class="text-3xl font-light text-slate-900 mt-2">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Completed</p>
            <p class="text-3xl font-light text-emerald-600 mt-2">{{ number_format($stats['completed']) }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Pending</p>
            <p class="text-3xl font-light text-amber-600 mt-2">{{ number_format($stats['pending']) }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Rewards</p>
            <p class="text-3xl font-light text-blue-600 mt-2">{{ number_format($stats['total_rewards']) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 w-64">
            <select name="status" onchange="this.form.submit()" class="h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase">Filter</button>
        </form>
    </div>

    {{-- Referrals Table --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Referrer</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Referred User</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Reward</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($referrals as $referral)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-medium text-slate-900">{{ $referral->referrer->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $referral->referrer->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-medium text-slate-900">{{ $referral->referred->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $referral->referred->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-[10px] font-bold tracking-wider uppercase {{ $referral->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($referral->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                                {{ $referral->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-[13px] font-semibold text-slate-900">{{ number_format($referral->referrer_reward) }} pts</p>
                        </td>
                        <td class="px-6 py-4 text-[13px] text-slate-600">
                            {{ $referral->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-[13px] text-slate-500">No referrals found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($referrals->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $referrals->links('vendor.pagination.admin') }}
        </div>
        @endif
    </div>
</div>

{{-- Adjust Points Modal --}}
<div id="adjustPointsModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeAdjustPointsModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md bg-white shadow-xl">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-serif text-slate-900">Adjust Customer Points</h3>
            <button onclick="closeAdjustPointsModal()" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="adjustPointsForm" method="POST" action="">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Select Customer</label>
                    <select name="user_id" id="customerSelect" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                        <option value="">Select a customer...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" data-points="{{ $customer->reward_points }}">
                                {{ $customer->name }} ({{ $customer->email }}) - {{ number_format($customer->reward_points) }} pts
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Current Points</label>
                    <input type="text" id="currentPoints" readonly value="0" class="w-full h-11 px-4 bg-slate-100 border border-slate-200 text-[13px] text-slate-600">
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Points to Add/Remove</label>
                    <input type="number" name="points" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" placeholder="Enter positive to add, negative to remove">
                    <p class="text-[11px] text-slate-400 mt-1">Use negative value to deduct points</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Reason/Description</label>
                    <input type="text" name="description" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" placeholder="e.g., Bonus points, Correction, etc.">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                <button type="button" onclick="closeAdjustPointsModal()" class="h-11 px-6 bg-slate-100 text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-200 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                    Adjust Points
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAdjustPointsModal() {
    document.getElementById('adjustPointsModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAdjustPointsModal() {
    document.getElementById('adjustPointsModal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.getElementById('customerSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const points = selectedOption.dataset.points || 0;
    document.getElementById('currentPoints').value = Number(points).toLocaleString();
    
    // Update form action
    if (this.value) {
        document.getElementById('adjustPointsForm').action = '{{ url("admin/referrals/users") }}/' + this.value + '/adjust-points';
    }
});

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAdjustPointsModal();
    }
});
</script>
@endsection
