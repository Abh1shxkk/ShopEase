@extends('layouts.admin')

@section('title', $user->name)

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.users.index') }}" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-slate-900 flex items-center gap-2 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Users
    </a>
</div>

<!-- User Header -->
<div class="bg-white border border-slate-200 p-6 mb-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-600 text-2xl font-serif">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ $user->name }}</h1>
                <p class="text-[12px] text-slate-500">{{ $user->email }}</p>
                <div class="flex items-center gap-3 mt-2">
                    <span class="px-2 py-0.5 text-[10px] tracking-widest uppercase {{ $user->role === 'admin' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $user->role }}</span>
                    <span class="text-[11px] text-slate-400">Joined {{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="h-10 px-5 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center hover:bg-slate-50 transition-colors">Edit</a>
            @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="h-10 px-5 bg-red-600 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-red-700 transition-colors">Delete</button>
            </form>
            @endif
        </div>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-slate-200 p-4 text-center">
        <p class="text-xl font-serif text-slate-900">{{ $stats['total_orders'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-slate-400 mt-1">Orders</p>
    </div>
    <div class="bg-white border border-slate-200 p-4 text-center">
        <p class="text-xl font-serif text-emerald-600">₹{{ number_format($stats['total_spent'], 0) }}</p>
        <p class="text-[10px] tracking-widest uppercase text-slate-400 mt-1">Total Spent</p>
    </div>
    <div class="bg-white border border-slate-200 p-4 text-center">
        <p class="text-xl font-serif text-slate-900">{{ $stats['wishlist_items'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-slate-400 mt-1">Wishlist</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Orders -->
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Recent Orders</h2>
            <span class="text-[11px] text-slate-400">{{ $user->orders->count() }} total</span>
        </div>
        <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
            @forelse($user->orders->take(10) as $order)
            <a href="{{ route('admin.orders.show', $order) }}" class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                <div>
                    <p class="text-[13px] font-medium text-slate-900">{{ $order->order_number }}</p>
                    <p class="text-[11px] text-slate-400">{{ $order->created_at->format('d M Y') }} • {{ $order->items->count() }} items</p>
                </div>
                <div class="text-right">
                    <p class="text-[13px] font-semibold text-slate-900">₹{{ number_format($order->total, 0) }}</p>
                    <span class="text-[10px] tracking-widest uppercase px-2 py-0.5 {{ 
                        $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-700' : 
                        ($order->status === 'cancelled' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') 
                    }}">{{ $order->status }}</span>
                </div>
            </a>
            @empty
            <div class="px-6 py-12 text-center text-[13px] text-slate-400">No orders yet</div>
            @endforelse
        </div>
    </div>

    <!-- Wishlist -->
    <div class="bg-white border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Wishlist</h2>
            <span class="text-[11px] text-slate-400">{{ $user->wishlists->count() }} items</span>
        </div>
        <div class="divide-y divide-slate-100 max-h-96 overflow-y-auto">
            @forelse($user->wishlists as $wishlist)
            @if($wishlist->product)
            <div class="px-6 py-4 flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 overflow-hidden flex-shrink-0">
                    @if($wishlist->product->image)
                    <img src="{{ Storage::url($wishlist->product->image) }}" alt="{{ $wishlist->product->name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-medium text-slate-900 truncate">{{ $wishlist->product->name }}</p>
                    <p class="text-[11px] text-slate-400">₹{{ number_format($wishlist->product->discount_price ?? $wishlist->product->price, 0) }}</p>
                </div>
            </div>
            @endif
            @empty
            <div class="px-6 py-12 text-center text-[13px] text-slate-400">No wishlist items</div>
            @endforelse
        </div>
    </div>

    <!-- Contact Info -->
    <div class="bg-white border border-slate-200 p-6">
        <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-4">Contact Information</h2>
        <div class="space-y-3 text-[12px]">
            <div class="flex justify-between">
                <span class="text-slate-500">Email</span>
                <span class="text-slate-900">{{ $user->email }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Phone</span>
                <span class="text-slate-900">{{ $user->phone ?? 'Not provided' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Login Method</span>
                <span class="text-slate-900">{{ $user->provider ? ucfirst($user->provider) : 'Email' }}</span>
            </div>
            @if($user->last_login_at)
            <div class="flex justify-between">
                <span class="text-slate-500">Last Login</span>
                <span class="text-slate-900">{{ $user->last_login_at->format('d M Y, h:i A') }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Addresses -->
    <div class="bg-white border border-slate-200 p-6">
        <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-4">Saved Addresses</h2>
        @if($user->addresses && $user->addresses->count() > 0)
        <div class="space-y-3">
            @foreach($user->addresses as $address)
            <div class="p-3 bg-slate-50 text-[12px]">
                <p class="font-medium text-slate-900">{{ $address->label ?? 'Address' }}</p>
                <p class="text-slate-500">{{ $address->address_line_1 }}</p>
                <p class="text-slate-500">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-[13px] text-slate-400">No saved addresses</p>
        @endif
    </div>
</div>
@endsection
