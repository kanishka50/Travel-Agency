@extends('layouts.dashboard')

@section('page-title', 'Account Settings')

@section('content')
<div class="max-w-4xl">
    <!-- Profile Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-slate-900">Profile Information</h2>
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            <div class="bg-slate-50 rounded-xl p-4">
                <label class="block text-sm font-medium text-slate-500 mb-1">Email</label>
                <p class="text-slate-900 font-medium">{{ $user->email }}</p>
            </div>

            @if($tourist)
                <div class="bg-slate-50 rounded-xl p-4">
                    <label class="block text-sm font-medium text-slate-500 mb-1">Account Type</label>
                    <p class="text-slate-900 font-medium capitalize">{{ $user->user_type }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <label class="block text-sm font-medium text-slate-500 mb-1">Member Since</label>
                    <p class="text-slate-900 font-medium">{{ $user->created_at->format('F d, Y') }}</p>
                </div>
            @endif
        </div>

        <div class="mt-6 pt-6 border-t border-slate-200">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Security Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-slate-900">Security</h2>
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            <div class="bg-slate-50 rounded-xl p-4">
                <label class="block text-sm font-medium text-slate-500 mb-1">Password</label>
                <p class="text-slate-900 font-medium">********</p>
            </div>

            <div class="bg-slate-50 rounded-xl p-4">
                <label class="block text-sm font-medium text-slate-500 mb-1">Email Verified</label>
                @if($user->email_verified_at)
                    <p class="inline-flex items-center gap-1 text-emerald-600 font-medium">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Verified on {{ $user->email_verified_at->format('M d, Y') }}
                    </p>
                @else
                    <p class="inline-flex items-center gap-1 text-amber-600 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Not verified
                    </p>
                @endif
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-slate-200">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Change Password
            </a>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-slate-900">Quick Links</h2>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <a href="{{ route('tourist.dashboard') }}" class="flex items-center p-4 border border-slate-200 rounded-xl hover:border-amber-200 hover:bg-amber-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-amber-200 transition-colors">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 group-hover:text-amber-600 transition-colors">Dashboard</p>
                    <p class="text-sm text-slate-500">View your activity</p>
                </div>
            </a>
            <a href="{{ route('tourist.bookings.index') }}" class="flex items-center p-4 border border-slate-200 rounded-xl hover:border-amber-200 hover:bg-amber-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-emerald-200 transition-colors">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 group-hover:text-amber-600 transition-colors">My Bookings</p>
                    <p class="text-sm text-slate-500">View your bookings</p>
                </div>
            </a>
            <a href="{{ route('tourist.requests.index') }}" class="flex items-center p-4 border border-slate-200 rounded-xl hover:border-amber-200 hover:bg-amber-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-orange-200 transition-colors">
                    <svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 group-hover:text-amber-600 transition-colors">My Requests</p>
                    <p class="text-sm text-slate-500">Manage tour requests</p>
                </div>
            </a>
            <a href="{{ route('tour-packages.index') }}" class="flex items-center p-4 border border-slate-200 rounded-xl hover:border-amber-200 hover:bg-amber-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center mr-4 group-hover:bg-cyan-200 transition-colors">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 group-hover:text-amber-600 transition-colors">Browse Tours</p>
                    <p class="text-sm text-slate-500">Find your next adventure</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
