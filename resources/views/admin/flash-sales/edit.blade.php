@extends('layouts.admin')

@section('title', 'Edit Flash Sale')

@section('content')
<div class="space-y-8">
    <div>
        <a href="{{ route('admin.flash-sales.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold tracking-[0.15em] uppercase text-slate-400 hover:text-slate-900 transition-colors mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
            Back to Flash Sales
        </a>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">Edit: {{ $flashSale->name }}</h1>
    </div>

    <form method="POST" action="{{ route('admin.flash-sales.update', $flashSale) }}" enctype="multipart/form-data" x-data="flashSaleForm()">
        @csrf @method('PUT')
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 p-6">
                    <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-4">Sale Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Sale Name</label>
                            <input type="text" name="name" value="{{ old('name', $flashSale->name) }}" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">{{ old('description', $flashSale->description) }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Starts At</label>
                                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $flashSale->starts_at->format('Y-m-d\TH:i')) }}" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Ends At</label>
                                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $flashSale->ends_at->format('Y-m-d\TH:i')) }}" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-2">Banner Image</label>
                            @if($flashSale->banner_image)<img src="{{ $flashSale->banner_url }}" class="h-20 mb-2">@endif
                            <input type="file" name="banner_image" accept="image/*" class="w-full text-[13px]">
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Products</h3>
                        <button type="button" @click="addProduct()" class="text-[11px] text-slate-600 hover:text-slate-900">+ Add Product</button>
                    </div>
                    <template x-for="(product, index) in products" :key="index">
                        <div class="flex items-start gap-4 p-4 bg-slate-50 mb-3">
                            <div class="flex-1 grid grid-cols-4 gap-3">
                                <div class="col-span-2">
                                    <select :name="'products['+index+'][product_id]'" x-model="product.product_id" required class="w-full h-10 px-3 bg-white border border-slate-200 text-[12px]">
                                        <option value="">Select Product</option>
                                        @foreach($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} (₹{{ $p->price }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div><input type="number" :name="'products['+index+'][sale_price]'" x-model="product.sale_price" placeholder="Sale Price" required step="0.01" class="w-full h-10 px-3 bg-white border border-slate-200 text-[12px]"></div>
                                <div><input type="number" :name="'products['+index+'][quantity_limit]'" x-model="product.quantity_limit" placeholder="Qty Limit" class="w-full h-10 px-3 bg-white border border-slate-200 text-[12px]"></div>
                            </div>
                            <input type="hidden" :name="'products['+index+'][per_user_limit]'" x-model="product.per_user_limit">
                            <button type="button" @click="removeProduct(index)" class="p-2 text-red-500 hover:text-red-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white border border-slate-200 p-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $flashSale->is_active ? 'checked' : '' }} class="w-4 h-4">
                        <span class="text-[13px] text-slate-700">Active</span>
                    </label>
                </div>
                <button type="submit" class="w-full h-11 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Update Flash Sale</button>
            </div>
        </div>
    </form>
</div>

<script>
function flashSaleForm() {
    return {
        products: @json($flashSale->flashSaleProducts->map(fn($p) => ['product_id' => $p->product_id, 'sale_price' => $p->sale_price, 'quantity_limit' => $p->quantity_limit, 'per_user_limit' => $p->per_user_limit])),
        addProduct() { this.products.push({ product_id: '', sale_price: '', quantity_limit: '', per_user_limit: 2 }); },
        removeProduct(index) { if (this.products.length > 1) this.products.splice(index, 1); }
    }
}
</script>
@endsection
