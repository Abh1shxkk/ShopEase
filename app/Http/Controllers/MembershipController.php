<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;
use App\Models\UserSubscription;
use App\Services\MembershipService;
use App\Services\Payment\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function __construct(
        protected MembershipService $membershipService,
        protected RazorpayService $razorpayService
    ) {}

    public function index()
    {
        $plans = MembershipPlan::active()->ordered()->get();
        $currentSubscription = Auth::check() ? Auth::user()->activeSubscription : null;

        return view('membership.index', compact('plans', 'currentSubscription'));
    }

    public function subscribe(Request $request, MembershipPlan $plan)
    {
        if (!$plan->is_active) {
            return back()->with('error', 'This plan is no longer available.');
        }

        $user = Auth::user();

        // Check if user already has this plan
        $currentSubscription = $user->activeSubscription;
        if ($currentSubscription && $currentSubscription->membership_plan_id === $plan->id) {
            return back()->with('error', 'You are already subscribed to this plan.');
        }

        return view('membership.checkout', compact('plan', 'currentSubscription'));
    }

    public function processPayment(Request $request, MembershipPlan $plan)
    {
        $request->validate([
            'payment_method' => 'required|in:razorpay,cod',
        ]);

        $user = Auth::user();

        try {
            // Create Razorpay order using Razorpay API directly
            $api = new \Razorpay\Api\Api(
                config('services.razorpay.key_id'),
                config('services.razorpay.key_secret')
            );
            
            $orderData = $api->order->create([
                'receipt' => 'membership_' . $plan->id . '_' . time(),
                'amount' => (int) ($plan->price * 100), // Amount in paise
                'currency' => 'INR',
                'notes' => [
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                ],
            ]);

            // Store pending subscription info in session
            session([
                'pending_membership' => [
                    'plan_id' => $plan->id,
                    'razorpay_order_id' => $orderData->id,
                ]
            ]);

            return response()->json([
                'success' => true,
                'razorpay_key' => config('services.razorpay.key_id'),
                'razorpay_order_id' => $orderData->id,
                'amount' => $plan->price * 100,
                'currency' => 'INR',
                'name' => config('app.name'),
                'description' => $plan->name . ' Membership',
                'prefill' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact' => $user->phone ?? '',
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Membership payment creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to create payment order: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $pendingMembership = session('pending_membership');
        if (!$pendingMembership) {
            return response()->json(['success' => false, 'error' => 'No pending membership found.'], 400);
        }

        // Verify signature
        $isValid = $this->razorpayService->verifyPaymentSignature(
            $request->razorpay_order_id,
            $request->razorpay_payment_id,
            $request->razorpay_signature
        );

        if (!$isValid) {
            return response()->json(['success' => false, 'error' => 'Payment verification failed.'], 400);
        }

        $plan = MembershipPlan::findOrFail($pendingMembership['plan_id']);
        $user = Auth::user();

        // Create subscription
        $subscription = $this->membershipService->subscribe($user, $plan, [
            'payment_method' => 'razorpay',
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_order_id' => $request->razorpay_order_id,
        ]);

        // Clear session
        session()->forget('pending_membership');

        return response()->json([
            'success' => true,
            'redirect' => route('membership.success', $subscription),
        ]);
    }

    public function success(UserSubscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        return view('membership.success', compact('subscription'));
    }

    public function manage()
    {
        $user = Auth::user();
        $currentSubscription = $user->activeSubscription;
        $subscriptionHistory = $user->subscriptions()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $paymentHistory = $user->subscriptionPayments()
            ->with('subscription.plan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('membership.manage', compact('currentSubscription', 'subscriptionHistory', 'paymentHistory'));
    }

    public function cancel(Request $request, UserSubscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $this->membershipService->cancelSubscription($subscription, $request->reason);

        return redirect()->route('membership.manage')
            ->with('success', 'Your subscription has been cancelled. You will continue to have access until ' . $subscription->ends_at->format('M d, Y') . '.');
    }

    public function toggleAutoRenew(UserSubscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        $subscription->update(['auto_renew' => !$subscription->auto_renew]);

        $message = $subscription->auto_renew 
            ? 'Auto-renewal has been enabled.' 
            : 'Auto-renewal has been disabled.';

        return back()->with('success', $message);
    }
}
