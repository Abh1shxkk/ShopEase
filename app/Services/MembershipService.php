<?php

namespace App\Services;

use App\Models\User;
use App\Models\MembershipPlan;
use App\Models\UserSubscription;
use App\Models\SubscriptionPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MembershipService
{
    public function subscribe(User $user, MembershipPlan $plan, array $paymentData = []): UserSubscription
    {
        return DB::transaction(function () use ($user, $plan, $paymentData) {
            // Cancel any existing active subscription
            $existingSubscription = $user->activeSubscription;
            if ($existingSubscription) {
                $existingSubscription->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'cancellation_reason' => 'Upgraded to new plan',
                ]);
            }

            // Create new subscription
            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'membership_plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addDays($plan->duration_days),
                'auto_renew' => $paymentData['auto_renew'] ?? true,
                'payment_method' => $paymentData['payment_method'] ?? 'razorpay',
                'razorpay_subscription_id' => $paymentData['razorpay_subscription_id'] ?? null,
                'amount_paid' => $plan->price,
            ]);

            // Create payment record
            SubscriptionPayment::create([
                'user_subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'amount' => $plan->price,
                'currency' => 'INR',
                'status' => 'completed',
                'payment_method' => $paymentData['payment_method'] ?? 'razorpay',
                'razorpay_payment_id' => $paymentData['razorpay_payment_id'] ?? null,
                'razorpay_order_id' => $paymentData['razorpay_order_id'] ?? null,
                'paid_at' => now(),
            ]);

            // Update user membership status
            $user->update([
                'is_member' => true,
                'membership_expires_at' => $subscription->ends_at,
            ]);

            return $subscription;
        });
    }

    public function cancelSubscription(UserSubscription $subscription, string $reason = null): void
    {
        $subscription->cancel($reason);
    }

    public function renewSubscription(UserSubscription $subscription, array $paymentData = []): UserSubscription
    {
        return DB::transaction(function () use ($subscription, $paymentData) {
            $plan = $subscription->plan;
            $user = $subscription->user;

            // Mark old subscription as expired
            $subscription->update(['status' => 'expired']);

            // Create new subscription
            $newSubscription = UserSubscription::create([
                'user_id' => $user->id,
                'membership_plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => $subscription->ends_at,
                'ends_at' => Carbon::parse($subscription->ends_at)->addDays($plan->duration_days),
                'auto_renew' => $subscription->auto_renew,
                'payment_method' => $paymentData['payment_method'] ?? $subscription->payment_method,
                'amount_paid' => $plan->price,
            ]);

            // Create payment record
            SubscriptionPayment::create([
                'user_subscription_id' => $newSubscription->id,
                'user_id' => $user->id,
                'amount' => $plan->price,
                'currency' => 'INR',
                'status' => 'completed',
                'payment_method' => $paymentData['payment_method'] ?? 'razorpay',
                'razorpay_payment_id' => $paymentData['razorpay_payment_id'] ?? null,
                'paid_at' => now(),
            ]);

            // Update user membership status
            $user->update([
                'is_member' => true,
                'membership_expires_at' => $newSubscription->ends_at,
            ]);

            return $newSubscription;
        });
    }

    public function calculateShipping(User $user, float $subtotal): float
    {
        // Free shipping for members with that benefit
        if ($user->hasFreeShipping()) {
            return 0;
        }

        // Standard shipping logic (free over ₹500)
        return $subtotal >= 500 ? 0 : 50;
    }

    public function applyMemberDiscount(User $user, float $subtotal): array
    {
        $discount = $user->getMemberDiscount();
        $discountAmount = 0;

        if ($discount > 0) {
            $discountAmount = round($subtotal * ($discount / 100), 2);
        }

        return [
            'discount_percentage' => $discount,
            'discount_amount' => $discountAmount,
            'final_subtotal' => $subtotal - $discountAmount,
        ];
    }

    public function getExpiringSoonSubscriptions(int $days = 7)
    {
        return UserSubscription::with(['user', 'plan'])
            ->where('status', 'active')
            ->where('auto_renew', true)
            ->whereBetween('ends_at', [now(), now()->addDays($days)])
            ->get();
    }

    public function processExpiredSubscriptions(): int
    {
        $expired = UserSubscription::where('status', 'active')
            ->where('ends_at', '<', now())
            ->get();

        foreach ($expired as $subscription) {
            $subscription->update(['status' => 'expired']);
            $subscription->user->updateMembershipStatus();
        }

        return $expired->count();
    }
}
