@extends('layouts.app')

@section('title', 'Unsubscribed')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-2xl shadow-sm p-8">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Successfully Unsubscribed</h1>
            <p class="text-gray-600 mb-6">
                You have been unsubscribed from our newsletter. You will no longer receive promotional emails from us.
            </p>
            <p class="text-sm text-gray-500 mb-6">
                Email: <span class="font-medium">{{ $subscriber->email }}</span>
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 transition-colors">
                Return to Homepage
            </a>
        </div>
    </div>
</div>
@endsection
