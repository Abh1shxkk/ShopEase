<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;
use App\Models\RewardTransaction;
use App\Models\ReferralSetting;
use App\Models\Order;
use Illuminate\Support\Str;

class ReferralService
{
    public function generateReferralCode(User $user): string
    {
        if ($user->referral_code) {
            return $user->referral_code;
        }

        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        $user->update(['referral_code' => $code]);
        return $code;
    }

    public function applyReferralCode(User $newUser, string $code): bool
    {
        if (!$this->isEnabled()) return false;

        $referrer = User::where('referral_code', $code)->first();
        
        if (!$referrer || $referrer->id === $newUser->id) {
            return false;
        }

        if ($newUser->referred_by) {
            return false; // Already referred
        }

        $newUser->update(['referred_by' => $referrer->id]);

        Referral::create([
            'referrer_id' => $referrer->id,
            'referred_id' => $newUser->id,
            'status' => 'pending',
            'referrer_reward' => $this->getSetting('referrer_reward_points'),
            'referred_reward' => $this->getSetting('referred_reward_points'),
        ]);

        // Give signup bonus to referred user
        $signupBonus = $this->getSetting('referred_reward_points');
        if ($signupBonus > 0) {
            $this->addPoints($newUser, $signupBonus, 'signup', $referrer, 'Signup bonus for using referral code');
        }

        return true;
    }

    public function completeReferral(Order $order): void
    {
        if (!$this->isEnabled()) return;

        $user = $order->user;
        $minOrder = $this->getSetting('min_order_for_completion');

        if ($order->total < $minOrder) return;

        $referral = Referral::where('referred_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$referral) return;

        // Complete the referral
        $referral->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Reward the referrer
        $this->addPoints(
            $referral->referrer,
            $referral->referrer_reward,
            'referral',
            $referral,
            "Referral bonus for {$user->name}'s first order"
        );
    }

    public function earnPointsFromOrder(Order $order): void
    {
        if (!$this->isEnabled()) return;

        $pointsPerRupee = $this->getSetting('points_per_rupee');
        $maxPoints = $this->getSetting('max_points_per_order');
        
        $earnedPoints = min($order->total * $pointsPerRupee, $maxPoints);
        
        if ($earnedPoints > 0) {
            $this->addPoints(
                $order->user,
                $earnedPoints,
                'order',
                $order,
                "Points earned from order #{$order->order_number}"
            );
        }
    }

    public function addPoints(User $user, float $points, string $source, $sourceable = null, string $description = null): RewardTransaction
    {
        $newBalance = $user->reward_points + $points;
        
        $transaction = RewardTransaction::create([
            'user_id' => $user->id,
            'type' => 'earned',
            'points' => $points,
            'balance_after' => $newBalance,
            'source' => $source,
            'sourceable_type' => $sourceable ? get_class($sourceable) : null,
            'sourceable_id' => $sourceable ? $sourceable->id : null,
            'description' => $description,
        ]);

        $user->update([
            'reward_points' => $newBalance,
            'total_earned_points' => $user->total_earned_points + $points,
        ]);

        return $transaction;
    }

    public function redeemPoints(User $user, float $points, $sourceable = null, string $description = null): ?RewardTransaction
    {
        if (!$this->isEnabled()) return null;
        
        $minPoints = $this->getSetting('min_points_to_redeem');
        
        if ($points < $minPoints || $user->reward_points < $points) {
            return null;
        }

        $newBalance = $user->reward_points - $points;
        
        $transaction = RewardTransaction::create([
            'user_id' => $user->id,
            'type' => 'redeemed',
            'points' => -$points,
            'balance_after' => $newBalance,
            'source' => 'redemption',
            'sourceable_type' => $sourceable ? get_class($sourceable) : null,
            'sourceable_id' => $sourceable ? $sourceable->id : null,
            'description' => $description,
        ]);

        $user->update([
            'reward_points' => $newBalance,
            'total_redeemed_points' => $user->total_redeemed_points + $points,
        ]);

        return $transaction;
    }

    public function getPointsValue(float $points): float
    {
        return $points * $this->getSetting('points_value_in_rupees');
    }

    public function getReferralStats(User $user): array
    {
        $referrals = Referral::where('referrer_id', $user->id)->get();
        
        return [
            'total_referrals' => $referrals->count(),
            'completed_referrals' => $referrals->where('status', 'completed')->count(),
            'pending_referrals' => $referrals->where('status', 'pending')->count(),
            'total_earned' => $referrals->where('status', 'completed')->sum('referrer_reward'),
        ];
    }

    public function getSetting(string $key)
    {
        $defaults = ReferralSetting::getDefaults();
        return ReferralSetting::get($key, $defaults[$key] ?? null);
    }

    public function isEnabled(): bool
    {
        return (bool) $this->getSetting('is_enabled');
    }
}
