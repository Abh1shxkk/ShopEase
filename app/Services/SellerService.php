<?php

namespace App\Services;

use App\Models\Seller;
use App\Models\SellerEarning;
use App\Models\SellerPayout;
use App\Models\SellerSetting;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SellerService
{
    public function registerSeller(User $user, array $data): Seller
    {
        $settings = SellerSetting::get();
        
        $seller = Seller::create([
            'user_id' => $user->id,
            'store_name' => $data['store_name'],
            'store_description' => $data['store_description'] ?? null,
            'business_name' => $data['business_name'] ?? null,
            'business_email' => $data['business_email'],
            'business_phone' => $data['business_phone'],
            'business_address' => $data['business_address'] ?? null,
            'gst_number' => $data['gst_number'] ?? null,
            'pan_number' => $data['pan_number'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'bank_account_number' => $data['bank_account_number'] ?? null,
            'bank_ifsc_code' => $data['bank_ifsc_code'] ?? null,
            'bank_account_holder' => $data['bank_account_holder'] ?? null,
            'commission_rate' => $settings->default_commission_rate,
            'status' => $settings->auto_approve_sellers ? 'approved' : 'pending',
            'approved_at' => $settings->auto_approve_sellers ? now() : null,
        ]);

        // Update user role
        $user->update(['role' => 'seller']);

        return $seller;
    }

    public function approveSeller(Seller $seller): void
    {
        $seller->update([
            'status' => 'approved',
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);
    }

    public function rejectSeller(Seller $seller, string $reason): void
    {
        $seller->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function suspendSeller(Seller $seller, string $reason): void
    {
        $seller->update([
            'status' => 'suspended',
            'rejection_reason' => $reason,
        ]);
    }

    public function calculateEarnings(Order $order): void
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            
            if (!$product || !$product->seller_id) {
                continue;
            }

            $seller = $product->seller;
            $orderAmount = $item->price * $item->quantity;
            $commissionRate = $seller->commission_rate;
            $commissionAmount = ($orderAmount * $commissionRate) / 100;
            $sellerAmount = $orderAmount - $commissionAmount;

            SellerEarning::create([
                'seller_id' => $seller->id,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'order_amount' => $orderAmount,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'seller_amount' => $sellerAmount,
                'status' => 'pending',
            ]);
        }
    }

    public function processEarnings(Order $order): void
    {
        // Only process when order is delivered
        if ($order->status !== 'delivered') {
            return;
        }

        $earnings = SellerEarning::where('order_id', $order->id)
            ->where('status', 'pending')
            ->get();

        foreach ($earnings as $earning) {
            $earning->markAsProcessed();
        }
    }

    public function requestPayout(Seller $seller, float $amount, string $paymentMethod = 'bank_transfer'): ?SellerPayout
    {
        $settings = SellerSetting::get();

        if ($amount < $settings->minimum_payout_amount) {
            return null;
        }

        if ($seller->wallet_balance < $amount) {
            return null;
        }

        return DB::transaction(function () use ($seller, $amount, $paymentMethod) {
            $seller->deductBalance($amount);

            return SellerPayout::create([
                'seller_id' => $seller->id,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'status' => 'pending',
            ]);
        });
    }

    public function processPayout(SellerPayout $payout, string $transactionId = null): void
    {
        $payout->markAsCompleted($transactionId);
    }

    public function getSellerStats(Seller $seller): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        return [
            'total_products' => $seller->products()->count(),
            'active_products' => $seller->products()->where('status', 'active')->count(),
            'pending_products' => $seller->products()->where('approval_status', 'pending')->count(),
            'total_orders' => $seller->earnings()->distinct('order_id')->count('order_id'),
            'today_orders' => $seller->earnings()->whereDate('created_at', $today)->distinct('order_id')->count('order_id'),
            'month_orders' => $seller->earnings()->where('created_at', '>=', $thisMonth)->distinct('order_id')->count('order_id'),
            'total_earnings' => $seller->total_earnings,
            'wallet_balance' => $seller->wallet_balance,
            'pending_earnings' => $seller->pending_earnings,
            'today_earnings' => $seller->earnings()->whereDate('created_at', $today)->where('status', 'processed')->sum('seller_amount'),
            'month_earnings' => $seller->earnings()->where('created_at', '>=', $thisMonth)->where('status', 'processed')->sum('seller_amount'),
            'average_rating' => $seller->average_rating,
            'total_reviews' => $seller->reviews()->where('is_approved', true)->count(),
        ];
    }
}
