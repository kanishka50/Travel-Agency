@extends('layouts.dashboard')

@section('page-title', 'Booking Details')

@section('content')
<!-- Header -->
<div class="mb-8">
    <a href="{{ route('guide.bookings') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium mb-4 group">
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Bookings
    </a>
</div>

<!-- Header Card with Booking Number and Status -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Booking Details</h1>
            <p class="text-lg text-slate-600 mt-1">{{ $booking->booking_number }}</p>
        </div>
        <div class="text-right">
            @php
                $statusColors = [
                    'pending_payment' => 'bg-amber-100 text-amber-700 border-amber-300',
                    'payment_failed' => 'bg-red-100 text-red-700 border-red-300',
                    'confirmed' => 'bg-emerald-100 text-emerald-700 border-emerald-300',
                    'ongoing' => 'bg-cyan-100 text-cyan-700 border-cyan-300',
                    'completed' => 'bg-slate-100 text-slate-700 border-slate-300',
                    'cancelled_by_tourist' => 'bg-red-100 text-red-700 border-red-300',
                    'cancelled_by_guide' => 'bg-red-100 text-red-700 border-red-300',
                    'cancelled_by_admin' => 'bg-red-100 text-red-700 border-red-300',
                ];
                $statusColor = $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700 border-slate-300';
            @endphp
            <span class="inline-block px-4 py-2 rounded-xl text-sm font-semibold border-2 {{ $statusColor }}">
                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
            </span>
            <p class="text-sm text-slate-500 mt-2">Booked on {{ $booking->created_at->format('M d, Y') }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content (Left Column) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Tour Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Tour Information</h2>
            </div>
            <div class="space-y-4">
                @if($booking->booking_type === 'custom_request' && $booking->touristRequest)
                    {{-- Custom Request Booking --}}
                    <div>
                        <span class="inline-block px-3 py-1 bg-cyan-100 text-cyan-700 text-xs font-semibold rounded-lg mb-2">Custom Tour Request</span>
                        <h3 class="font-bold text-lg text-slate-900">{{ $booking->touristRequest->title }}</h3>
                        <p class="text-slate-600 mt-2">{{ $booking->touristRequest->description }}</p>
                    </div>

                    @if($booking->acceptedBid && $booking->acceptedBid->day_by_day_plan)
                        <div class="pt-4 border-t border-slate-200">
                            <span class="text-sm text-slate-500 font-medium">Your Proposed Itinerary</span>
                            <div class="mt-2 bg-slate-50 p-4 rounded-xl">
                                <pre class="whitespace-pre-wrap text-sm text-slate-700 font-sans">{{ $booking->acceptedBid->day_by_day_plan }}</pre>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Start Date</span>
                            <p class="font-semibold text-slate-900">{{ $booking->start_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">End Date</span>
                            <p class="font-semibold text-slate-900">{{ $booking->end_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Duration</span>
                            <p class="font-semibold text-slate-900">{{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Destinations</span>
                            <p class="font-semibold text-slate-900">
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
                        <h3 class="font-bold text-lg text-slate-900">{{ $booking->guidePlan->title }}</h3>
                        <p class="text-slate-600 mt-2">{{ $booking->guidePlan->description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Start Date</span>
                            <p class="font-semibold text-slate-900">{{ $booking->start_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">End Date</span>
                            <p class="font-semibold text-slate-900">{{ $booking->end_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Duration</span>
                            <p class="font-semibold text-slate-900">{{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Location</span>
                            <p class="font-semibold text-slate-900">{{ $booking->guidePlan->destination }}</p>
                        </div>
                    </div>
                @else
                    {{-- Fallback --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Start Date</span>
                            <p class="font-semibold text-slate-900">{{ $booking->start_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">End Date</span>
                            <p class="font-semibold text-slate-900">{{ $booking->end_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tourist Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Tourist Information</h2>
            </div>
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                    {{ strtoupper(substr($booking->tourist->full_name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg text-slate-900">{{ $booking->tourist->full_name }}</h3>

                    @if(in_array($booking->status, ['confirmed', 'ongoing', 'completed']))
                        {{-- Show contact details only after payment --}}
                        <p class="text-slate-600">{{ $booking->tourist->user->email }}</p>
                        @if($booking->tourist->phone)
                            <p class="text-slate-600">{{ $booking->tourist->phone }}</p>
                        @endif
                        @if($booking->tourist->country)
                            <p class="text-slate-600">{{ $booking->tourist->country }}</p>
                        @endif
                    @else
                        {{-- Hide contact details before payment --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-3">
                            <p class="text-sm text-amber-800 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
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
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Traveler Details</h2>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Adults</span>
                    <p class="font-semibold text-slate-900 text-xl">{{ $booking->num_adults }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Children</span>
                    <p class="font-semibold text-slate-900 text-xl">{{ $booking->num_children }}</p>
                </div>
            </div>

            @if($booking->num_children > 0 && $booking->children_ages)
                <div class="mt-4 bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Children Ages</span>
                    <p class="font-semibold text-slate-900">{{ implode(', ', json_decode($booking->children_ages)) }} years old</p>
                </div>
            @endif

            @if($booking->tourist_notes)
                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <p class="text-sm font-semibold text-amber-800">Special Requests</p>
                    </div>
                    <p class="text-sm text-amber-700">{{ $booking->tourist_notes }}</p>
                </div>
            @endif
        </div>

        <!-- Add-ons -->
        @if($booking->addons && $booking->addons->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Add-ons</h2>
                </div>
                <div class="space-y-3">
                    @foreach($booking->addons as $addon)
                        <div class="flex justify-between items-center py-3 border-b border-slate-100 last:border-0">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $addon->addon_name }}</p>
                                <p class="text-sm text-slate-500">Participants: {{ $addon->num_participants }}</p>
                                @if($addon->addon_description)
                                    <p class="text-xs text-slate-400 mt-1">{{ Str::limit($addon->addon_description, 50) }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">${{ number_format($addon->total_price, 2) }}</p>
                                <p class="text-sm text-slate-500">${{ number_format($addon->price_per_person, 2) }} per person</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Vehicle Assignment -->
        @if(!in_array($booking->status, ['cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin']))
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900">Vehicle Assignment</h2>
                    </div>
                    @if($booking->hasVehicleAssigned())
                        <a href="{{ route('guide.bookings.vehicle.view', $booking) }}" class="text-sm text-amber-600 hover:text-amber-700 font-semibold">
                            View Details
                        </a>
                    @endif
                </div>

                @if($booking->hasVehicleAssigned())
                    @php
                        $assignment = $booking->vehicleAssignment;
                        $vehicleDetails = $assignment->vehicle_details;
                    @endphp
                    <div class="flex items-start gap-4">
                        @if(!$assignment->is_temporary && $assignment->vehicle && $assignment->vehicle->photos->count() > 0)
                            <img src="{{ Storage::url($assignment->vehicle->photos->first()->photo_path) }}"
                                 alt="{{ $assignment->vehicle_display_name }}"
                                 class="w-24 h-20 object-cover rounded-xl">
                        @else
                            <div class="w-24 h-20 bg-slate-100 rounded-xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-slate-900">{{ $assignment->vehicle_display_name }}</h3>
                                @if($assignment->is_temporary)
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg">Temporary</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="text-xs px-2 py-1 bg-slate-100 text-slate-700 rounded-lg">
                                    {{ ucfirst(str_replace('_', ' ', $vehicleDetails['vehicle_type'] ?? 'N/A')) }}
                                </span>
                                <span class="text-xs px-2 py-1 bg-cyan-100 text-cyan-700 rounded-lg">
                                    {{ $assignment->seating_capacity }} seats
                                </span>
                                <span class="text-xs px-2 py-1 {{ ($vehicleDetails['has_ac'] ?? false) ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }} rounded-lg">
                                    {{ ($vehicleDetails['has_ac'] ?? false) ? 'AC' : 'Non-AC' }}
                                </span>
                                <span class="text-xs px-2 py-1 bg-slate-100 text-slate-700 rounded-lg">
                                    {{ $assignment->license_plate }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-2">
                                Assigned on {{ $assignment->assigned_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                    </div>
                @else
                    @if($booking->needsVehicleAssignment())
                        <div class="bg-{{ $booking->isWithinVehicleDeadline() ? 'red' : 'amber' }}-50 border border-{{ $booking->isWithinVehicleDeadline() ? 'red' : 'amber' }}-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 {{ $booking->isWithinVehicleDeadline() ? 'text-red-600' : 'text-amber-600' }} mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
                                       class="inline-flex items-center gap-2 mt-3 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Assign Vehicle Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-500 text-sm">Vehicle assignment is not required for this booking status.</p>
                    @endif
                @endif
            </div>
        @endif
    </div>

    <!-- Sidebar (Right Column) -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Earnings Summary -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl shadow-lg p-6 text-white">
            <h2 class="text-lg font-bold mb-4">Your Earnings</h2>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-amber-100">Subtotal</span>
                    <span class="font-semibold">${{ number_format($booking->subtotal, 2) }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-amber-100">Platform Fee (10%)</span>
                    <span class="font-semibold">-${{ number_format($booking->platform_fee, 2) }}</span>
                </div>

                <div class="flex justify-between text-lg font-bold pt-3 border-t-2 border-amber-400">
                    <span>Your Payout</span>
                    <span>${{ number_format($booking->guide_payout, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Payment Status</h2>
            </div>

            @if($booking->status === 'pending_payment')
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="text-sm text-amber-800 font-semibold mb-1">Pending Payment</p>
                    <p class="text-xs text-amber-700">Tourist hasn't completed payment yet.</p>
                </div>
            @elseif($booking->status === 'confirmed')
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                    <p class="text-sm text-emerald-800 font-semibold mb-1">Payment Confirmed</p>
                    <p class="text-xs text-emerald-700">Tour is confirmed and ready!</p>
                </div>
            @elseif($booking->status === 'ongoing')
                <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-4">
                    <p class="text-sm text-cyan-800 font-semibold mb-1">Tour Ongoing</p>
                    <p class="text-xs text-cyan-700">Have a great tour!</p>
                </div>
            @elseif($booking->status === 'completed')
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                    <p class="text-sm text-emerald-800 font-semibold mb-1">Tour Completed</p>
                    <p class="text-xs text-emerald-700">Payment will be processed soon.</p>
                </div>
            @elseif(str_starts_with($booking->status, 'cancelled_'))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-sm text-red-800 font-semibold mb-1">Booking Cancelled</p>
                    <p class="text-xs text-red-700">This booking has been cancelled.</p>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('guide.bookings') }}" class="block w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 rounded-xl transition-colors">
                    View All Bookings
                </a>
                @if($booking->guidePlan)
                    <a href="{{ route('guide.plans.show', $booking->guidePlan->id) }}" class="block w-full text-center bg-amber-100 hover:bg-amber-200 text-amber-700 font-semibold py-3 rounded-xl transition-colors">
                        View Tour Details
                    </a>
                @elseif($booking->touristRequest)
                    <a href="{{ route('guide.requests.show', $booking->touristRequest->id) }}" class="block w-full text-center bg-cyan-100 hover:bg-cyan-200 text-cyan-700 font-semibold py-3 rounded-xl transition-colors">
                        View Original Request
                    </a>
                @endif
            </div>
        </div>

        <!-- Download Options -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-red-100 to-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Documents</h2>
            </div>
            <div class="space-y-3">
                @if($booking->agreement_pdf_path)
                    <a href="{{ route('bookings.download-agreement', $booking->id) }}" class="flex items-center justify-between w-full bg-slate-50 hover:bg-slate-100 p-4 rounded-xl transition-colors">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                            </svg>
                            <span class="text-sm font-semibold text-slate-700">Booking Agreement</span>
                        </div>
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                @else
                    <p class="text-sm text-slate-500 text-center py-4">No documents available</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
