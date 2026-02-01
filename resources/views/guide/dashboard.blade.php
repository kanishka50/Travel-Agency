@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Card -->
<div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 mb-8 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="relative">
        <h1 class="text-2xl font-bold text-white mb-2">Welcome back, {{ $guide->full_name }}!</h1>
        <p class="text-amber-100">Manage your tours and connect with travelers.</p>
        <a href="{{ route('guide.plans.create') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Plan
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Bookings -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Total Bookings</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalBookings }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-amber-50 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-emerald-600 mt-3 font-medium">All time</p>
    </div>

    <!-- Pending Payment -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Pending Payment</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $pendingPayment }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-orange-50 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-amber-600 mt-3 font-medium">Awaiting confirmation</p>
    </div>

    <!-- Total Earnings -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Total Earnings</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">${{ number_format($totalEarnings, 0) }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-emerald-600 mt-3 font-medium">Lifetime</p>
    </div>

    <!-- This Month -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">This Month</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">${{ number_format($thisMonthEarnings, 0) }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
        </div>
        <p class="text-sm text-cyan-600 mt-3 font-medium">{{ now()->format('F Y') }}</p>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Calendar & Bookings -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Calendar -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Booking Calendar</h2>
            <div id="calendar" class="calendar-container"></div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-900">Upcoming Bookings</h2>
                <a href="{{ route('guide.bookings') }}" class="text-amber-600 hover:text-amber-700 text-sm font-semibold flex items-center gap-1 transition-colors">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="p-6">
                @if($upcomingBookings->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">No upcoming bookings</p>
                        <p class="text-slate-400 text-sm mt-1">New bookings will appear here</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingBookings as $booking)
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors group">
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-slate-900 truncate">
                                        @if($booking->booking_type === 'custom_request' && $booking->touristRequest)
                                            {{ $booking->touristRequest->title }}
                                        @elseif($booking->guidePlan)
                                            {{ $booking->guidePlan->title }}
                                        @else
                                            Booking #{{ $booking->booking_number }}
                                        @endif
                                    </h3>
                                    <p class="text-sm text-slate-500">{{ $booking->start_date->format('M d, Y') }} - {{ $booking->end_date->format('M d, Y') }}</p>
                                    <p class="text-sm font-semibold text-emerald-600 mt-1">${{ number_format($booking->guide_payout, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    @php
                                        $statusColors = [
                                            'pending_payment' => 'bg-amber-100 text-amber-700',
                                            'confirmed' => 'bg-emerald-100 text-emerald-700',
                                        ];
                                        $statusColor = $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $statusColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('guide.plans.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-slate-700 font-medium group-hover:text-amber-600 transition-colors">Create New Plan</span>
                </a>

                <a href="{{ route('guide.requests.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <span class="text-slate-700 font-medium group-hover:text-amber-600 transition-colors">Browse Requests</span>
                    @if(isset($openTourRequests) && $openTourRequests > 0)
                        <span class="ml-auto bg-indigo-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $openTourRequests }}</span>
                    @endif
                </a>

                <a href="{{ route('guide.proposals.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center group-hover:bg-cyan-200 transition-colors">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <span class="text-slate-700 font-medium group-hover:text-amber-600 transition-colors">View Proposals</span>
                    @if(isset($pendingProposals) && $pendingProposals > 0)
                        <span class="ml-auto bg-cyan-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingProposals }}</span>
                    @endif
                </a>

                <a href="{{ route('guide.vehicles.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <span class="text-slate-700 font-medium group-hover:text-amber-600 transition-colors">My Vehicles</span>
                </a>
            </div>
        </div>

        <!-- Recent Bids -->
        @if(isset($recentBids) && $recentBids->isNotEmpty())
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900">My Recent Bids</h3>
                    <a href="{{ route('guide.requests.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-semibold">View All</a>
                </div>
                <div class="space-y-3">
                    @foreach($recentBids as $bid)
                        <div class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-200">
                            <h4 class="font-semibold text-slate-900 text-sm truncate">{{ $bid->touristRequest->title ?? 'Tour Request' }}</h4>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-bold text-slate-700">${{ number_format($bid->total_price, 2) }}</span>
                                @php
                                    $bidStatusColors = [
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'accepted' => 'bg-emerald-100 text-emerald-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ];
                                    $bidStatusColor = $bidStatusColors[$bid->status] ?? 'bg-slate-100 text-slate-700';
                                @endphp
                                <span class="px-2 py-0.5 rounded-lg text-xs font-semibold {{ $bidStatusColor }}">
                                    {{ ucfirst($bid->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Ongoing Tours -->
        @if($ongoingBookings->isNotEmpty())
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border border-emerald-100 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Ongoing Tours</h3>
                <div class="space-y-3">
                    @foreach($ongoingBookings as $booking)
                        <div class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-200">
                            <h4 class="font-semibold text-slate-900 text-sm truncate">
                                @if($booking->booking_type === 'custom_request' && $booking->touristRequest)
                                    {{ $booking->touristRequest->title }}
                                @elseif($booking->guidePlan)
                                    {{ $booking->guidePlan->title }}
                                @else
                                    Booking #{{ $booking->booking_number }}
                                @endif
                            </h4>
                            <p class="text-sm text-slate-500 mt-1">Ends: {{ $booking->end_date->format('M d, Y') }}</p>
                            <a href="{{ route('guide.bookings.show', $booking->id) }}" class="text-emerald-600 hover:text-emerald-700 text-sm font-semibold mt-2 inline-block">View Details</a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Calendar Styling -->
<style>
    .calendar-container .fc {
        font-family: inherit;
    }
    .calendar-container .fc-toolbar-title {
        font-size: 1.125rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
    }
    .calendar-container .fc-button {
        background: linear-gradient(to right, #f59e0b, #f97316) !important;
        border: none !important;
        border-radius: 0.75rem !important;
        padding: 0.5rem 1rem !important;
        font-weight: 600 !important;
        text-transform: capitalize !important;
        box-shadow: 0 4px 6px -1px rgb(245 158 11 / 0.25) !important;
    }
    .calendar-container .fc-button:hover {
        background: linear-gradient(to right, #d97706, #ea580c) !important;
    }
    .calendar-container .fc-button-active {
        background: linear-gradient(to right, #d97706, #ea580c) !important;
    }
    .calendar-container .fc-day-today {
        background-color: #fef3c7 !important;
    }
    .calendar-container .fc-event {
        background: linear-gradient(to right, #f59e0b, #f97316) !important;
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 0.125rem 0.5rem !important;
        font-weight: 500 !important;
    }
    .calendar-container .fc-daygrid-day-number {
        color: #334155;
        font-weight: 500;
    }
    .calendar-container .fc-col-header-cell-cushion {
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
    }
</style>

@push('scripts')
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
@endpush
@endsection
