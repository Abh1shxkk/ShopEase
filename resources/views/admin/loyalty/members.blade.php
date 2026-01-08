@extends('layouts.admin')

@section('title', 'Loyalty Members')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.loyalty.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Loyalty
            </a>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Loyalty Members</h1>
            <p class="text-[12px] text-slate-500 mt-1">View and manage member points</p>
        </div>
        <button onclick="document.getElementById('bulk-award-modal').classList.remove('hidden')" class="h-10 px-5 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
            Bulk Award Points
        </button>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full h-10 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
            </div>
            <select name="tier" class="h-10 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                <option value="">All Tiers</option>
                <option value="bronze" {{ request('tier') === 'bronze' ? 'selected' : '' }}>🥉 Bronze</option>
                <option value="silver" {{ request('tier') === 'silver' ? 'selected' : '' }}>🥈 Silver</option>
                <option value="gold" {{ request('tier') === 'gold' ? 'selected' : '' }}>🥇 Gold</option>
                <option value="platinum" {{ request('tier') === 'platinum' ? 'selected' : '' }}>💎 Platinum</option>
            </select>
            <select name="sort" class="h-10 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                <option value="total_earned_points" {{ request('sort') === 'total_earned_points' ? 'selected' : '' }}>Sort by Lifetime Points</option>
                <option value="reward_points" {{ request('sort') === 'reward_points' ? 'selected' : '' }}>Sort by Current Points</option>
                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Sort by Join Date</option>
            </select>
            <button type="submit" class="h-10 px-5 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'tier', 'sort']))
            <a href="{{ route('admin.loyalty.members') }}" class="h-10 px-4 border border-slate-200 text-slate-600 text-[11px] font-bold tracking-[0.15em] uppercase flex items-center hover:bg-slate-50 transition-colors">
                Clear
            </a>
            @endif
        </form>
    </div>

    {{-- Members Table --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Member</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Tier</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Current Points</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Lifetime Earned</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Redeemed</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($members as $member)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <p class="text-[13px] font-medium text-slate-900">{{ $member->name }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $member->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-{{ $member->tier['color'] }}-100 text-{{ $member->tier['color'] }}-700 text-[11px] font-medium">
                                {{ $member->tier['icon'] }} {{ $member->tier['name'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[14px] font-semibold text-slate-900">{{ number_format($member->reward_points) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[13px] text-emerald-600">{{ number_format($member->total_earned_points) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-[13px] text-slate-500">{{ number_format($member->total_redeemed_points) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button onclick="openAdjustModal({{ $member->id }}, '{{ $member->name }}', {{ $member->reward_points }})" class="text-[11px] text-slate-600 hover:text-slate-900 font-medium">
                                Adjust Points
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">No members found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($members->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $members->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Adjust Points Modal --}}
<div id="adjust-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50" onclick="closeAdjustModal()"></div>
        <div class="relative bg-white w-full max-w-md p-6">
            <h3 class="text-lg font-serif text-slate-900 mb-4">Adjust Points</h3>
            <p class="text-[13px] text-slate-600 mb-4">Adjusting points for: <strong id="adjust-user-name"></strong></p>
            <p class="text-[12px] text-slate-500 mb-4">Current balance: <strong id="adjust-current-points"></strong> points</p>
            
            <form id="adjust-form" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Points to Add/Remove</label>
                        <input type="number" name="points" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" placeholder="Use negative for deduction">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Reason</label>
                        <input type="text" name="reason" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" placeholder="e.g., Manual adjustment, Bonus, Correction">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeAdjustModal()" class="flex-1 h-11 border border-slate-200 text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 h-11 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                        Adjust Points
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Bulk Award Modal --}}
<div id="bulk-award-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50" onclick="document.getElementById('bulk-award-modal').classList.add('hidden')"></div>
        <div class="relative bg-white w-full max-w-md p-6">
            <h3 class="text-lg font-serif text-slate-900 mb-4">Bulk Award Points</h3>
            
            <form method="POST" action="{{ route('admin.loyalty.bulk-award') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Points to Award</label>
                        <input type="number" name="points" required min="1" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Target Users</label>
                        <select name="target" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                            <option value="all">All Users</option>
                            <option value="tier">Specific Tier</option>
                        </select>
                    </div>
                    <div id="tier-select" class="hidden">
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Select Tier</label>
                        <select name="tier" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                            <option value="bronze">🥉 Bronze</option>
                            <option value="silver">🥈 Silver</option>
                            <option value="gold">🥇 Gold</option>
                            <option value="platinum">💎 Platinum</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Reason</label>
                        <input type="text" name="reason" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" placeholder="e.g., Holiday bonus, Promotion">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('bulk-award-modal').classList.add('hidden')" class="flex-1 h-11 border border-slate-200 text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 h-11 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                        Award Points
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAdjustModal(userId, userName, currentPoints) {
    document.getElementById('adjust-user-name').textContent = userName;
    document.getElementById('adjust-current-points').textContent = currentPoints.toLocaleString();
    document.getElementById('adjust-form').action = `/admin/loyalty/members/${userId}/adjust`;
    document.getElementById('adjust-modal').classList.remove('hidden');
}

function closeAdjustModal() {
    document.getElementById('adjust-modal').classList.add('hidden');
}

// Show/hide tier select based on target
document.querySelector('select[name="target"]')?.addEventListener('change', function() {
    document.getElementById('tier-select').classList.toggle('hidden', this.value !== 'tier');
});
</script>
@endsection
