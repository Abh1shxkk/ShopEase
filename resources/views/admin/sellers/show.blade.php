@extends('layouts.admin')

@section('title', $seller->store_name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.sellers.index') }}" class="p-2 text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <img src="{{ $seller->logo_url }}" alt="{{ $seller->store_name }}" class="w-12 h-12 rounded-full object-cover border border-slate-200">
            <div>
                <h1 class="text-2xl font-serif text-slate-900">{{ $seller->store_name }}</h1>
                <p class="text-[12px] text-slate-500">{{ $seller->user->email }}</p>
            </div>
        </div>
        <span class="px-3 py-1.5 text-[11px] font-medium
            @if($seller->status === 'approved') bg-emerald-50 text-emerald-700
            @elseif($seller->status === 'pending') bg-amber-50 text-amber-700
            @else bg-red-50 text-red-700 @endif">
            {{ ucfirst($seller->status) }}
        </span>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Total Products</p>
            <p class="text-2xl font-serif text-slate-900 mt-1">{{ $stats['total_products'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Total Orders</p>
            <p class="text-2xl font-serif text-slate-900 mt-1">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Total Earnings</p>
            <p class="text-2xl font-serif text-slate-900 mt-1">₹{{ number_format($stats['total_earnings'], 2) }}</p>
        </div>
        <div class="bg-white border border-slate-200 p-5">
            <p class="text-[10px] font-bold tracking-widest uppercase text-slate-400">Wallet Balance</p>
            <p class="text-2xl font-serif text-emerald-600 mt-1">₹{{ number_format($stats['wallet_balance'], 2) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Seller Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-serif text-slate-900">Business Details</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-2 gap-4 text-[13px]">
                        <div><dt class="text-slate-500 text-[11px] uppercase tracking-wider">Business Name</dt><dd class="text-slate-900 mt-1">{{ $seller->business_name ?? '-' }}</dd></div>
                        <div><dt class="text-slate-500 text-[11px] uppercase tracking-wider">Business Email</dt><dd class="text-slate-900 mt-1">{{ $seller->business_email }}</dd></div>
                        <div><dt class="text-slate-500 text-[11px] uppercase tracking-wider">Business Phone</dt><dd class="text-slate-900 mt-1">{{ $seller->business_phone }}</dd></div>
                        <div><dt class="text-slate-500 text-[11px] uppercase tracking-wider">GST Number</dt><dd class="text-slate-900 mt-1">{{ $seller->gst_number ?? '-' }}</dd></div>
                        <div class="col-span-2"><dt class="text-slate-500 text-[11px] uppercase tracking-wider">Address</dt><dd class="text-slate-900 mt-1">{{ $seller->business_address ?? '-' }}</dd></div>
                    </dl>
                </div>
            </div>

            <div class="bg-white border border-slate-200">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-serif text-slate-900">Bank Details</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-2 gap-4 text-[13px]">
                        <div><dt class="text-slate-500 text-[11px] uppercase tracking-wider">Bank Name</dt><dd class="text-slate-900 mt-1">{{ $seller->bank_name ?? '-' }}</dd></div>
                        <div><dt class="text-slate-500 text-[11px] uppercase tracking-wider">Account Holder</dt><dd class="text-slate-900 mt-1">{{ $seller->bank_account_holder ?? '-' }}</dd></div>
                        <div><dt class="text-slate-500 text-[11px] uppercase tracking-wider">Account Number</dt><dd class="text-slate-900 mt-1">{{ $seller->bank_account_number ? '****' . substr($seller->bank_account_number, -4) : '-' }}</dd></div>
                        <div><dt class="text-slate-500 text-[11px] uppercase tracking-wider">IFSC Code</dt><dd class="text-slate-900 mt-1">{{ $seller->bank_ifsc_code ?? '-' }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            <!-- Commission -->
            <div class="bg-white border border-slate-200 p-6">
                <h2 class="text-lg font-serif text-slate-900 mb-4">Commission Rate</h2>
                <form action="{{ route('admin.sellers.commission', $seller) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="flex space-x-2">
                        <input type="number" name="commission_rate" value="{{ $seller->commission_rate }}" step="0.01" min="0" max="100"
                            class="flex-1 px-3 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        <button type="submit" class="px-4 py-2.5 bg-slate-900 text-white text-[12px] font-medium hover:bg-slate-800">Save</button>
                    </div>
                </form>
            </div>

            <!-- Actions -->
            <div class="bg-white border border-slate-200 p-6">
                <h2 class="text-lg font-serif text-slate-900 mb-4">Actions</h2>
                <div class="space-y-3">
                    @if($seller->status === 'pending')
                    <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-emerald-600 text-white text-[12px] font-medium hover:bg-emerald-700">Approve Seller</button>
                    </form>
                    <form action="{{ route('admin.sellers.reject', $seller) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        <input type="text" name="reason" placeholder="Rejection reason" required class="w-full mb-2 px-3 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white text-[12px] font-medium hover:bg-red-700">Reject Seller</button>
                    </form>
                    @elseif($seller->status === 'approved')
                    <form action="{{ route('admin.sellers.suspend', $seller) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        <input type="text" name="reason" placeholder="Suspension reason" required class="w-full mb-2 px-3 py-2.5 border border-slate-200 text-[13px] focus:ring-2 focus:ring-slate-900">
                        <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white text-[12px] font-medium hover:bg-red-700">Suspend Seller</button>
                    </form>
                    @elseif($seller->status === 'suspended')
                    <form action="{{ route('admin.sellers.approve', $seller) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 bg-emerald-600 text-white text-[12px] font-medium hover:bg-emerald-700">Reactivate Seller</button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white border border-slate-200 p-6">
                <h2 class="text-lg font-serif text-slate-900 mb-4">Timeline</h2>
                <div class="space-y-2 text-[12px] text-slate-600">
                    <p>Joined: {{ $seller->created_at->format('M d, Y') }}</p>
                    @if($seller->approved_at)<p>Approved: {{ $seller->approved_at->format('M d, Y') }}</p>@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
