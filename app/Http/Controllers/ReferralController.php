<?php

namespace App\Http\Controllers;

use App\Services\ReferralService;
use App\Models\Referral;
use App\Models\RewardTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    protected ReferralService $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Generate referral code if not exists
        $this->referralService->generateReferralCode($user);
        
        $stats = $this->referralService->getReferralStats($user);
        $referrals = Referral::where('referrer_id', $user->id)
            ->with('referred')
            ->latest()
            ->paginate(10);
        
        $transactions = RewardTransaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $pointsValue = $this->referralService->getPointsValue($user->reward_points);

        return view('referral.index', compact('user', 'stats', 'referrals', 'transactions', 'pointsValue'));
    }

    public function applyCode(Request $request)
    {
        $request->validate(['code' => 'required|string|size:8']);

        $result = $this->referralService->applyReferralCode(Auth::user(), $request->code);

        if ($result) {
            return back()->with('success', 'Referral code applied successfully! You earned bonus points.');
        }

        return back()->with('error', 'Invalid or already used referral code.');
    }
}
