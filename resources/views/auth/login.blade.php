<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'ShopEase') | Sign In</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/css/landing.css', 'resources/js/app.js', 'resources/js/landing.js'])
</head>
<body class="bg-white text-slate-900 overflow-x-hidden" style="font-family: 'Inter', sans-serif;">
    {{-- Film Grain Overlay --}}
    <div class="film-grain"></div>
    
    {{-- Navbar --}}
    @include('partials.landing.navbar')

    <div x-data="{ showPassword: false, loading: false, showNotification: {{ session('success') ? 'true' : 'false' }} }" 
         x-init="if(showNotification) { setTimeout(() => showNotification = false, 5000) }"
         class="min-h-screen flex flex-col md:flex-row bg-white pt-20">
        {{-- Left Side: Cinematic Heritage Imagery --}}
        <div class="relative w-full md:w-[55%] lg:w-[60%] h-[40vh] md:h-auto md:min-h-screen overflow-hidden">
            <img 
                src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=1200" 
                alt="Luxury Shopping" 
                class="w-full h-full object-cover grayscale brightness-[0.4]"
            />
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-12">
                <div class="max-w-md">
                    <h2 class="text-3xl md:text-5xl lg:text-6xl font-serif text-white mb-6 tracking-tight leading-tight">
                        Welcome Back to <span class="italic">Shop<span class="text-blue-400">/</span>Ease</span>
                    </h2>
                    <div class="w-12 h-[1px] bg-white/30 mx-auto mb-6"></div>
                    <p class="text-slate-300 font-light tracking-[0.2em] uppercase text-[10px] md:text-xs">
                        Your premium shopping experience awaits
                    </p>
                </div>
            </div>
        </div>

        {{-- Right Side: Refined Minimalist Form --}}
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
                    <h1 class="text-2xl font-serif text-slate-900 mb-2">Sign in</h1>
                    <p class="text-slate-400 text-[11px] tracking-wide">Enter your credentials to access your account</p>
                </div>

                {{-- Success Message --}}
                <div x-show="showNotification"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mb-6 p-4 bg-emerald-50 border border-emerald-200">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-[12px] text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" @submit="loading = true" class="space-y-5">
                    @csrf
                    
                    {{-- Email Field --}}
                    <div class="space-y-1">
                        <label for="email" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 block">Email Address</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="name@example.com"
                            autofocus
                            class="w-full px-0 py-2.5 bg-transparent border-b border-slate-200 focus:border-slate-900 focus:outline-none transition-all text-[12px] text-slate-900 placeholder:text-slate-300 rounded-none @error('email') border-red-500 @enderror"
                        />
                        @error('email')
                            <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Field --}}
                    <div class="space-y-1">
                        <label for="password" class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500 block">Password</label>
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
                        @error('password')
                            <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <div class="relative flex items-center justify-center w-3.5 h-3.5 border border-slate-200 group-hover:border-slate-900 transition-colors">
                                <input type="checkbox" name="remember" class="absolute opacity-0 w-full h-full cursor-pointer peer" />
                                <div class="w-1.5 h-1.5 bg-slate-900 scale-0 peer-checked:scale-100 transition-transform"></div>
                            </div>
                            <span class="text-[10px] font-medium text-slate-500 group-hover:text-slate-900 transition-colors uppercase tracking-wider">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-[10px] font-medium text-slate-400 hover:text-slate-900 transition-colors uppercase tracking-wider">
                            Forgot?
                        </a>
                    </div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit" 
                        class="w-full bg-slate-900 text-white py-3 text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-all rounded-none mt-3 flex items-center justify-center"
                        :disabled="loading"
                    >
                        <svg x-show="loading" x-cloak class="animate-spin -ml-1 mr-2 h-3 w-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Signing in...' : 'Sign In'">Sign In</span>
                    </button>

                    {{-- Divider --}}
                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-100"></div>
                        </div>
                        <div class="relative flex justify-center text-[9px] uppercase font-bold tracking-[0.2em]">
                            <span class="bg-slate-50 px-3 text-slate-300">Or continue with</span>
                        </div>
                    </div>

                    {{-- Social Login Buttons --}}
                    <div class="space-y-3">
                        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 py-3 border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all group">
                            <svg class="w-4 h-4" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-600 group-hover:text-slate-900">Continue with Google</span>
                        </a>
                        
                        {{-- Facebook login - Coming Soon --}}
                        <div class="w-full flex items-center justify-center gap-3 py-3 bg-[#1877F2]/50 cursor-not-allowed opacity-60">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <span class="text-[10px] font-bold tracking-[0.15em] uppercase text-white">Facebook - Coming Soon</span>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="relative py-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-100"></div>
                        </div>
                        <div class="relative flex justify-center text-[9px] uppercase font-bold tracking-[0.2em]">
                            <span class="bg-slate-50 px-3 text-slate-300">New to ShopEase?</span>
                        </div>
                    </div>

                    {{-- Create Account Button --}}
                    <a 
                        href="{{ route('register') }}"
                        class="block w-full text-center border border-slate-200 py-3 text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all rounded-none"
                    >
                        Create Account
                    </a>
                </form>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('partials.landing.footer')

    <style>[x-cloak] { display: none !important; }</style>
    @stack('scripts')
</body>
</html>
