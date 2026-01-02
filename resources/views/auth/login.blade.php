@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div x-data="{ showPassword: false, loading: false }" class="min-h-screen grid lg:grid-cols-2">
    <!-- Left Side - Image -->
    <div class="hidden lg:block relative">
        <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=2070&auto=format&fit=crop" 
             alt="Shopping" 
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 flex flex-col justify-center px-12">
            <h1 class="text-4xl xl:text-5xl font-bold text-white mb-4">Welcome Back to ShopEase</h1>
            <p class="text-lg text-white/80">Your premium shopping experience awaits</p>
        </div>
    </div>

    <!-- Right Side - Form -->
    <div class="flex items-center justify-center px-6 py-12 bg-white">
        <div class="w-full max-w-[400px]">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-slate-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-semibold text-slate-900">ShopEase</span>
                </a>
            </div>

            <!-- Card -->
            <div class="card p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-semibold text-slate-900">Sign in to your account</h2>
                </div>

                <form method="POST" action="{{ route('login') }}" @submit="loading = true" class="space-y-5">
                    @csrf
                    
                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="label">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="input w-full @error('email') input-error @enderror"
                            placeholder="name@example.com">
                        @error('email')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="label">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                class="input w-full pr-10 @error('password') input-error @enderror"
                                placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg x-show="!showPassword" class="w-5 h-5 text-slate-400 hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5 text-slate-400 hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="checkbox">
                            <span class="text-sm text-slate-600">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-slate-600 hover:text-slate-900 transition-colors">Forgot password?</a>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary w-full" :disabled="loading">
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Signing in...' : 'Sign In'"></span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="divider my-6">
                    <span class="text-sm text-muted">or</span>
                </div>

                <!-- Register Link -->
                <p class="text-center text-sm text-slate-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-slate-900 hover:underline">Sign up</a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>[x-cloak] { display: none !important; }</style>
@endsection
