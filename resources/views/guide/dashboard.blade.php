@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Guide Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ $guide->full_name }}!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Quick Stats -->
        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">My Plans</h3>
            <p class="text-3xl font-bold text-blue-600">0</p>
            <p class="text-sm text-gray-600 mt-1">Active plans</p>
        </div>

        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Bookings</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $guide->total_bookings }}</p>
            <p class="text-sm text-gray-600 mt-1">Total bookings</p>
        </div>

        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Rating</h3>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($guide->average_rating, 1) }}</p>
            <p class="text-sm text-gray-600 mt-1">Average rating</p>
        </div>

        <div class="bg-white p-6 rounded border">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Reviews</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $guide->total_reviews }}</p>
            <p class="text-sm text-gray-600 mt-1">Total reviews</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 bg-white p-6 rounded border">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="space-y-2">
            <a href="#" class="block text-blue-600 hover:text-blue-800">Create New Tour Plan</a>
            <a href="#" class="block text-blue-600 hover:text-blue-800">View Tourist Requests</a>
            <a href="#" class="block text-blue-600 hover:text-blue-800">Manage My Plans</a>
            <a href="#" class="block text-blue-600 hover:text-blue-800">View Bookings</a>
        </div>
    </div>
</div>
@endsection