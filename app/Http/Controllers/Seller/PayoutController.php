<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerPayout;
use App\Models\SellerSetting;
use App\Services\SellerService;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function __construct(protected SellerService $sellerService) {}

    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        
        $payouts = $seller->payouts()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        $settings = SellerSetting::get();
        $minimumPayout = $settings->minimum_payout_amount ?? 500;
        $pendingEarnings = $seller->earnings()->where('status', 'pending')->sum('seller_amount');

        return view('seller.payouts.index', compact('payouts', 'seller', 'minimumPayout', 'pendingEarnings'));
    }

    public function store(Request $request)
    {
        $seller = $request->user()->seller;
        $settings = SellerSetting::get();

        $validated = $request->validate([
            'amount' => "required|numeric|min:{$settings->minimum_payout_amount}|max:{$seller->wallet_balance}",
            'payment_method' => 'required|in:bank_transfer,upi,razorpay',
        ]);

        $payout = $this->sellerService->requestPayout(
            $seller,
            $validated['amount'],
            $validated['payment_method']
        );

        if (!$payout) {
            return back()->with('error', 'Unable to process payout request. Please check your balance.');
        }

        return back()->with('success', 'Payout request submitted successfully!');
    }
}
