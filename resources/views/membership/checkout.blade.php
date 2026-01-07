@extends('layouts.shop')

@section('title', 'Subscribe to ' . $plan->name)

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12" x-data="membershipCheckout()">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-12">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('membership.index') }}" class="hover:text-slate-900 transition-colors">Membership</a>
        <span>/</span>
        <span class="text-slate-900">Checkout</span>
    </nav>

    <div class="grid lg:grid-cols-2 gap-12">
        {{-- Plan Summary --}}
        <div>
            <h1 class="text-2xl font-serif mb-8">Complete Your Subscription</h1>
            
            <div class="bg-slate-50 p-6 mb-8">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-amber-500 text-white rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">{{ $plan->name }}</h3>
                        <p class="text-slate-600 text-sm mt-1">{{ $plan->description }}</p>
                    </div>
                </div>
            </div>

            <h3 class="font-medium mb-4">What you'll get:</h3>
            <ul class="space-y-3 mb-8">
                @if($plan->free_shipping)
                <li class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Free shipping on all orders</span>
                </li>
                @endif
                @if($plan->discount_percentage > 0)
                <li class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ $plan->discount_percentage }}% discount on all purchases</span>
                </li>
                @endif
                @if($plan->early_access_days > 0)
                <li class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ $plan->early_access_days }}-day early access to sales</span>
                </li>
                @endif
                @if($plan->priority_support)
                <li class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Priority customer support</span>
                </li>
                @endif
            </ul>

            @if($currentSubscription)
            <div class="bg-amber-50 border border-amber-200 p-4 text-sm">
                <p class="text-amber-800">
                    <strong>Note:</strong> Your current {{ $currentSubscription->plan->name }} subscription will be cancelled and replaced with this plan.
                </p>
            </div>
            @endif
        </div>

        {{-- Payment Section --}}
        <div>
            <div class="bg-white border border-slate-200 p-8">
                <h2 class="text-lg font-semibold mb-6">Payment Summary</h2>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-slate-600">{{ $plan->name }} ({{ ucfirst($plan->billing_cycle) }})</span>
                        <span class="font-medium">₹{{ number_format($plan->price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500">
                        <span>Duration</span>
                        <span>{{ $plan->duration_days }} days</span>
                    </div>
                    <div class="border-t border-slate-200 pt-4 flex justify-between">
                        <span class="font-semibold">Total</span>
                        <span class="text-xl font-bold">₹{{ number_format($plan->price, 2) }}</span>
                    </div>
                </div>

                {{-- Error Message --}}
                <div x-show="error" x-cloak class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-sm">
                    <span x-text="error"></span>
                </div>

                <button 
                    @click="processPayment()"
                    :disabled="loading"
                    class="w-full py-4 bg-slate-900 text-white font-medium hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                >
                    <svg x-show="loading" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="loading ? 'Processing...' : 'Pay ₹{{ number_format($plan->price, 2) }}'"></span>
                </button>

                <p class="text-xs text-slate-500 text-center mt-4">
                    By subscribing, you agree to our Terms of Service and Privacy Policy.
                </p>

                <div class="mt-6 pt-6 border-t border-slate-200 flex items-center justify-center gap-2 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span class="text-xs">Secure payment powered by Razorpay</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function membershipCheckout() {
    return {
        loading: false,
        error: null,

        async processPayment() {
            this.loading = true;
            this.error = null;

            try {
                const response = await fetch('{{ route("membership.process-payment", $plan) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ payment_method: 'razorpay' })
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || 'Failed to create payment order');
                }

                // Open Razorpay checkout
                const options = {
                    key: data.razorpay_key,
                    amount: data.amount,
                    currency: data.currency,
                    name: data.name,
                    description: data.description,
                    order_id: data.razorpay_order_id,
                    prefill: data.prefill,
                    theme: { color: '#0f172a' },
                    handler: async (response) => {
                        await this.verifyPayment(response);
                    },
                    modal: {
                        ondismiss: () => {
                            this.loading = false;
                        }
                    }
                };

                const rzp = new Razorpay(options);
                rzp.on('payment.failed', (response) => {
                    this.error = response.error.description || 'Payment failed';
                    this.loading = false;
                });
                rzp.open();

            } catch (err) {
                this.error = err.message;
                this.loading = false;
            }
        },

        async verifyPayment(paymentResponse) {
            try {
                const response = await fetch('{{ route("membership.verify-payment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        razorpay_payment_id: paymentResponse.razorpay_payment_id,
                        razorpay_order_id: paymentResponse.razorpay_order_id,
                        razorpay_signature: paymentResponse.razorpay_signature,
                    })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    throw new Error(data.error || 'Payment verification failed');
                }
            } catch (err) {
                this.error = err.message;
                this.loading = false;
            }
        }
    }
}
</script>
@endsection
