@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Account Settings</h1>

        <!-- Profile Section -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Email</label>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>

                @if($tourist)
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Account Type</label>
                        <p class="text-gray-900 capitalize">{{ $user->user_type }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Member Since</label>
                        <p class="text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                @endif
            </div>

            <div class="mt-6 pt-6 border-t">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Security Section -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Security</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Password</label>
                    <p class="text-gray-900">••••••••</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Email Verified</label>
                    <p class="text-gray-900">
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center text-emerald-600">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Verified on {{ $user->email_verified_at->format('M d, Y') }}
                            </span>
                        @else
                            <span class="text-yellow-600">Not verified</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Change Password
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h2>

            <div class="grid md:grid-cols-2 gap-4">
                <a href="{{ route('tourist.dashboard') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Dashboard</p>
                        <p class="text-sm text-gray-500">View your activity</p>
                    </div>
                </a>
                <a href="{{ route('tourist.bookings.index') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">My Bookings</p>
                        <p class="text-sm text-gray-500">View your bookings</p>
                    </div>
                </a>
                <a href="{{ route('tourist.requests.index') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">My Requests</p>
                        <p class="text-sm text-gray-500">Manage tour requests</p>
                    </div>
                </a>
                <a href="{{ route('tour-packages.index') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Browse Tours</p>
                        <p class="text-sm text-gray-500">Find your next adventure</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
