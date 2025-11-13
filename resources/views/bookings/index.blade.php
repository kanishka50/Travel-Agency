<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

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
                    <a href="#" class="border-b-2 border-blue-600 py-4 px-1 text-sm font-semibold text-blue-600">
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
                    <a href="{{ route('plans.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
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
                                                {{ $booking->guidePlan->title }}
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">{{ $booking->booking_number }}</p>
                                        </div>
                                        <span class="ml-4 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                            {{ ucfirst($booking->status) }}
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
                                            <p class="font-semibold text-gray-900">{{ $booking->guide->user->name }}</p>
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
                                    <div class="mt-3">
                                        @if($isOngoing)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <circle cx="10" cy="10" r="8"/>
                                                </svg>
                                                Ongoing
                                            </span>
                                        @elseif($isUpcoming)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Starts {{ $booking->start_date->diffForHumans() }}
                                            </span>
                                        @elseif($isPast)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Completed {{ $booking->end_date->diffForHumans() }}
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
                                        <a href="{{ route('bookings.show', $booking->id) }}"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                            View Details
                                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>

                                        @if($booking->status === 'pending_payment')
                                            <button class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                                Pay Now
                                            </button>
                                        @endif
                                    </div>

                                    @if($booking->status === 'pending_payment')
                                        <p class="text-xs text-yellow-600 font-semibold">
                                            ⚠ Payment required to confirm
                                        </p>
                                    @elseif($booking->status === 'payment_failed')
                                        <p class="text-xs text-red-600 font-semibold">
                                            ✗ Payment failed
                                        </p>
                                    @elseif($booking->status === 'confirmed')
                                        <p class="text-xs text-green-600 font-semibold">
                                            ✓ Confirmed and ready!
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Quick Actions Bar -->
                            @if(in_array($booking->status, ['confirmed', 'in_progress']))
                                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <div class="flex space-x-4 text-sm">
                                        <button class="text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Message Guide
                                        </button>
                                        <a href="{{ route('bookings.download-agreement', $booking->id) }}" class="text-gray-600 hover:text-gray-800 font-semibold flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download Agreement
                                        </a>
                                    </div>

                                    @if($isUpcoming && in_array($booking->status, ['pending_payment', 'confirmed']))
                                        <button class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                            Cancel Booking
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif

        <!-- Summary Stats (Optional) -->
        @if($bookings->isNotEmpty())
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-sm text-gray-500">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $bookings->total() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-sm text-gray-500">Upcoming Tours</p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ $bookings->filter(fn($b) => $b->start_date->isFuture())->count() }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-sm text-gray-500">Completed Tours</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $bookings->filter(fn($b) => $b->end_date->isPast() && $b->status === 'completed')->count() }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <p class="text-sm text-gray-500">Total Spent</p>
                    <p class="text-2xl font-bold text-gray-900">
                        ${{ number_format($bookings->sum('total_amount'), 2) }}
                    </p>
                </div>
            </div>
        @endif

        <!-- Browse More Tours CTA -->
        <div class="mt-8 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-8 text-center text-white">
            <h2 class="text-2xl font-bold mb-2">Ready for your next adventure?</h2>
            <p class="text-blue-100 mb-6">Discover amazing tours curated by local guides</p>
            <a href="{{ route('plans.index') }}"
               class="inline-flex items-center px-6 py-3 bg-white hover:bg-gray-100 text-blue-600 font-semibold rounded-lg transition-colors shadow-md">
                Browse Available Tours
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</body>
</html>
