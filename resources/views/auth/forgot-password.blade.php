<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>ShopEase | Forgot Password</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js', 'resources/js/landing.js'])
</head>
<body class="bg-white text-slate-900 overflow-x-hidden" style="font-family: 'Inter', sans-serif;">
    <div class="film-grain"></div>
    
    @include('partials.landing.navbar')

    <div x-data="forgotPassword()" class="min-h-screen flex flex-col md:flex-row bg-white pt-20">
        {{-- Left Side --}}
        <div class="relative w-full md:w-[55%] lg:w-[60%] h-[40vh] md:h-auto md:min-h-screen overflow-hidden">
            <img 
                src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&q=80&w=1200" 
                alt="Security" 
                class="w-full h-full object-cover grayscale brightness-[0.4]"
            />
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-12">
                <div class="max-w-md">
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-serif text-white mb-6 tracking-tight leading-tight">
                        Forgot Your <span class="italic">Password?</span>
                    </h2>
                    <div class="w-12 h-[1px] bg-white/30 mx-auto mb-6"></div>
                    <p class="text-slate-300 font-light tracking-[0.2em] uppercase text-[10px] md:text-xs">
                        Don't worry, we'll help you reset it
                    </p>
                </div>
            </div>
        </div>

        {{-- Right Side --}}
        <div class="w-full md:w-[45%] lg:w-[40%] flex items-center justify-center p-6 md:p-12 lg:p-16 bg-slate-50/30">
            <div class="w-full max-w-[320px]">
                {{-- Logo & Header --}}
                <div class="text-center mb-10">
                    <a href="{{ route('home') }}" class="flex items-center justify-center gap-3 mb-8">
                        <div class="bg-slate-900 p-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-serif tracking-[0.15em] uppercase text-slate-900">ShopEase</span>
                    </a>
                    <h1 class="text-2xl font-serif text-slate-900 mb-2" x-text="step === 1 ? 'Reset Password' : 'Verify OTP'"></h1>
                    <p class="text-slate-400 text-[11px] tracking-wide" x-text="step === 1 ? 'Enter your email to receive OTP' : 'Enter the 6-digit code sent to your email'"></p>
                </div>

                {{-- Toast Notification --}}
                <div x-show="toast.show" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     :class="toast.type === 'success' ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200'"
                     class="mb-6 p-4 border"
                     style="display: none;">
                    <div class="flex items-center gap-3">
                        <svg x-show="toast.type === 'success'" class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <svg x-show="toast.type === 'error'" class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-[12px]" :class="toast.type === 'success' ? 'text-emerald-700' : 'text-red-700'" x-text="toast.message"></p>
                    </div>
                </div>

                {{-- Step 1: Email Form --}}
                <form x-show="step === 1" @submit.prevent="sendOtp" class="space-y-5">
                    <div class="space-y-1">
                        <label for="email" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 block">Email Address</label>
                        <input 
                            type="email" 
                            id="email"
                            x-model="email"
                            placeholder="name@example.com"
                            required
                            class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 focus:border-slate-900 focus:outline-none transition-all text-[12px] text-slate-900 placeholder:text-slate-300 rounded-none"
                        />
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-slate-900 text-white py-3 text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-all rounded-none mt-6 flex items-center justify-center"
                        :disabled="loading"
                    >
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-3 w-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Sending OTP...' : 'Send OTP'"></span>
                    </button>
                </form>

                {{-- Step 2: OTP Form --}}
                <form x-show="step === 2" x-cloak @submit.prevent="verifyOtp" class="space-y-5">
                    {{-- Email Display --}}
                    <div class="p-3 bg-slate-100 flex items-center justify-between">
                        <span class="text-[12px] text-slate-600" x-text="email"></span>
                        <button type="button" @click="step = 1" class="text-[10px] text-slate-500 hover:text-slate-900 uppercase tracking-wider">Change</button>
                    </div>

                    {{-- OTP Input --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 block">Enter OTP</label>
                        <div class="flex gap-2 justify-between">
                            <template x-for="(digit, index) in otpDigits" :key="index">
                                <input 
                                    type="text" 
                                    maxlength="1"
                                    x-model="otpDigits[index]"
                                    @input="handleOtpInput($event, index)"
                                    @keydown.backspace="handleOtpBackspace($event, index)"
                                    @paste="handleOtpPaste($event)"
                                    :id="'otp-' + index"
                                    class="w-12 h-14 text-center text-xl font-bold border border-slate-200 focus:border-slate-900 focus:outline-none transition-all rounded-none"
                                />
                            </template>
                        </div>
                    </div>

                    {{-- Timer & Resend --}}
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-400" x-show="timer > 0">Resend in <span x-text="timer"></span>s</span>
                        <button type="button" 
                                x-show="timer === 0" 
                                @click="resendOtp"
                                :disabled="loading"
                                class="text-slate-900 font-medium hover:underline">
                            Resend OTP
                        </button>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-slate-900 text-white py-3 text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-all rounded-none flex items-center justify-center"
                        :disabled="loading || otpDigits.join('').length !== 6"
                    >
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-3 w-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Verifying...' : 'Verify OTP'"></span>
                    </button>
                </form>

                {{-- Back to Login --}}
                <div class="mt-6">
                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-100"></div>
                        </div>
                        <div class="relative flex justify-center text-[9px] uppercase font-bold tracking-[0.2em]">
                            <span class="bg-slate-50 px-3 text-slate-300">Remember your password?</span>
                        </div>
                    </div>

                    <a 
                        href="{{ route('login') }}"
                        class="block w-full text-center border border-slate-200 py-3 text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all rounded-none"
                    >
                        Back to Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.landing.footer')
    
    <style>[x-cloak] { display: none !important; }</style>
    
    <script>
    function forgotPassword() {
        return {
            step: 1,
            email: '',
            otpDigits: ['', '', '', '', '', ''],
            loading: false,
            timer: 0,
            timerInterval: null,
            toast: { show: false, message: '', type: 'success' },

            showToast(message, type = 'success') {
                this.toast = { show: true, message, type };
                setTimeout(() => this.toast.show = false, 5000);
            },

            startTimer() {
                this.timer = 60;
                if (this.timerInterval) clearInterval(this.timerInterval);
                this.timerInterval = setInterval(() => {
                    this.timer--;
                    if (this.timer <= 0) clearInterval(this.timerInterval);
                }, 1000);
            },

            async sendOtp() {
                if (!this.email) return;
                this.loading = true;

                try {
                    const response = await fetch('{{ route("password.sendOtp") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email: this.email })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.step = 2;
                        this.startTimer();
                        this.showToast(data.message, 'success');
                    } else {
                        this.showToast(data.message, 'error');
                    }
                } catch (error) {
                    this.showToast('Something went wrong. Please try again.', 'error');
                } finally {
                    this.loading = false;
                }
            },

            async verifyOtp() {
                const otp = this.otpDigits.join('');
                if (otp.length !== 6) return;
                this.loading = true;

                try {
                    const response = await fetch('{{ route("password.verifyOtp") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ email: this.email, otp: otp })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.showToast(data.message, 'success');
                        // Redirect to login after 2 seconds
                        setTimeout(() => {
                            window.location.href = '{{ route("login") }}';
                        }, 2000);
                    } else {
                        this.showToast(data.message, 'error');
                        this.otpDigits = ['', '', '', '', '', ''];
                        document.getElementById('otp-0').focus();
                    }
                } catch (error) {
                    this.showToast('Something went wrong. Please try again.', 'error');
                } finally {
                    this.loading = false;
                }
            },

            async resendOtp() {
                this.otpDigits = ['', '', '', '', '', ''];
                await this.sendOtp();
            },

            handleOtpInput(event, index) {
                const value = event.target.value;
                if (!/^\d*$/.test(value)) {
                    this.otpDigits[index] = '';
                    return;
                }
                if (value && index < 5) {
                    document.getElementById('otp-' + (index + 1)).focus();
                }
            },

            handleOtpBackspace(event, index) {
                if (!this.otpDigits[index] && index > 0) {
                    document.getElementById('otp-' + (index - 1)).focus();
                }
            },

            handleOtpPaste(event) {
                event.preventDefault();
                const paste = (event.clipboardData || window.clipboardData).getData('text');
                const digits = paste.replace(/\D/g, '').slice(0, 6).split('');
                digits.forEach((digit, i) => {
                    this.otpDigits[i] = digit;
                });
                if (digits.length > 0) {
                    const focusIndex = Math.min(digits.length, 5);
                    document.getElementById('otp-' + focusIndex).focus();
                }
            }
        }
    }
    </script>
</body>
</html>
