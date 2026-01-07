@extends('layouts.admin')

@section('title', 'Early Access Sales')
@section('page-title', 'Early Access Sales')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <p class="text-slate-600">Manage exclusive early access sales for members</p>
        <a href="{{ route('admin.membership.sales.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded hover:bg-slate-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Sale
        </a>
    </div>

    {{-- Sales Table --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Sale</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Member Access</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Public Access</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Ends</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Member Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-bold tracking-wider uppercase text-slate-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold tracking-wider uppercase text-slate-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($sales as $sale)
                @php
                    $now = now();
                    $status = 'upcoming';
                    $statusClass = 'bg-blue-100 text-blue-700';
                    
                    if (!$sale->is_active) {
                        $status = 'inactive';
                        $statusClass = 'bg-slate-100 text-slate-700';
                    } elseif ($sale->ends_at && $sale->ends_at < $now) {
                        $status = 'ended';
                        $statusClass = 'bg-slate-100 text-slate-700';
                    } elseif ($sale->public_access_at <= $now) {
                        $status = 'public';
                        $statusClass = 'bg-emerald-100 text-emerald-700';
                    } elseif ($sale->member_access_at <= $now) {
                        $status = 'members only';
                        $statusClass = 'bg-purple-100 text-purple-700';
                    }
                @endphp
                <tr>
                    <td class="px-6 py-4">
                        <p class="font-medium text-slate-900">{{ $sale->name }}</p>
                        @if($sale->description)
                        <p class="text-xs text-slate-500 truncate max-w-xs">{{ $sale->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $sale->member_access_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $sale->public_access_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        {{ $sale->ends_at ? $sale->ends_at->format('M d, Y h:i A') : 'No end date' }}
                    </td>
                    <td class="px-6 py-4 font-medium">
                        @if($sale->member_discount > 0)
                        <span class="text-emerald-600">{{ $sale->member_discount }}% extra</span>
                        @else
                        <span class="text-slate-400">None</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.membership.sales.edit', $sale) }}" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.membership.sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this sale?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-lg font-medium text-slate-600 mb-1">No early access sales</p>
                            <p class="text-sm text-slate-500 mb-4">Create your first early access sale for members</p>
                            <a href="{{ route('admin.membership.sales.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded hover:bg-slate-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Create Sale
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($sales->hasPages())
        <div class="p-6 border-t border-slate-200">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
