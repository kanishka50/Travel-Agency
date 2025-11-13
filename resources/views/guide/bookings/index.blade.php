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
            <p class="text-gray-600 mt-2">Manage and view all your tour bookings</p>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <a href="{{ route('guide.bookings') }}"
                       class="border-b-2 {{ !request('date_filter') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                        All Bookings
                    </a>
                    <a href="{{ route('guide.bookings', ['date_filter' => 'upcoming']) }}"
                       class="border-b-2 {{ request('date_filter') === 'upcoming' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                        Upcoming
                    </a>
                    <a href="{{ route('guide.bookings', ['date_filter' => 'ongoing']) }}"
                       class="border-b-2 {{ request('date_filter') === 'ongoing' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                        Ongoing
                    </a>
                    <a href="{{ route('guide.bookings', ['date_filter' => 'past']) }}"
                       class="border-b-2 {{ request('date_filter') === 'past' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
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
                    <a href="{{ route('guide.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
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
                                </div>

                                <!-- Right: Earnings & Actions -->
                                <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col items-end space-y-3">
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-green-600">${{ number_format($booking->guide_payout, 2) }}</p>
                                        <p class="text-sm text-gray-500">Your Earnings</p>
                                    </div>

                                    <a href="{{ route('guide.bookings.show', $booking->id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
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
</body>
</html>
