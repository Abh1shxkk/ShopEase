<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralSetting;
use App\Models\RewardTransaction;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    protected LoyaltyService $loyaltyService;

    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Loyalty dashboard
     */
    public function index(Request $request)
    {
        $stats = $this->loyaltyService->getGlobalStats();
        
        // Top earners
        $topEarners = User::where('total_earned_points', '>', 0)
            ->orderByDesc('total_earned_points')
            ->take(10)
            ->get();

        // Recent transactions
        $recentTransactions = RewardTransaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Tier distribution
        $tierDistribution = $this->getTierDistribution();

        return view('admin.loyalty.index', compact('stats', 'topEarners', 'recentTransactions', 'tierDistribution'));
    }

    /**
     * All transactions
     */
    public function transactions(Request $request)
    {
        $query = RewardTransaction::with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(25);

        $stats = [
            'total_earned' => RewardTransaction::where('type', 'earned')->sum('points'),
            'total_redeemed' => abs(RewardTransaction::where('type', 'redeemed')->sum('points')),
            'total_adjusted' => RewardTransaction::where('type', 'adjusted')->sum('points'),
        ];

        return view('admin.loyalty.transactions', compact('transactions', 'stats'));
    }

    /**
     * Members list
     */
    public function members(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('tier')) {
            $settings = $this->loyaltyService->getSettings();
            switch ($request->tier) {
                case 'platinum':
                    $query->where('total_earned_points', '>=', $settings['tier_platinum_min']);
                    break;
                case 'gold':
                    $query->where('total_earned_points', '>=', $settings['tier_gold_min'])
                          ->where('total_earned_points', '<', $settings['tier_platinum_min']);
                    break;
                case 'silver':
                    $query->where('total_earned_points', '>=', $settings['tier_silver_min'])
                          ->where('total_earned_points', '<', $settings['tier_gold_min']);
                    break;
                case 'bronze':
                    $query->where('total_earned_points', '<', $settings['tier_silver_min']);
                    break;
            }
        }

        $sortBy = $request->get('sort', 'total_earned_points');
        $sortDir = $request->get('dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $members = $query->paginate(25);

        // Add tier info to each member
        foreach ($members as $member) {
            $member->tier = $this->loyaltyService->getUserTier($member);
        }

        return view('admin.loyalty.members', compact('members'));
    }

    /**
     * Loyalty settings
     */
    public function settings()
    {
        $settings = $this->loyaltyService->getSettings();
        return view('admin.loyalty.settings', compact('settings'));
    }

    /**
     * Update settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'points_per_rupee' => 'required|numeric|min:0|max:100',
            'points_value_in_rupees' => 'required|numeric|min:0.01|max:10',
            'min_points_to_redeem' => 'required|integer|min:1',
            'max_points_per_order' => 'required|integer|min:1',
            'max_redemption_percent' => 'required|integer|min:1|max:100',
            'signup_bonus_points' => 'required|integer|min:0',
            'birthday_bonus_points' => 'required|integer|min:0',
            'review_bonus_points' => 'required|integer|min:0',
            'points_expiry_days' => 'required|integer|min:30',
            'tier_silver_min' => 'required|integer|min:1',
            'tier_gold_min' => 'required|integer|min:1',
            'tier_platinum_min' => 'required|integer|min:1',
            'tier_bronze_multiplier' => 'required|numeric|min:1|max:5',
            'tier_silver_multiplier' => 'required|numeric|min:1|max:5',
            'tier_gold_multiplier' => 'required|numeric|min:1|max:5',
            'tier_platinum_multiplier' => 'required|numeric|min:1|max:5',
        ]);

        foreach ($request->except(['_token']) as $key => $value) {
            ReferralSetting::set($key, $value);
        }

        return back()->with('success', 'Loyalty settings updated successfully.');
    }

    /**
     * Toggle loyalty program
     */
    public function toggleStatus(Request $request)
    {
        $currentStatus = ReferralSetting::get('loyalty_enabled', true);
        ReferralSetting::set('loyalty_enabled', !$currentStatus);

        return back()->with('success', 'Loyalty program ' . (!$currentStatus ? 'enabled' : 'disabled') . ' successfully.');
    }

    /**
     * Adjust user points
     */
    public function adjustPoints(Request $request, User $user)
    {
        $request->validate([
            'points' => 'required|integer',
            'reason' => 'required|string|max:255',
        ]);

        $points = $request->points;
        $newBalance = $user->reward_points + $points;

        if ($newBalance < 0) {
            return back()->with('error', 'Cannot reduce points below zero.');
        }

        RewardTransaction::create([
            'user_id' => $user->id,
            'type' => 'adjusted',
            'points' => $points,
            'balance_after' => $newBalance,
            'source' => 'admin',
            'description' => $request->reason,
        ]);

        $user->update([
            'reward_points' => $newBalance,
            'total_earned_points' => $points > 0 ? $user->total_earned_points + $points : $user->total_earned_points,
            'total_redeemed_points' => $points < 0 ? $user->total_redeemed_points + abs($points) : $user->total_redeemed_points,
        ]);

        return back()->with('success', "Points adjusted successfully. New balance: {$newBalance}");
    }

    /**
     * Bulk award points
     */
    public function bulkAward(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'target' => 'required|in:all,tier,selected',
            'tier' => 'required_if:target,tier|in:bronze,silver,gold,platinum',
            'user_ids' => 'required_if:target,selected|array',
        ]);

        $query = User::where('role', '!=', 'admin');

        if ($request->target === 'tier') {
            $settings = $this->loyaltyService->getSettings();
            switch ($request->tier) {
                case 'platinum':
                    $query->where('total_earned_points', '>=', $settings['tier_platinum_min']);
                    break;
                case 'gold':
                    $query->where('total_earned_points', '>=', $settings['tier_gold_min'])
                          ->where('total_earned_points', '<', $settings['tier_platinum_min']);
                    break;
                case 'silver':
                    $query->where('total_earned_points', '>=', $settings['tier_silver_min'])
                          ->where('total_earned_points', '<', $settings['tier_gold_min']);
                    break;
                case 'bronze':
                    $query->where('total_earned_points', '<', $settings['tier_silver_min']);
                    break;
            }
        } elseif ($request->target === 'selected') {
            $query->whereIn('id', $request->user_ids);
        }

        $users = $query->get();
        $count = 0;

        foreach ($users as $user) {
            $newBalance = $user->reward_points + $request->points;
            
            RewardTransaction::create([
                'user_id' => $user->id,
                'type' => 'earned',
                'points' => $request->points,
                'balance_after' => $newBalance,
                'source' => 'admin',
                'description' => $request->reason,
            ]);

            $user->update([
                'reward_points' => $newBalance,
                'total_earned_points' => $user->total_earned_points + $request->points,
            ]);

            $count++;
        }

        return back()->with('success', "Successfully awarded {$request->points} points to {$count} users.");
    }

    /**
     * Get tier distribution
     */
    private function getTierDistribution(): array
    {
        $settings = $this->loyaltyService->getSettings();

        return [
            'platinum' => User::where('total_earned_points', '>=', $settings['tier_platinum_min'])->count(),
            'gold' => User::where('total_earned_points', '>=', $settings['tier_gold_min'])
                         ->where('total_earned_points', '<', $settings['tier_platinum_min'])->count(),
            'silver' => User::where('total_earned_points', '>=', $settings['tier_silver_min'])
                           ->where('total_earned_points', '<', $settings['tier_gold_min'])->count(),
            'bronze' => User::where('total_earned_points', '<', $settings['tier_silver_min'])
                           ->where('total_earned_points', '>', 0)->count(),
        ];
    }
}
