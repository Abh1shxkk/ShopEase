@extends('layouts.admin')

@section('title', 'Abandoned Carts')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Abandoned Carts</h1>
            <p class="text-[12px] text-slate-500 mt-1">Recover lost sales with automated reminders</p>
        </div>
        <form method="POST" action="{{ route('admin.abandoned-carts.send-bulk') }}">
            @csrf
            <button type="submit" class="h-10 px-5 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Send Reminders
            </button>
        </form>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-slate-900">{{ $stats['total_abandoned'] }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Total Abandoned</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-emerald-600">{{ $stats['recovered'] }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Recovered ({{ $stats['recovery_rate'] }}%)</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-amber-600">{{ $stats['pending'] + $stats['reminded'] }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Pending Recovery</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-2xl font-light text-slate-900">₹{{ number_format($stats['potential_recovery']) }}</p>
            <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mt-1">Potential Revenue</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="flex-1 min-w-[200px] h-10 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
            <select name="status" class="h-10 px-4 bg-slate-50 border border-slate-200 text-[13px]">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reminded" {{ request('status') === 'reminded' ? 'selected' : '' }}>Reminded</option>
                <option value="recovered" {{ request('status') === 'recovered' ? 'selected' : '' }}>Recovered</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <button type="submit" class="h-10 px-5 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase">Filter</button>
        </form>
    </div>

    {{-- Carts Table --}}
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Customer</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Items</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Value</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Reminders</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Status</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Abandoned</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($carts as $cart)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <p class="text-[13px] font-medium text-slate-900">{{ $cart->user->name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $cart->user->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-center text-[13px]">{{ $cart->items_count }}</td>
                        <td class="px-6 py-4 text-right text-[13px] font-semibold text-slate-900">₹{{ number_format($cart->cart_total) }}</td>
                        <td class="px-6 py-4 text-center text-[13px]">{{ $cart->reminder_count }}/3</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'reminded' => 'bg-blue-100 text-blue-700', 'recovered' => 'bg-emerald-100 text-emerald-700', 'expired' => 'bg-slate-100 text-slate-500'];
                            @endphp
                            <span class="px-2 py-1 text-[10px] font-bold uppercase {{ $statusColors[$cart->status] ?? 'bg-slate-100 text-slate-500' }}">{{ $cart->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-center text-[12px] text-slate-500">{{ $cart->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.abandoned-carts.show', $cart) }}" class="p-2 text-slate-400 hover:text-slate-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                @if($cart->canSendReminder())
                                <form method="POST" action="{{ route('admin.abandoned-carts.send-reminder', $cart) }}">
                                    @csrf
                                    <button type="submit" class="p-2 text-slate-400 hover:text-blue-600" title="Send Reminder">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-slate-500">No abandoned carts found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($carts->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">{{ $carts->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
