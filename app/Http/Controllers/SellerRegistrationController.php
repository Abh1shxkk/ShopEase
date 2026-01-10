<?php

namespace App\Http\Controllers;

use App\Services\SellerService;
use Illuminate\Http\Request;

class SellerRegistrationController extends Controller
{
    public function __construct(protected SellerService $sellerService) {}

    public function showForm()
    {
        $user = auth()->user();
        
        // Check if already a seller
        if ($user->seller) {
            return redirect()->route('seller.dashboard');
        }

        return view('seller.register');
    }

    public function register(Request $request)
    {
        $user = auth()->user();

        if ($user->seller) {
            return redirect()->route('seller.dashboard');
        }

        $validated = $request->validate([
            'store_name' => 'required|string|max:255|unique:sellers,store_name',
            'store_description' => 'nullable|string|max:1000',
            'business_name' => 'nullable|string|max:255',
            'business_email' => 'required|email|max:255',
            'business_phone' => 'required|string|max:20',
            'business_address' => 'nullable|string|max:500',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:30',
            'bank_ifsc_code' => 'nullable|string|max:15',
            'bank_account_holder' => 'nullable|string|max:255',
            'terms' => 'required|accepted',
        ]);

        unset($validated['terms']);

        $seller = $this->sellerService->registerSeller($user, $validated);

        if ($seller->isApproved()) {
            return redirect()->route('seller.dashboard')
                ->with('success', 'Congratulations! Your seller account is now active.');
        }

        return redirect()->route('seller.pending')
            ->with('success', 'Your seller application has been submitted for review.');
    }
}
