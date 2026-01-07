<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\RewardTransaction;
use App\Models\ReferralSetting;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    protected ReferralService $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    public function index(Request $request)
    {
        $query = Referral::with(['referrer', 'referred']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('referrer', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                  ->orWhereHas('referred', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
        }

        $referrals = $query->latest()->paginate(20);

        $stats = [
            'total' => Referral::count(),
            'completed' => Referral::completed()->count(),
            'pending' => Referral::pending()->count(),
            'total_rewards' => Referral::completed()->sum('referrer_reward'),
        ];

        // Get all customers for the adjust points dropdown
        $customers = User::where('role', '!=', 'admin')
            ->orderBy('name')
            ->select('id', 'name', 'email', 'reward_points')
            ->get();

        return view('admin.referrals.index', compact('referrals', 'stats', 'customers'));
    }

    public function transactions(Request $request)
    {
        $query = RewardTransaction::with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        $transactions = $query->latest()->paginate(20);

        $stats = [
            'total_earned' => RewardTransaction::earned()->sum('points'),
            'total_redeemed' => abs(RewardTransaction::redeemed()->sum('points')),
            'active_points' => User::sum('reward_points'),
        ];

        return view('admin.referrals.transactions', compact('transactions', 'stats'));
    }

    public function settings()
    {
        $defaults = ReferralSetting::getDefaults();
        $settings = [];
        
        foreach ($defaults as $key => $default) {
            $settings[$key] = ReferralSetting::get($key, $default);
        }

        return view('admin.referrals.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'referrer_reward_points' => 'required|numeric|min:0',
            'referred_reward_points' => 'required|numeric|min:0',
            'min_order_for_completion' => 'required|numeric|min:0',
            'points_per_rupee' => 'required|numeric|min:0',
            'points_value_in_rupees' => 'required|numeric|min:0',
            'min_points_to_redeem' => 'required|numeric|min:0',
            'max_points_per_order' => 'required|numeric|min:0',
            'referral_expiry_days' => 'required|integer|min:1',
        ]);

        foreach ($request->except('_token') as $key => $value) {
            ReferralSetting::set($key, $value);
        }

        return back()->with('success', 'Referral settings updated successfully.');
    }

    public function adjustPoints(Request $request, User $user)
    {
        $request->validate([
            'points' => 'required|numeric',
            'description' => 'required|string|max:255',
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
            'description' => $request->description,
        ]);

        $user->update([
            'reward_points' => $newBalance,
            'total_earned_points' => $points > 0 ? $user->total_earned_points + $points : $user->total_earned_points,
        ]);

        return back()->with('success', 'Points adjusted successfully.');
    }
}
