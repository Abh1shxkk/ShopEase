@extends('layouts.admin')

@section('title', 'Add Product')

@section('content')
<div x-data="{ imagePreview: null }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
            <p class="text-gray-500 mt-1">Create a new product listing</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="label mb-2 block">Product Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="input w-full @error('name') input-error @enderror" placeholder="Enter product name" required>
                            @error('name')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="description" class="label mb-2 block">Description <span class="text-red-500">*</span></label>
                            <textarea id="description" name="description" rows="4" class="input w-full min-h-[120px] @error('description') input-error @enderror" placeholder="Enter product description" required>{{ old('description') }}</textarea>
                            @error('description')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="category" class="label mb-2 block">Category <span class="text-red-500">*</span></label>
                                <select id="category" name="category" class="input select w-full @error('category') input-error @enderror" required>
                                    <option value="">Select category</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('category')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="stock" class="label mb-2 block">Stock Quantity <span class="text-red-500">*</span></label>
                                <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" class="input w-full @error('stock') input-error @enderror" required>
                                @error('stock')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h3>
                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="label mb-2 block">Price ($) <span class="text-red-500">*</span></label>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" class="input w-full @error('price') input-error @enderror" placeholder="0.00" required>
                            @error('price')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="discount_price" class="label mb-2 block">Discount Price ($)</label>
                            <input type="number" id="discount_price" name="discount_price" value="{{ old('discount_price') }}" min="0" step="0.01" class="input w-full @error('discount_price') input-error @enderror" placeholder="0.00">
                            @error('discount_price')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                    <div class="space-y-3">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="status" value="active" {{ old('status', 'active') == 'active' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="status" value="inactive" {{ old('status') == 'inactive' ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                            <span class="text-gray-700">Inactive</span>
                        </label>
                    </div>
                </div>

                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Image</h3>
                    <div class="space-y-4">
                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-dashed border-gray-300 flex items-center justify-center">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <div class="text-center p-4">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-sm text-gray-500">No image selected</p>
                                </div>
                            </template>
                        </div>
                        <input type="file" id="image" name="image" accept="image/*" class="input w-full text-sm" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                        @error('image')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline flex-1">Cancel</a>
                    <button type="submit" class="btn btn-primary flex-1">Add Product</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
