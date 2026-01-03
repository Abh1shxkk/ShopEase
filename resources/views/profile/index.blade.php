@extends('layouts.shop')

@section('title', 'My Account')

@section('content')
<div class="max-w-[1200px] mx-auto px-6 md:px-12 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[11px] tracking-widest uppercase text-slate-400 mb-12">
        <a href="{{ route('home') }}" class="hover:text-slate-900 transition-colors">Home</a>
        <span>/</span>
        <span class="text-slate-900">My Account</span>
    </nav>

    {{-- Toast Notification --}}
    <div id="toast" class="fixed top-4 right-4 z-50 hidden">
        <div class="px-6 py-4 shadow-lg flex items-center gap-3 min-w-[300px]">
            <span id="toast-icon"></span>
            <span id="toast-message" class="font-medium text-sm"></span>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-12">
        {{-- Sidebar --}}
        <div class="lg:w-72 flex-shrink-0">
            <div class="bg-slate-50 p-6 mb-6">
                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-16 h-16 object-cover" id="sidebar-avatar">
                    <div>
                        <h2 class="font-medium text-slate-900" id="sidebar-name">{{ auth()->user()->name }}</h2>
                        <p class="text-[12px] text-slate-500" id="sidebar-email">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <p class="text-[10px] tracking-widest uppercase text-slate-400">Member since {{ auth()->user()->created_at->format('M Y') }}</p>
            </div>

            <nav class="bg-slate-50 overflow-hidden">
                <button onclick="showTab('personal')" id="tab-personal" class="tab-btn w-full px-6 py-4 text-left text-[12px] font-medium hover:bg-slate-100 border-l-2 border-transparent flex items-center gap-3 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Personal Info
                </button>
                <button onclick="showTab('addresses')" id="tab-addresses" class="tab-btn w-full px-6 py-4 text-left text-[12px] font-medium hover:bg-slate-100 border-l-2 border-transparent flex items-center gap-3 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Addresses
                </button>
                <button onclick="showTab('payment')" id="tab-payment" class="tab-btn w-full px-6 py-4 text-left text-[12px] font-medium hover:bg-slate-100 border-l-2 border-transparent flex items-center gap-3 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Payment Methods
                </button>
                <button onclick="showTab('password')" id="tab-password" class="tab-btn w-full px-6 py-4 text-left text-[12px] font-medium hover:bg-slate-100 border-l-2 border-transparent flex items-center gap-3 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Password
                </button>
                <button onclick="showTab('preferences')" id="tab-preferences" class="tab-btn w-full px-6 py-4 text-left text-[12px] font-medium hover:bg-slate-100 border-l-2 border-transparent flex items-center gap-3 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Preferences
                </button>
            </nav>
        </div>

        {{-- Main Content --}}
        <div class="flex-1">
            {{-- Personal Info Tab --}}
            <div id="content-personal" class="tab-content">
                <div class="bg-slate-50 p-8">
                    <h2 class="text-xl font-serif tracking-wide text-slate-900 mb-8">Personal Information</h2>
                    <form id="profile-form" enctype="multipart/form-data">
                        <div class="mb-6">
                            <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Profile Picture</label>
                            <div class="flex items-center gap-4">
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-20 h-20 object-cover" id="avatar-preview">
                                <input type="file" name="avatar" accept="image/*" class="text-[12px]" onchange="previewAvatar(this)">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="+91 9876543210" class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', auth()->user()->date_of_birth?->format('Y-m-d')) }}" class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Gender</label>
                            <select name="gender" class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                <option value="">Prefer not to say</option>
                                <option value="male" {{ old('gender', auth()->user()->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', auth()->user()->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', auth()->user()->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors flex items-center justify-center gap-2">
                            <span>Save Changes</span>
                            <svg class="w-4 h-4 animate-spin hidden" id="profile-spinner" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Addresses Tab --}}
            <div id="content-addresses" class="tab-content hidden">
                <div class="bg-slate-50 p-8 mb-6">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-serif tracking-wide text-slate-900">Saved Addresses</h2>
                        <button onclick="toggleAddressForm()" class="px-4 py-2 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
                            + Add Address
                        </button>
                    </div>

                    {{-- Add Address Form --}}
                    <div id="address-form" class="hidden mb-8 p-6 bg-white border border-slate-200">
                        <form id="add-address-form">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Label</label>
                                    <select name="label" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                        <option value="Home">Home</option>
                                        <option value="Office">Office</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Address Type</label>
                                    <select name="type" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                        <option value="both">Shipping & Billing</option>
                                        <option value="shipping">Shipping Only</option>
                                        <option value="billing">Billing Only</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Recipient Name</label>
                                    <input type="text" name="name" required class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Phone</label>
                                    <input type="tel" name="phone" required class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Address Line 1</label>
                                <input type="text" name="address_line_1" required placeholder="House/Flat No., Building Name, Street" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                            </div>
                            <div class="mb-4">
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Address Line 2 (Optional)</label>
                                <input type="text" name="address_line_2" placeholder="Area, Colony" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">City</label>
                                    <input type="text" name="city" required class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">State</label>
                                    <input type="text" name="state" required class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Pincode</label>
                                    <input type="text" name="pincode" required class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Landmark</label>
                                    <input type="text" name="landmark" placeholder="Near..." class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mb-4">
                                <input type="checkbox" name="is_default" value="1" id="is_default" class="w-4 h-4">
                                <label for="is_default" class="text-[12px] text-slate-700">Set as default address</label>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="px-6 py-3 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors flex items-center gap-2">
                                    <span>Save Address</span>
                                    <svg class="w-4 h-4 animate-spin hidden address-spinner" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                </button>
                                <button type="button" onclick="toggleAddressForm()" class="px-6 py-3 bg-white text-slate-700 text-[11px] font-bold tracking-widest uppercase border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                            </div>
                        </form>
                    </div>

                    {{-- Address List --}}
                    <div id="address-list">
                        @if($addresses->count() > 0)
                        <div class="space-y-4">
                            @foreach($addresses as $address)
                            <div class="address-item p-4 bg-white border {{ $address->is_default ? 'border-slate-900' : 'border-slate-200' }}" data-id="{{ $address->id }}">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-2 py-1 bg-slate-100 text-[10px] font-bold tracking-widest uppercase">{{ $address->label }}</span>
                                            @if($address->is_default)
                                            <span class="px-2 py-1 bg-slate-900 text-white text-[10px] font-bold tracking-widest uppercase">Default</span>
                                            @endif
                                            <span class="px-2 py-1 bg-slate-100 text-[10px] tracking-widest uppercase">{{ $address->type }}</span>
                                        </div>
                                        <p class="font-medium text-slate-900 text-[13px]">{{ $address->name }}</p>
                                        <p class="text-[12px] text-slate-500">{{ $address->phone }}</p>
                                        <p class="text-[12px] text-slate-500 mt-1">{{ $address->full_address }}</p>
                                    </div>
                                    <button onclick="deleteAddress({{ $address->id }})" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-red-600 transition-colors">Delete</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-slate-500 text-center py-12 empty-message text-[13px]">No addresses saved yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Payment Methods Tab --}}
            <div id="content-payment" class="tab-content hidden">
                <div class="bg-slate-50 p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-serif tracking-wide text-slate-900">Payment Methods</h2>
                        <button onclick="togglePaymentForm()" class="px-4 py-2 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors">
                            + Add Payment
                        </button>
                    </div>

                    {{-- Add Payment Form --}}
                    <div id="payment-form" class="hidden mb-8 p-6 bg-white border border-slate-200">
                        <form id="add-payment-form">
                            <div class="mb-4">
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Payment Type</label>
                                <select name="type" id="payment-type" onchange="togglePaymentFields()" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                    <option value="upi">UPI</option>
                                    <option value="netbanking">Net Banking</option>
                                </select>
                            </div>
                            <div id="upi-fields">
                                <div class="mb-4">
                                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">UPI ID</label>
                                    <input type="text" name="upi_id" placeholder="yourname@upi" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                </div>
                            </div>
                            <div id="netbanking-fields" class="hidden">
                                <div class="mb-4">
                                    <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Bank Name</label>
                                    <input type="text" name="bank_name" placeholder="HDFC Bank" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-2">Label (Optional)</label>
                                <input type="text" name="label" placeholder="Personal UPI" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 text-[12px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                            </div>
                            <div class="flex items-center gap-2 mb-4">
                                <input type="checkbox" name="is_default" value="1" id="payment_default" class="w-4 h-4">
                                <label for="payment_default" class="text-[12px] text-slate-700">Set as default</label>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="px-6 py-3 bg-slate-900 text-white text-[11px] font-bold tracking-widest uppercase hover:bg-slate-800 transition-colors flex items-center gap-2">
                                    <span>Save</span>
                                    <svg class="w-4 h-4 animate-spin hidden payment-spinner" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                </button>
                                <button type="button" onclick="togglePaymentForm()" class="px-6 py-3 bg-white text-slate-700 text-[11px] font-bold tracking-widest uppercase border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                            </div>
                        </form>
                    </div>

                    {{-- Payment Methods List --}}
                    <div id="payment-list">
                        @if($paymentMethods->count() > 0)
                        <div class="space-y-4">
                            @foreach($paymentMethods as $method)
                            <div class="payment-item p-4 bg-white border {{ $method->is_default ? 'border-slate-900' : 'border-slate-200' }} flex items-center justify-between" data-id="{{ $method->id }}">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-slate-100 flex items-center justify-center">
                                        @if($method->type == 'upi')
                                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        @else
                                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900 text-[13px]">{{ $method->display_name }}</p>
                                        <p class="text-[11px] text-slate-500 uppercase tracking-widest">{{ $method->type }} {{ $method->label ? "• {$method->label}" : '' }}</p>
                                    </div>
                                    @if($method->is_default)
                                    <span class="px-2 py-1 bg-slate-900 text-white text-[10px] font-bold tracking-widest uppercase">Default</span>
                                    @endif
                                </div>
                                <button onclick="deletePaymentMethod({{ $method->id }})" class="text-[11px] tracking-widest uppercase text-slate-400 hover:text-red-600 transition-colors">Remove</button>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-12 empty-payment-message">
                            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <p class="text-slate-500 text-[13px]">No payment methods saved yet.</p>
                            <p class="text-[12px] text-slate-400 mt-1">Add UPI or Net Banking for faster checkout.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Password Tab --}}
            <div id="content-password" class="tab-content hidden">
                <div class="bg-slate-50 p-8">
                    <h2 class="text-xl font-serif tracking-wide text-slate-900 mb-8">Change Password</h2>
                    <form id="password-form">
                        <div class="space-y-6 mb-8">
                            <div>
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Current Password</label>
                                <input type="password" name="current_password" required class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">New Password</label>
                                <input type="password" name="password" required minlength="8" class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                                <p class="text-[11px] text-slate-400 mt-2">Minimum 8 characters</p>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold tracking-widest uppercase text-slate-500 mb-3">Confirm New Password</label>
                                <input type="password" name="password_confirmation" required class="w-full h-12 px-4 bg-white border border-slate-200 text-[13px] focus:outline-none focus:ring-1 focus:ring-slate-900">
                            </div>
                        </div>
                        <button type="submit" class="w-full h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors flex items-center justify-center gap-2">
                            <span>Update Password</span>
                            <svg class="w-4 h-4 animate-spin hidden" id="password-spinner" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Preferences Tab --}}
            <div id="content-preferences" class="tab-content hidden">
                <div class="bg-slate-50 p-8 mb-6">
                    <h2 class="text-xl font-serif tracking-wide text-slate-900 mb-8">Notification Preferences</h2>
                    <form id="preferences-form">
                        <div class="space-y-4 mb-8">
                            <label class="flex items-center justify-between p-4 bg-white border border-slate-200 cursor-pointer hover:border-slate-300 transition-colors">
                                <div>
                                    <p class="font-medium text-slate-900 text-[13px]">Email Notifications</p>
                                    <p class="text-[12px] text-slate-500">Receive order updates via email</p>
                                </div>
                                <input type="checkbox" name="email_notifications" value="1" {{ auth()->user()->email_notifications ? 'checked' : '' }} class="w-5 h-5">
                            </label>
                            <label class="flex items-center justify-between p-4 bg-white border border-slate-200 cursor-pointer hover:border-slate-300 transition-colors">
                                <div>
                                    <p class="font-medium text-slate-900 text-[13px]">SMS Notifications</p>
                                    <p class="text-[12px] text-slate-500">Receive order updates via SMS</p>
                                </div>
                                <input type="checkbox" name="sms_notifications" value="1" {{ auth()->user()->sms_notifications ? 'checked' : '' }} class="w-5 h-5">
                            </label>
                            <label class="flex items-center justify-between p-4 bg-white border border-slate-200 cursor-pointer hover:border-slate-300 transition-colors">
                                <div>
                                    <p class="font-medium text-slate-900 text-[13px]">Marketing Emails</p>
                                    <p class="text-[12px] text-slate-500">Receive promotional offers and updates</p>
                                </div>
                                <input type="checkbox" name="marketing_emails" value="1" {{ auth()->user()->marketing_emails ? 'checked' : '' }} class="w-5 h-5">
                            </label>
                        </div>
                        <button type="submit" class="w-full h-12 bg-slate-900 text-white text-[11px] font-bold tracking-[0.2em] uppercase hover:bg-slate-800 transition-colors flex items-center justify-center gap-2">
                            <span>Save Preferences</span>
                            <svg class="w-4 h-4 animate-spin hidden" id="preferences-spinner" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab functionality
    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-slate-900', 'bg-slate-100');
            el.classList.add('border-transparent');
        });
        document.getElementById('content-' + tabName).classList.remove('hidden');
        const btn = document.getElementById('tab-' + tabName);
        btn.classList.add('border-slate-900', 'bg-slate-100');
        btn.classList.remove('border-transparent');
    }
    showTab('personal');

    // Toast notification
    function showToastProfile(message, type = 'success') {
        const toast = document.getElementById('toast');
        const icon = document.getElementById('toast-icon');
        const msg = document.getElementById('toast-message');
        toast.querySelector('div').className = `px-6 py-4 shadow-lg flex items-center gap-3 min-w-[300px] ${type === 'success' ? 'bg-slate-900 text-white' : 'bg-red-600 text-white'}`;
        icon.innerHTML = type === 'success' ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        msg.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    // Avatar preview
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('avatar-preview').src = e.target.result;
                document.getElementById('sidebar-avatar').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Profile form
    document.getElementById('profile-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const spinner = document.getElementById('profile-spinner');
        spinner.classList.remove('hidden');
        const formData = new FormData(this);
        try {
            const res = await fetch('{{ route("profile.update") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                showToastProfile(data.message);
                if (data.user) {
                    document.getElementById('sidebar-name').textContent = data.user.name;
                    document.getElementById('sidebar-email').textContent = data.user.email;
                }
            } else {
                showToastProfile(data.message || 'Error updating profile', 'error');
            }
        } catch (err) {
            showToastProfile('Error updating profile', 'error');
        }
        spinner.classList.add('hidden');
    });

    // Address form toggle
    function toggleAddressForm() {
        document.getElementById('address-form').classList.toggle('hidden');
    }

    // Add address
    document.getElementById('add-address-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const spinner = this.querySelector('.address-spinner');
        spinner.classList.remove('hidden');
        const formData = new FormData(this);
        try {
            const res = await fetch('{{ route("profile.addresses.store") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                showToastProfile(data.message);
                location.reload();
            } else {
                showToastProfile(data.message || 'Error adding address', 'error');
            }
        } catch (err) {
            showToastProfile('Error adding address', 'error');
        }
        spinner.classList.add('hidden');
    });

    // Delete address
    async function deleteAddress(id) {
        if (!confirm('Delete this address?')) return;
        try {
            const res = await fetch(`/profile/addresses/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                showToastProfile(data.message);
                document.querySelector(`.address-item[data-id="${id}"]`).remove();
            } else {
                showToastProfile(data.message || 'Error deleting address', 'error');
            }
        } catch (err) {
            showToastProfile('Error deleting address', 'error');
        }
    }

    // Payment form toggle
    function togglePaymentForm() {
        document.getElementById('payment-form').classList.toggle('hidden');
    }

    function togglePaymentFields() {
        const type = document.getElementById('payment-type').value;
        document.getElementById('upi-fields').classList.toggle('hidden', type !== 'upi');
        document.getElementById('netbanking-fields').classList.toggle('hidden', type !== 'netbanking');
    }

    // Add payment method
    document.getElementById('add-payment-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const spinner = this.querySelector('.payment-spinner');
        spinner.classList.remove('hidden');
        const formData = new FormData(this);
        try {
            const res = await fetch('{{ route("profile.payment-methods.store") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                showToastProfile(data.message);
                location.reload();
            } else {
                showToastProfile(data.message || 'Error adding payment method', 'error');
            }
        } catch (err) {
            showToastProfile('Error adding payment method', 'error');
        }
        spinner.classList.add('hidden');
    });

    // Delete payment method
    async function deletePaymentMethod(id) {
        if (!confirm('Remove this payment method?')) return;
        try {
            const res = await fetch(`/profile/payment-methods/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                showToastProfile(data.message);
                document.querySelector(`.payment-item[data-id="${id}"]`).remove();
            } else {
                showToastProfile(data.message || 'Error removing payment method', 'error');
            }
        } catch (err) {
            showToastProfile('Error removing payment method', 'error');
        }
    }

    // Password form
    document.getElementById('password-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const spinner = document.getElementById('password-spinner');
        spinner.classList.remove('hidden');
        const formData = new FormData(this);
        try {
            const res = await fetch('{{ route("profile.password") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                showToastProfile(data.message);
                this.reset();
            } else {
                showToastProfile(data.message || 'Error updating password', 'error');
            }
        } catch (err) {
            showToastProfile('Error updating password', 'error');
        }
        spinner.classList.add('hidden');
    });

    // Preferences form
    document.getElementById('preferences-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const spinner = document.getElementById('preferences-spinner');
        spinner.classList.remove('hidden');
        const formData = new FormData(this);
        try {
            const res = await fetch('{{ route("profile.preferences") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                showToastProfile(data.message);
            } else {
                showToastProfile(data.message || 'Error saving preferences', 'error');
            }
        } catch (err) {
            showToastProfile('Error saving preferences', 'error');
        }
        spinner.classList.add('hidden');
    });
</script>
@endpush
