@extends('layouts.admin')

@section('title', 'Abandoned Cart Details')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('admin.abandoned-carts.index') }}" class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-400 hover:text-slate-900 transition-colors flex items-center gap-2 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Abandoned Carts
            </a>
            <h1 class="text-2xl font-serif text-slate-900">Cart #{{ $abandonedCart->id }}</h1>
        </div>
        
        @if($abandonedCart->status === 'abandoned')
        <form action="{{ route('admin.abandoned-carts.send-reminder', $abandonedCart) }}" method="POST">
            @csrf
            <button type="submit" class="h-10 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Send Reminder
            </button>
        </form>
        @endif
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Cart Items --}}
            <div class="bg-white border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-900">Cart Items</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @php $cartItems = json_decode($abandonedCart->cart_data, true) ?? []; @endphp
                    @forelse($cartItems as $item)
                    <div class="p-6 flex gap-4">
                        <div class="w-20 h-20 bg-slate-100 flex-shrink-0">
                            @if(isset($item['image']))
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] ?? 'Product' }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-[14px] font-medium text-slate-900">{{ $item['name'] ?? 'Unknown Product' }}</h3>
                            @if(isset($item['variant']))
                            <p class="text-[12px] text-slate-500 mt-1">{{ $item['variant'] }}</p>
                            @endif
                            <div class="flex items-center gap-4 mt-2">
                                <span class="text-[12px] text-slate-500">Qty: {{ $item['quantity'] ?? 1 }}</span>
                                <span class="text-[14px] font-medium text-slate-900">₹{{ number_format($item['price'] ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[14px] font-semibold text-slate-900">₹{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-slate-500 text-[13px]">
                        No items in cart data
                    </div>
                    @endforelse
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <div class="flex justify-between items-center">
                        <span class="text-[12px] font-bold tracking-widest uppercase text-slate-500">Cart Total</span>
                        <span class="text-xl font-semibold text-slate-900">₹{{ number_format($abandonedCart->cart_total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Reminder History --}}
            <div class="bg-white border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-900">Reminder History</h2>
                </div>
                @if($abandonedCart->reminders && $abandonedCart->reminders->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($abandonedCart->reminders as $reminder)
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <p class="text-[13px] text-slate-900">Reminder #{{ $reminder->reminder_number }}</p>
                            <p class="text-[12px] text-slate-500 mt-1">Sent: {{ $reminder->sent_at ? $reminder->sent_at->format('M d, Y h:i A') : 'Pending' }}</p>
                        </div>
                        <div class="text-right">
                            @if($reminder->opened_at)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold tracking-wider uppercase text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Opened
                            </span>
                            <p class="text-[11px] text-slate-400 mt-1">{{ $reminder->opened_at->format('M d, h:i A') }}</p>
                            @elseif($reminder->sent_at)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold tracking-wider uppercase text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Sent
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold tracking-wider uppercase text-amber-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pending
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-6 text-center text-slate-500 text-[13px]">
                    No reminders sent yet
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="bg-white border border-slate-200 p-6">
                <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-500 mb-4">Status</h3>
                @php
                    $statusColors = [
                        'abandoned' => 'bg-amber-100 text-amber-800',
                        'recovered' => 'bg-green-100 text-green-800',
                        'expired' => 'bg-slate-100 text-slate-600',
                    ];
                @endphp
                <span class="inline-flex px-3 py-1 text-[11px] font-bold tracking-wider uppercase {{ $statusColors[$abandonedCart->status] ?? 'bg-slate-100 text-slate-600' }}">
                    {{ ucfirst($abandonedCart->status) }}
                </span>
                
                <div class="mt-6 space-y-4">
                    <div>
                        <p class="text-[11px] font-bold tracking-widest uppercase text-slate-400">Abandoned At</p>
                        <p class="text-[13px] text-slate-900 mt-1">{{ $abandonedCart->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold tracking-widest uppercase text-slate-400">Time Since</p>
                        <p class="text-[13px] text-slate-900 mt-1">{{ $abandonedCart->created_at->diffForHumans() }}</p>
                    </div>
                    @if($abandonedCart->recovered_at)
                    <div>
                        <p class="text-[11px] font-bold tracking-widest uppercase text-slate-400">Recovered At</p>
                        <p class="text-[13px] text-slate-900 mt-1">{{ $abandonedCart->recovered_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white border border-slate-200 p-6">
                <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-500 mb-4">Customer</h3>
                @if($abandonedCart->user)
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-slate-900 flex items-center justify-center text-white font-medium">
                        {{ substr($abandonedCart->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[14px] font-medium text-slate-900">{{ $abandonedCart->user->name }}</p>
                        <p class="text-[12px] text-slate-500">{{ $abandonedCart->user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $abandonedCart->user) }}" class="text-[11px] font-bold tracking-widest uppercase text-slate-900 hover:text-slate-600 transition-colors">
                    View Customer →
                </a>
                @else
                <div class="space-y-3">
                    @if($abandonedCart->email)
                    <div>
                        <p class="text-[11px] font-bold tracking-widest uppercase text-slate-400">Email</p>
                        <p class="text-[13px] text-slate-900 mt-1">{{ $abandonedCart->email }}</p>
                    </div>
                    @endif
                    <p class="text-[12px] text-slate-500 italic">Guest checkout</p>
                </div>
                @endif
            </div>

            {{-- Recovery Link --}}
            @if($abandonedCart->status === 'abandoned' && $abandonedCart->recovery_token)
            <div class="bg-white border border-slate-200 p-6">
                <h3 class="text-[11px] font-bold tracking-[0.2em] uppercase text-slate-500 mb-4">Recovery Link</h3>
                <div class="bg-slate-50 p-3 rounded">
                    <p class="text-[11px] text-slate-600 break-all font-mono">{{ route('cart.recover', $abandonedCart->recovery_token) }}</p>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ route('cart.recover', $abandonedCart->recovery_token) }}')" class="mt-3 text-[11px] font-bold tracking-widest uppercase text-slate-900 hover:text-slate-600 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Copy Link
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
