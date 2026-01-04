@extends('layouts.admin')

@section('title', 'Coupons')

@section('content')
<div x-data="{ showDeleteModal: false, deleteId: null, deleteName: '' }">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Coupons</h1>
            <p class="text-[12px] text-slate-500 mt-1">Manage discount codes and promotions</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Coupon
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white border border-slate-200 p-4 text-center">
            <p class="text-xl font-serif text-slate-900">{{ $stats['total'] }}</p>
            <p class="text-[10px] tracking-widest uppercase text-slate-400 mt-1">Total</p>
        </div>
        <div class="bg-emerald-50 border border-emerald-200 p-4 text-center">
            <p class="text-xl font-serif text-emerald-700">{{ $stats['active'] }}</p>
            <p class="text-[10px] tracking-widest uppercase text-emerald-600 mt-1">Active</p>
        </div>
        <div class="bg-amber-50 border border-amber-200 p-4 text-center">
            <p class="text-xl font-serif text-amber-700">{{ $stats['inactive'] ?? 0 }}</p>
            <p class="text-[10px] tracking-widest uppercase text-amber-600 mt-1">Inactive</p>
        </div>
        <div class="bg-red-50 border border-red-200 p-4 text-center">
            <p class="text-xl font-serif text-red-700">{{ $stats['expired'] }}</p>
            <p class="text-[10px] tracking-widest uppercase text-red-600 mt-1">Expired</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 p-4 text-center">
            <p class="text-xl font-serif text-blue-700">{{ $stats['scheduled'] ?? 0 }}</p>
            <p class="text-[10px] tracking-widest uppercase text-blue-600 mt-1">Scheduled</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-slate-200 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by code or name..." class="flex-1 min-w-[200px] h-11 px-4 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
            <select name="status" class="h-11 px-4 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Filter</button>
            <a href="{{ route('admin.coupons.index') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">Reset</a>
        </form>
    </div>


    <!-- Coupons Table -->
    <div class="bg-white border border-slate-200">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200">
                    <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Code</th>
                    <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Discount</th>
                    <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Min Order</th>
                    <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Usage</th>
                    <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Validity</th>
                    <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                    <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($coupons as $coupon)
                @php $status = $coupon->status; @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-[13px] font-mono font-bold text-slate-900">{{ $coupon->code }}</p>
                        <p class="text-[11px] text-slate-400">{{ $coupon->name }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[13px] font-semibold {{ $coupon->type === 'percentage' ? 'text-purple-600' : 'text-blue-600' }}">{{ $coupon->formatted_value }} OFF</span>
                        @if($coupon->max_discount)
                        <p class="text-[10px] text-slate-400">Max: ₹{{ number_format($coupon->max_discount, 0) }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-[13px] text-slate-600">₹{{ number_format($coupon->min_order_amount, 0) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-[13px] text-slate-900">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</p>
                        <p class="text-[10px] text-slate-400">{{ $coupon->usage_limit_per_user }}/user</p>
                    </td>
                    <td class="px-6 py-4 text-[12px]">
                        @if($coupon->starts_at || $coupon->expires_at)
                        <p class="text-slate-600">{{ $coupon->starts_at?->format('M d, Y') ?? 'Now' }}</p>
                        <p class="text-slate-400">to {{ $coupon->expires_at?->format('M d, Y') ?? 'Never' }}</p>
                        @else
                        <span class="text-slate-400">No expiry</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="is_active" onchange="this.form.submit()" class="h-8 px-2 text-[11px] border border-slate-200 focus:outline-none focus:ring-1 focus:ring-slate-900 {{ 
                                $status === 'active' ? 'bg-emerald-50 text-emerald-700' : 
                                ($status === 'expired' ? 'bg-red-50 text-red-700' : 
                                ($status === 'scheduled' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700')) 
                            }}">
                                <option value="1" {{ $coupon->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$coupon->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-slate-900">Edit</a>
                            @if($coupon->used_count === 0)
                            <button @click="showDeleteModal = true; deleteId = {{ $coupon->id }}; deleteName = '{{ $coupon->code }}'" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-red-600">Delete</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-[13px] text-slate-400">No coupons found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $coupons->links('vendor.pagination.admin') }}
    </div>


    <!-- Delete Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showDeleteModal = false" class="fixed inset-0 bg-black/40"></div>
            
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white shadow-xl max-w-md w-full p-8">
                <div class="text-center">
                    <div class="w-14 h-14 bg-red-50 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-xl font-serif text-slate-900 mb-2">Delete Coupon</h3>
                    <p class="text-[13px] text-slate-500 mb-8">Are you sure you want to delete "<span x-text="deleteName" class="font-mono font-bold text-slate-900"></span>"? This action cannot be undone.</p>
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false" class="flex-1 h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                        <form :action="'{{ url('admin/coupons') }}/' + deleteId" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full h-11 px-6 bg-red-600 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-red-700 transition-colors">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection