@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div x-data="{ imagePreview: '{{ $product->image_url }}' }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-serif tracking-wide text-slate-900">Edit Product</h1>
            <p class="text-[12px] text-slate-500 mt-1">Update product information</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Information -->
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Product Information</h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors @error('name') border-red-300 @enderror" placeholder="Enter product name" required>
                            @error('name')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="description" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Description <span class="text-red-500">*</span></label>
                            <textarea id="description" name="description" rows="4" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors resize-none @error('description') border-red-300 @enderror" placeholder="Enter product description" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="category" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Category <span class="text-red-500">*</span></label>
                                <select id="category" name="category" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors @error('category') border-red-300 @enderror" required>
                                    <option value="">Select category</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $product->category_name) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('category')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="gender" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Gender <span class="text-red-500">*</span></label>
                                <select id="gender" name="gender" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors @error('gender') border-red-300 @enderror" required>
                                    <option value="unisex" {{ old('gender', $product->gender ?? 'unisex') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                                    <option value="men" {{ old('gender', $product->gender) == 'men' ? 'selected' : '' }}>Men</option>
                                    <option value="women" {{ old('gender', $product->gender) == 'women' ? 'selected' : '' }}>Women</option>
                                </select>
                                @error('gender')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="stock" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Stock Quantity <span class="text-red-500">*</span></label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 focus:outline-none focus:border-slate-900 transition-colors @error('stock') border-red-300 @enderror" required>
                            @error('stock')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Pricing</h3>
                    
                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label for="price" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Price (₹) <span class="text-red-500">*</span></label>
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors @error('price') border-red-300 @enderror" placeholder="0.00" required>
                            @error('price')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="discount_price" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Discount Price (₹)</label>
                            <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}" min="0" step="0.01" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors @error('discount_price') border-red-300 @enderror" placeholder="0.00">
                            @error('discount_price')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status -->
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Status</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="status" value="active" {{ old('status', $product->status) == 'active' ? 'checked' : '' }} class="w-4 h-4 text-slate-900 border-slate-300 focus:ring-slate-900">
                            <span class="text-[13px] text-slate-600 group-hover:text-slate-900 transition-colors">Active</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="radio" name="status" value="inactive" {{ old('status', $product->status) == 'inactive' ? 'checked' : '' }} class="w-4 h-4 text-slate-900 border-slate-300 focus:ring-slate-900">
                            <span class="text-[13px] text-slate-600 group-hover:text-slate-900 transition-colors">Inactive</span>
                        </label>
                    </div>
                </div>

                <!-- Product Image -->
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-6">Product Image</h3>
                    <div class="space-y-4">
                        <div class="aspect-square bg-slate-50 border border-slate-200 overflow-hidden flex items-center justify-center">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <div class="text-center p-6">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-[12px] text-slate-400">No image</p>
                                </div>
                            </template>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Image URL</label>
                            <input type="text" name="image_url" value="{{ old('image_url', str_starts_with($product->image ?? '', 'http') ? $product->image : '') }}" placeholder="https://example.com/image.jpg" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors" @input="imagePreview = $event.target.value">
                        </div>
                        <p class="text-[10px] text-slate-400 text-center">— OR Upload File —</p>
                        <input type="file" id="image" name="image" accept="image/*" class="w-full text-[12px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[11px] file:font-bold file:tracking-[0.1em] file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 file:cursor-pointer" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                        <p class="text-[11px] text-slate-400">Leave empty to keep current image</p>
                        @error('image')<p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <a href="{{ route('admin.products.index') }}" class="flex-1 h-11 px-6 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors">Cancel</a>
                    <button type="submit" class="flex-1 h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
