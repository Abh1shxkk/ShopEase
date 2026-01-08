<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ReferralSetting;
use App\Models\RewardTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    /**
     * Get loyalty settings
     */
    public function getSettings(): array
    {
        $defaults = [
            'loyalty_enabled' => true,
            'points_per_rupee' => 1,
            'points_value_in_rupees' => 0.25,
            'min_points_to_redeem' => 100,
            'max_points_per_order' => 500,
            'max_redemption_percent' => 50, // Max % of order that can be paid with points
            'signup_bonus_points' => 50,
            'birthday_bonus_points' => 100,
            'review_bonus_points' => 10,
            'points_expiry_days' => 365,
            'tier_bronze_min' => 0,
            'tier_silver_min' => 500,
            'tier_gold_min' => 2000,
            'tier_platinum_min' => 5000,
            'tier_bronze_multiplier' => 1,
            'tier_silver_multiplier' => 1.25,
            'tier_gold_multiplier' => 1.5,
            'tier_platinum_multiplier' => 2,
        ];

        $settings = [];
        foreach ($defaults as $key => $default) {
            $settings[$key] = ReferralSetting::get($key, $default);
        }

        return $settings;
    }

    /**
     * Check if loyalty program is enabled
     */
    public function isEnabled(): bool
    {
        return (bool) ReferralSetting::get('loyalty_enabled', true);
    }

    /**
     * Get user's loyalty tier
     */
    public function getUserTier(User $user): array
    {
        $totalEarned = $user->total_earned_points ?? 0;
        $settings = $this->getSettings();

        if ($totalEarned >= $settings['tier_platinum_min']) {
            return [
                'name' => 'Platinum',
                'icon' => '💎',
                'color' => 'purple',
                'multiplier' => $settings['tier_platinum_multiplier'],
                'min_points' => $settings['tier_platinum_min'],
                'next_tier' => null,
                'points_to_next' => 0,
            ];
        } elseif ($totalEarned >= $settings['tier_gold_min']) {
            return [
                'name' => 'Gold',
                'icon' => '🥇',
                'color' => 'amber',
                'multiplier' => $settings['tier_gold_multiplier'],
                'min_points' => $settings['tier_gold_min'],
                'next_tier' => 'Platinum',
                'points_to_next' => $settings['tier_platinum_min'] - $totalEarned,
            ];
        } elseif ($totalEarned >= $settings['tier_silver_min']) {
            return [
                'name' => 'Silver',
                'icon' => '🥈',
                'color' => 'slate',
                'multiplier' => $settings['tier_silver_multiplier'],
                'min_points' => $settings['tier_silver_min'],
                'next_tier' => 'Gold',
                'points_to_next' => $settings['tier_gold_min'] - $totalEarned,
            ];
        } else {
            return [
                'name' => 'Bronze',
                'icon' => '🥉',
                'color' => 'orange',
                'multiplier' => $settings['tier_bronze_multiplier'],
                'min_points' => $settings['tier_bronze_min'],
                'next_tier' => 'Silver',
                'points_to_next' => $settings['tier_silver_min'] - $totalEarned,
            ];
        }
    }

    /**
     * Calculate points to earn for an order
     */
    public function calculateEarnablePoints(User $user, float $orderTotal): int
    {
        if (!$this->isEnabled()) {
            return 0;
        }

        $settings = $this->getSettings();
        $tier = $this->getUserTier($user);
        
        $basePoints = floor($orderTotal * $settings['points_per_rupee']);
        $multipliedPoints = floor($basePoints * $tier['multiplier']);
        
        return min($multipliedPoints, $settings['max_points_per_order']);
    }

    /**
     * Calculate discount value from points
     */
    public function calculatePointsValue(int $points): float
    {
        $settings = $this->getSettings();
        return $points * $settings['points_value_in_rupees'];
    }

    /**
     * Get maximum redeemable points for an order
     */
    public function getMaxRedeemablePoints(User $user, float $orderTotal): int
    {
        if (!$this->isEnabled()) {
            return 0;
        }

        $settings = $this->getSettings();
        $userPoints = (int) $user->reward_points;

        if ($userPoints < $settings['min_points_to_redeem']) {
            return 0;
        }

        // Max based on order total percentage
        $maxByPercent = floor(($orderTotal * $settings['max_redemption_percent'] / 100) / $settings['points_value_in_rupees']);
        
        return min($userPoints, $maxByPercent);
    }

    /**
     * Validate points redemption
     */
    public function validateRedemption(User $user, int $points, float $orderTotal): array
    {
        $settings = $this->getSettings();

        if (!$this->isEnabled()) {
            return ['valid' => false, 'error' => 'Loyalty program is currently disabled.'];
        }

        if ($points < $settings['min_points_to_redeem']) {
            return ['valid' => false, 'error' => "Minimum {$settings['min_points_to_redeem']} points required to redeem."];
        }

        if ($points > $user->reward_points) {
            return ['valid' => false, 'error' => 'Insufficient points balance.'];
        }

        $maxRedeemable = $this->getMaxRedeemablePoints($user, $orderTotal);
        if ($points > $maxRedeemable) {
            return ['valid' => false, 'error' => "Maximum {$maxRedeemable} points can be redeemed for this order."];
        }

        return ['valid' => true, 'discount' => $this->calculatePointsValue($points)];
    }

    /**
     * Award points to user
     */
    public function awardPoints(User $user, int $points, string $source, ?string $description = null, $sourceable = null): RewardTransaction
    {
        $newBalance = $user->reward_points + $points;

        $transaction = RewardTransaction::create([
            'user_id' => $user->id,
            'type' => 'earned',
            'points' => $points,
            'balance_after' => $newBalance,
            'source' => $source,
            'sourceable_type' => $sourceable ? get_class($sourceable) : null,
            'sourceable_id' => $sourceable?->id,
            'description' => $description ?? "Earned {$points} points from {$source}",
        ]);

        $user->update([
            'reward_points' => $newBalance,
            'total_earned_points' => $user->total_earned_points + $points,
        ]);

        return $transaction;
    }

    /**
     * Redeem points from user
     */
    public function redeemPoints(User $user, int $points, string $source, ?string $description = null, $sourceable = null): RewardTransaction
    {
        $newBalance = $user->reward_points - $points;

        $transaction = RewardTransaction::create([
            'user_id' => $user->id,
            'type' => 'redeemed',
            'points' => -$points,
            'balance_after' => $newBalance,
            'source' => $source,
            'sourceable_type' => $sourceable ? get_class($sourceable) : null,
            'sourceable_id' => $sourceable?->id,
            'description' => $description ?? "Redeemed {$points} points for {$source}",
        ]);

        $user->update([
            'reward_points' => $newBalance,
            'total_redeemed_points' => $user->total_redeemed_points + $points,
        ]);

        return $transaction;
    }

    /**
     * Process order completion - award points
     */
    public function processOrderCompletion(Order $order): ?RewardTransaction
    {
        if (!$this->isEnabled() || $order->points_earned > 0) {
            return null;
        }

        $user = $order->user;
        $earnablePoints = $this->calculateEarnablePoints($user, $order->total);

        if ($earnablePoints <= 0) {
            return null;
        }

        $order->update(['points_earned' => $earnablePoints]);

        return $this->awardPoints(
            $user,
            $earnablePoints,
            'order',
            "Earned {$earnablePoints} points from order #{$order->order_number}",
            $order
        );
    }

    /**
     * Award signup bonus
     */
    public function awardSignupBonus(User $user): ?RewardTransaction
    {
        if (!$this->isEnabled()) {
            return null;
        }

        $settings = $this->getSettings();
        $bonusPoints = (int) $settings['signup_bonus_points'];

        if ($bonusPoints <= 0) {
            return null;
        }

        return $this->awardPoints(
            $user,
            $bonusPoints,
            'signup',
            "Welcome bonus! Earned {$bonusPoints} points for signing up.",
            $user
        );
    }

    /**
     * Award review bonus
     */
    public function awardReviewBonus(User $user, $review): ?RewardTransaction
    {
        if (!$this->isEnabled()) {
            return null;
        }

        $settings = $this->getSettings();
        $bonusPoints = (int) $settings['review_bonus_points'];

        if ($bonusPoints <= 0) {
            return null;
        }

        return $this->awardPoints(
            $user,
            $bonusPoints,
            'review',
            "Earned {$bonusPoints} points for writing a review.",
            $review
        );
    }

    /**
     * Get user loyalty stats
     */
    public function getUserStats(User $user): array
    {
        $tier = $this->getUserTier($user);
        $settings = $this->getSettings();

        return [
            'current_points' => (int) $user->reward_points,
            'total_earned' => (int) $user->total_earned_points,
            'total_redeemed' => (int) $user->total_redeemed_points,
            'points_value' => $this->calculatePointsValue((int) $user->reward_points),
            'tier' => $tier,
            'can_redeem' => $user->reward_points >= $settings['min_points_to_redeem'],
            'min_to_redeem' => (int) $settings['min_points_to_redeem'],
        ];
    }

    /**
     * Get global loyalty stats for admin
     */
    public function getGlobalStats(): array
    {
        return [
            'total_points_issued' => (int) RewardTransaction::where('type', 'earned')->sum('points'),
            'total_points_redeemed' => abs((int) RewardTransaction::where('type', 'redeemed')->sum('points')),
            'total_active_points' => (int) User::sum('reward_points'),
            'total_members' => User::where('reward_points', '>', 0)->count(),
            'avg_points_per_user' => round(User::where('reward_points', '>', 0)->avg('reward_points') ?? 0),
            'points_value_outstanding' => $this->calculatePointsValue((int) User::sum('reward_points')),
        ];
    }
}
