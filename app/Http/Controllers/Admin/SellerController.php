<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\SellerPayout;
use App\Models\SellerSetting;
use App\Services\SellerService;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function __construct(protected SellerService $sellerService) {}

    public function index(Request $request)
    {
        $sellers = Seller::with('user')
            ->when($request->search, fn($q) => $q->where('store_name', 'like', "%{$request->search}%")
                ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$request->search}%")))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('admin.sellers.index', compact('sellers'));
    }

    public function show(Seller $seller)
    {
        $seller->load(['user', 'products', 'earnings', 'payouts']);
        $stats = $this->sellerService->getSellerStats($seller);
        
        return view('admin.sellers.show', compact('seller', 'stats'));
    }

    public function approve(Seller $seller)
    {
        $this->sellerService->approveSeller($seller);
        return back()->with('success', 'Seller approved successfully!');
    }

    public function reject(Request $request, Seller $seller)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->sellerService->rejectSeller($seller, $validated['reason']);
        return back()->with('success', 'Seller rejected.');
    }

    public function suspend(Request $request, Seller $seller)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->sellerService->suspendSeller($seller, $validated['reason']);
        return back()->with('success', 'Seller suspended.');
    }

    public function updateCommission(Request $request, Seller $seller)
    {
        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $seller->update($validated);
        return back()->with('success', 'Commission rate updated!');
    }

    public function payouts(Request $request)
    {
        $payouts = SellerPayout::with('seller.user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('admin.sellers.payouts', compact('payouts'));
    }

    public function processPayout(Request $request, SellerPayout $payout)
    {
        $validated = $request->validate([
            'transaction_id' => 'nullable|string|max:255',
        ]);

        $this->sellerService->processPayout($payout, $validated['transaction_id'] ?? null);
        return back()->with('success', 'Payout processed successfully!');
    }

    public function rejectPayout(Request $request, SellerPayout $payout)
    {
        $validated = $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        // Refund the amount back to seller wallet
        $payout->seller->increment('wallet_balance', $payout->amount);
        $payout->seller->decrement('total_withdrawn', $payout->amount);
        
        $payout->update([
            'status' => 'failed',
            'notes' => $validated['notes'],
        ]);

        return back()->with('success', 'Payout rejected and amount refunded to seller wallet.');
    }

    public function settings()
    {
        $settings = SellerSetting::get();
        return view('admin.sellers.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'default_commission_rate' => 'required|numeric|min:0|max:100',
            'minimum_payout_amount' => 'required|numeric|min:0',
            'payout_frequency_days' => 'required|integer|min:1',
            'auto_approve_sellers' => 'boolean',
            'auto_approve_products' => 'boolean',
            'seller_terms' => 'nullable|string',
        ]);

        $validated['auto_approve_sellers'] = $request->boolean('auto_approve_sellers');
        $validated['auto_approve_products'] = $request->boolean('auto_approve_products');

        SellerSetting::updateOrCreate(['id' => 1], $validated);

        return back()->with('success', 'Settings updated successfully!');
    }

    public function products(Request $request)
    {
        $products = \App\Models\Product::with(['seller.user', 'category'])
            ->whereNotNull('seller_id')
            ->when($request->approval, fn($q) => $q->where('approval_status', $request->approval))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(15);

        return view('admin.sellers.products', compact('products'));
    }

    public function approveProduct(\App\Models\Product $product)
    {
        $product->update(['approval_status' => 'approved']);
        return back()->with('success', 'Product approved!');
    }

    public function rejectProduct(Request $request, \App\Models\Product $product)
    {
        $product->update(['approval_status' => 'rejected']);
        return back()->with('success', 'Product rejected.');
    }
}
