@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('guide.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-emerald-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-emerald-600 md:ml-2">My Bookings</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
        <p class="text-gray-600 mt-2">Manage and view all your tour bookings</p>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <a href="{{ route('guide.bookings') }}"
                   class="border-b-2 {{ !request('date_filter') ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                    All Bookings
                </a>
                <a href="{{ route('guide.bookings', ['date_filter' => 'upcoming']) }}"
                   class="border-b-2 {{ request('date_filter') === 'upcoming' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                    Upcoming
                </a>
                <a href="{{ route('guide.bookings', ['date_filter' => 'ongoing']) }}"
                   class="border-b-2 {{ request('date_filter') === 'ongoing' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                    Ongoing
                </a>
                <a href="{{ route('guide.bookings', ['date_filter' => 'past']) }}"
                   class="border-b-2 {{ request('date_filter') === 'past' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                    Past
                </a>
            </nav>
        </div>
    </div>

    @if($bookings->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-4 text-xl font-semibold text-gray-900">No bookings found</h3>
            <p class="mt-2 text-gray-600">You don't have any bookings matching this filter.</p>
            <div class="mt-6">
                <a href="{{ route('guide.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>
    @else
        <!-- Bookings List -->
        <div class="space-y-4">
            @foreach($bookings as $booking)
                @php
                    $statusColors = [
                        'pending_payment' => 'bg-amber-100 text-amber-800 border-amber-300',
                        'payment_failed' => 'bg-red-100 text-red-800 border-red-300',
                        'confirmed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        'ongoing' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
                        'completed' => 'bg-gray-100 text-gray-800 border-gray-300',
                        'cancelled_by_tourist' => 'bg-red-100 text-red-800 border-red-300',
                        'cancelled_by_guide' => 'bg-red-100 text-red-800 border-red-300',
                        'cancelled_by_admin' => 'bg-red-100 text-red-800 border-red-300',
                    ];
                    $statusColor = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                @endphp

                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <!-- Left: Booking Info -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">
                                            @if($booking->booking_type === 'custom_request' && $booking->touristRequest)
                                                {{ $booking->touristRequest->title }}
                                                <span class="ml-2 px-2 py-0.5 bg-cyan-100 text-cyan-800 text-xs font-semibold rounded">Custom</span>
                                            @elseif($booking->guidePlan)
                                                {{ $booking->guidePlan->title }}
                                            @else
                                                Booking #{{ $booking->booking_number }}
                                            @endif
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ $booking->booking_number }}</p>
                                    </div>
                                    <span class="ml-4 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500">Start Date</span>
                                        <p class="font-semibold text-gray-900">{{ $booking->start_date->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">End Date</span>
                                        <p class="font-semibold text-gray-900">{{ $booking->end_date->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Tourist</span>
                                        <p class="font-semibold text-gray-900">{{ $booking->tourist->full_name }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Travelers</span>
                                        <p class="font-semibold text-gray-900">
                                            {{ $booking->num_adults }} {{ Str::plural('Adult', $booking->num_adults) }}
                                            @if($booking->num_children > 0)
                                                , {{ $booking->num_children }} {{ Str::plural('Child', $booking->num_children) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Vehicle Assignment Status -->
                                @if($booking->start_date >= now() && !in_array($booking->status, ['cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin', 'completed', 'pending_payment', 'payment_failed']))
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        @if($booking->hasVehicleAssigned())
                                            <div class="flex items-center text-emerald-600">
                                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm font-medium">
                                                    Vehicle: {{ $booking->vehicleAssignment->vehicle_display_name }}
                                                    @if($booking->vehicleAssignment->is_temporary)
                                                        <span class="text-xs text-amber-600">(Temporary)</span>
                                                    @endif
                                                </span>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center {{ $booking->isWithinVehicleDeadline() ? 'text-red-600' : 'text-amber-600' }}">
                                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-sm font-medium">
                                                        Vehicle not assigned
                                                        @if($booking->isWithinVehicleDeadline())
                                                            - {{ $booking->days_until_start }} {{ Str::plural('day', $booking->days_until_start) }} left!
                                                        @endif
                                                    </span>
                                                </div>
                                                <a href="{{ route('guide.bookings.vehicle.assign', $booking) }}"
                                                   class="text-sm px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded transition-colors">
                                                    Assign Vehicle
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Right: Earnings & Actions -->
                            <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col items-end space-y-3">
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-emerald-600">${{ number_format($booking->guide_payout, 2) }}</p>
                                    <p class="text-sm text-gray-500">Your Earnings</p>
                                </div>

                                <a href="{{ route('guide.bookings.show', $booking->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    View Details
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
