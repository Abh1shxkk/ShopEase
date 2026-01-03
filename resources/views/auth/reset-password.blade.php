<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>ShopEase | Reset Password</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js', 'resources/js/landing.js'])
</head>
<body class="bg-white text-slate-900 overflow-x-hidden" style="font-family: 'Inter', sans-serif;">
    <div class="film-grain"></div>
    
    @include('partials.landing.navbar')

    <div x-data="{ showPassword: false, showConfirmPassword: false, loading: false }" class="min-h-screen flex flex-col md:flex-row bg-white pt-20">
        {{-- Left Side --}}
        <div class="relative w-full md:w-[55%] lg:w-[60%] h-[40vh] md:h-auto md:min-h-screen overflow-hidden">
            <img 
                src="https://images.unsplash.com/photo-1633265486064-086b219458ec?auto=format&fit=crop&q=80&w=1200" 
                alt="Security" 
                class="w-full h-full object-cover grayscale brightness-[0.4]"
            />
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-12">
                <div class="max-w-md">
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-serif text-white mb-6 tracking-tight leading-tight">
                        Create New <span class="italic">Password</span>
                    </h2>
                    <div class="w-12 h-[1px] bg-white/30 mx-auto mb-6"></div>
                    <p class="text-slate-300 font-light tracking-[0.2em] uppercase text-[10px] md:text-xs">
                        Choose a strong password to secure your account
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
                    <h1 class="text-2xl font-serif text-slate-900 mb-2">New Password</h1>
                    <p class="text-slate-400 text-[11px] tracking-wide">Enter your new password below</p>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('password.update') }}" @submit="loading = true" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    {{-- Email Display --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 block">Email Address</label>
                        <p class="py-2.5 text-[12px] text-slate-600">{{ $email }}</p>
                    </div>

                    {{-- Password Field --}}
                    <div class="space-y-1">
                        <label for="password" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 block">New Password</label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 focus:border-slate-900 focus:outline-none transition-all text-[12px] text-slate-900 placeholder:text-slate-300 rounded-none @error('password') border-red-500 @enderror"
                            />
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-0 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-900 transition-colors">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Minimum 8 characters</p>
                        @error('password')
                            <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password Field --}}
                    <div class="space-y-1">
                        <label for="password_confirmation" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 block">Confirm Password</label>
                        <div class="relative">
                            <input 
                                :type="showConfirmPassword ? 'text' : 'password'" 
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="••••••••"
                                class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 focus:border-slate-900 focus:outline-none transition-all text-[12px] text-slate-900 placeholder:text-slate-300 rounded-none"
                            />
                            <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-0 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-900 transition-colors">
                                <svg x-show="!showConfirmPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showConfirmPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @error('email')
                        <p class="text-[10px] text-red-500 p-3 bg-red-50 border border-red-200">{{ $message }}</p>
                    @enderror

                    <button 
                        type="submit" 
                        class="w-full bg-slate-900 text-white py-3 text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-all rounded-none mt-6 flex items-center justify-center"
                        :disabled="loading"
                    >
                        <svg x-show="loading" x-cloak class="animate-spin -ml-1 mr-2 h-3 w-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Resetting...' : 'Reset Password'">Reset Password</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @include('partials.landing.footer')
    <style>[x-cloak] { display: none !important; }</style>
</body>
</html>
