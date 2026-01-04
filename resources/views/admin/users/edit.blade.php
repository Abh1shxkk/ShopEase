@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.users.index') }}" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-slate-900 flex items-center gap-2 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Users
    </a>
</div>

<div class="max-w-2xl">
    <h1 class="text-2xl font-serif tracking-wide text-slate-900 mb-8">Edit User</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-slate-50 p-8">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <div>
                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900 @error('name') border-red-500 @enderror">
                @error('name')
                <p class="text-red-500 text-[11px] mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900 @error('email') border-red-500 @enderror">
                @error('email')
                <p class="text-red-500 text-[11px] mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Role</label>
                <select name="role" required class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900 {{ $user->id === auth()->id() ? 'opacity-50' : '' }}" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @if($user->id === auth()->id())
                <input type="hidden" name="role" value="{{ $user->role }}">
                <p class="text-[11px] text-slate-400 mt-2">You cannot change your own role</p>
                @endif
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">New Password (leave blank to keep current)</label>
                <input type="password" name="password" class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900 @error('password') border-red-500 @enderror">
                @error('password')
                <p class="text-red-500 text-[11px] mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
            </div>
        </div>

        <div class="flex gap-4 mt-8">
            <button type="submit" class="h-12 px-8 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">
                Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="h-12 px-8 bg-white text-slate-700 text-[11px] font-bold tracking-[0.15em] uppercase border border-slate-200 hover:bg-slate-100 transition-colors flex items-center">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
