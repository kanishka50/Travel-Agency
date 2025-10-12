@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome, {{ $admin->full_name }} ({{ ucfirst(str_replace('_', ' ', $admin->role)) }})</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Statistics -->
        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Total Users</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
            <p class="text-sm text-gray-600 mt-1">Registered users</p>
        </div>

        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tourists</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_tourists'] }}</p>
            <p class="text-sm text-gray-600 mt-1">Active tourists</p>
        </div>

        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Guides</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_guides'] }}</p>
            <p class="text-sm text-gray-600 mt-1">Active guides</p>
        </div>

        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Pending Registrations</h3>
            <p class="text-3xl font-bold text-orange-600">{{ $stats['pending_registrations'] }}</p>
            <p class="text-sm text-gray-600 mt-1">Awaiting review</p>
        </div>

        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Total Bookings</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_bookings'] }}</p>
            <p class="text-sm text-gray-600 mt-1">All time bookings</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white p-6 rounded border">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="#" class="text-blue-600 hover:text-blue-800">Review Guide Registrations</a>
            <a href="#" class="text-blue-600 hover:text-blue-800">Manage Users</a>
            <a href="#" class="text-blue-600 hover:text-blue-800">View All Bookings</a>
            <a href="#" class="text-blue-600 hover:text-blue-800">Handle Complaints</a>
            <a href="#" class="text-blue-600 hover:text-blue-800">Process Guide Payments</a>
            <a href="#" class="text-blue-600 hover:text-blue-800">System Settings</a>
        </div>
    </div>
</div>
@endsection