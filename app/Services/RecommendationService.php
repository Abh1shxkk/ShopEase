<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\RecentlyViewed;
use Illuminate\Support\Collection;

class RecommendationService
{
    /**
     * Get "You may also like" recommendations based on product
     */
    public function getRelatedProducts(Product $product, int $limit = 8): Collection
    {
        // Get products from same category
        $categoryProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        // If not enough, fill with similar price range products
        if ($categoryProducts->count() < $limit) {
            $priceRange = $product->price * 0.3;
            $additionalProducts = Product::where('id', '!=', $product->id)
                ->where('status', 'active')
                ->whereNotIn('id', $categoryProducts->pluck('id'))
                ->whereBetween('price', [$product->price - $priceRange, $product->price + $priceRange])
                ->inRandomOrder()
                ->limit($limit - $categoryProducts->count())
                ->get();
            
            $categoryProducts = $categoryProducts->merge($additionalProducts);
        }

        return $categoryProducts;
    }

    /**
     * Get personalized recommendations for user
     */
    public function getPersonalizedRecommendations(?int $userId, int $limit = 8): Collection
    {
        if (!$userId) {
            return $this->getTrendingProducts($limit);
        }

        // Get categories from user's recent views and purchases
        $viewedCategories = RecentlyViewed::where('user_id', $userId)
            ->with('product')
            ->latest('viewed_at')
            ->limit(20)
            ->get()
            ->pluck('product.category_id')
            ->filter()
            ->unique();

        $purchasedCategories = Order::where('user_id', $userId)
            ->with('items.product')
            ->latest()
            ->limit(10)
            ->get()
            ->flatMap(fn($order) => $order->items->pluck('product.category_id'))
            ->filter()
            ->unique();

        $preferredCategories = $viewedCategories->merge($purchasedCategories)->unique();

        if ($preferredCategories->isEmpty()) {
            return $this->getTrendingProducts($limit);
        }

        // Get products from preferred categories that user hasn't viewed recently
        $recentlyViewedIds = RecentlyViewed::where('user_id', $userId)
            ->pluck('product_id');

        return Product::whereIn('category_id', $preferredCategories)
            ->whereNotIn('id', $recentlyViewedIds)
            ->where('status', 'active')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * Get trending/popular products
     */
    public function getTrendingProducts(int $limit = 8): Collection
    {
        // Based on recent orders
        $popularProductIds = \DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->select('order_items.product_id', \DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('order_items.product_id')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->pluck('product_id');

        if ($popularProductIds->isEmpty()) {
            return Product::where('status', 'active')
                ->inRandomOrder()
                ->limit($limit)
                ->get();
        }

        return Product::whereIn('id', $popularProductIds)
            ->where('status', 'active')
            ->get()
            ->sortBy(fn($p) => array_search($p->id, $popularProductIds->toArray()));
    }

    /**
     * Get "Frequently bought together" products
     */
    public function getFrequentlyBoughtTogether(Product $product, int $limit = 4): Collection
    {
        // Find orders containing this product
        $orderIds = \DB::table('order_items')
            ->where('product_id', $product->id)
            ->pluck('order_id');

        if ($orderIds->isEmpty()) {
            return collect();
        }

        // Find other products in those orders
        $relatedProductIds = \DB::table('order_items')
            ->whereIn('order_id', $orderIds)
            ->where('product_id', '!=', $product->id)
            ->select('product_id', \DB::raw('COUNT(*) as frequency'))
            ->groupBy('product_id')
            ->orderByDesc('frequency')
            ->limit($limit)
            ->pluck('product_id');

        return Product::whereIn('id', $relatedProductIds)
            ->where('status', 'active')
            ->get();
    }
}
