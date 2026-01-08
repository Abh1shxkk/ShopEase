<?php

namespace App\Services;

use App\Models\FlashSale;
use App\Models\FlashSaleProduct;
use App\Models\Product;
use Illuminate\Support\Collection;

class FlashSaleService
{
    /**
     * Get all live flash sales
     */
    public function getLiveSales(): Collection
    {
        return FlashSale::live()
            ->with(['products' => fn($q) => $q->where('status', 'active')])
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get upcoming flash sales
     */
    public function getUpcomingSales(): Collection
    {
        return FlashSale::upcoming()
            ->orderBy('starts_at')
            ->get();
    }

    /**
     * Get flash sale price for a product
     */
    public function getFlashSalePrice(int $productId): ?array
    {
        $flashSaleProduct = FlashSaleProduct::whereHas('flashSale', fn($q) => $q->live())
            ->where('product_id', $productId)
            ->with('flashSale')
            ->first();

        if (!$flashSaleProduct || !$flashSaleProduct->hasStock()) {
            return null;
        }

        return [
            'sale_price' => $flashSaleProduct->sale_price,
            'original_price' => $flashSaleProduct->product->price,
            'discount_percentage' => $flashSaleProduct->discount_percentage,
            'ends_at' => $flashSaleProduct->flashSale->ends_at,
            'time_remaining' => $flashSaleProduct->flashSale->time_remaining,
            'quantity_limit' => $flashSaleProduct->quantity_limit,
            'quantity_sold' => $flashSaleProduct->quantity_sold,
            'remaining' => $flashSaleProduct->remaining_quantity,
            'sold_percentage' => $flashSaleProduct->sold_percentage,
            'flash_sale_id' => $flashSaleProduct->flash_sale_id,
            'flash_sale_name' => $flashSaleProduct->flashSale->name,
        ];
    }

    /**
     * Check if product is in active flash sale
     */
    public function isInFlashSale(int $productId): bool
    {
        return FlashSaleProduct::whereHas('flashSale', fn($q) => $q->live())
            ->where('product_id', $productId)
            ->exists();
    }

    /**
     * Get effective price for product (flash sale or regular)
     */
    public function getEffectivePrice(Product $product): array
    {
        $flashSale = $this->getFlashSalePrice($product->id);
        
        if ($flashSale) {
            return [
                'price' => $flashSale['sale_price'],
                'original_price' => $product->price,
                'is_flash_sale' => true,
                'flash_sale' => $flashSale,
            ];
        }

        return [
            'price' => $product->discount_price ?? $product->price,
            'original_price' => $product->price,
            'is_flash_sale' => false,
            'flash_sale' => null,
        ];
    }

    /**
     * Process flash sale purchase
     */
    public function processPurchase(int $productId, int $quantity, int $userId): bool
    {
        $flashSaleProduct = FlashSaleProduct::whereHas('flashSale', fn($q) => $q->live())
            ->where('product_id', $productId)
            ->first();

        if (!$flashSaleProduct) {
            return false;
        }

        $check = $flashSaleProduct->canUserPurchase($userId, $quantity);
        if (!$check['can']) {
            return false;
        }

        $flashSaleProduct->incrementSold($quantity);
        return true;
    }

    /**
     * Get flash sale stats for admin
     */
    public function getStats(): array
    {
        return [
            'total_sales' => FlashSale::count(),
            'active_sales' => FlashSale::live()->count(),
            'upcoming_sales' => FlashSale::upcoming()->count(),
            'total_products_sold' => FlashSaleProduct::sum('quantity_sold'),
            'total_revenue' => FlashSaleProduct::join('flash_sales', 'flash_sale_products.flash_sale_id', '=', 'flash_sales.id')
                ->selectRaw('SUM(flash_sale_products.sale_price * flash_sale_products.quantity_sold) as total')
                ->value('total') ?? 0,
        ];
    }
}
