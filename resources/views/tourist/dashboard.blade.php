@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $tourist->full_name }}!</h1>
        <p class="text-gray-600 mt-2">Manage your bookings, proposals, and travel requests all in one place</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Bookings -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Bookings</p>
                    <p class="text-4xl font-bold mt-2">{{ $totalBookings }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Upcoming Tours</p>
                    <p class="text-4xl font-bold mt-2">{{ $upcomingBookings }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Requests -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Active Requests</p>
                    <p class="text-4xl font-bold mt-2">{{ $activeRequests }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Proposals -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Pending Proposals</p>
                    <p class="text-4xl font-bold mt-2">{{ $pendingProposals }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Quick Actions
                </h2>
                <div class="space-y-3">
                    <a href="{{ route('plans.index') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition group">
                        <div class="bg-blue-600 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 group-hover:text-blue-600">Browse Tours</p>
                            <p class="text-xs text-gray-500">Discover amazing tours</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('bookings.index') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition group">
                        <div class="bg-green-600 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 group-hover:text-green-600">My Bookings</p>
                            <p class="text-xs text-gray-500">View all bookings</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('tourist.proposals.index') }}" class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition group">
                        <div class="bg-orange-600 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 group-hover:text-orange-600">My Proposals</p>
                            <p class="text-xs text-gray-500">Track your proposals</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('tourist-requests.index') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition group">
                        <div class="bg-purple-600 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 group-hover:text-purple-600">My Requests</p>
                            <p class="text-xs text-gray-500">View custom requests</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <a href="{{ route('tourist-requests.create') }}" class="flex items-center p-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 rounded-lg transition text-white">
                        <div class="bg-white bg-opacity-20 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold">Request Custom Tour</p>
                            <p class="text-xs text-blue-100">Create personalized tour</p>
                        </div>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Recent Bookings
                    </h2>
                    <a href="{{ route('bookings.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View All →
                    </a>
                </div>

                @if($recentBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentBookings as $booking)
                            @php
                                // Determine the plan and guide based on booking type
                                if ($booking->guidePlan) {
                                    $plan = $booking->guidePlan;
                                    $guide = $plan->guide;
                                    $title = $plan->title;
                                } elseif ($booking->acceptedProposal && $booking->acceptedProposal->guidePlan) {
                                    $plan = $booking->acceptedProposal->guidePlan;
                                    $guide = $plan->guide;
                                    $title = $plan->title;
                                } elseif ($booking->acceptedBid && $booking->acceptedBid->touristRequest) {
                                    $request = $booking->acceptedBid->touristRequest;
                                    $guide = $booking->acceptedBid->guide;
                                    $title = $request->title;
                                    $plan = null;
                                } else {
                                    continue; // Skip if no valid data
                                }
                                $statusColors = [
                                    'pending_payment' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'upcoming' => 'bg-blue-100 text-blue-800',
                                    'ongoing' => 'bg-purple-100 text-purple-800',
                                    'completed' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="font-semibold text-gray-900">{{ $title }}</h3>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                            </span>
                                        </div>
                                        <div class="space-y-1 text-sm text-gray-600">
                                            <p class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                Guide: {{ $guide->full_name }}
                                            </p>
                                            <p class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}
                                            </p>
                                            <p class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Total: ${{ number_format($booking->total_price, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="ml-4 text-blue-600 hover:text-blue-800">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No bookings yet</h3>
                        <p class="text-gray-600 mb-4">Start exploring amazing tours and book your first adventure!</p>
                        <a href="{{ route('plans.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Browse Tours
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
