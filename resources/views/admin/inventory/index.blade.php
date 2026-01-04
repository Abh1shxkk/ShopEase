@extends('layouts.admin')

@section('title', 'Inventory')

@section('content')
<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Inventory Management</h1>
            <p class="text-[12px] text-slate-500 mt-1">Monitor and manage product stock levels</p>
        </div>
        <a href="{{ route('admin.inventory.alerts') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors relative">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            View Alerts
            @php $unreadAlerts = \App\Services\InventoryService::getUnreadAlertsCount(); @endphp
            @if($unreadAlerts > 0)
            <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">{{ $unreadAlerts > 9 ? '9+' : $unreadAlerts }}</span>
            @endif
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Total Products</p>
                    <p class="text-2xl font-serif text-slate-900 mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">In Stock</p>
                    <p class="text-2xl font-serif text-emerald-600 mt-2">{{ $stats['in_stock'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Low Stock</p>
                    <p class="text-2xl font-serif text-amber-600 mt-2">{{ $stats['low_stock'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400">Out of Stock</p>
                    <p class="text-2xl font-serif text-red-600 mt-2">{{ $stats['out_of_stock'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-slate-200 p-6 mb-6">
        <form action="{{ route('admin.inventory.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors">
            </div>
            <div class="w-full lg:w-48">
                <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Stock Status</label>
                <select name="status" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    <option value="">All Status</option>
                    <option value="in_stock" {{ request('status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Filter</button>
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.inventory.index') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors flex items-center">Clear</a>
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
                        <th class="text-center px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Stock</th>
                        <th class="text-center px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Threshold</th>
                        <th class="text-center px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Status</th>
                        <th class="text-center px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Waiting</th>
                        <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($products as $product)
                    @php
                        $stockStatus = $product->stock <= 0 ? 'out' : ($product->stock <= $product->low_stock_threshold ? 'low' : 'ok');
                        $waitingCount = \App\Models\StockNotification::where('product_id', $product->id)->where('notified', false)->count();
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-slate-100 flex-shrink-0 overflow-hidden">
                                    @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[13px] font-medium text-slate-900">{{ Str::limit($product->name, 35) }}</p>
                                    <p class="text-[11px] text-slate-400">ID: #{{ $product->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[12px] text-slate-600">{{ $product->category_name }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-lg font-bold {{ $stockStatus === 'out' ? 'text-red-600' : ($stockStatus === 'low' ? 'text-amber-600' : 'text-slate-900') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-[12px] text-slate-600">{{ $product->low_stock_threshold }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-[10px] tracking-widest uppercase px-2 py-1
                                {{ $stockStatus === 'out' ? 'bg-red-50 text-red-700' : '' }}
                                {{ $stockStatus === 'low' ? 'bg-amber-50 text-amber-700' : '' }}
                                {{ $stockStatus === 'ok' ? 'bg-emerald-50 text-emerald-700' : '' }}">
                                {{ $stockStatus === 'out' ? 'Out of Stock' : ($stockStatus === 'low' ? 'Low Stock' : 'In Stock') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($waitingCount > 0)
                            <a href="{{ route('admin.inventory.notifications', $product) }}" class="inline-flex items-center gap-1 text-[12px] text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                {{ $waitingCount }}
                            </a>
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end">
                                <button onclick="openStockModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->stock }}, {{ $product->low_stock_threshold }})" 
                                        class="h-9 px-4 bg-slate-900 text-white text-[10px] font-bold tracking-[0.1em] uppercase hover:bg-slate-800 transition-colors">
                                    Update
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
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-lg font-serif text-slate-900 mb-2">No products found</h3>
            <p class="text-[13px] text-slate-500 mb-6">No products match your current filters.</p>
        </div>
        @endif
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stockModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div onclick="closeStockModal()" class="fixed inset-0 bg-black/40"></div>
        
        <div class="relative bg-white shadow-xl max-w-md w-full p-8">
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-xl font-serif text-slate-900">Update Stock</h3>
                <p id="modalProductName" class="text-[13px] text-slate-500 mt-1"></p>
            </div>
            <form id="stockForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Current Stock</label>
                        <input type="number" name="stock" id="modalStock" min="0" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" id="modalThreshold" min="1" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors">
                        <p class="text-[11px] text-slate-400 mt-1">Alert when stock falls below this number</p>
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closeStockModal()" class="flex-1 h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openStockModal(productId, productName, stock, threshold) {
    document.getElementById('stockModal').classList.remove('hidden');
    document.getElementById('modalProductName').textContent = productName;
    document.getElementById('modalStock').value = stock;
    document.getElementById('modalThreshold').value = threshold;
    document.getElementById('stockForm').action = `/admin/inventory/${productId}/stock`;
}

function closeStockModal() {
    document.getElementById('stockModal').classList.add('hidden');
}
</script>
@endsection
