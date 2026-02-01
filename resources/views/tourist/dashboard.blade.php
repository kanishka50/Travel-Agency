@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Card -->
<div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 mb-8 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="relative">
        <h1 class="text-2xl font-bold text-white mb-2">Welcome back, {{ $tourist->full_name ?? 'Traveler' }}!</h1>
        <p class="text-amber-100">Ready to plan your next adventure?</p>
        <a href="{{ route('tour-packages.index') }}" class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-white text-amber-600 font-semibold rounded-xl hover:bg-amber-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Explore Tours
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
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $totalBookings ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-amber-50 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Requests -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Active Requests</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $activeRequests ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-orange-100 to-orange-50 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending Proposals -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Pending Proposals</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $pendingProposals ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Upcoming Trips -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Upcoming Trips</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $upcomingTrips ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Bookings -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-900">Recent Bookings</h2>
                <a href="{{ route('tourist.bookings.index') }}" class="text-amber-600 hover:text-amber-700 text-sm font-semibold flex items-center gap-1 transition-colors">
                    View All
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="p-6">
                @if(isset($recentBookings) && $recentBookings->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentBookings as $booking)
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-slate-900 truncate">
                                        {{ $booking->guidePlan->title ?? $booking->touristRequest->title ?? 'Booking #' . $booking->booking_number }}
                                    </h3>
                                    <p class="text-sm text-slate-500">{{ $booking->start_date->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    @php
                                        $statusColors = [
                                            'pending_payment' => 'bg-amber-100 text-amber-700',
                                            'confirmed' => 'bg-emerald-100 text-emerald-700',
                                            'completed' => 'bg-slate-100 text-slate-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
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
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">No bookings yet</p>
                        <p class="text-slate-400 text-sm mt-1">Start exploring tours to book your first trip!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="{{ route('tour-packages.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <span class="text-slate-700 font-medium group-hover:text-amber-600 transition-colors">Browse Tours</span>
                </a>

                <a href="{{ route('tourist.requests.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="text-slate-700 font-medium group-hover:text-amber-600 transition-colors">Create Request</span>
                </a>

                <a href="{{ route('tourist.bookings.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-slate-700 font-medium group-hover:text-amber-600 transition-colors">My Bookings</span>
                </a>

                <a href="{{ route('tourist.proposals.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-cyan-100 rounded-xl flex items-center justify-center group-hover:bg-cyan-200 transition-colors">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <span class="text-slate-700 font-medium group-hover:text-amber-600 transition-colors">View Proposals</span>
                </a>
            </div>
        </div>

        <!-- Active Requests -->
        @if(isset($activeRequestsList) && $activeRequestsList->count() > 0)
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border border-amber-100 p-6 mt-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Active Requests</h3>
            <div class="space-y-3">
                @foreach($activeRequestsList->take(3) as $request)
                    <a href="{{ route('tourist.requests.show', $request->id) }}" class="block bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-200">
                        <h4 class="font-semibold text-slate-900 text-sm truncate">{{ $request->title }}</h4>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-slate-500">{{ $request->bid_count ?? 0 }} bids</span>
                            <span class="text-xs text-amber-600 font-medium">View</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
