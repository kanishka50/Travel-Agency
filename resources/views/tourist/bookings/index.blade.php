@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
        <p class="text-gray-600 mt-2">View and manage all your tour bookings</p>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <a href="#" class="border-b-2 border-emerald-600 py-4 px-1 text-sm font-semibold text-emerald-600">
                    All Bookings
                </a>
                <a href="#" class="border-b-2 border-transparent py-4 px-1 text-sm font-semibold text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Upcoming
                </a>
                <a href="#" class="border-b-2 border-transparent py-4 px-1 text-sm font-semibold text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Past
                </a>
                <a href="#" class="border-b-2 border-transparent py-4 px-1 text-sm font-semibold text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Cancelled
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
            <h3 class="mt-4 text-xl font-semibold text-gray-900">No bookings yet</h3>
            <p class="mt-2 text-gray-600">Start exploring amazing tours and book your adventure!</p>
            <div class="mt-6">
                <a href="{{ route('tour-packages.index') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors">
                    Browse Tours
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    @else
        <!-- Bookings List -->
        <div class="space-y-4">
            @foreach($bookings as $booking)
                @php
                    $statusColors = [
                        'pending_payment' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'payment_failed' => 'bg-red-100 text-red-800 border-red-300',
                        'confirmed' => 'bg-green-100 text-green-800 border-green-300',
                        'ongoing' => 'bg-purple-100 text-purple-800 border-purple-300',
                        'completed' => 'bg-gray-100 text-gray-800 border-gray-300',
                        'cancelled_by_tourist' => 'bg-red-100 text-red-800 border-red-300',
                        'cancelled_by_guide' => 'bg-red-100 text-red-800 border-red-300',
                        'cancelled_by_admin' => 'bg-red-100 text-red-800 border-red-300',
                    ];
                    $statusColor = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';

                    $isPast = $booking->end_date->isPast();
                    $isUpcoming = $booking->start_date->isFuture();
                    $isOngoing = $booking->start_date->isPast() && $booking->end_date->isFuture();
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
                                                <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-semibold rounded">Custom</span>
                                            @elseif($booking->guidePlan)
                                                {{ $booking->guidePlan->title }}
                                            @else
                                                Tour Booking
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
                                        <span class="text-gray-500">Guide</span>
                                        <p class="font-semibold text-gray-900">{{ $booking->guide->full_name ?? $booking->guide->user->name }}</p>
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

                                <!-- Timeline Badge -->
                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    @if($isOngoing)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                            Ongoing
                                        </span>
                                    @elseif($isUpcoming)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                            Starts {{ $booking->start_date->diffForHumans() }}
                                        </span>
                                    @elseif($isPast)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            Completed {{ $booking->end_date->diffForHumans() }}
                                        </span>
                                    @endif

                                    @if(in_array($booking->status, ['confirmed', 'ongoing', 'completed']) && $booking->vehicleAssignment)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            {{ $booking->vehicleAssignment->vehicle_display_name }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Price & Actions -->
                            <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col items-end space-y-3">
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-gray-900">${{ number_format($booking->total_amount, 2) }}</p>
                                    <p class="text-sm text-gray-500">Total Amount</p>
                                </div>

                                <div class="flex space-x-2">
                                    <a href="{{ route('tourist.bookings.show', $booking->id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                        View Details
                                    </a>

                                    @if($booking->status === 'pending_payment')
                                        <form action="{{ route('payment.checkout', $booking->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                                Pay Now
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                @if($booking->status === 'pending_payment')
                                    <p class="text-xs text-yellow-600 font-semibold">Payment required to confirm</p>
                                @elseif($booking->status === 'confirmed')
                                    <p class="text-xs text-green-600 font-semibold">Confirmed and ready!</p>
                                @endif
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

    <!-- Browse More Tours CTA -->
    <div class="mt-8 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-lg shadow-lg p-8 text-center text-white">
        <h2 class="text-2xl font-bold mb-2">Ready for your next adventure?</h2>
        <p class="text-emerald-100 mb-6">Discover amazing tours curated by local guides</p>
        <a href="{{ route('tour-packages.index') }}"
           class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-100 text-emerald-600 font-semibold rounded-lg transition-colors shadow-md">
            Browse Available Tours
        </a>
    </div>
</div>
@endsection
