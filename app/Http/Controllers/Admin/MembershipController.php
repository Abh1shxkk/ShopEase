<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\UserSubscription;
use App\Models\EarlyAccessSale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MembershipController extends Controller
{
    // Plans Management
    public function plans()
    {
        $plans = MembershipPlan::withCount('activeSubscriptions')
            ->ordered()
            ->paginate(10);

        $stats = [
            'total_plans' => MembershipPlan::count(),
            'active_plans' => MembershipPlan::where('is_active', true)->count(),
            'total_subscribers' => UserSubscription::where('status', 'active')->count(),
            'monthly_revenue' => UserSubscription::where('status', 'active')
                ->whereMonth('created_at', now()->month)
                ->sum('amount_paid'),
        ];

        return view('admin.membership.plans.index', compact('plans', 'stats'));
    }

    public function createPlan()
    {
        return view('admin.membership.plans.form');
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'duration_days' => 'required|integer|min:1',
            'free_shipping' => 'boolean',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'early_access_days' => 'nullable|integer|min:0',
            'priority_support' => 'boolean',
            'exclusive_products' => 'boolean',
            'features' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['free_shipping'] = $request->boolean('free_shipping');
        $validated['priority_support'] = $request->boolean('priority_support');
        $validated['exclusive_products'] = $request->boolean('exclusive_products');
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active', true);

        MembershipPlan::create($validated);

        return redirect()->route('admin.membership.plans')
            ->with('success', 'Membership plan created successfully.');
    }

    public function editPlan(MembershipPlan $plan)
    {
        return view('admin.membership.plans.form', compact('plan'));
    }

    public function updatePlan(Request $request, MembershipPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'duration_days' => 'required|integer|min:1',
            'free_shipping' => 'boolean',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'early_access_days' => 'nullable|integer|min:0',
            'priority_support' => 'boolean',
            'exclusive_products' => 'boolean',
            'features' => 'nullable|array',
            'sort_order' => 'nullable|integer',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['free_shipping'] = $request->boolean('free_shipping');
        $validated['priority_support'] = $request->boolean('priority_support');
        $validated['exclusive_products'] = $request->boolean('exclusive_products');
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active');

        $plan->update($validated);

        return redirect()->route('admin.membership.plans')
            ->with('success', 'Membership plan updated successfully.');
    }

    public function destroyPlan(MembershipPlan $plan)
    {
        if ($plan->activeSubscriptions()->count() > 0) {
            return back()->with('error', 'Cannot delete plan with active subscribers.');
        }

        $plan->delete();

        return redirect()->route('admin.membership.plans')
            ->with('success', 'Membership plan deleted successfully.');
    }

    // Subscribers Management
    public function subscribers(Request $request)
    {
        $query = UserSubscription::with(['user', 'plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan')) {
            $query->where('membership_plan_id', $request->plan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $subscriptions = $query->latest()->paginate(20);
        $plans = MembershipPlan::all();

        return view('admin.membership.subscribers.index', compact('subscriptions', 'plans'));
    }

    public function showSubscriber(UserSubscription $subscription)
    {
        $subscription->load(['user', 'plan', 'payments']);

        return view('admin.membership.subscribers.show', compact('subscription'));
    }

    // Early Access Sales
    public function sales()
    {
        $sales = EarlyAccessSale::latest()->paginate(10);

        return view('admin.membership.sales.index', compact('sales'));
    }

    public function createSale()
    {
        return view('admin.membership.sales.form');
    }

    public function storeSale(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'member_access_at' => 'required|date',
            'public_access_at' => 'required|date|after:member_access_at',
            'ends_at' => 'nullable|date|after:public_access_at',
            'member_discount' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        EarlyAccessSale::create($validated);

        return redirect()->route('admin.membership.sales')
            ->with('success', 'Early access sale created successfully.');
    }

    public function editSale(EarlyAccessSale $sale)
    {
        return view('admin.membership.sales.form', compact('sale'));
    }

    public function updateSale(Request $request, EarlyAccessSale $sale)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'member_access_at' => 'required|date',
            'public_access_at' => 'required|date|after:member_access_at',
            'ends_at' => 'nullable|date|after:public_access_at',
            'member_discount' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $sale->update($validated);

        return redirect()->route('admin.membership.sales')
            ->with('success', 'Early access sale updated successfully.');
    }

    public function destroySale(EarlyAccessSale $sale)
    {
        $sale->delete();

        return redirect()->route('admin.membership.sales')
            ->with('success', 'Early access sale deleted successfully.');
    }
}
