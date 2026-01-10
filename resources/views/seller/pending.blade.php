@extends('layouts.shop')

@section('title', 'Application Pending')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-white">
    {{-- Hero Section --}}
    <div class="relative bg-slate-900 text-white py-16 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="max-w-6xl mx-auto px-6 text-center relative">
            <div class="inline-flex items-center gap-2 bg-amber-500/20 text-amber-300 px-4 py-2 text-xs font-bold tracking-wider uppercase mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Application Status
            </div>
            <h1 class="text-4xl md:text-5xl font-serif mb-4">Under Review</h1>
            <p class="text-slate-300 max-w-2xl mx-auto">Your seller application is being reviewed by our team.</p>
        </div>
    </div>

    {{-- Status Card --}}
    <div class="max-w-xl mx-auto px-6 -mt-8 relative z-10">
        <div class="bg-white shadow-lg border border-slate-200 p-8 text-center">
            {{-- Icon --}}
            <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h2 class="text-2xl font-serif text-slate-900 mb-3">Application Under Review</h2>
            <p class="text-slate-600 mb-6">
                Thank you for applying to become a seller on ShopEase! Our team is reviewing your application.
            </p>

            <div class="bg-amber-50 border border-amber-200 p-4 mb-6 text-left">
                <p class="text-sm text-amber-800">
                    <span class="font-semibold">What happens next?</span><br>
                    We typically review applications within 24-48 hours. You'll receive an email once your application is approved.
                </p>
            </div>

            @if(isset($seller) && $seller->created_at)
            <p class="text-sm text-slate-500 mb-6">
                Applied on: {{ $seller->created_at->format('F d, Y \a\t h:i A') }}
            </p>
            @endif

            <div class="space-y-3">
                <a href="{{ route('home') }}" class="block w-full px-6 py-3 bg-slate-900 text-white font-medium hover:bg-slate-800 transition">
                    Continue Shopping
                </a>
                <a href="{{ route('profile') }}" class="block w-full px-6 py-3 border border-slate-300 text-slate-700 hover:bg-slate-50 transition">
                    Go to Profile
                </a>
            </div>
        </div>

        <p class="text-center mt-8 text-sm text-slate-500">
            Have questions? <a href="{{ route('support.index') }}" class="text-slate-900 underline hover:no-underline">Contact Support</a>
        </p>
    </div>

    {{-- Timeline Section --}}
    <div class="max-w-xl mx-auto px-6 py-16">
        <h3 class="text-lg font-serif text-slate-900 mb-6 text-center">Application Process</h3>
        <div class="space-y-4">
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-900">Application Submitted</p>
                    <p class="text-sm text-slate-500">Your application has been received</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 bg-amber-500 text-white rounded-full flex items-center justify-center flex-shrink-0 animate-pulse">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-900">Under Review</p>
                    <p class="text-sm text-slate-500">Our team is reviewing your details</p>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-slate-400">Approval</p>
                    <p class="text-sm text-slate-400">Start selling on ShopEase</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
