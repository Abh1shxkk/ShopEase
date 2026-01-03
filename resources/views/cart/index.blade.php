@extends('layouts.shop')

@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-[1440px] mx-auto px-6 md:px-12 py-12" x-data="cartManager()">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-12">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-slate-900">Shopping Cart</span>
    </nav>

    <h1 class="text-3xl font-serif tracking-wide mb-12">Shopping Cart</h1>

    {{-- Toast Notification --}}
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         :class="toast.type === 'success' ? 'bg-slate-900' : 'bg-red-600'"
         class="fixed bottom-6 right-6 z-50 px-5 py-4 text-white shadow-2xl flex items-center gap-3"
         style="display: none;">
        <svg x-show="toast.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <svg x-show="toast.type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span class="font-medium text-sm" x-text="toast.message"></span>
    </div>

    <template x-if="items.length > 0">
        <div class="grid lg:grid-cols-3 gap-12">
            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-6">
                <template x-for="item in items" :key="item.id">
                    <div class="flex flex-col sm:flex-row gap-6 pb-6 border-b border-slate-100" 
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        <div class="w-full sm:w-32 h-40 bg-[#f7f7f7] overflow-hidden flex-shrink-0">
                            <img x-show="item.image" :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                            <div x-show="!item.image" class="w-full h-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <p class="text-[10px] tracking-widest uppercase text-slate-400 mb-1" x-text="item.category"></p>
                                <a :href="item.url" class="text-[15px] font-medium text-slate-900 hover:text-slate-600 transition-colors" x-text="item.name"></a>
                                <p class="text-[14px] font-semibold text-slate-900 mt-2">Rs. <span x-text="item.price.toFixed(2)"></span></p>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center gap-3">
                                    <button @click="updateQuantity(item.id, item.quantity - 1)" 
                                            :disabled="item.quantity <= 1 || item.loading"
                                            class="w-8 h-8 flex items-center justify-center border border-slate-200 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                    <span class="w-8 text-center text-[13px] font-medium" x-text="item.quantity"></span>
                                    <button @click="updateQuantity(item.id, item.quantity + 1)" 
                                            :disabled="item.quantity >= item.max_stock || item.loading"
                                            class="w-8 h-8 flex items-center justify-center border border-slate-200 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                    <svg x-show="item.loading" class="w-4 h-4 animate-spin ml-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                </div>
                                <button @click="removeItem(item.id)" 
                                        :disabled="item.loading"
                                        class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-red-600 transition-colors disabled:opacity-50">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Order Summary --}}
            <div class="lg:col-span-1">
                <div class="bg-slate-50 p-8 sticky top-28">
                    <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-8">Order Summary</h2>
                    <div class="space-y-4 text-[13px]">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Subtotal (<span x-text="items.length"></span> items)</span>
                            <span class="font-medium text-slate-900">Rs. <span x-text="subtotal.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Shipping</span>
                            <span class="font-medium" :class="shipping == 0 ? 'text-green-600' : 'text-slate-900'" x-text="shipping == 0 ? 'FREE' : 'Rs. ' + shipping.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tax (8%)</span>
                            <span class="font-medium text-slate-900">Rs. <span x-text="tax.toFixed(2)"></span></span>
                        </div>
                        <div class="border-t border-slate-200 pt-4 flex justify-between">
                            <span class="font-semibold text-slate-900">Total</span>
                            <span class="text-lg font-bold text-slate-900">Rs. <span x-text="total.toFixed(2)"></span></span>
                        </div>
                    </div>
                    <template x-if="subtotal < 250">
                        <p class="text-[12px] text-slate-500 mt-6 p-3 bg-white border border-slate-200">
                            Add Rs. <span x-text="(250 - subtotal).toFixed(2)"></span> more for free shipping!
                        </p>
                    </template>
                    <a href="{{ route('checkout') }}" class="mt-8 w-full h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase flex items-center justify-center hover:bg-slate-800 transition-colors">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('shop.index') }}" class="mt-3 w-full h-12 bg-white text-slate-900 text-[11px] font-bold tracking-[0.2em] uppercase flex items-center justify-center border border-slate-200 hover:bg-slate-50 transition-colors">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </template>

    <template x-if="items.length === 0">
        <div class="text-center py-24 bg-slate-50">
            <div class="w-20 h-20 bg-white border border-slate-200 flex items-center justify-center mx-auto mb-8">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h3 class="text-xl font-serif tracking-wide text-slate-900 mb-3">Your cart is empty</h3>
            <p class="text-[13px] text-slate-500 mb-8">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('shop.index') }}" class="inline-flex h-12 px-10 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase items-center justify-center hover:bg-slate-800 transition-colors">
                Start Shopping
            </a>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
function cartManager() {
    return {
        items: @json($cartItems->map(function($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'category' => $item->product->category_name,
                'price' => (float)($item->product->discount_price ?? $item->product->price),
                'quantity' => $item->quantity,
                'max_stock' => min(10, $item->product->stock),
                'image' => $item->product->image ? (str_starts_with($item->product->image, 'http') ? $item->product->image : '/storage/' . $item->product->image) : null,
                'url' => route('shop.show', $item->product_id),
                'loading' => false
            ];
        })),
        toast: { show: false, message: '', type: 'success' },
        
        get subtotal() {
            return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },
        get shipping() {
            return this.subtotal >= 250 ? 0 : 10;
        },
        get tax() {
            return this.subtotal * 0.08;
        },
        get total() {
            return this.subtotal + this.shipping + this.tax;
        },
        
        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => this.toast.show = false, 3000);
        },
        
        async updateQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return;
            
            const item = this.items.find(i => i.id === itemId);
            if (!item || item.loading) return;
            
            item.loading = true;
            
            try {
                const response = await fetch(`/cart/update/${itemId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity: newQuantity })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    item.quantity = newQuantity;
                    this.updateCartBadge();
                    this.showToast('Cart updated');
                } else {
                    this.showToast(data.message || 'Failed to update', 'error');
                }
            } catch (error) {
                this.showToast('Error updating cart', 'error');
            } finally {
                item.loading = false;
            }
        },
        
        async removeItem(itemId) {
            const item = this.items.find(i => i.id === itemId);
            if (!item || item.loading) return;
            
            item.loading = true;
            
            try {
                const response = await fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.items = this.items.filter(i => i.id !== itemId);
                    this.updateCartBadge();
                    this.showToast('Item removed from cart');
                } else {
                    this.showToast(data.message || 'Failed to remove', 'error');
                }
            } catch (error) {
                this.showToast('Error removing item', 'error');
            } finally {
                item.loading = false;
            }
        },
        
        updateCartBadge() {
            const totalQty = this.items.reduce((sum, item) => sum + item.quantity, 0);
            const badges = document.querySelectorAll('[data-cart-count]');
            badges.forEach(badge => {
                badge.textContent = totalQty > 99 ? '99+' : totalQty;
                badge.style.display = totalQty > 0 ? 'flex' : 'none';
            });
        }
    }
}
</script>
@endpush
