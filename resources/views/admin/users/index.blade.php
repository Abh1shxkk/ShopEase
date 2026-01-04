@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-serif tracking-wide text-slate-900">Users</h1>
        <p class="text-[12px] text-slate-500 mt-1">Manage customer accounts</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase flex items-center gap-2 hover:bg-slate-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add User
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white border border-slate-200 p-4 text-center">
        <p class="text-xl font-serif text-slate-900">{{ $stats['total'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-slate-400 mt-1">Total Users</p>
    </div>
    <div class="bg-slate-900 p-4 text-center">
        <p class="text-xl font-serif text-white">{{ $stats['admins'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-slate-400 mt-1">Admins</p>
    </div>
    <div class="bg-white border border-slate-200 p-4 text-center">
        <p class="text-xl font-serif text-slate-900">{{ $stats['customers'] }}</p>
        <p class="text-[10px] tracking-widest uppercase text-slate-400 mt-1">Customers</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white border border-slate-200 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="flex-1 min-w-[200px] h-11 px-4 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
        <select name="role" class="h-11 px-4 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
        </select>
        <button type="submit" class="h-11 px-6 bg-slate-900 text-white text-[11px] font-bold tracking-[0.15em] uppercase hover:bg-slate-800 transition-colors">Search</button>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white border border-slate-200">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-200">
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">User</th>
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Role</th>
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Orders</th>
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Wishlist</th>
                <th class="text-left px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Joined</th>
                <th class="text-right px-6 py-4 text-[10px] font-bold tracking-[0.15em] uppercase text-slate-500">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($users as $user)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 flex items-center justify-center text-slate-600 font-medium text-[12px]">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[13px] font-medium text-slate-900">{{ $user->name }}</p>
                            <p class="text-[11px] text-slate-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="role" onchange="this.form.submit()" class="h-8 px-2 text-[11px] border border-slate-200 focus:outline-none {{ $user->role === 'admin' ? 'bg-slate-900 text-white' : 'bg-slate-50 text-slate-700' }}">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </form>
                </td>
                <td class="px-6 py-4 text-[13px] text-slate-600">{{ $user->orders_count ?? 0 }}</td>
                <td class="px-6 py-4 text-[13px] text-slate-600">{{ $user->wishlists_count ?? 0 }}</td>
                <td class="px-6 py-4 text-[12px] text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-slate-400 hover:text-slate-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-slate-400 hover:text-slate-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-[13px] text-slate-400">No users found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $users->links('vendor.pagination.admin') }}
</div>
@endsection
