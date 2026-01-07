@extends('layouts.admin')

@section('title', 'Product Variants - ' . $product->name)

@section('content')
<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.products.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ $product->name }}</h1>
            </div>
            <p class="text-[12px] text-slate-500">Manage product variants - Size, Color, Material options</p>
        </div>
        <div class="flex gap-3">
            <button onclick="document.getElementById('bulkModal').classList.remove('hidden')" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Bulk Create
            </button>
            <a href="{{ route('admin.products.variants.create', $product) }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Variant
            </a>
        </div>
    </div>

    <!-- Product Summary -->
    <div class="bg-white border border-slate-200 p-6 mb-6">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-slate-100 overflow-hidden flex-shrink-0">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
            <div class="flex-1">
                <div class="grid grid-cols-4 gap-6">
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-1">Base Price</p>
                        <p class="text-[14px] font-medium text-slate-900">₹{{ number_format($product->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-1">Total Variants</p>
                        <p class="text-[14px] font-medium text-slate-900">{{ $variants->total() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-1">Total Stock</p>
                        <p class="text-[14px] font-medium text-slate-900">{{ $product->total_variant_stock }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-1">Status</p>
                        <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold tracking-wider uppercase {{ $product->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $product->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Variants Table -->
    <div class="bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 px-6 py-4">SKU</th>
                        <th class="text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 px-6 py-4">Variant</th>
                        <th class="text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 px-6 py-4">Price</th>
                        <th class="text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 px-6 py-4">Stock</th>
                        <th class="text-left text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 px-6 py-4">Status</th>
                        <th class="text-right text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($variants as $variant)
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-[12px] font-mono text-slate-600">{{ $variant->sku }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($variant->color_code)
                                <span class="w-6 h-6 rounded-full border border-slate-200" style="background-color: {{ $variant->color_code }}"></span>
                                @endif
                                <div>
                                    <p class="text-[13px] font-medium text-slate-900">{{ $variant->variant_name }}</p>
                                    <div class="flex gap-2 mt-1">
                                        @if($variant->size)
                                        <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5">Size: {{ $variant->size }}</span>
                                        @endif
                                        @if($variant->color)
                                        <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5">{{ $variant->color }}</span>
                                        @endif
                                        @if($variant->material)
                                        <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5">{{ $variant->material }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($variant->price)
                            <p class="text-[13px] font-medium text-slate-900">₹{{ number_format($variant->price, 2) }}</p>
                            @if($variant->discount_price)
                            <p class="text-[11px] text-green-600">Sale: ₹{{ number_format($variant->discount_price, 2) }}</p>
                            @endif
                            @else
                            <span class="text-[12px] text-slate-400">Base price</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2" x-data="{ editing: false, stock: {{ $variant->stock }} }">
                                <template x-if="!editing">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[13px] font-medium {{ $variant->stock <= 0 ? 'text-red-600' : ($variant->isLowStock() ? 'text-amber-600' : 'text-slate-900') }}">
                                            {{ $variant->stock }}
                                        </span>
                                        <button @click="editing = true" class="text-slate-400 hover:text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                    </div>
                                </template>
                                <template x-if="editing">
                                    <form action="{{ route('admin.products.variants.update-stock', [$product, $variant]) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="stock" x-model="stock" min="0" class="w-20 h-8 px-2 text-[12px] border border-slate-200 focus:outline-none focus:border-slate-900">
                                        <button type="submit" class="text-green-600 hover:text-green-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        <button type="button" @click="editing = false" class="text-slate-400 hover:text-slate-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </template>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold tracking-wider uppercase {{ $variant->is_active ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $variant->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                <form action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" method="POST" onsubmit="return confirm('Delete this variant?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-slate-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <p class="text-[14px] text-slate-500 mb-4">No variants yet</p>
                            <a href="{{ route('admin.products.variants.create', $product) }}" class="inline-flex h-10 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase items-center hover:bg-slate-800 transition-colors">
                                Add First Variant
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($variants->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $variants->links('vendor.pagination.admin') }}
        </div>
        @endif
    </div>
</div>

<!-- Bulk Create Modal -->
<div id="bulkModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50" onclick="document.getElementById('bulkModal').classList.add('hidden')"></div>
        <div class="relative bg-white w-full max-w-lg p-6">
            <h3 class="text-lg font-serif text-slate-900 mb-6">Bulk Create Variants</h3>
            <form action="{{ route('admin.products.variants.bulk-create', $product) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Sizes (comma separated)</label>
                        <input type="text" name="sizes" placeholder="S, M, L, XL" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                        <p class="text-[11px] text-slate-400 mt-1">e.g., S, M, L, XL or 38, 40, 42</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Colors (comma separated)</label>
                        <input type="text" name="colors" placeholder="Black, White, Navy" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Color Codes (comma separated, optional)</label>
                        <input type="text" name="color_codes" placeholder="#000000, #FFFFFF, #1e3a5f" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                        <p class="text-[11px] text-slate-400 mt-1">Hex codes matching colors above</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Materials (comma separated)</label>
                        <input type="text" name="materials" placeholder="Cotton, Silk, Linen" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Default Stock per Variant</label>
                        <input type="number" name="default_stock" value="10" min="0" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" required>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('bulkModal').classList.add('hidden')" class="flex-1 h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Create Variants</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
