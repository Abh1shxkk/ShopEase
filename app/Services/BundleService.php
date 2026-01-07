<?php

namespace App\Services;

use App\Models\ProductBundle;
use App\Models\BundleItem;
use App\Models\FrequentlyBoughtTogether;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Collection;

class BundleService
{
    public function createBundle(array $data, array $productIds): ProductBundle
    {
        $bundle = ProductBundle::create($data);
        
        foreach ($productIds as $index => $item) {
            BundleItem::create([
                'bundle_id' => $bundle->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'] ?? 1,
                'sort_order' => $index,
            ]);
        }

        $bundle->load('items.product');
        $bundle->calculatePrices();
        $bundle->save();

        return $bundle;
    }

    public function updateBundle(ProductBundle $bundle, array $data, array $productIds): ProductBundle
    {
        $bundle->update($data);
        
        // Remove existing items
        $bundle->items()->delete();
        
        // Add new items
        foreach ($productIds as $index => $item) {
            BundleItem::create([
                'bundle_id' => $bundle->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'] ?? 1,
                'sort_order' => $index,
            ]);
        }

        $bundle->load('items.product');
        $bundle->calculatePrices();
        $bundle->save();

        return $bundle;
    }

    public function getActiveBundles(): Collection
    {
        return ProductBundle::active()
            ->with(['items.product'])
            ->orderBy('sort_order')
            ->get()
            ->filter(fn($bundle) => $bundle->isAvailable());
    }

    public function getBundlesForProduct(Product $product): Collection
    {
        return ProductBundle::active()
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->with(['items.product'])
            ->get()
            ->filter(fn($bundle) => $bundle->isAvailable());
    }

    public function getFrequentlyBoughtTogether(Product $product, int $limit = 4): Collection
    {
        return FrequentlyBoughtTogether::forProduct($product->id)
            ->active()
            ->where('purchase_count', '>=', 2)
            ->with('relatedProduct')
            ->orderByDesc('purchase_count')
            ->limit($limit)
            ->get()
            ->pluck('relatedProduct')
            ->filter(fn($p) => $p && $p->status === 'active' && $p->stock > 0);
    }

    public function recordOrderPurchases(Order $order): void
    {
        $productIds = $order->items->pluck('product_id')->toArray();
        FrequentlyBoughtTogether::recordPurchase($productIds);
    }

    public function addBundleToCart($user, ProductBundle $bundle): array
    {
        $cartItems = [];
        
        foreach ($bundle->items as $item) {
            $existingCartItem = $user->cartItems()
                ->where('product_id', $item->product_id)
                ->first();

            if ($existingCartItem) {
                $existingCartItem->increment('quantity', $item->quantity);
                $cartItems[] = $existingCartItem;
            } else {
                $cartItems[] = $user->cartItems()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ]);
            }
        }

        return $cartItems;
    }

    public function calculateBundleDiscount(ProductBundle $bundle): float
    {
        return $bundle->savings;
    }

    public function generateFrequentlyBoughtData(): void
    {
        // Analyze last 90 days of orders
        $orders = Order::where('created_at', '>=', now()->subDays(90))
            ->where('status', '!=', 'cancelled')
            ->with('items')
            ->get();

        foreach ($orders as $order) {
            $productIds = $order->items->pluck('product_id')->toArray();
            FrequentlyBoughtTogether::recordPurchase($productIds);
        }
    }
}
