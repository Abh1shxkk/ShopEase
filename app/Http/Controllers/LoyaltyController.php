<?php

namespace App\Http\Controllers;

use App\Models\RewardTransaction;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    protected LoyaltyService $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Display loyalty dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $stats = $this->loyaltyService->getUserStats($user);
        $settings = $this->loyaltyService->getSettings();
        
        $transactions = RewardTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        // Calculate tier progress
        $tier = $stats['tier'];
        $tierProgress = 0;
        if ($tier['next_tier']) {
            $currentMin = $tier['min_points'];
            $nextMin = $settings["tier_" . strtolower($tier['next_tier']) . "_min"] ?? 0;
            if ($nextMin > $currentMin) {
                $tierProgress = min(100, (($user->total_earned_points - $currentMin) / ($nextMin - $currentMin)) * 100);
            }
        } else {
            $tierProgress = 100;
        }

        // Get earning opportunities
        $opportunities = $this->getEarningOpportunities($settings);

        return view('loyalty.index', compact('user', 'stats', 'settings', 'transactions', 'tierProgress', 'opportunities'));
    }

    /**
     * Get points history via AJAX
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $query = RewardTransaction::where('user_id', $user->id);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('loyalty.partials.transactions', compact('transactions'))->render(),
                'hasMore' => $transactions->hasMorePages(),
            ]);
        }

        return view('loyalty.history', compact('transactions'));
    }

    /**
     * Calculate points preview for checkout
     */
    public function calculatePreview(Request $request)
    {
        $request->validate([
            'order_total' => 'required|numeric|min:0',
            'redeem_points' => 'nullable|integer|min:0',
        ]);

        $user = Auth::user();
        $orderTotal = $request->order_total;
        $redeemPoints = $request->redeem_points ?? 0;

        $earnablePoints = $this->loyaltyService->calculateEarnablePoints($user, $orderTotal);
        $maxRedeemable = $this->loyaltyService->getMaxRedeemablePoints($user, $orderTotal);

        $response = [
            'earnable_points' => $earnablePoints,
            'max_redeemable' => $maxRedeemable,
            'current_balance' => (int) $user->reward_points,
        ];

        if ($redeemPoints > 0) {
            $validation = $this->loyaltyService->validateRedemption($user, $redeemPoints, $orderTotal);
            $response['redemption'] = $validation;
        }

        return response()->json($response);
    }

    /**
     * Get earning opportunities
     */
    private function getEarningOpportunities(array $settings): array
    {
        return [
            [
                'icon' => '🛒',
                'title' => 'Shop & Earn',
                'description' => "Earn {$settings['points_per_rupee']} point per ₹1 spent",
                'points' => "{$settings['points_per_rupee']}x",
            ],
            [
                'icon' => '⭐',
                'title' => 'Write Reviews',
                'description' => 'Share your experience with products',
                'points' => "+{$settings['review_bonus_points']}",
            ],
            [
                'icon' => '👥',
                'title' => 'Refer Friends',
                'description' => 'Invite friends and earn when they shop',
                'points' => '+100',
            ],
            [
                'icon' => '🎂',
                'title' => 'Birthday Bonus',
                'description' => 'Special points on your birthday',
                'points' => "+{$settings['birthday_bonus_points']}",
            ],
        ];
    }
}
