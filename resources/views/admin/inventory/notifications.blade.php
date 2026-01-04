@extends('layouts.admin')

@section('title', 'Stock Notifications - ' . $product->name)

@section('content')
<div>
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center gap-2 text-[12px] text-slate-500 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Inventory
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">Restock Notifications</h1>
        <p class="text-[12px] text-slate-500 mt-1">Customers waiting for: <span class="font-medium text-slate-900">{{ $product->name }}</span></p>
    </div>

    <!-- Product Info -->
    <div class="bg-white border border-slate-200 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-slate-100 overflow-hidden flex-shrink-0">
                @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @endif
            </div>
            <div class="flex-1">
                <h2 class="text-[15px] font-medium text-slate-900">{{ $product->name }}</h2>
                <p class="text-[12px] text-slate-500 mt-1">Current Stock: 
                    <span class="font-semibold {{ $product->stock > 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $product->stock }}</span>
                </p>
            </div>
            <div>
                <span class="text-[10px] tracking-widest uppercase px-3 py-1.5 bg-blue-50 text-blue-700">
                    {{ $notifications->total() }} waiting
                </span>
            </div>
        </div>
    </div>

    <!-- Notifications Table -->
    <div class="bg-white border border-slate-200">
        @if($notifications->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Customer</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Email</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Requested</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($notifications as $notification)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            @if($notification->user)
                            <p class="text-[13px] font-medium text-slate-900">{{ $notification->user->name }}</p>
                            @else
                            <p class="text-[13px] text-slate-500">Guest</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] text-slate-600">{{ $notification->email }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] text-slate-600">{{ $notification->created_at->format('M d, Y H:i') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($notification->notified)
                            <span class="text-[10px] tracking-widest uppercase px-2 py-1 bg-emerald-50 text-emerald-700">
                                Notified {{ $notification->notified_at->diffForHumans() }}
                            </span>
                            @else
                            <span class="text-[10px] tracking-widest uppercase px-2 py-1 bg-amber-50 text-amber-700">
                                Waiting
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $notifications->links('vendor.pagination.admin') }}
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <h3 class="text-lg font-serif text-slate-900 mb-2">No notifications found</h3>
            <p class="text-[13px] text-slate-500">No customers are waiting for this product.</p>
        </div>
        @endif
    </div>
</div>
@endsection