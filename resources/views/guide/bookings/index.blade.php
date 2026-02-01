@extends('layouts.dashboard')

@section('page-title', 'My Bookings')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">My Bookings</h1>
    <p class="text-slate-500 mt-1">Manage and view all your tour bookings</p>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
    <div class="border-b border-slate-200">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            <a href="{{ route('guide.bookings') }}"
               class="border-b-2 {{ !request('date_filter') ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} py-4 px-1 text-sm font-semibold">
                All Bookings
            </a>
            <a href="{{ route('guide.bookings', ['date_filter' => 'upcoming']) }}"
               class="border-b-2 {{ request('date_filter') === 'upcoming' ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} py-4 px-1 text-sm font-semibold">
                Upcoming
            </a>
            <a href="{{ route('guide.bookings', ['date_filter' => 'ongoing']) }}"
               class="border-b-2 {{ request('date_filter') === 'ongoing' ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} py-4 px-1 text-sm font-semibold">
                Ongoing
            </a>
            <a href="{{ route('guide.bookings', ['date_filter' => 'past']) }}"
               class="border-b-2 {{ request('date_filter') === 'past' ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} py-4 px-1 text-sm font-semibold">
                Past
            </a>
        </nav>
    </div>
</div>

@if($bookings->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">No bookings found</h3>
        <p class="text-slate-600 mb-6">You don't have any bookings matching this filter.</p>
        <a href="{{ route('guide.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
            Back to Dashboard
        </a>
    </div>
@else
    <!-- Bookings List -->
    <div class="space-y-4">
        @foreach($bookings as $booking)
            @php
                $statusColors = [
                    'pending_payment' => 'bg-amber-100 text-amber-700',
                    'payment_failed' => 'bg-red-100 text-red-700',
                    'confirmed' => 'bg-emerald-100 text-emerald-700',
                    'ongoing' => 'bg-cyan-100 text-cyan-700',
                    'completed' => 'bg-slate-100 text-slate-700',
                    'cancelled_by_tourist' => 'bg-red-100 text-red-700',
                    'cancelled_by_guide' => 'bg-red-100 text-red-700',
                    'cancelled_by_admin' => 'bg-red-100 text-red-700',
                ];
                $statusColor = $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700';
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-amber-200 transition-all duration-300">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <!-- Left: Booking Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900">
                                        @if($booking->booking_type === 'custom_request' && $booking->touristRequest)
                                            {{ $booking->touristRequest->title }}
                                            <span class="ml-2 px-2 py-0.5 bg-cyan-100 text-cyan-700 text-xs font-semibold rounded-lg">Custom</span>
                                        @elseif($booking->guidePlan)
                                            {{ $booking->guidePlan->title }}
                                        @else
                                            Booking #{{ $booking->booking_number }}
                                        @endif
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1">{{ $booking->booking_number }}</p>
                                </div>
                                <span class="ml-4 px-3 py-1 rounded-lg text-xs font-semibold {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div class="bg-slate-50 rounded-xl p-3">
                                    <span class="text-slate-500 text-xs">Start Date</span>
                                    <p class="font-semibold text-slate-900">{{ $booking->start_date->format('M d, Y') }}</p>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-3">
                                    <span class="text-slate-500 text-xs">End Date</span>
                                    <p class="font-semibold text-slate-900">{{ $booking->end_date->format('M d, Y') }}</p>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-3">
                                    <span class="text-slate-500 text-xs">Tourist</span>
                                    <p class="font-semibold text-slate-900">{{ $booking->tourist->full_name }}</p>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-3">
                                    <span class="text-slate-500 text-xs">Travelers</span>
                                    <p class="font-semibold text-slate-900">
                                        {{ $booking->num_adults }} {{ Str::plural('Adult', $booking->num_adults) }}
                                        @if($booking->num_children > 0)
                                            , {{ $booking->num_children }} {{ Str::plural('Child', $booking->num_children) }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Vehicle Assignment Status -->
                            @if($booking->start_date >= now() && !in_array($booking->status, ['cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin', 'completed', 'pending_payment', 'payment_failed']))
                                <div class="mt-4 pt-4 border-t border-slate-100">
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
                                               class="text-sm px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
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
                                <p class="text-2xl font-bold text-amber-600">${{ number_format($booking->guide_payout, 2) }}</p>
                                <p class="text-sm text-slate-500">Your Earnings</p>
                            </div>

                            <a href="{{ route('guide.bookings.show', $booking->id) }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                                View Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
@endsection
