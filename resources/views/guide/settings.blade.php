@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Account Settings</h1>

        <!-- Profile Section -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h2>

            <div class="flex items-start gap-6">
                <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    @if($guide->profile_photo)
                        <img src="{{ Storage::url($guide->profile_photo) }}" alt="{{ $guide->full_name }}"
                             class="w-20 h-20 rounded-full object-cover">
                    @else
                        <span class="text-emerald-600 font-bold text-2xl">
                            {{ strtoupper(substr($guide->full_name, 0, 1)) }}
                        </span>
                    @endif
                </div>

                <div class="flex-1 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Full Name</label>
                        <p class="text-gray-900">{{ $guide->full_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Guide ID</label>
                        <p class="text-gray-900 font-mono">{{ $guide->guide_id_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Guide Type</label>
                        <p class="text-gray-900">{{ $guide->guide_type_label }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Phone</label>
                        <p class="text-gray-900">{{ $guide->phone ?? 'Not set' }}</p>
                    </div>
                </div>
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

        <!-- License & Insurance -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">License & Insurance</h2>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">License Number</label>
                    <p class="text-gray-900">{{ $guide->license_number ?? 'Not provided' }}</p>
                    @if($guide->license_expiry)
                        <p class="text-sm {{ $guide->license_expiry->isPast() ? 'text-red-600' : 'text-gray-500' }}">
                            Expires: {{ $guide->license_expiry->format('M d, Y') }}
                        </p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Insurance Policy</label>
                    <p class="text-gray-900">{{ $guide->insurance_policy_number ?? 'Not provided' }}</p>
                    @if($guide->insurance_expiry)
                        <p class="text-sm {{ $guide->insurance_expiry->isPast() ? 'text-red-600' : 'text-gray-500' }}">
                            Expires: {{ $guide->insurance_expiry->format('M d, Y') }}
                        </p>
                    @endif
                </div>
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
                    <label class="block text-sm font-medium text-gray-500">Account Status</label>
                    <p class="text-gray-900">
                        <span class="inline-flex items-center px-2 py-1 {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' }} text-sm rounded-full">
                            {{ ucfirst($user->status) }}
                        </span>
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
                <a href="{{ route('guide.dashboard') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Dashboard</p>
                        <p class="text-sm text-gray-500">View your activity</p>
                    </div>
                </a>
                <a href="{{ route('guide.plans.index') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">My Plans</p>
                        <p class="text-sm text-gray-500">Manage tour packages</p>
                    </div>
                </a>
                <a href="{{ route('guide.bookings') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">Bookings</p>
                        <p class="text-sm text-gray-500">View your bookings</p>
                    </div>
                </a>
                <a href="{{ route('guide.vehicles.index') }}" class="flex items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-8 h-8 text-emerald-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900">My Vehicles</p>
                        <p class="text-sm text-gray-500">Manage vehicles</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
