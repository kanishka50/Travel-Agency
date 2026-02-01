@extends('layouts.dashboard')

@section('page-title', 'View Proposal')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('tourist.requests.show', $touristRequest) }}" class="inline-flex items-center text-slate-600 hover:text-amber-600 transition-colors group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Request
    </a>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
@endif

<!-- Validation Errors -->
@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-slate-900">Proposal from {{ $bid->guide->full_name }}</h1>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-semibold rounded-lg">
                            Bid #{{ $bid->bid_number }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500">For: <strong class="text-slate-700">{{ $touristRequest->title }}</strong></p>
                    <p class="text-sm text-slate-500">Submitted {{ $bid->created_at->diffForHumans() }}</p>
                </div>

                <!-- Status Badge -->
                @if($bid->status === 'pending')
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-sm font-semibold rounded-lg">Pending Review</span>
                @elseif($bid->status === 'accepted')
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-lg">Accepted</span>
                @elseif($bid->status === 'rejected')
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-lg">Rejected</span>
                @endif
            </div>

            <!-- Price Highlight -->
            <div class="p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-xl">
                <div class="flex items-center justify-between">
                    <span class="text-slate-700 font-medium">Total Price</span>
                    <span class="text-3xl font-bold text-amber-600">${{ number_format($bid->total_price, 2) }}</span>
                </div>
                @if($bid->estimated_days)
                    <p class="text-sm text-slate-600 mt-1">For {{ $bid->estimated_days }} days</p>
                @endif
            </div>
        </div>

        <!-- Proposal Message -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Proposal Message</h2>
            </div>
            <p class="text-slate-700 whitespace-pre-line">{{ $bid->proposal_message }}</p>
        </div>

        <!-- Day by Day Plan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Day-by-Day Itinerary</h2>
            </div>
            <pre class="whitespace-pre-line font-sans text-slate-700 bg-slate-50 p-4 rounded-xl">{{ $bid->day_by_day_plan }}</pre>
        </div>

        <!-- Price Breakdown -->
        @if($bid->price_breakdown)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Price Breakdown</h2>
            </div>
            <pre class="whitespace-pre-line font-sans text-slate-700 bg-slate-50 p-4 rounded-xl">{{ $bid->price_breakdown }}</pre>
        </div>
        @endif

        <!-- Destinations Covered -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Destinations Covered</h2>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach(is_array($bid->destinations_covered) ? $bid->destinations_covered : json_decode($bid->destinations_covered, true) ?? [] as $destination)
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-sm font-medium rounded-lg">{{ $destination }}</span>
                @endforeach
            </div>
        </div>

        <!-- Additional Details -->
        @if($bid->accommodation_details || $bid->transport_details || $bid->included_services || $bid->excluded_services)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Additional Details</h2>
            </div>

            <div class="space-y-4">
                @if($bid->accommodation_details)
                <div class="bg-slate-50 rounded-xl p-4">
                    <h3 class="font-semibold text-slate-900 mb-2">Accommodation</h3>
                    <p class="text-slate-700 whitespace-pre-line">{{ $bid->accommodation_details }}</p>
                </div>
                @endif

                @if($bid->transport_details)
                <div class="bg-slate-50 rounded-xl p-4">
                    <h3 class="font-semibold text-slate-900 mb-2">Transportation</h3>
                    <p class="text-slate-700 whitespace-pre-line">{{ $bid->transport_details }}</p>
                </div>
                @endif

                @if($bid->included_services)
                <div class="bg-slate-50 rounded-xl p-4">
                    <h3 class="font-semibold text-slate-900 mb-2">Included Services</h3>
                    <p class="text-slate-700 whitespace-pre-line">{{ $bid->included_services }}</p>
                </div>
                @endif

                @if($bid->excluded_services)
                <div class="bg-slate-50 rounded-xl p-4">
                    <h3 class="font-semibold text-slate-900 mb-2">Excluded Services</h3>
                    <p class="text-slate-700 whitespace-pre-line">{{ $bid->excluded_services }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Rejection Reason (if rejected) -->
        @if($bid->status === 'rejected' && $bid->rejection_reason)
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-red-900">Rejection Reason</h2>
            </div>
            <p class="text-red-700">{{ $bid->rejection_reason }}</p>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Action Buttons -->
        @if($bid->status === 'pending')
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-slate-900 mb-4">Actions</h3>

            <div class="space-y-4">
                <!-- Accept Button -->
                <form action="{{ route('tourist.bids.accept', [$touristRequest, $bid]) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to accept this proposal? All other pending proposals will be automatically rejected.')">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                        Accept Proposal
                    </button>
                </form>

                <!-- Reject Button -->
                <div x-data="{ showRejectForm: false }">
                    <button @click="showRejectForm = !showRejectForm" type="button"
                            class="w-full px-4 py-3 bg-red-100 text-red-700 rounded-xl font-semibold hover:bg-red-200 transition-colors">
                        Reject Proposal
                    </button>

                    <!-- Reject Form -->
                    <div x-show="showRejectForm" x-collapse class="mt-4 p-4 bg-red-50 rounded-xl">
                        <form action="{{ route('tourist.bids.reject', [$touristRequest, $bid]) }}" method="POST">
                            @csrf
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Rejection Reason *
                            </label>
                            <textarea name="rejection_reason" rows="3" required
                                      class="w-full rounded-xl border-slate-200 focus:border-red-500 focus:ring-red-500 text-sm"
                                      placeholder="Please explain why you're rejecting this proposal..."></textarea>
                            <button type="submit" class="mt-3 w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold transition-colors">
                                Confirm Rejection
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <p class="text-xs text-slate-500 mt-4">
                <strong>Note:</strong> Accepting this proposal will automatically reject all other pending proposals for this request.
            </p>
        </div>
        @elseif($bid->status === 'accepted')
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-emerald-900">Proposal Accepted</h3>
            </div>

            @if($touristRequest->booking)
                <p class="text-sm text-emerald-700 mb-4">Your booking has been created! Proceed to complete the payment.</p>
                <a href="{{ route('tourist.bookings.show', $touristRequest->booking->id) }}"
                   class="block w-full px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl text-center font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                    @if($touristRequest->booking->status === 'pending_payment')
                        Go to Payment
                    @else
                        View Booking
                    @endif
                </a>
                <p class="text-xs text-slate-600 mt-2 text-center">
                    Booking #{{ $touristRequest->booking->booking_number }}
                </p>
            @else
                <p class="text-sm text-emerald-700">This proposal was accepted. Please contact support if you need assistance with your booking.</p>
            @endif
        </div>
        @elseif($bid->status === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h3 class="font-bold text-red-900">Proposal Rejected</h3>
            </div>
            <p class="text-sm text-red-700">This proposal has been rejected and the guide has been notified.</p>
        </div>
        @endif

        <!-- Guide Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Guide Information</h3>
            </div>

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-slate-500">Guide Name</p>
                    <p class="font-semibold text-slate-900">{{ $bid->guide->full_name }}</p>
                </div>
                @if($bid->guide->years_experience)
                <div>
                    <p class="text-slate-500">Experience</p>
                    <p class="font-semibold text-slate-900">{{ $bid->guide->years_experience }} years</p>
                </div>
                @endif
                @if($bid->guide->average_rating)
                <div>
                    <p class="text-slate-500">Rating</p>
                    <p class="font-semibold text-slate-900">{{ number_format($bid->guide->average_rating, 1) }}/5.0</p>
                </div>
                @endif
                @if($bid->guide->total_bookings)
                <div>
                    <p class="text-slate-500">Completed Tours</p>
                    <p class="font-semibold text-slate-900">{{ $bid->guide->total_bookings }} tours</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Request Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Your Request</h3>
            </div>

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-slate-500">Budget Range</p>
                    <p class="font-bold text-amber-600">${{ number_format($touristRequest->budget_min) }} - ${{ number_format($touristRequest->budget_max) }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Duration</p>
                    <p class="font-semibold text-slate-900">{{ $touristRequest->duration_days }} days</p>
                </div>
                <div>
                    <p class="text-slate-500">Start Date</p>
                    <p class="font-semibold text-slate-900">{{ $touristRequest->start_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Total Proposals</p>
                    <p class="font-semibold text-slate-900">{{ $touristRequest->bid_count }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
