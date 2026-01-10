@extends('layouts.seller')

@section('title', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif text-slate-900">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h1>
            <p class="text-slate-500">{{ isset($product) ? 'Update your product details' : 'Fill in the details to add a new product' }}</p>
        </div>
        <a href="{{ route('seller.products.index') }}" class="text-slate-400 hover:text-slate-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>
    </div>

    <form action="{{ isset($product) ? route('seller.products.update', $product) : route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Basic Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Product Name *</label>
                            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                                class="w-full px-4 py-2 border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Description *</label>
                            <textarea name="description" rows="5" required
                                class="w-full px-4 py-2 border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition @error('description') border-red-500 @enderror">{{ old('description', $product->description ?? '') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-white shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Pricing</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Regular Price (₹) *</label>
                            <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" step="0.01" min="0" required
                                class="w-full px-4 py-2 border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition @error('price') border-red-500 @enderror">
                            @error('price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Sale Price (₹)</label>
                            <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price ?? '') }}" step="0.01" min="0"
                                class="w-full px-4 py-2 border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition @error('discount_price') border-red-500 @enderror">
                            @error('discount_price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="bg-white shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Inventory</h2>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" required
                            class="w-full px-4 py-2 border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition @error('stock') border-red-500 @enderror">
                        @error('stock')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                @if(isset($product))
                <!-- Status (only for edit) -->
                <div class="bg-white shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Status</h2>
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-slate-900">
                        <option value="active" {{ old('status', $product->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $product->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                @endif

                <!-- Category -->
                <div class="bg-white shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Category</h2>
                    <select name="category_id" required class="w-full px-4 py-2 border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-slate-900 @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div class="bg-white shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Target Audience</h2>
                    <select name="gender" class="w-full px-4 py-2 border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-slate-900">
                        <option value="">All</option>
                        <option value="men" {{ old('gender', $product->gender ?? '') === 'men' ? 'selected' : '' }}>Men</option>
                        <option value="women" {{ old('gender', $product->gender ?? '') === 'women' ? 'selected' : '' }}>Women</option>
                        <option value="unisex" {{ old('gender', $product->gender ?? '') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                    </select>
                </div>

                <!-- Product Image -->
                <div class="bg-white shadow-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Product Image</h2>
                    
                    @if(isset($product) && $product->image)
                    <div class="mb-4">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover border border-slate-200">
                    </div>
                    @endif

                    <div class="border-2 border-dashed border-slate-300 p-6 text-center">
                        <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        <label for="image" class="cursor-pointer">
                            <svg class="w-12 h-12 mx-auto text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-slate-500">Click to upload image</p>
                            <p class="text-xs text-slate-400 mt-1">PNG, JPG up to 2MB</p>
                        </label>
                        <div id="imagePreview" class="mt-4 hidden">
                            <img id="preview" src="" alt="Preview" class="w-full h-48 object-cover border border-slate-200">
                        </div>
                    </div>
                    @error('image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 px-4 py-3 bg-slate-900 text-white hover:bg-slate-800 transition font-medium">
                        {{ isset($product) ? 'Update Product' : 'Add Product' }}
                    </button>
                    <a href="{{ route('seller.products.index') }}" class="px-4 py-3 bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
