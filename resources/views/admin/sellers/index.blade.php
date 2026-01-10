@extends('layouts.admin')

@section('title', 'Sellers')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif text-slate-900">Sellers</h1>
            <p class="text-[13px] text-slate-500 mt-1">Manage marketplace sellers</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-slate-200 p-4">
        <form action="{{ route('admin.sellers.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search sellers..." 
                    class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900 focus:border-slate-900">
            </div>
            <select name="status" class="px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-[12px] font-medium hover:bg-slate-200">Filter</button>
        </form>
    </div>

    <!-- Sellers Table -->
    <div class="bg-white border border-slate-200">
        @if($sellers->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Seller</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Store</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Products</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Earnings</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Status</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold tracking-widest uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($sellers as $seller)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img src="{{ $seller->logo_url }}" alt="{{ $seller->store_name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                <div class="ml-3">
                                    <p class="text-[12px] font-medium text-slate-900">{{ $seller->user->name }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $seller->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[12px] text-slate-900">{{ $seller->store_name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $seller->business_phone }}</p>
                        </td>
                        <td class="px-6 py-4 text-[12px] text-slate-600">{{ $seller->total_products }}</td>
                        <td class="px-6 py-4 text-[12px] font-medium text-slate-900">₹{{ number_format($seller->total_earnings, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-[10px] font-medium
                                @if($seller->status === 'approved') bg-emerald-50 text-emerald-700
                                @elseif($seller->status === 'pending') bg-amber-50 text-amber-700
                                @elseif($seller->status === 'suspended') bg-red-50 text-red-700
                                @else bg-slate-100 text-slate-600 @endif">
                                {{ ucfirst($seller->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.sellers.show', $seller) }}" class="text-[12px] font-medium text-slate-900 hover:underline">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $sellers->links() }}</div>
        @else
        <div class="p-16 text-center">
            <p class="text-[13px] text-slate-500">No sellers found.</p>
        </div>
        @endif
    </div>
</div>
@endsection
