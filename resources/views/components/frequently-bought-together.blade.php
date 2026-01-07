@props(['product', 'relatedProducts'])

@if($relatedProducts->isNotEmpty())
<div class="bg-white border border-slate-200 p-6 mt-8">
    <h3 class="text-lg font-serif text-slate-900 mb-6">Frequently Bought Together</h3>
    
    <div class="flex flex-wrap items-center gap-4 mb-6">
        {{-- Current Product --}}
        <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200">
            <input type="checkbox" checked disabled class="w-4 h-4 rounded border-slate-300">
            <div class="w-16 h-16 bg-white">
                @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @endif
            </div>
            <div>
                <p class="text-[12px] font-medium text-slate-900">{{ Str::limit($product->name, 20) }}</p>
                <p class="text-[13px] font-semibold text-slate-900">₹{{ number_format($product->price) }}</p>
            </div>
        </div>

        @foreach($relatedProducts as $related)
        <span class="text-2xl text-slate-300">+</span>
        <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 fbt-item" data-product-id="{{ $related->id }}" data-price="{{ $related->price }}">
            <input type="checkbox" checked class="w-4 h-4 rounded border-slate-300 fbt-checkbox" onchange="updateFbtTotal()">
            <div class="w-16 h-16 bg-white">
                @if($related->image)
                <img src="{{ Storage::url($related->image) }}" alt="{{ $related->name }}" class="w-full h-full object-cover">
                @endif
            </div>
            <div>
                <a href="{{ route('shop.show', $related) }}" class="text-[12px] font-medium text-slate-900 hover:underline">{{ Str::limit($related->name, 20) }}</a>
                <p class="text-[13px] font-semibold text-slate-900">₹{{ number_format($related->price) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Total & Add All --}}
    <div class="flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200">
        <div>
            <p class="text-[12px] text-slate-500">Total for selected items:</p>
            <p class="text-xl font-bold text-slate-900" id="fbtTotal">₹{{ number_format($product->price + $relatedProducts->sum('price')) }}</p>
        </div>
        <form id="fbtForm" method="POST" action="{{ route('cart.add') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="fbt_products" id="fbtProducts" value="{{ $relatedProducts->pluck('id')->implode(',') }}">
            <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                Add All to Cart
            </button>
        </form>
    </div>
</div>

<script>
function updateFbtTotal() {
    const basePrice = {{ $product->price }};
    let total = basePrice;
    const selectedIds = [];
    
    document.querySelectorAll('.fbt-checkbox:checked').forEach(checkbox => {
        const item = checkbox.closest('.fbt-item');
        total += parseFloat(item.dataset.price);
        selectedIds.push(item.dataset.productId);
    });
    
    document.getElementById('fbtTotal').textContent = '₹' + total.toLocaleString('en-IN');
    document.getElementById('fbtProducts').value = selectedIds.join(',');
}
</script>
@endif
