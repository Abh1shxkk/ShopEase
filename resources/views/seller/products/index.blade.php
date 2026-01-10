@extends('layouts.seller')

@section('title', 'Products')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif text-slate-900">My Products</h1>
            <p class="text-[13px] text-slate-500 mt-1">Manage your product listings</p>
        </div>
        <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-5 py-2.5 bg-slate-900 text-white text-[12px] font-medium hover:bg-slate-800 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Product
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-slate-200 p-4">
        <form action="{{ route('seller.products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." 
                    class="w-full px-4 py-2.5 border border-slate-200 bg-white text-slate-900 text-[13px] focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
            </div>
            <select name="status" class="px-4 py-2.5 border border-slate-200 bg-white text-slate-900 text-[13px] focus:ring-2 focus:ring-slate-900">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <select name="approval" class="px-4 py-2.5 border border-slate-200 bg-white text-slate-900 text-[13px] focus:ring-2 focus:ring-slate-900">
                <option value="">All Approval</option>
                <option value="approved" {{ request('approval') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="pending" {{ request('approval') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="rejected" {{ request('approval') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-[12px] font-medium hover:bg-slate-200 transition">
                Filter
            </button>
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
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Category</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Price</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Stock</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold tracking-widest uppercase text-slate-500">Status</th>
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
                                    <p class="text-[11px] text-slate-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[12px] text-slate-600">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($product->discount_price)
                            <p class="text-[12px] font-medium text-slate-900">₹{{ number_format($product->discount_price, 2) }}</p>
                            <p class="text-[11px] text-slate-400 line-through">₹{{ number_format($product->price, 2) }}</p>
                            @else
                            <p class="text-[12px] font-medium text-slate-900">₹{{ number_format($product->price, 2) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] font-medium @if($product->stock <= 5) text-red-600 @elseif($product->stock <= 20) text-amber-600 @else text-emerald-600 @endif">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-[10px] font-medium {{ $product->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-[10px] font-medium
                                @if($product->approval_status === 'approved') bg-emerald-50 text-emerald-700
                                @elseif($product->approval_status === 'pending') bg-amber-50 text-amber-700
                                @else bg-red-50 text-red-700 @endif">
                                {{ ucfirst($product->approval_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('seller.products.edit', $product) }}" class="p-2 text-slate-400 hover:text-slate-900 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
        @else
        <div class="p-16 text-center">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <h3 class="text-lg font-serif text-slate-900 mb-2">No products yet</h3>
            <p class="text-[13px] text-slate-500 mb-6">Start by adding your first product to your store.</p>
            <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-5 py-2.5 bg-slate-900 text-white text-[12px] font-medium hover:bg-slate-800 transition">
                Add Your First Product
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
