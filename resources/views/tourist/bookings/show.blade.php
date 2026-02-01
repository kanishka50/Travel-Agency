@extends('layouts.dashboard')

@section('page-title', 'Booking Details')

@section('content')
<!-- Back Button & Header -->
<div class="mb-8">
    <a href="{{ route('tourist.bookings.index') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium mb-4 group">
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Bookings
    </a>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Header Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Booking Details</h1>
                <p class="text-lg text-amber-600 font-semibold mt-1">{{ $booking->booking_number }}</p>
            </div>
            <div class="text-right">
                @php
                    $statusColors = [
                        'pending_payment' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'payment_failed' => 'bg-red-100 text-red-700 border-red-200',
                        'confirmed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'ongoing' => 'bg-purple-100 text-purple-700 border-purple-200',
                        'completed' => 'bg-slate-100 text-slate-700 border-slate-200',
                        'cancelled_by_tourist' => 'bg-red-100 text-red-700 border-red-200',
                        'cancelled_by_guide' => 'bg-red-100 text-red-700 border-red-200',
                        'cancelled_by_admin' => 'bg-red-100 text-red-700 border-red-200',
                    ];
                    $statusColor = $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                @endphp
                <span class="inline-block px-4 py-2 rounded-xl text-sm font-semibold border {{ $statusColor }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                </span>
                <p class="text-sm text-slate-500 mt-2">Booked on {{ $booking->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content (Left Column) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Tour Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Tour Information</h2>
            </div>

            <div class="space-y-4">
                @if($booking->booking_type === 'custom_request' && $booking->touristRequest)
                    {{-- Custom Request Booking --}}
                    <div>
                        <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-lg mb-2">Custom Tour Request</span>
                        <h3 class="font-bold text-lg text-slate-900">{{ $booking->touristRequest->title }}</h3>
                        <p class="text-slate-600 mt-1">{{ $booking->touristRequest->description }}</p>
                    </div>

                    @if($booking->acceptedBid && $booking->acceptedBid->day_by_day_plan)
                        <div class="pt-4 border-t border-slate-200">
                            <span class="text-sm text-slate-500 font-medium">Guide's Proposed Itinerary</span>
                            <div class="mt-2 bg-slate-50 p-4 rounded-xl">
                                <pre class="whitespace-pre-wrap text-sm text-slate-700 font-sans">{{ $booking->acceptedBid->day_by_day_plan }}</pre>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Start Date</span>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->start_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">End Date</span>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->end_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Duration</span>
                            @php $duration = $booking->start_date->diffInDays($booking->end_date) + 1; @endphp
                            <p class="font-semibold text-slate-900 mt-1">{{ $duration }} {{ Str::plural('day', $duration) }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Destinations</span>
                            <p class="font-semibold text-slate-900 mt-1">
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
                        <p class="text-slate-600 mt-1">{{ $booking->guidePlan->description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Start Date</span>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->start_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">End Date</span>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->end_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Duration</span>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->guidePlan->num_days }} {{ Str::plural('day', $booking->guidePlan->num_days) }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Location</span>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->guidePlan->location }}</p>
                        </div>
                    </div>
                @else
                    {{-- Fallback --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">Start Date</span>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->start_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <span class="text-sm text-slate-500">End Date</span>
                            <p class="font-semibold text-slate-900 mt-1">{{ $booking->end_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Guide Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Your Guide</h2>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-amber-500/25">
                    {{ strtoupper(substr($booking->guide->user->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg text-slate-900">{{ $booking->guide->user->name }}</h3>
                    <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg mt-1">{{ $booking->guide->guide_type_label }}</span>

                    @if(in_array($booking->status, ['confirmed', 'ongoing', 'completed']))
                        {{-- Show contact details only after payment --}}
                        <div class="mt-3 space-y-1">
                            <p class="text-slate-600 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $booking->guide->user->email }}
                            </p>
                            @if($booking->guide->phone)
                                <p class="text-slate-600 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $booking->guide->phone }}
                                </p>
                            @endif
                        </div>
                    @else
                        {{-- Hide contact details before payment --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mt-3">
                            <p class="text-sm text-amber-800 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                Contact details will be revealed after payment
                            </p>
                        </div>
                    @endif

                    @if($booking->guide->bio)
                        <p class="text-sm text-slate-600 mt-3">{{ Str::limit($booking->guide->bio, 150) }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Traveler Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Traveler Details</h2>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Adults</span>
                    <p class="font-semibold text-slate-900 text-xl mt-1">{{ $booking->num_adults }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Children</span>
                    <p class="font-semibold text-slate-900 text-xl mt-1">{{ $booking->num_children }}</p>
                </div>
            </div>

            @if($booking->children_ages && count($booking->children_ages) > 0)
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <span class="text-sm text-slate-500">Children's Ages</span>
                    <p class="font-semibold text-slate-900 mt-1">{{ implode(', ', $booking->children_ages) }} years</p>
                </div>
            @endif

            @if($booking->tourist_notes)
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <span class="text-sm text-slate-500 block mb-2">Special Requests / Notes</span>
                    <p class="text-slate-700 bg-slate-50 p-4 rounded-xl">{{ $booking->tourist_notes }}</p>
                </div>
            @endif
        </div>

        <!-- Add-ons -->
        @if($booking->addons && $booking->addons->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
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
                                <p class="font-bold text-slate-900">${{ number_format($addon->total_price, 2) }}</p>
                                <p class="text-sm text-slate-500">${{ number_format($addon->price_per_person, 2) }} per person</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Vehicle Information -->
        @if(in_array($booking->status, ['confirmed', 'ongoing', 'completed']))
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Tour Vehicle</h2>
                </div>

                @if($booking->vehicleAssignment)
                    @php
                        $assignment = $booking->vehicleAssignment;
                        $vehicleDetails = $assignment->vehicle_details;
                    @endphp
                    <div class="flex items-start space-x-4">
                        @if(!$assignment->is_temporary && $assignment->vehicle && $assignment->vehicle->photos->count() > 0)
                            <img src="{{ Storage::url($assignment->vehicle->photos->first()->photo_path) }}"
                                 alt="{{ $assignment->vehicle_display_name }}"
                                 class="w-28 h-24 object-cover rounded-xl shadow-sm">
                        @else
                            <div class="w-28 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/>
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-slate-900">{{ $assignment->vehicle_display_name }}</h3>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    {{ ucfirst(str_replace('_', ' ', $vehicleDetails['vehicle_type'] ?? 'Vehicle')) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $assignment->seating_capacity }} Seats
                                </span>
                                @if($vehicleDetails['has_ac'] ?? false)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-cyan-100 text-cyan-700">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Air Conditioned
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600">
                                        Non-AC
                                    </span>
                                @endif
                            </div>

                            @if(!empty($vehicleDetails['description']))
                                <p class="text-sm text-slate-600 mt-3">{{ $vehicleDetails['description'] }}</p>
                            @endif

                            <div class="mt-3 pt-3 border-t border-slate-100">
                                <p class="text-xs text-slate-500">
                                    <span class="font-medium">License Plate:</span> {{ $assignment->license_plate }}
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-cyan-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-cyan-900">Vehicle Being Arranged</p>
                                <p class="text-sm text-cyan-700 mt-1">
                                    Your guide is arranging a suitable vehicle for your tour. Vehicle details will be displayed here once confirmed.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Sidebar (Right Column) -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Price Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Price Summary</h2>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Base Price</span>
                    <span class="font-semibold text-slate-900">${{ number_format($booking->base_price, 2) }}</span>
                </div>

                @if($booking->addons_total > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">Add-ons</span>
                        <span class="font-semibold text-slate-900">${{ number_format($booking->addons_total, 2) }}</span>
                    </div>
                @endif

                <div class="flex justify-between text-sm pt-2 border-t border-slate-200">
                    <span class="text-slate-600">Subtotal</span>
                    <span class="font-semibold text-slate-900">${{ number_format($booking->subtotal, 2) }}</span>
                </div>

                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Platform Fee (10%)</span>
                    <span class="text-slate-600">${{ number_format($booking->platform_fee, 2) }}</span>
                </div>

                <div class="flex justify-between text-lg font-bold pt-3 border-t-2 border-slate-300">
                    <span class="text-slate-900">Total Amount</span>
                    <span class="text-amber-600">${{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Status & Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Payment Status</h2>
            </div>

            @if($booking->status === 'pending_payment')
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-amber-800 font-semibold mb-1">Payment Required</p>
                    <p class="text-xs text-amber-700">Please complete payment to confirm your booking.</p>
                </div>

                <form action="{{ route('payment.checkout', $booking->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Proceed to Payment
                    </button>
                </form>

                <p class="text-xs text-center text-slate-500 mt-3 flex items-center justify-center gap-1">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Secure payment via Stripe
                </p>
            @elseif($booking->status === 'payment_failed')
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-red-800 font-semibold mb-1">Payment Failed</p>
                    <p class="text-xs text-red-700">Your payment was not successful. Please try again.</p>
                </div>

                <form action="{{ route('payment.checkout', $booking->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-3 rounded-xl transition-all shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Retry Payment
                    </button>
                </form>
            @elseif($booking->status === 'confirmed')
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                    <p class="text-sm text-emerald-800 font-semibold mb-1">Booking Confirmed</p>
                    <p class="text-xs text-emerald-700">Your tour is confirmed! Check your email for details.</p>
                </div>
            @elseif(str_starts_with($booking->status, 'cancelled_'))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-sm text-red-800 font-semibold mb-1">Booking Cancelled</p>
                    <p class="text-xs text-red-700">This booking has been cancelled.</p>
                </div>
            @endif

            @if($booking->payment_intent_id)
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <p class="text-xs text-slate-500">Payment ID</p>
                    <p class="text-sm font-mono text-slate-700 break-all">{{ $booking->payment_intent_id }}</p>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Quick Actions</h2>
            </div>

            <div class="space-y-2">
                <a href="{{ route('tourist.bookings.index') }}" class="block w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 rounded-xl transition-colors">
                    View All Bookings
                </a>
                @if($booking->guidePlan)
                    <a href="{{ route('tour-packages.show', $booking->guidePlan->id) }}" class="block w-full text-center bg-amber-100 hover:bg-amber-200 text-amber-700 font-semibold py-2.5 rounded-xl transition-colors">
                        View Tour Details
                    </a>
                @elseif($booking->touristRequest)
                    <a href="{{ route('tourist.requests.show', $booking->touristRequest->id) }}" class="block w-full text-center bg-purple-100 hover:bg-purple-200 text-purple-700 font-semibold py-2.5 rounded-xl transition-colors">
                        View Original Request
                    </a>
                @endif
                @if(in_array($booking->status, ['pending_payment', 'confirmed']))
                    <button class="block w-full text-center bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 rounded-xl transition-colors">
                        Cancel Booking
                    </button>
                @endif
            </div>
        </div>

        <!-- Download Options -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-red-100 to-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Documents</h2>
            </div>

            <div class="space-y-2">
                <a href="{{ route('bookings.download-agreement', $booking->id) }}" class="flex items-center justify-between w-full bg-slate-50 hover:bg-slate-100 p-3 rounded-xl transition-colors">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                        </svg>
                        <span class="text-sm font-semibold text-slate-700">Booking Agreement</span>
                    </div>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </a>

                @if($booking->status !== 'pending_payment')
                    <button class="flex items-center justify-between w-full bg-slate-50 p-3 rounded-xl opacity-50 cursor-not-allowed" disabled>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-semibold text-slate-500">Receipt / Invoice</span>
                        </div>
                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </button>
                    <p class="text-xs text-slate-500 text-center">Coming soon</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
