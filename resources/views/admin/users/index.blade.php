@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div x-data="{ showDeleteModal: false, deleteId: null, deleteName: '', showRoleModal: false, roleUserId: null, roleUserName: '', currentRole: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
            <p class="text-gray-500 mt-1">Manage user accounts and roles</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="input w-full">
            </div>
            <select name="role" class="input select w-full sm:w-40">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
            <button type="submit" class="btn btn-outline">Filter</button>
            @if(request()->hasAny(['search', 'role']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Clear</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
        @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="table">
                <thead class="bg-gray-50">
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-medium flex-shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'badge-info' : 'badge-success' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-gray-600">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-end space-x-2">
                                <button @click="showRoleModal = true; roleUserId = {{ $user->id }}; roleUserName = '{{ $user->name }}'; currentRole = '{{ $user->role }}'" class="btn btn-ghost btn-icon" title="Change Role">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                @if($user->id !== auth()->id())
                                <button @click="showDeleteModal = true; deleteId = {{ $user->id }}; deleteName = '{{ $user->name }}'" class="btn btn-ghost btn-icon" title="Delete">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No users found</h3>
            <p class="text-gray-500">Try adjusting your search or filter.</p>
        </div>
        @endif
    </div>

    <!-- Role Modal -->
    <div x-show="showRoleModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showRoleModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showRoleModal = false" class="fixed inset-0 bg-black/50"></div>
            
            <div x-show="showRoleModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Change User Role</h3>
                <p class="text-gray-500 mb-4">Update role for <span x-text="roleUserName" class="font-medium"></span></p>
                <form :action="'{{ route('admin.users.index') }}/' + roleUserId + '/role'" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-3 mb-6">
                        <label class="flex items-center space-x-3 cursor-pointer p-3 rounded-lg border" :class="currentRole === 'user' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                            <input type="radio" name="role" value="user" x-model="currentRole" class="w-4 h-4 text-blue-600">
                            <div>
                                <span class="font-medium text-gray-900">User</span>
                                <p class="text-sm text-gray-500">Regular user with limited access</p>
                            </div>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer p-3 rounded-lg border" :class="currentRole === 'admin' ? 'border-blue-500 bg-blue-50' : 'border-gray-200'">
                            <input type="radio" name="role" value="admin" x-model="currentRole" class="w-4 h-4 text-blue-600">
                            <div>
                                <span class="font-medium text-gray-900">Admin</span>
                                <p class="text-sm text-gray-500">Full access to admin panel</p>
                            </div>
                        </label>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" @click="showRoleModal = false" class="btn btn-outline flex-1">Cancel</button>
                        <button type="submit" class="btn btn-primary flex-1">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showDeleteModal = false" class="fixed inset-0 bg-black/50"></div>
            
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete User</h3>
                    <p class="text-gray-500 mb-6">Are you sure you want to delete "<span x-text="deleteName" class="font-medium"></span>"? This action cannot be undone.</p>
                    <div class="flex space-x-3">
                        <button @click="showDeleteModal = false" class="btn btn-outline flex-1">Cancel</button>
                        <form :action="'{{ route('admin.users.index') }}/' + deleteId" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
