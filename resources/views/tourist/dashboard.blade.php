@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tourist Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ $tourist->full_name }}!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Quick Stats -->
        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">My Bookings</h3>
            <p class="text-3xl font-bold text-blue-600">0</p>
            <p class="text-sm text-gray-600 mt-1">Total bookings</p>
        </div>

        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">My Requests</h3>
            <p class="text-3xl font-bold text-blue-600">0</p>
            <p class="text-sm text-gray-600 mt-1">Active requests</p>
        </div>

        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Favorites</h3>
            <p class="text-3xl font-bold text-blue-600">0</p>
            <p class="text-sm text-gray-600 mt-1">Saved plans</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white p-6 rounded border">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="space-y-2">
            <a href="#" class="block text-blue-600 hover:text-blue-800">Browse Tour Plans</a>
            <a href="#" class="block text-blue-600 hover:text-blue-800">Post New Request</a>
            <a href="#" class="block text-blue-600 hover:text-blue-800">View My Bookings</a>
        </div>
    </div>
</div>
@endsection