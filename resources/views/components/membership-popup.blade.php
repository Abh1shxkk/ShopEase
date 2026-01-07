@if(session('show_membership_popup') && auth()->check() && !auth()->user()->isMember())
@php
    $plans = \App\Models\MembershipPlan::active()->ordered()->take(3)->get();
@endphp

{{-- Overlay with film grain (no blur, no scroll) - Initially hidden --}}
<div id="membership-popup-overlay" class="fixed inset-0 z-[100] overflow-hidden" style="background: rgba(15, 23, 42, 0.85); display: none; opacity: 0;" onclick="dismissPopup()">
    <div class="film-grain"></div>
    
    {{-- Popup Container --}}
    <div class="fixed inset-0 flex items-center justify-center p-6 overflow-hidden">
        <div id="membership-popup" class="bg-white max-w-5xl w-full relative" style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);" onclick="event.stopPropagation()">
            
            {{-- Close Button --}}
            <button onclick="dismissPopup()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 transition-colors z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Content (No Scroll) --}}
            <div class="p-8">
                {{-- Header --}}
                <div class="text-center mb-6">
                    <div class="w-10 h-10 mx-auto mb-4 bg-slate-900 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-serif tracking-wide text-slate-900 mb-2">Exclusive Membership</h2>
                    <p class="text-[10px] tracking-[0.2em] uppercase text-slate-400">Choose Your Plan</p>
                </div>

                {{-- Plans Grid --}}
                <div class="grid grid-cols-3 gap-4 mb-6">
                    @foreach($plans as $plan)
                    <div class="border border-slate-200 p-4 hover:border-slate-900 transition-all group">
                        @if($plan->is_popular)
                        <div class="mb-2">
                            <span class="inline-block px-2 py-0.5 bg-slate-900 text-white text-[8px] tracking-widest uppercase">Popular</span>
                        </div>
                        @endif
                        
                        <h3 class="text-base font-serif text-slate-900 mb-2">{{ $plan->name }}</h3>
                        <div class="mb-3">
                            <span class="text-2xl font-serif text-slate-900">₹{{ number_format($plan->price, 0) }}</span>
                            <span class="text-xs text-slate-500">/{{ $plan->billing_cycle === 'monthly' ? 'mo' : ($plan->billing_cycle === 'yearly' ? 'yr' : 'qtr') }}</span>
                        </div>
                        
                        <ul class="space-y-1.5 mb-4 text-[11px] text-slate-600">
                            @if($plan->free_shipping)
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-slate-900 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Free Shipping
                            </li>
                            @endif
                            @if($plan->discount_percentage > 0)
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-slate-900 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $plan->discount_percentage }}% Discount
                            </li>
                            @endif
                            @if($plan->early_access_days > 0)
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-slate-900 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Early Access
                            </li>
                            @endif
                            @if($plan->priority_support)
                            <li class="flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-slate-900 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Priority Support
                            </li>
                            @endif
                        </ul>
                        
                        <a href="{{ route('membership.subscribe', $plan) }}" onclick="dismissPopup()"
                           class="block w-full h-9 bg-slate-900 text-white text-[9px] font-bold tracking-[0.2em] uppercase flex items-center justify-center hover:bg-slate-800 transition-colors group-hover:bg-slate-800">
                            Select Plan
                        </a>
                    </div>
                    @endforeach
                </div>

                {{-- Don't show again --}}
                <div class="flex items-center justify-center gap-6 pt-4 border-t border-slate-200">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" id="dont-show-again" class="w-3 h-3 border-slate-300 text-slate-900 focus:ring-slate-900 focus:ring-offset-0">
                        <span class="text-[10px] text-slate-400 group-hover:text-slate-600 transition-colors">Don't show this again</span>
                    </label>
                    <button onclick="dismissPopup()" class="text-[10px] text-slate-400 hover:text-slate-900 transition-colors tracking-wider uppercase">
                        Maybe Later
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Prevent body scroll when popup is open */
body.popup-open {
    overflow: hidden !important;
}

/* Popup animation */
@keyframes popupFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes popupSlideIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
#membership-popup-overlay {
    animation: popupFadeIn 0.3s ease-out;
}
#membership-popup {
    animation: popupSlideIn 0.4s ease-out;
}
</style>

<script>
// Show popup after 4-5 seconds with smooth fade in
setTimeout(() => {
    const overlay = document.getElementById('membership-popup-overlay');
    if (overlay) {
        // Prevent body scroll
        document.body.classList.add('popup-open');
        
        // Show and fade in
        overlay.style.display = 'block';
        setTimeout(() => {
            overlay.style.opacity = '1';
            overlay.style.transition = 'opacity 0.4s ease-out';
        }, 50);
    }
}, 4500); // 4.5 seconds delay

function dismissPopup() {
    const dontShowAgain = document.getElementById('dont-show-again').checked;
    const overlay = document.getElementById('membership-popup-overlay');
    
    // Fade out animation
    overlay.style.opacity = '0';
    overlay.style.transition = 'opacity 0.2s';
    
    setTimeout(() => {
        overlay.style.display = 'none';
        document.body.classList.remove('popup-open');
    }, 200);
    
    // Save preference
    if (dontShowAgain) {
        fetch('{{ route("membership.popup.hide") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        });
    } else {
        fetch('{{ route("membership.popup.dismiss") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        });
    }
}

// Close on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('membership-popup-overlay')) {
        dismissPopup();
    }
});
</script>
@endif
