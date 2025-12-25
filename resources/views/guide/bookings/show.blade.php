@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
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
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <a href="{{ route('guide.bookings') }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-emerald-600 md:ml-2">My Bookings</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-emerald-600 md:ml-2">{{ $booking->booking_number }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header with Booking Number and Status -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Booking Details</h1>
                <p class="text-xl text-gray-600 mt-2">{{ $booking->booking_number }}</p>
            </div>
            <div class="text-right">
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
                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold border-2 {{ $statusColor }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                </span>
                <p class="text-sm text-gray-500 mt-2">Booked on {{ $booking->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left Column) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tour Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Tour Information</h2>
                <div class="space-y-4">
                    @if($booking->booking_type === 'custom_request' && $booking->touristRequest)
                        {{-- Custom Request Booking --}}
                        <div>
                            <span class="inline-block px-2 py-1 bg-cyan-100 text-cyan-800 text-xs font-semibold rounded mb-2">Custom Tour Request</span>
                            <h3 class="font-semibold text-lg text-gray-900">{{ $booking->touristRequest->title }}</h3>
                            <p class="text-gray-600 mt-1">{{ $booking->touristRequest->description }}</p>
                        </div>

                        @if($booking->acceptedBid && $booking->acceptedBid->day_by_day_plan)
                            <div class="pt-4 border-t border-gray-200">
                                <span class="text-sm text-gray-500 font-medium">Your Proposed Itinerary</span>
                                <div class="mt-2 bg-gray-50 p-4 rounded-lg">
                                    <pre class="whitespace-pre-wrap text-sm text-gray-700 font-sans">{{ $booking->acceptedBid->day_by_day_plan }}</pre>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <span class="text-sm text-gray-500">Start Date</span>
                                <p class="font-semibold text-gray-900">{{ $booking->start_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">End Date</span>
                                <p class="font-semibold text-gray-900">{{ $booking->end_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Duration</span>
                                <p class="font-semibold text-gray-900">{{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Destinations</span>
                                <p class="font-semibold text-gray-900">
                                    @if(is_array($booking->touristRequest->preferred_destinations))
                                        {{ implode(', ', $booking->touristRequest->preferred_destinations) }}
                                    @else
                                        {{ $booking->touristRequest->preferred_destinations }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @elseif($booking->guidePlan)
                        {{-- Standard Guide Plan Booking --}}
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900">{{ $booking->guidePlan->title }}</h3>
                            <p class="text-gray-600 mt-1">{{ $booking->guidePlan->description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <span class="text-sm text-gray-500">Start Date</span>
                                <p class="font-semibold text-gray-900">{{ $booking->start_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">End Date</span>
                                <p class="font-semibold text-gray-900">{{ $booking->end_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Duration</span>
                                <p class="font-semibold text-gray-900">{{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Location</span>
                                <p class="font-semibold text-gray-900">{{ $booking->guidePlan->destination }}</p>
                            </div>
                        </div>
                    @else
                        {{-- Fallback --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-500">Start Date</span>
                                <p class="font-semibold text-gray-900">{{ $booking->start_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">End Date</span>
                                <p class="font-semibold text-gray-900">{{ $booking->end_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tourist Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Tourist Information</h2>
                <div class="flex items-start space-x-4">
                    <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($booking->tourist->full_name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg text-gray-900">{{ $booking->tourist->full_name }}</h3>

                        @if(in_array($booking->status, ['confirmed', 'ongoing', 'completed']))
                            {{-- Show contact details only after payment --}}
                            <p class="text-gray-600">{{ $booking->tourist->user->email }}</p>
                            @if($booking->tourist->phone)
                                <p class="text-gray-600">{{ $booking->tourist->phone }}</p>
                            @endif
                            @if($booking->tourist->country)
                                <p class="text-gray-600">{{ $booking->tourist->country }}</p>
                            @endif
                        @else
                            {{-- Hide contact details before payment --}}
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mt-2">
                                <p class="text-sm text-amber-800">
                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Contact details will be revealed after payment
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Travelers Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Traveler Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Adults</span>
                        <p class="font-semibold text-gray-900">{{ $booking->num_adults }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Children</span>
                        <p class="font-semibold text-gray-900">{{ $booking->num_children }}</p>
                    </div>
                </div>

                @if($booking->num_children > 0 && $booking->children_ages)
                    <div class="mt-4">
                        <span class="text-sm text-gray-500">Children Ages</span>
                        <p class="font-semibold text-gray-900">{{ implode(', ', json_decode($booking->children_ages)) }} years old</p>
                    </div>
                @endif

                @if($booking->tourist_notes)
                    <div class="mt-4 p-4 bg-teal-50 border border-teal-200 rounded-lg">
                        <p class="text-sm font-semibold text-teal-900 mb-1">Special Requests</p>
                        <p class="text-sm text-teal-800">{{ $booking->tourist_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Add-ons -->
            @if($booking->addons && $booking->addons->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Add-ons</h2>
                    <div class="space-y-3">
                        @foreach($booking->addons as $addon)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $addon->addon_name }}</p>
                                    <p class="text-sm text-gray-500">Participants: {{ $addon->num_participants }}</p>
                                    @if($addon->addon_description)
                                        <p class="text-xs text-gray-400 mt-1">{{ Str::limit($addon->addon_description, 50) }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">${{ number_format($addon->total_price, 2) }}</p>
                                    <p class="text-sm text-gray-500">${{ number_format($addon->price_per_person, 2) }} per person</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Vehicle Assignment -->
            @if(!in_array($booking->status, ['cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin']))
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Vehicle Assignment</h2>
                        @if($booking->hasVehicleAssigned())
                            <a href="{{ route('guide.bookings.vehicle.view', $booking) }}" class="text-sm text-emerald-600 hover:text-emerald-800 font-medium">
                                View Details
                            </a>
                        @endif
                    </div>

                    @if($booking->hasVehicleAssigned())
                        @php
                            $assignment = $booking->vehicleAssignment;
                            $vehicleDetails = $assignment->vehicle_details;
                        @endphp
                        <div class="flex items-start space-x-4">
                            @if(!$assignment->is_temporary && $assignment->vehicle && $assignment->vehicle->photos->count() > 0)
                                <img src="{{ Storage::url($assignment->vehicle->photos->first()->photo_path) }}"
                                     alt="{{ $assignment->vehicle_display_name }}"
                                     class="w-24 h-20 object-cover rounded-lg">
                            @else
                                <div class="w-24 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <h3 class="font-semibold text-gray-900">{{ $assignment->vehicle_display_name }}</h3>
                                    @if($assignment->is_temporary)
                                        <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-800 text-xs font-semibold rounded">Temporary</span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-700 rounded">
                                        {{ ucfirst(str_replace('_', ' ', $vehicleDetails['vehicle_type'] ?? 'N/A')) }}
                                    </span>
                                    <span class="text-xs px-2 py-0.5 bg-teal-100 text-teal-700 rounded">
                                        {{ $assignment->seating_capacity }} seats
                                    </span>
                                    <span class="text-xs px-2 py-0.5 {{ ($vehicleDetails['has_ac'] ?? false) ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }} rounded">
                                        {{ ($vehicleDetails['has_ac'] ?? false) ? 'AC' : 'Non-AC' }}
                                    </span>
                                    <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-700 rounded">
                                        {{ $assignment->license_plate }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Assigned on {{ $assignment->assigned_at->format('M d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @else
                        @if($booking->needsVehicleAssignment())
                            <div class="bg-{{ $booking->isWithinVehicleDeadline() ? 'red' : 'amber' }}-50 border border-{{ $booking->isWithinVehicleDeadline() ? 'red' : 'amber' }}-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 {{ $booking->isWithinVehicleDeadline() ? 'text-red-600' : 'text-amber-600' }} mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="font-semibold text-{{ $booking->isWithinVehicleDeadline() ? 'red' : 'amber' }}-800">
                                            No vehicle assigned yet
                                            @if($booking->isWithinVehicleDeadline())
                                                - Only {{ $booking->days_until_start }} {{ Str::plural('day', $booking->days_until_start) }} left!
                                            @endif
                                        </p>
                                        <p class="text-sm text-{{ $booking->isWithinVehicleDeadline() ? 'red' : 'amber' }}-700 mt-1">
                                            Please assign a vehicle for this tour. Minimum capacity needed: {{ $booking->total_participants }} seats
                                        </p>
                                        <a href="{{ route('guide.bookings.vehicle.assign', $booking) }}"
                                           class="inline-block mt-3 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                            Assign Vehicle Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">Vehicle assignment is not required for this booking status.</p>
                        @endif
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar (Right Column) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Earnings Summary -->
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-lg p-6 text-white">
                <h2 class="text-lg font-semibold mb-4">Your Earnings</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-emerald-100">Subtotal</span>
                        <span class="font-semibold">${{ number_format($booking->subtotal, 2) }}</span>
                    </div>

                    <div class="flex justify-between text-sm">
                        <span class="text-emerald-100">Platform Fee (10%)</span>
                        <span class="font-semibold">-${{ number_format($booking->platform_fee, 2) }}</span>
                    </div>

                    <div class="flex justify-between text-lg font-bold pt-3 border-t-2 border-emerald-400">
                        <span>Your Payout</span>
                        <span>${{ number_format($booking->guide_payout, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Status</h2>

                @if($booking->status === 'pending_payment')
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <p class="text-sm text-amber-800 font-semibold mb-1">Pending Payment</p>
                        <p class="text-xs text-amber-700">Tourist hasn't completed payment yet.</p>
                    </div>
                @elseif($booking->status === 'confirmed')
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                        <p class="text-sm text-emerald-800 font-semibold mb-1">Payment Confirmed</p>
                        <p class="text-xs text-emerald-700">Tour is confirmed and ready!</p>
                    </div>
                @elseif($booking->status === 'ongoing')
                    <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                        <p class="text-sm text-cyan-800 font-semibold mb-1">Tour Ongoing</p>
                        <p class="text-xs text-cyan-700">Have a great tour!</p>
                    </div>
                @elseif($booking->status === 'completed')
                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                        <p class="text-sm text-teal-800 font-semibold mb-1">Tour Completed</p>
                        <p class="text-xs text-teal-700">Payment will be processed soon.</p>
                    </div>
                @elseif(str_starts_with($booking->status, 'cancelled_'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-red-800 font-semibold mb-1">Booking Cancelled</p>
                        <p class="text-xs text-red-700">This booking has been cancelled.</p>
                    </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('guide.bookings') }}" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg transition-colors">
                        View All Bookings
                    </a>
                    @if($booking->guidePlan)
                        <a href="{{ route('guide.plans.show', $booking->guidePlan->id) }}" class="block w-full text-center bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-semibold py-2 rounded-lg transition-colors">
                            View Tour Details
                        </a>
                    @elseif($booking->touristRequest)
                        <a href="{{ route('guide.requests.show', $booking->touristRequest->id) }}" class="block w-full text-center bg-cyan-100 hover:bg-cyan-200 text-cyan-700 font-semibold py-2 rounded-lg transition-colors">
                            View Original Request
                        </a>
                    @endif
                </div>
            </div>

            <!-- Download Options -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Documents</h2>
                <div class="space-y-2">
                    @if($booking->agreement_pdf_path)
                        <a href="{{ route('bookings.download-agreement', $booking->id) }}" class="flex items-center justify-between w-full bg-gray-50 hover:bg-gray-100 p-3 rounded-lg transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Booking Agreement</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Bookings -->
    <div class="mt-8 text-center">
        <a href="{{ route('guide.bookings') }}" class="text-emerald-600 hover:text-emerald-800 font-semibold">
            ← Back to All Bookings
        </a>
    </div>
</div>
@endsection
