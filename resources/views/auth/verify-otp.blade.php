@extends('layouts.shop')

@section('title', 'Verify OTP')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-3xl font-serif tracking-[0.1em] text-slate-900">
                Shop<span class="mx-1 italic text-blue-600">/</span>Ease
            </a>
        </div>

        {{-- OTP Card --}}
        <div class="bg-white border border-slate-200 p-8">
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-semibold text-slate-900 mb-2">Verify Your Email</h1>
                <p class="text-sm text-slate-500">
                    We've sent a 6-digit OTP to<br>
                    <span class="font-medium text-slate-700">{{ $email }}</span>
                </p>
            </div>

            @if(session('success'))
            <div class="mb-6 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded">
                {{ session('success') }}
            </div>
            @endif

            {{-- Dev Mode: Show OTP for testing --}}
            @if(app()->environment('local', 'development') && session('dev_otp'))
            <div class="mb-6 p-3 bg-amber-50 border border-amber-300 rounded">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[10px] font-bold tracking-widest uppercase text-amber-700">Dev Mode</span>
                </div>
                <p class="text-sm text-amber-800">Your OTP: <span class="font-mono font-bold text-lg tracking-widest">{{ session('dev_otp') }}</span></p>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login.otp.verify') }}" method="POST" id="otp-form">
                @csrf
                
                {{-- OTP Input --}}
                <div class="mb-6">
                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Enter OTP</label>
                    <div class="flex gap-2 justify-center" id="otp-inputs">
                        @for($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" 
                               class="w-12 h-14 text-center text-xl font-semibold border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 otp-input"
                               data-index="{{ $i }}"
                               inputmode="numeric"
                               pattern="[0-9]">
                        @endfor
                    </div>
                    <input type="hidden" name="otp" id="otp-hidden">
                </div>

                {{-- Submit Button --}}
                <button type="submit" id="verify-btn" disabled
                        class="w-full h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Verify & Login
                </button>
            </form>

            {{-- Resend OTP --}}
            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500 mb-2">Didn't receive the code?</p>
                <button type="button" id="resend-btn" class="text-sm font-medium text-blue-600 hover:text-blue-800 disabled:opacity-50 disabled:cursor-not-allowed">
                    Resend OTP <span id="countdown"></span>
                </button>
            </div>

            {{-- Back to Login --}}
            <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-slate-700">
                    ← Back to Login
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otp-hidden');
    const verifyBtn = document.getElementById('verify-btn');
    const resendBtn = document.getElementById('resend-btn');
    const countdown = document.getElementById('countdown');
    
    // Focus first input
    inputs[0].focus();
    
    // Handle input
    inputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = value;
            
            if (value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            
            updateHiddenInput();
        });
        
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
        
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const digits = paste.replace(/[^0-9]/g, '').slice(0, 6);
            
            digits.split('').forEach((digit, i) => {
                if (inputs[i]) {
                    inputs[i].value = digit;
                }
            });
            
            if (digits.length > 0) {
                inputs[Math.min(digits.length, 5)].focus();
            }
            
            updateHiddenInput();
        });
    });
    
    function updateHiddenInput() {
        let otp = '';
        inputs.forEach(input => otp += input.value);
        hiddenInput.value = otp;
        verifyBtn.disabled = otp.length !== 6;
    }
    
    // Resend OTP with countdown
    let resendTimer = 30;
    resendBtn.disabled = true;
    
    function updateCountdown() {
        if (resendTimer > 0) {
            countdown.textContent = `(${resendTimer}s)`;
            resendTimer--;
            setTimeout(updateCountdown, 1000);
        } else {
            countdown.textContent = '';
            resendBtn.disabled = false;
        }
    }
    updateCountdown();
    
    resendBtn.addEventListener('click', async function() {
        if (resendBtn.disabled) return;
        
        resendBtn.disabled = true;
        resendBtn.textContent = 'Sending...';
        
        try {
            const response = await fetch('{{ route("login.otp.resend") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                resendTimer = 30;
                resendBtn.innerHTML = 'Resend OTP <span id="countdown"></span>';
                countdown = document.getElementById('countdown');
                updateCountdown();
                
                // Clear inputs
                inputs.forEach(input => input.value = '');
                inputs[0].focus();
                updateHiddenInput();
                
                // Update dev OTP display if present
                if (data.dev_otp) {
                    const devOtpSpan = document.querySelector('.font-mono.font-bold.text-lg');
                    if (devOtpSpan) {
                        devOtpSpan.textContent = data.dev_otp;
                    }
                }
            } else {
                alert(data.error || 'Failed to resend OTP');
                resendBtn.disabled = false;
            }
        } catch (err) {
            alert('Failed to resend OTP');
            resendBtn.disabled = false;
        }
        
        resendBtn.innerHTML = 'Resend OTP <span id="countdown"></span>';
    });
});
</script>
@endsection
