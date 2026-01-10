<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        return view('seller.profile.index', compact('seller'));
    }

    public function update(Request $request)
    {
        $seller = $request->user()->seller;

        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string|max:1000',
            'business_name' => 'nullable|string|max:255',
            'business_email' => 'required|email|max:255',
            'business_phone' => 'required|string|max:20',
            'business_address' => 'nullable|string|max:500',
            'gst_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'store_logo' => 'nullable|image|max:2048',
            'store_banner' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('store_logo')) {
            if ($seller->store_logo) {
                Storage::disk('public')->delete($seller->store_logo);
            }
            $validated['store_logo'] = $request->file('store_logo')->store('sellers/logos', 'public');
        }

        if ($request->hasFile('store_banner')) {
            if ($seller->store_banner) {
                Storage::disk('public')->delete($seller->store_banner);
            }
            $validated['store_banner'] = $request->file('store_banner')->store('sellers/banners', 'public');
        }

        $seller->update($validated);

        return back()->with('success', 'Store profile updated successfully!');
    }

    public function updateBankDetails(Request $request)
    {
        $seller = $request->user()->seller;

        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:30',
            'bank_ifsc_code' => 'required|string|max:15',
            'bank_account_holder' => 'required|string|max:255',
        ]);

        $seller->update($validated);

        return back()->with('success', 'Bank details updated successfully!');
    }
}
