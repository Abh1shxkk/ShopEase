@extends('layouts.shop')

@section('title', 'Profile Settings')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-8">
        <a href="/" class="hover:text-slate-900">Home</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-slate-900 font-medium">Profile Settings</span>
    </nav>

    <h1 class="text-3xl font-bold text-slate-900 mb-8">Profile Settings</h1>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-6">Personal Information</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-6">Change Password</h2>
            <p class="text-sm text-slate-500 mb-4">Leave blank if you don't want to change your password.</p>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                    <input type="password" name="password" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <button type="submit" class="w-full h-12 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors">
            Save Changes
        </button>
    </form>
</div>
@endsection
