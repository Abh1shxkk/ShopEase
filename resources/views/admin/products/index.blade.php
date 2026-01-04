@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div x-data="{ showDeleteModal: false, deleteId: null, deleteName: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Products Management</h1>
            <p class="text-[12px] text-slate-500 mt-1">Manage your product inventory</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 mb-6">
        <form method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 dark:text-slate-500 block mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-[13px] text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-slate-900 dark:focus:border-slate-400 transition-colors">
            </div>
            <div class="w-full lg:w-48">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 dark:text-slate-500 block mb-2">Category</label>
                <select name="category" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-[13px] text-slate-900 dark:text-white focus:outline-none focus:border-slate-900 dark:focus:border-slate-400 transition-colors">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full lg:w-40">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 dark:text-slate-500 block mb-2">Status</label>
                <select name="status" class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-[13px] text-slate-900 dark:text-white focus:outline-none focus:border-slate-900 dark:focus:border-slate-400 transition-colors">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-11 px-6 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors">Filter</button>
                @if(request()->hasAny(['search', 'category', 'status']))
                <a href="{{ route('admin.products.index') }}" class="h-11 px-6 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors flex items-center">Clear</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white border border-slate-200">
        @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Product</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Category</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Price</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Stock</th>
                        <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($products as $product)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-slate-100 flex-shrink-0 overflow-hidden">
                                    @if($product->image)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[13px] font-medium text-slate-900">{{ $product->name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] text-slate-600">{{ $product->category_name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <span class="text-[13px] font-semibold text-slate-900">₹{{ number_format($product->price, 0) }}</span>
                                @if($product->discount_price)
                                <span class="text-[11px] text-emerald-600 block">₹{{ number_format($product->discount_price, 0) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] {{ $product->stock < 10 ? 'text-red-600 font-semibold' : 'text-slate-600' }}">{{ $product->stock }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] tracking-widest uppercase px-2 py-1 {{ $product->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button @click="showDeleteModal = true; deleteId = {{ $product->id }}; deleteName = '{{ addslashes($product->name) }}'" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $products->links('vendor.pagination.admin') }}
        </div>
        @else
        <!-- Empty State -->
        <div class="p-16 text-center">
            <div class="w-16 h-16 bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h3 class="text-lg font-serif text-slate-900 mb-2">No products found</h3>
            <p class="text-[13px] text-slate-500 mb-6">Get started by creating your first product.</p>
            <a href="{{ route('admin.products.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase inline-flex items-center gap-2 hover:bg-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Product
            </a>
        </div>
        @endif
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
                    <h3 class="text-xl font-serif text-slate-900 mb-2">Delete Product</h3>
                    <p class="text-[13px] text-slate-500 mb-8">Are you sure you want to delete "<span x-text="deleteName" class="font-medium text-slate-900"></span>"? This action cannot be undone.</p>
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false" class="flex-1 h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                        <form :action="'{{ url('admin/products') }}/' + deleteId" method="POST" class="flex-1">
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
