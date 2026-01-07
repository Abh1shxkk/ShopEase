@extends('layouts.admin')

@section('title', isset($bundle) ? 'Edit Bundle' : 'Create Bundle')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div>
        <a href="{{ route('admin.bundles.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Bundles
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ isset($bundle) ? 'Edit Bundle' : 'Create Bundle' }}</h1>
        <p class="text-[12px] text-slate-500 mt-1">{{ isset($bundle) ? 'Update bundle details and products' : 'Create a new product bundle with discount' }}</p>
    </div>

    <form method="POST" action="{{ isset($bundle) ? route('admin.bundles.update', $bundle) : route('admin.bundles.store') }}" enctype="multipart/form-data" id="bundleForm">
        @csrf
        @if(isset($bundle))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Info --}}
                <div class="bg-white border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Bundle Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Bundle Name</label>
                            <input type="text" name="name" value="{{ old('name', $bundle->name ?? '') }}" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" placeholder="e.g., Summer Essentials Combo">
                            @error('name')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" placeholder="Describe what's included in this bundle...">{{ old('description', $bundle->description ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Bundle Image</label>
                            <input type="file" name="image" accept="image/*" class="w-full text-[13px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[11px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                            @if(isset($bundle) && $bundle->image)
                            <p class="text-[11px] text-slate-400 mt-1">Current: {{ basename($bundle->image) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Products Selection --}}
                <div class="bg-white border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                        <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Bundle Products</h3>
                        <button type="button" onclick="addProduct()" class="text-[11px] font-bold text-slate-900 hover:text-slate-600">+ Add Product</button>
                    </div>
                    <div class="p-6">
                        <div id="productsContainer" class="space-y-3">
                            @if(isset($bundle))
                                @foreach($bundle->items as $index => $item)
                                <div class="product-row flex items-center gap-3 p-3 bg-slate-50 border border-slate-200">
                                    <select name="products[{{ $index }}][product_id]" required class="flex-1 h-10 px-3 bg-white border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" onchange="updatePreview()">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-image="{{ $product->image ? Storage::url($product->image) : '' }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} - ₹{{ number_format($product->price) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="products[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="1" class="w-20 h-10 px-3 bg-white border border-slate-200 text-[13px] text-center focus:outline-none focus:border-slate-900" placeholder="Qty" onchange="updatePreview()">
                                    <button type="button" onclick="removeProduct(this)" class="w-10 h-10 bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="product-row flex items-center gap-3 p-3 bg-slate-50 border border-slate-200">
                                    <select name="products[0][product_id]" required class="flex-1 h-10 px-3 bg-white border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" onchange="updatePreview()">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-image="{{ $product->image ? Storage::url($product->image) : '' }}">
                                            {{ $product->name }} - ₹{{ number_format($product->price) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="products[0][quantity]" value="1" min="1" class="w-20 h-10 px-3 bg-white border border-slate-200 text-[13px] text-center focus:outline-none focus:border-slate-900" placeholder="Qty" onchange="updatePreview()">
                                    <button type="button" onclick="removeProduct(this)" class="w-10 h-10 bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="product-row flex items-center gap-3 p-3 bg-slate-50 border border-slate-200">
                                    <select name="products[1][product_id]" required class="flex-1 h-10 px-3 bg-white border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" onchange="updatePreview()">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-image="{{ $product->image ? Storage::url($product->image) : '' }}">
                                            {{ $product->name }} - ₹{{ number_format($product->price) }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="products[1][quantity]" value="1" min="1" class="w-20 h-10 px-3 bg-white border border-slate-200 text-[13px] text-center focus:outline-none focus:border-slate-900" placeholder="Qty" onchange="updatePreview()">
                                    <button type="button" onclick="removeProduct(this)" class="w-10 h-10 bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-400 mt-3">Minimum 2 products required for a bundle</p>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Discount Settings --}}
                <div class="bg-white border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Discount</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Discount Type</label>
                            <select name="discount_type" id="discountType" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" onchange="updatePreview()">
                                <option value="percentage" {{ old('discount_type', $bundle->discount_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('discount_type', $bundle->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Discount Value</label>
                            <input type="number" name="discount_value" id="discountValue" value="{{ old('discount_value', $bundle->discount_value ?? 10) }}" step="0.01" min="0" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" onchange="updatePreview()">
                        </div>
                    </div>
                </div>

                {{-- Price Preview --}}
                <div class="bg-white border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Price Preview</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between text-[13px]">
                            <span class="text-slate-500">Original Price:</span>
                            <span class="text-slate-900" id="originalPrice">₹0</span>
                        </div>
                        <div class="flex justify-between text-[13px]">
                            <span class="text-slate-500">Discount:</span>
                            <span class="text-red-600" id="discountAmount">-₹0</span>
                        </div>
                        <div class="border-t border-slate-200 pt-3 flex justify-between">
                            <span class="text-[13px] font-medium text-slate-900">Bundle Price:</span>
                            <span class="text-lg font-bold text-emerald-600" id="bundlePrice">₹0</span>
                        </div>
                        <div class="bg-emerald-50 p-3 text-center">
                            <span class="text-[12px] font-bold text-emerald-700" id="savingsText">Save 0%</span>
                        </div>
                    </div>
                </div>

                {{-- Schedule --}}
                <div class="bg-white border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Schedule (Optional)</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Start Date</label>
                            <input type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($bundle) && $bundle->starts_at ? $bundle->starts_at->format('Y-m-d\TH:i') : '') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">End Date</label>
                            <input type="datetime-local" name="ends_at" value="{{ old('ends_at', isset($bundle) && $bundle->ends_at ? $bundle->ends_at->format('Y-m-d\TH:i') : '') }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="bg-white border border-slate-200 p-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $bundle->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                        <span class="text-[13px] text-slate-700">Active (visible to customers)</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                    {{ isset($bundle) ? 'Update Bundle' : 'Create Bundle' }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let productIndex = {{ isset($bundle) ? $bundle->items->count() : 2 }};

function addProduct() {
    const container = document.getElementById('productsContainer');
    const template = `
        <div class="product-row flex items-center gap-3 p-3 bg-slate-50 border border-slate-200">
            <select name="products[${productIndex}][product_id]" required class="flex-1 h-10 px-3 bg-white border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900" onchange="updatePreview()">
                <option value="">Select Product</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-image="{{ $product->image ? Storage::url($product->image) : '' }}">
                    {{ $product->name }} - ₹{{ number_format($product->price) }}
                </option>
                @endforeach
            </select>
            <input type="number" name="products[${productIndex}][quantity]" value="1" min="1" class="w-20 h-10 px-3 bg-white border border-slate-200 text-[13px] text-center focus:outline-none focus:border-slate-900" placeholder="Qty" onchange="updatePreview()">
            <button type="button" onclick="removeProduct(this)" class="w-10 h-10 bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    productIndex++;
}

function removeProduct(btn) {
    const rows = document.querySelectorAll('.product-row');
    if (rows.length <= 2) {
        alert('Minimum 2 products required');
        return;
    }
    btn.closest('.product-row').remove();
    updatePreview();
}

function updatePreview() {
    let originalPrice = 0;
    const selects = document.querySelectorAll('[name^="products"][name$="[product_id]"]');
    const quantities = document.querySelectorAll('[name^="products"][name$="[quantity]"]');
    
    selects.forEach((select, index) => {
        const option = select.options[select.selectedIndex];
        if (option && option.dataset.price) {
            const qty = quantities[index] ? parseInt(quantities[index].value) || 1 : 1;
            originalPrice += parseFloat(option.dataset.price) * qty;
        }
    });

    const discountType = document.getElementById('discountType').value;
    const discountValue = parseFloat(document.getElementById('discountValue').value) || 0;
    
    let discount = 0;
    if (discountType === 'percentage') {
        discount = originalPrice * (discountValue / 100);
    } else {
        discount = discountValue;
    }
    
    const bundlePrice = Math.max(0, originalPrice - discount);
    const savingsPercent = originalPrice > 0 ? ((discount / originalPrice) * 100).toFixed(1) : 0;

    document.getElementById('originalPrice').textContent = '₹' + originalPrice.toLocaleString('en-IN');
    document.getElementById('discountAmount').textContent = '-₹' + discount.toLocaleString('en-IN');
    document.getElementById('bundlePrice').textContent = '₹' + bundlePrice.toLocaleString('en-IN');
    document.getElementById('savingsText').textContent = 'Save ' + savingsPercent + '%';
}

// Initial preview update
document.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endsection
