@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-serif tracking-wide text-slate-900">My Profile</h1>
    <p class="text-[12px] text-slate-500 mt-1">Manage your account settings</p>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Profile Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Personal Information -->
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Personal Information</h2>
            </div>
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6" id="profile-form">
                @csrf

                <div class="flex items-start gap-6 mb-6" x-data="{ avatarPreview: '{{ auth()->user()->avatar ? (str_starts_with(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar)) : '' }}' }">
                    <div class="w-24 h-24 bg-slate-100 flex items-center justify-center text-slate-600 text-3xl font-serif overflow-hidden flex-shrink-0">
                        <template x-if="avatarPreview">
                            <img :src="avatarPreview" alt="Avatar" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!avatarPreview">
                            <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </template>
                    </div>
                    <div class="flex-1 space-y-3">
                        <div>
                            <label class="text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 block mb-2">Avatar URL</label>
                            <input type="text" name="avatar_url" placeholder="https://example.com/avatar.jpg" class="w-full h-10 px-4 bg-slate-50 border border-slate-200 text-[12px] text-slate-900 placeholder-slate-400 focus:outline-none focus:border-slate-900 transition-colors" @input="avatarPreview = $event.target.value">
                        </div>
                        <p class="text-[10px] text-slate-400">— OR Upload File —</p>
                        <input type="file" name="avatar" accept="image/*" class="text-[12px] text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:border-0 file:text-[10px] file:font-bold file:tracking-[0.1em] file:uppercase file:bg-slate-900 file:text-white hover:file:bg-slate-800 file:cursor-pointer" @change="avatarPreview = URL.createObjectURL($event.target.files[0])">
                        <p class="text-[10px] text-slate-400">JPG, PNG or WebP, max 2MB</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 @error('name') border-red-500 @enderror">
                        @error('name')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900 @error('email') border-red-500 @enderror">
                        @error('email')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Phone Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="+91 9876543210" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                </div>

                <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500">Change Password</h2>
            </div>
            <form action="{{ route('admin.profile.password') }}" method="POST" class="p-6" id="password-form">
                @csrf

                <div class="space-y-5 mb-6">
                    <div>
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Current Password</label>
                        <input type="password" name="current_password" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">New Password</label>
                        <input type="password" name="password" required minlength="8" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                        <p class="text-[10px] text-slate-400 mt-1">Minimum 8 characters</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold tracking-[0.15em] uppercase text-slate-400 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required class="w-full h-11 px-4 bg-slate-50 border border-slate-200 text-[13px] focus:outline-none focus:border-slate-900">
                    </div>
                </div>

                <button type="submit" class="h-11 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                    Update Password
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Account Info -->
        <div class="bg-white border border-slate-200 p-6">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-4">Account Info</h2>
            <div class="space-y-4 text-[12px]">
                <div class="flex justify-between">
                    <span class="text-slate-500">Role</span>
                    <span class="px-2 py-0.5 bg-slate-900 text-white text-[10px] tracking-widest uppercase">{{ auth()->user()->role }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Member Since</span>
                    <span class="text-slate-900">{{ auth()->user()->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white border border-slate-200 p-6">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-slate-500 mb-4">Quick Stats</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-[12px] text-slate-500">Total Products</span>
                    <span class="text-[14px] font-semibold text-slate-900">{{ \App\Models\Product::count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[12px] text-slate-500">Total Orders</span>
                    <span class="text-[14px] font-semibold text-slate-900">{{ \App\Models\Order::count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[12px] text-slate-500">Total Users</span>
                    <span class="text-[14px] font-semibold text-slate-900">{{ \App\Models\User::count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[12px] text-slate-500">Pending Orders</span>
                    <span class="text-[14px] font-semibold text-amber-600">{{ \App\Models\Order::where('status', 'pending')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-red-50 border border-red-200 p-6">
            <h2 class="text-[11px] font-bold tracking-[0.15em] uppercase text-red-600 mb-4">Danger Zone</h2>
            <p class="text-[12px] text-red-600 mb-4">Once you logout, you'll need to login again.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full h-10 bg-red-600 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-red-700 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
