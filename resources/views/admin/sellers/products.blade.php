@extends('layouts.admin')

@section('title', 'Seller Products')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-serif text-slate-900">Seller Products</h1>
        <p class="text-[13px] text-slate-500 mt-1">Review and approve seller product listings</p>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-slate-200 p-4">
        <form action="{{ route('admin.sellers.products') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." 
                    class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
            </div>
            <select name="approval" class="px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                <option value="">All Approval Status</option>
                <option value="pending" {{ request('approval') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('approval') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('approval') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-[12px] font-medium hover:bg-slate-200">Filter</button>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white border border-slate-200">
        @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Product</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Seller</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Category</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Price</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Approval</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold tracking-widest uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($products as $product)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover border border-slate-200">
                                <div class="ml-4">
                                    <p class="text-[12px] font-medium text-slate-900">{{ Str::limit($product->name, 30) }}</p>
                                    <p class="text-[11px] text-slate-500">Stock: {{ $product->stock }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[12px] text-slate-900">{{ $product->seller->store_name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $product->seller->user->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-[12px] text-slate-600">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-[12px] font-medium text-slate-900">₹{{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-[10px] font-medium
                                @if($product->approval_status === 'approved') bg-emerald-50 text-emerald-700
                                @elseif($product->approval_status === 'pending') bg-amber-50 text-amber-700
                                @else bg-red-50 text-red-700 @endif">
                                {{ ucfirst($product->approval_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($product->approval_status === 'pending')
                            <div class="flex justify-end space-x-2">
                                <form action="{{ route('admin.sellers.products.approve', $product) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white text-[11px] font-medium hover:bg-emerald-700">Approve</button>
                                </form>
                                <form action="{{ route('admin.sellers.products.reject', $product) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-[11px] font-medium hover:bg-red-700">Reject</button>
                                </form>
                            </div>
                            @else
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-[12px] font-medium text-slate-900 hover:underline">Edit</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">{{ $products->links() }}</div>
        @else
        <div class="p-16 text-center">
            <p class="text-[13px] text-slate-500">No seller products found.</p>
        </div>
        @endif
    </div>
</div>
@endsection
