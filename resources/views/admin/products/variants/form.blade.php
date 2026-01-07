@extends('layouts.admin')

@section('title', ($variant ? 'Edit' : 'Add') . ' Variant - ' . $product->name)

@section('content')
<div x-data="{ imagePreview: '{{ $variant?->image_url ?? '' }}' }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.products.variants.index', $product) }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="text-2xl font-serif tracking-wide text-slate-900">{{ $variant ? 'Edit' : 'Add' }} Variant</h1>
            </div>
            <p class="text-[12px] text-slate-500">{{ $product->name }}</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ $variant ? route('admin.products.variants.update', [$product, $variant]) : route('admin.products.variants.store', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($variant)
        @method('PUT')
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Variant Options -->
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Variant Options</h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label for="sku" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">SKU</label>
                            <input type="text" id="sku" name="sku" value="{{ old('sku', $variant?->sku) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors font-mono @error('sku') border-red-300 @enderror" placeholder="Auto-generated if empty">
                            @error('sku')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            <p class="text-[11px] text-slate-400 mt-1">Leave empty to auto-generate</p>
                        </div>

                        <div class="grid sm:grid-cols-3 gap-5">
                            <div>
                                <label for="size" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Size</label>
                                <input type="text" id="size" name="size" value="{{ old('size', $variant?->size) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors @error('size') border-red-300 @enderror" placeholder="e.g., M, L, XL">
                                @error('size')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="color" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Color</label>
                                <input type="text" id="color" name="color" value="{{ old('color', $variant?->color) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors @error('color') border-red-300 @enderror" placeholder="e.g., Black, Navy">
                                @error('color')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="color_code" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Color Code</label>
                                <div class="flex gap-2">
                                    <input type="color" id="color_picker" value="{{ old('color_code', $variant?->color_code ?? '#000000') }}" class="w-11 h-11 p-1 bg-slate-50 border border-slate-200 cursor-pointer" onchange="document.getElementById('color_code').value = this.value">
                                    <input type="text" id="color_code" name="color_code" value="{{ old('color_code', $variant?->color_code) }}" class="flex-1 h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors font-mono @error('color_code') border-red-300 @enderror" placeholder="#000000" oninput="document.getElementById('color_picker').value = this.value">
                                </div>
                                @error('color_code')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="material" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Material</label>
                            <input type="text" id="material" name="material" value="{{ old('material', $variant?->material) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors @error('material') border-red-300 @enderror" placeholder="e.g., Cotton, Silk, Leather">
                            @error('material')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing & Stock -->
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Pricing & Stock</h3>
                    
                    <div class="grid sm:grid-cols-3 gap-5">
                        <div>
                            <label for="price" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Price (₹)</label>
                            <input type="number" id="price" name="price" value="{{ old('price', $variant?->price) }}" min="0" step="0.01" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors @error('price') border-red-300 @enderror" placeholder="Leave empty for base price">
                            @error('price')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            <p class="text-[11px] text-slate-400 mt-1">Base: ₹{{ number_format($product->price, 2) }}</p>
                        </div>

                        <div>
                            <label for="discount_price" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Discount Price (₹)</label>
                            <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price', $variant?->discount_price) }}" min="0" step="0.01" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors @error('discount_price') border-red-300 @enderror" placeholder="Optional">
                            @error('discount_price')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="stock" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Stock <span class="text-red-500">*</span></label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', $variant?->stock ?? 0) }}" min="0" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors @error('stock') border-red-300 @enderror" required>
                            @error('stock')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status -->
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Status</h3>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $variant?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-slate-900 border-slate-300 focus:ring-slate-900">
                        <span class="text-[13px] text-slate-600 group-hover:text-slate-900 transition-colors">Active</span>
                    </label>
                    <p class="text-[11px] text-slate-400 mt-2">Inactive variants won't be shown to customers</p>
                </div>

                <!-- Variant Image -->
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Variant Image</h3>
                    <div class="space-y-4">
                        <div class="aspect-square bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <div class="text-center p-6">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-[12px] text-slate-400">Uses product image</p>
                                </div>
                            </template>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Image URL</label>
                            <input type="text" name="image_url" value="{{ old('image_url', ($variant && str_starts_with($variant->image ?? '', 'http')) ? $variant->image : '') }}" placeholder="https://example.com/image.jpg" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors" @input="imagePreview = $event.target.value">
                        </div>
                        <p class="text-[10px] text-slate-400 text-center">— OR Upload File —</p>
                        <input type="file" id="image" name="image" accept="image/*" class="w-full text-[12px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[11px] file:font-bold file:tracking-[0.1em] file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 file:cursor-pointer" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                        <p class="text-[11px] text-slate-400">Optional - uses product image if not set</p>
                        @error('image')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <a href="{{ route('admin.products.variants.index', $product) }}" class="flex-1 h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">Cancel</a>
                    <button type="submit" class="flex-1 h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">{{ $variant ? 'Update' : 'Create' }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
