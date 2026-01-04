<?php

namespace App\Services;

use App\Models\InventoryAlert;
use App\Models\Product;
use App\Models\StockNotification;
use App\Mail\LowStockAlert;
use App\Mail\RestockNotification;
use Illuminate\Support\Facades\Mail;

class InventoryService
{
    public function checkStock(Product $product, int $previousStock = null): void
    {
        $currentStock = $product->stock;
        $threshold = $product->low_stock_threshold;

        // Check for out of stock
        if ($currentStock <= 0) {
            $this->createAlert($product, 'out_of_stock', $currentStock);
        }
        // Check for low stock
        elseif ($currentStock <= $threshold && $currentStock > 0) {
            // Only create alert if we haven't already for this stock level
            $existingAlert = InventoryAlert::where('product_id', $product->id)
                ->where('type', 'low_stock')
                ->where('stock_level', $currentStock)
                ->whereDate('created_at', today())
                ->exists();

            if (!$existingAlert) {
                $this->createAlert($product, 'low_stock', $currentStock);
            }
        }

        // Check for restock (was out of stock, now has stock)
        if ($previousStock !== null && $previousStock <= 0 && $currentStock > 0) {
            $this->createAlert($product, 'restocked', $currentStock);
            $this->notifyCustomers($product);
        }
    }

    protected function createAlert(Product $product, string $type, int $stockLevel): void
    {
        InventoryAlert::create([
            'product_id' => $product->id,
            'type' => $type,
            'stock_level' => $stockLevel,
        ]);
    }

    public function notifyCustomers(Product $product): void
    {
        $notifications = StockNotification::where('product_id', $product->id)
            ->where('notified', false)
            ->get();

        foreach ($notifications as $notification) {
            try {
                Mail::to($notification->email)->queue(new RestockNotification($product));
                
                $notification->update([
                    'notified' => true,
                    'notified_at' => now(),
                ]);
            } catch (\Exception $e) {
                \Log::error("Failed to send restock notification: " . $e->getMessage());
            }
        }
    }

    public function sendLowStockEmailToAdmin(): void
    {
        $lowStockProducts = Product::where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->where('status', 'active')
            ->get();

        $outOfStockProducts = Product::where('stock', '<=', 0)
            ->where('status', 'active')
            ->get();

        if ($lowStockProducts->isNotEmpty() || $outOfStockProducts->isNotEmpty()) {
            $adminEmail = config('mail.admin_email', config('mail.from.address'));
            Mail::to($adminEmail)->queue(new LowStockAlert($lowStockProducts, $outOfStockProducts));

            // Mark alerts as email sent
            InventoryAlert::whereIn('product_id', $lowStockProducts->pluck('id'))
                ->orWhereIn('product_id', $outOfStockProducts->pluck('id'))
                ->where('email_sent', false)
                ->update(['email_sent' => true]);
        }
    }

    public static function getLowStockCount(): int
    {
        return Product::where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->where('status', 'active')
            ->count();
    }

    public static function getOutOfStockCount(): int
    {
        return Product::where('stock', '<=', 0)
            ->where('status', 'active')
            ->count();
    }

    public static function getUnreadAlertsCount(): int
    {
        return InventoryAlert::where('is_read', false)->count();
    }
}
