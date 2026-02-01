@extends('layouts.dashboard')

@section('page-title', 'My Bookings')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">My Bookings</h1>
    <p class="text-slate-500 mt-1">View and manage all your tour bookings</p>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
    <div class="border-b border-slate-200">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            <a href="#" class="border-b-2 border-amber-500 py-4 px-1 text-sm font-semibold text-amber-600">
                All Bookings
            </a>
            <a href="#" class="border-b-2 border-transparent py-4 px-1 text-sm font-semibold text-slate-500 hover:text-slate-700 hover:border-slate-300">
                Upcoming
            </a>
            <a href="#" class="border-b-2 border-transparent py-4 px-1 text-sm font-semibold text-slate-500 hover:text-slate-700 hover:border-slate-300">
                Past
            </a>
            <a href="#" class="border-b-2 border-transparent py-4 px-1 text-sm font-semibold text-slate-500 hover:text-slate-700 hover:border-slate-300">
                Cancelled
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
        <h3 class="text-xl font-bold text-slate-900 mb-2">No bookings yet</h3>
        <p class="text-slate-600 max-w-md mx-auto mb-6">Start exploring amazing tours and book your adventure!</p>
        <a href="{{ route('tour-packages.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
            Browse Tours
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
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
                    'ongoing' => 'bg-purple-100 text-purple-700',
                    'completed' => 'bg-slate-100 text-slate-700',
                    'cancelled_by_tourist' => 'bg-red-100 text-red-700',
                    'cancelled_by_guide' => 'bg-red-100 text-red-700',
                    'cancelled_by_admin' => 'bg-red-100 text-red-700',
                ];
                $statusColor = $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700';

                $isPast = $booking->end_date->isPast();
                $isUpcoming = $booking->start_date->isFuture();
                $isOngoing = $booking->start_date->isPast() && $booking->end_date->isFuture();
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
                                            <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-semibold rounded-lg">Custom</span>
                                        @elseif($booking->guidePlan)
                                            {{ $booking->guidePlan->title }}
                                        @else
                                            Tour Booking
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
                                    <span class="text-slate-500 text-xs">Guide</span>
                                    <p class="font-semibold text-slate-900">{{ $booking->guide->full_name ?? $booking->guide->user->name }}</p>
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

                            <!-- Timeline Badge -->
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                @if($isOngoing)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-purple-100 text-purple-700">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></span>
                                        Ongoing
                                    </span>
                                @elseif($isUpcoming)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Starts {{ $booking->start_date->diffForHumans() }}
                                    </span>
                                @elseif($isPast)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                        Completed {{ $booking->end_date->diffForHumans() }}
                                    </span>
                                @endif

                                @if(in_array($booking->status, ['confirmed', 'ongoing', 'completed']) && $booking->vehicleAssignment)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-semibold bg-cyan-100 text-cyan-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                        {{ $booking->vehicleAssignment->vehicle_display_name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Price & Actions -->
                        <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col items-end space-y-3">
                            <div class="text-right">
                                <p class="text-2xl font-bold text-slate-900">${{ number_format($booking->total_amount, 2) }}</p>
                                <p class="text-sm text-slate-500">Total Amount</p>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('tourist.bookings.show', $booking->id) }}"
                                   class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                                    View Details
                                </a>

                                @if($booking->status === 'pending_payment')
                                    <form action="{{ route('payment.checkout', $booking->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors">
                                            Pay Now
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @if($booking->status === 'pending_payment')
                                <p class="text-xs text-amber-600 font-semibold">Payment required to confirm</p>
                            @elseif($booking->status === 'confirmed')
                                <p class="text-xs text-emerald-600 font-semibold">Confirmed and ready!</p>
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
<div class="mt-8 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl shadow-lg p-8 text-center text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="relative">
        <h2 class="text-2xl font-bold mb-2">Ready for your next adventure?</h2>
        <p class="text-amber-100 mb-6">Discover amazing tours curated by local guides</p>
        <a href="{{ route('tour-packages.index') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-amber-50 text-amber-600 font-semibold rounded-xl transition-colors shadow-lg">
            Browse Available Tours
        </a>
    </div>
</div>
@endsection
