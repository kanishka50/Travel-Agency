@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Guide Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back, {{ $guide->full_name }}!</p>
    </div>

    <!-- Earnings & Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 rounded-lg shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-100 text-sm font-semibold">Total Bookings</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalBookings }}</p>
                </div>
                <svg class="w-12 h-12 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-amber-600 p-6 rounded-lg shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-100 text-sm font-semibold">Pending Payment</p>
                    <p class="text-3xl font-bold mt-2">{{ $pendingPayment }}</p>
                </div>
                <svg class="w-12 h-12 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-teal-500 to-teal-600 p-6 rounded-lg shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-100 text-sm font-semibold">Total Earnings</p>
                    <p class="text-3xl font-bold mt-2">${{ number_format($totalEarnings, 0) }}</p>
                </div>
                <svg class="w-12 h-12 text-teal-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 p-6 rounded-lg shadow-lg text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-cyan-100 text-sm font-semibold">This Month</p>
                    <p class="text-3xl font-bold mt-2">${{ number_format($thisMonthEarnings, 0) }}</p>
                </div>
                <svg class="w-12 h-12 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Calendar Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Booking Calendar</h2>
                <div id="calendar"></div>
            </div>

            <!-- Upcoming Bookings -->
            <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Upcoming Bookings</h2>
                    <a href="{{ route('guide.bookings') }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-semibold">View All</a>
                </div>

                @if($upcomingBookings->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-2 text-gray-500">No upcoming bookings</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingBookings as $booking)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">
                                            @if($booking->booking_type === 'custom_request' && $booking->touristRequest)
                                                {{ $booking->touristRequest->title }}
                                                <span class="ml-2 px-2 py-0.5 bg-cyan-100 text-cyan-800 text-xs font-semibold rounded">Custom</span>
                                            @elseif($booking->guidePlan)
                                                {{ $booking->guidePlan->title }}
                                            @else
                                                Booking #{{ $booking->booking_number }}
                                            @endif
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}
                                        </p>
                                        <div class="flex items-center mt-2 space-x-4 text-sm text-gray-500">
                                            <span>{{ $booking->num_adults }} {{ Str::plural('Adult', $booking->num_adults) }}</span>
                                            @if($booking->num_children > 0)
                                                <span>{{ $booking->num_children }} {{ Str::plural('Child', $booking->num_children) }}</span>
                                            @endif
                                            <span class="font-semibold text-emerald-600">${{ number_format($booking->guide_payout, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        @php
                                            $statusColors = [
                                                'pending_payment' => 'bg-amber-100 text-amber-800',
                                                'confirmed' => 'bg-emerald-100 text-emerald-800',
                                            ];
                                            $statusColor = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="{{ route('guide.bookings.show', $booking->id) }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-semibold">View Details</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('guide.plans.create') }}" class="flex items-center p-3 rounded-lg hover:bg-emerald-50 transition-colors group">
                        <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="text-gray-700 font-medium group-hover:text-emerald-600">Create New Plan</span>
                    </a>
                    <a href="{{ route('guide.plans.index') }}" class="flex items-center p-3 rounded-lg hover:bg-emerald-50 transition-colors group">
                        <svg class="w-5 h-5 text-emerald-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="text-gray-700 font-medium group-hover:text-emerald-600">Manage Plans</span>
                    </a>
                    <a href="{{ route('guide.bookings') }}" class="flex items-center p-3 rounded-lg hover:bg-teal-50 transition-colors group">
                        <svg class="w-5 h-5 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-700 font-medium group-hover:text-teal-600">View All Bookings</span>
                    </a>
                    <a href="{{ route('guide.proposals.index') }}" class="flex items-center p-3 rounded-lg hover:bg-teal-50 transition-colors group">
                        <svg class="w-5 h-5 text-teal-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span class="text-gray-700 font-medium group-hover:text-teal-600">View Proposals</span>
                        @if(isset($pendingProposals) && $pendingProposals > 0)
                            <span class="ml-auto bg-teal-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingProposals }}</span>
                        @endif
                    </a>
                    <a href="{{ route('guide.requests.index') }}" class="flex items-center p-3 rounded-lg hover:bg-indigo-50 transition-colors group">
                        <svg class="w-5 h-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"/>
                        </svg>
                        <span class="text-gray-700 font-medium group-hover:text-indigo-600">Browse Tour Requests</span>
                        @if(isset($openTourRequests) && $openTourRequests > 0)
                            <span class="ml-auto bg-indigo-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $openTourRequests }}</span>
                        @endif
                    </a>
                    <a href="{{ route('guide.vehicles.index') }}" class="flex items-center p-3 rounded-lg hover:bg-cyan-50 transition-colors group">
                        <svg class="w-5 h-5 text-cyan-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span class="text-gray-700 font-medium group-hover:text-cyan-600">My Vehicles</span>
                    </a>
                </div>
            </div>

            <!-- My Recent Bids -->
            @if(isset($recentBids) && $recentBids->isNotEmpty())
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-indigo-900">My Recent Bids</h3>
                        <a href="{{ route('guide.requests.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">View All</a>
                    </div>
                    @foreach($recentBids as $bid)
                        <div class="bg-white rounded-lg p-4 mb-3 shadow-sm">
                            <h4 class="font-semibold text-gray-900 text-sm">{{ $bid->touristRequest->title ?? 'Tour Request' }}</h4>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm text-gray-600">${{ number_format($bid->total_price, 2) }}</span>
                                @php
                                    $bidStatusColors = [
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'accepted' => 'bg-emerald-100 text-emerald-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $bidStatusColor = $bidStatusColors[$bid->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $bidStatusColor }}">
                                    {{ ucfirst($bid->status) }}
                                </span>
                            </div>
                            <a href="{{ route('guide.requests.show', $bid->tourist_request_id) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold mt-2 inline-block">View Request →</a>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Ongoing Tours -->
            @if($ongoingBookings->isNotEmpty())
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-emerald-900 mb-3">Ongoing Tours</h3>
                    @foreach($ongoingBookings as $booking)
                        <div class="bg-white rounded-lg p-4 mb-3 shadow-sm">
                            <h4 class="font-semibold text-gray-900">
                                @if($booking->booking_type === 'custom_request' && $booking->touristRequest)
                                    {{ $booking->touristRequest->title }}
                                    <span class="ml-1 px-1.5 py-0.5 bg-cyan-100 text-cyan-800 text-xs font-semibold rounded">Custom</span>
                                @elseif($booking->guidePlan)
                                    {{ $booking->guidePlan->title }}
                                @else
                                    Booking #{{ $booking->booking_number }}
                                @endif
                            </h4>
                            <p class="text-sm text-gray-600 mt-1">Ends: {{ $booking->end_date->format('M d, Y') }}</p>
                            <a href="{{ route('guide.bookings.show', $booking->id) }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-semibold mt-2 inline-block">View Details →</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        events: @json($calendarBookings),
        eventClick: function(info) {
            window.location.href = '/guide/bookings/' + info.event.id;
        },
        height: 'auto'
    });
    calendar.render();
});
</script>
@endsection