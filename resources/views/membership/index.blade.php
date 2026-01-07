@extends('layouts.shop')

@section('title', 'Premium Membership')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-white">
    {{-- Hero Section --}}
    <div class="relative bg-slate-900 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="max-w-6xl mx-auto px-6 text-center relative">
            <div class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-300 px-4 py-2 text-xs font-bold tracking-wider uppercase mb-6">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Premium Membership
            </div>
            <h1 class="text-4xl md:text-5xl font-serif mb-6">Unlock Exclusive Benefits</h1>
            <p class="text-slate-300 max-w-2xl mx-auto text-lg">Join our premium membership and enjoy free shipping, early access to sales, exclusive discounts, and priority support.</p>
        </div>
    </div>

    {{-- Benefits Overview --}}
    <div class="max-w-6xl mx-auto px-6 -mt-10 relative z-10">
        <div class="grid md:grid-cols-4 gap-4">
            <div class="bg-white p-6 shadow-lg border border-slate-100 text-center">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <h3 class="font-semibold mb-1">Free Shipping</h3>
                <p class="text-sm text-slate-500">On all orders, always</p>
            </div>
            <div class="bg-white p-6 shadow-lg border border-slate-100 text-center">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold mb-1">Early Access</h3>
                <p class="text-sm text-slate-500">Shop sales before anyone</p>
            </div>
            <div class="bg-white p-6 shadow-lg border border-slate-100 text-center">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <h3 class="font-semibold mb-1">Member Discounts</h3>
                <p class="text-sm text-slate-500">Extra savings on every order</p>
            </div>
            <div class="bg-white p-6 shadow-lg border border-slate-100 text-center">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold mb-1">Priority Support</h3>
                <p class="text-sm text-slate-500">Get help faster</p>
            </div>
        </div>
    </div>

    {{-- Current Membership Status --}}
    @auth
        @if($currentSubscription)
        <div class="max-w-6xl mx-auto px-6 mt-12">
            <div class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-500 text-white rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-amber-700 font-medium">You're a {{ $currentSubscription->plan->name }} Member!</p>
                        <p class="text-xs text-amber-600">Your membership expires on {{ $currentSubscription->ends_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('membership.manage') }}" class="px-6 py-2 bg-amber-600 text-white text-sm font-medium hover:bg-amber-700 transition-colors">
                    Manage Membership
                </a>
            </div>
        </div>
        @endif
    @endauth

    {{-- Pricing Plans --}}
    <div class="max-w-6xl mx-auto px-6 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-serif mb-4">Choose Your Plan</h2>
            <p class="text-slate-600">Select the membership that fits your shopping style</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($plans as $plan)
            <div class="relative bg-white border {{ $plan->is_popular ? 'border-amber-400 shadow-xl' : 'border-slate-200 shadow-lg' }} overflow-hidden">
                @if($plan->is_popular)
                <div class="absolute top-0 right-0 bg-amber-500 text-white text-xs font-bold px-4 py-1 uppercase tracking-wider">
                    Most Popular
                </div>
                @endif
                
                <div class="p-8">
                    <h3 class="text-xl font-serif mb-2">{{ $plan->name }}</h3>
                    <p class="text-slate-500 text-sm mb-6">{{ $plan->description }}</p>
                    
                    <div class="mb-6">
                        <span class="text-4xl font-bold">₹{{ number_format($plan->price) }}</span>
                        <span class="text-slate-500">/{{ $plan->billing_cycle }}</span>
                        @if($plan->billing_cycle !== 'monthly')
                        <p class="text-sm text-emerald-600 mt-1">₹{{ number_format($plan->monthly_price) }}/month</p>
                        @endif
                    </div>

                    <ul class="space-y-3 mb-8">
                        @if($plan->free_shipping)
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Free shipping on all orders</span>
                        </li>
                        @endif
                        @if($plan->discount_percentage > 0)
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ $plan->discount_percentage }}% off on all purchases</span>
                        </li>
                        @endif
                        @if($plan->early_access_days > 0)
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ $plan->early_access_days }}-day early access to sales</span>
                        </li>
                        @endif
                        @if($plan->priority_support)
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Priority customer support</span>
                        </li>
                        @endif
                        @if($plan->exclusive_products)
                        <li class="flex items-center gap-3 text-sm">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Access to exclusive products</span>
                        </li>
                        @endif
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                            <li class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>{{ $feature }}</span>
                            </li>
                            @endforeach
                        @endif
                    </ul>

                    @auth
                        @if($currentSubscription && $currentSubscription->membership_plan_id === $plan->id)
                        <button disabled class="w-full py-3 bg-slate-100 text-slate-500 font-medium cursor-not-allowed">
                            Current Plan
                        </button>
                        @else
                        <a href="{{ route('membership.subscribe', $plan) }}" 
                           class="block w-full py-3 text-center {{ $plan->is_popular ? 'bg-amber-500 hover:bg-amber-600' : 'bg-slate-900 hover:bg-slate-800' }} text-white font-medium transition-colors">
                            {{ $currentSubscription ? 'Switch Plan' : 'Get Started' }}
                        </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('membership.subscribe', $plan)) }}" 
                           class="block w-full py-3 text-center {{ $plan->is_popular ? 'bg-amber-500 hover:bg-amber-600' : 'bg-slate-900 hover:bg-slate-800' }} text-white font-medium transition-colors">
                            Login to Subscribe
                        </a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- FAQ Section --}}
    <div class="bg-slate-50 py-16">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-2xl font-serif text-center mb-12">Frequently Asked Questions</h2>
            
            <div class="space-y-4" x-data="{ open: null }">
                <div class="bg-white border border-slate-200">
                    <button @click="open = open === 1 ? null : 1" class="w-full flex items-center justify-between p-5 text-left">
                        <span class="font-medium">How does the membership work?</span>
                        <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': open === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 1" x-collapse class="px-5 pb-5 text-sm text-slate-600">
                        Once you subscribe, your benefits are activated immediately. You'll enjoy free shipping, member discounts, and early access to sales for the duration of your membership.
                    </div>
                </div>
                
                <div class="bg-white border border-slate-200">
                    <button @click="open = open === 2 ? null : 2" class="w-full flex items-center justify-between p-5 text-left">
                        <span class="font-medium">Can I cancel my membership?</span>
                        <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': open === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 2" x-collapse class="px-5 pb-5 text-sm text-slate-600">
                        Yes, you can cancel anytime. Your benefits will continue until the end of your current billing period. No refunds are provided for partial periods.
                    </div>
                </div>
                
                <div class="bg-white border border-slate-200">
                    <button @click="open = open === 3 ? null : 3" class="w-full flex items-center justify-between p-5 text-left">
                        <span class="font-medium">What is early access to sales?</span>
                        <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': open === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 3" x-collapse class="px-5 pb-5 text-sm text-slate-600">
                        Members get exclusive early access to our sales events before they're open to the public. This means you can shop the best deals before items sell out.
                    </div>
                </div>
                
                <div class="bg-white border border-slate-200">
                    <button @click="open = open === 4 ? null : 4" class="w-full flex items-center justify-between p-5 text-left">
                        <span class="font-medium">How do member discounts work?</span>
                        <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': open === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open === 4" x-collapse class="px-5 pb-5 text-sm text-slate-600">
                        Your member discount is automatically applied at checkout. It stacks with sale prices but not with coupon codes. The discount percentage depends on your membership tier.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
