@extends('layouts.seller')

@section('title', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-serif text-slate-900">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h1>
            <p class="text-[13px] text-slate-500 mt-1">{{ isset($product) ? 'Update your product details' : 'Fill in the details to add a new product' }}</p>
        </div>
        <a href="{{ route('seller.products.index') }}" class="p-2 text-slate-400 hover:text-slate-600 transition">
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
                <div class="bg-white border border-slate-200">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="text-lg font-serif text-slate-900">Basic Information</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-[12px] font-medium text-slate-700 mb-2">Product Name *</label>
                            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                                class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                            @error('name')<p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-slate-700 mb-2">Description *</label>
                            <textarea name="description" rows="5" required class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">{{ old('description', $product->description ?? '') }}</textarea>
                            @error('description')<p class="mt-1 text-[11px] text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-white border border-slate-200">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="text-lg font-serif text-slate-900">Pricing</h2>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[12px] font-medium text-slate-700 mb-2">Regular Price (₹) *</label>
                            <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" step="0.01" min="0" required class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-slate-700 mb-2">Sale Price (₹)</label>
                            <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price ?? '') }}" step="0.01" min="0" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        </div>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="bg-white border border-slate-200">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="text-lg font-serif text-slate-900">Inventory</h2>
                    </div>
                    <div class="p-6">
                        <label class="block text-[12px] font-medium text-slate-700 mb-2">Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" required class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                @if(isset($product))
                <div class="bg-white border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Status</h2>
                    <select name="status" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        <option value="active" {{ ($product->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ ($product->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                @endif

                <div class="bg-white border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Category</h2>
                    <select name="category_id" required class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-white border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Target Audience</h2>
                    <select name="gender" class="w-full px-4 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        <option value="">All</option>
                        <option value="men" {{ old('gender', $product->gender ?? '') === 'men' ? 'selected' : '' }}>Men</option>
                        <option value="women" {{ old('gender', $product->gender ?? '') === 'women' ? 'selected' : '' }}>Women</option>
                        <option value="unisex" {{ old('gender', $product->gender ?? '') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                    </select>
                </div>

                <div class="bg-white border border-slate-200 p-6">
                    <h2 class="text-lg font-serif text-slate-900 mb-4">Product Image</h2>
                    @if(isset($product) && $product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover border border-slate-200 mb-4">
                    @endif
                    <div class="border-2 border-dashed border-slate-200 p-6 text-center">
                        <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        <label for="image" class="cursor-pointer">
                            <svg class="w-10 h-10 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-[12px] text-slate-500">Click to upload</p>
                        </label>
                        <div id="imagePreview" class="mt-4 hidden"><img id="preview" src="" class="w-full h-48 object-cover border"></div>
                    </div>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 px-5 py-3 bg-slate-900 text-white text-[12px] font-medium hover:bg-slate-800">{{ isset($product) ? 'Update' : 'Add Product' }}</button>
                    <a href="{{ route('seller.products.index') }}" class="px-5 py-3 bg-slate-100 text-slate-700 text-[12px] font-medium hover:bg-slate-200">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) { document.getElementById('preview').src = e.target.result; document.getElementById('imagePreview').classList.remove('hidden'); }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
