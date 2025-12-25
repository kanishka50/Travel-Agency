@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('tourist.requests.show', $touristRequest) }}" class="text-emerald-600 hover:text-emerald-700 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Request
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
        <p class="text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <ul class="list-disc list-inside text-red-700 text-sm">
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
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-2xl font-bold text-gray-900">Proposal from {{ $bid->guide->full_name }}</h1>
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm font-semibold rounded-full">
                                Bid #{{ $bid->bid_number }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">For: <strong>{{ $touristRequest->title }}</strong></p>
                        <p class="text-sm text-gray-500">Submitted {{ $bid->created_at->diffForHumans() }}</p>
                    </div>

                    <!-- Status Badge -->
                    @if($bid->status === 'pending')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">Pending Review</span>
                    @elseif($bid->status === 'accepted')
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">Accepted</span>
                    @elseif($bid->status === 'rejected')
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">Rejected</span>
                    @endif
                </div>

                <!-- Price Highlight -->
                <div class="p-4 bg-emerald-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 font-medium">Total Price</span>
                        <span class="text-3xl font-bold text-emerald-600">${{ number_format($bid->total_price, 2) }}</span>
                    </div>
                    @if($bid->estimated_days)
                    <p class="text-sm text-gray-600 mt-1">For {{ $bid->estimated_days }} days</p>
                    @endif
                </div>
            </div>

            <!-- Proposal Message -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Proposal Message</h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $bid->proposal_message }}</p>
            </div>

            <!-- Day by Day Plan -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Day-by-Day Itinerary</h2>
                <div class="prose max-w-none">
                    <pre class="whitespace-pre-line font-sans text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $bid->day_by_day_plan }}</pre>
                </div>
            </div>

            <!-- Price Breakdown -->
            @if($bid->price_breakdown)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Price Breakdown</h2>
                <pre class="whitespace-pre-line font-sans text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $bid->price_breakdown }}</pre>
            </div>
            @endif

            <!-- Destinations Covered -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Destinations Covered</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach(is_array($bid->destinations_covered) ? $bid->destinations_covered : json_decode($bid->destinations_covered, true) ?? [] as $destination)
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm font-medium rounded-full">{{ $destination }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Additional Details -->
            @if($bid->accommodation_details || $bid->transport_details || $bid->included_services || $bid->excluded_services)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Additional Details</h2>

                <div class="space-y-4">
                    @if($bid->accommodation_details)
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Accommodation</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $bid->accommodation_details }}</p>
                    </div>
                    @endif

                    @if($bid->transport_details)
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Transportation</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $bid->transport_details }}</p>
                    </div>
                    @endif

                    @if($bid->included_services)
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Included Services</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $bid->included_services }}</p>
                    </div>
                    @endif

                    @if($bid->excluded_services)
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-2">Excluded Services</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $bid->excluded_services }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Rejection Reason (if rejected) -->
            @if($bid->status === 'rejected' && $bid->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-red-900 mb-2">Rejection Reason</h2>
                <p class="text-red-700">{{ $bid->rejection_reason }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Action Buttons -->
            @if($bid->status === 'pending')
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6 space-y-4">
                <h3 class="font-semibold text-gray-900 mb-4">Actions</h3>

                <!-- Accept Button -->
                <form action="{{ route('tourist.bids.accept', [$touristRequest, $bid]) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to accept this proposal? All other pending proposals will be automatically rejected.')">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-semibold shadow-md hover:shadow-lg transition-all">
                        Accept Proposal
                    </button>
                </form>

                <!-- Reject Button -->
                <div x-data="{ showRejectForm: false }">
                    <button @click="showRejectForm = !showRejectForm" type="button"
                            class="w-full px-4 py-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-semibold transition-all">
                        Reject Proposal
                    </button>

                    <!-- Reject Form -->
                    <div x-show="showRejectForm" x-collapse class="mt-4 p-4 bg-red-50 rounded-lg">
                        <form action="{{ route('tourist.bids.reject', [$touristRequest, $bid]) }}" method="POST">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Rejection Reason <span class="text-red-500">*</span>
                            </label>
                            <textarea name="rejection_reason" rows="3" required
                                      class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-sm"
                                      placeholder="Please explain why you're rejecting this proposal..."></textarea>
                            <button type="submit" class="mt-3 w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                                Confirm Rejection
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-4">
                    <strong>Note:</strong> Accepting this proposal will automatically reject all other pending proposals for this request.
                </p>
            </div>
            @elseif($bid->status === 'accepted')
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 sticky top-6">
                <h3 class="font-semibold text-green-900 mb-2">Proposal Accepted</h3>

                @if($touristRequest->booking)
                    {{-- Booking exists - show link to booking --}}
                    <p class="text-sm text-green-700 mb-4">Your booking has been created! Proceed to complete the payment.</p>
                    <a href="{{ route('tourist.bookings.show', $touristRequest->booking->id) }}"
                       class="block w-full px-4 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-center font-semibold shadow-md hover:shadow-lg transition-all">
                        @if($touristRequest->booking->status === 'pending_payment')
                            Go to Payment
                        @else
                            View Booking
                        @endif
                    </a>
                    <p class="text-xs text-gray-600 mt-2 text-center">
                        Booking #{{ $touristRequest->booking->booking_number }}
                    </p>
                @else
                    {{-- Edge case: Old accepted bid without booking --}}
                    <p class="text-sm text-green-700">This proposal was accepted. Please contact support if you need assistance with your booking.</p>
                @endif
            </div>
            @elseif($bid->status === 'rejected')
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 sticky top-6">
                <h3 class="font-semibold text-red-900 mb-2">Proposal Rejected</h3>
                <p class="text-sm text-red-700">This proposal has been rejected and the guide has been notified.</p>
            </div>
            @endif

            <!-- Guide Info -->
            <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                <h3 class="font-semibold text-gray-900 mb-4">Guide Information</h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Guide Name</p>
                        <p class="font-medium text-gray-900">{{ $bid->guide->full_name }}</p>
                    </div>
                    @if($bid->guide->years_experience)
                    <div>
                        <p class="text-gray-500">Experience</p>
                        <p class="font-medium text-gray-900">{{ $bid->guide->years_experience }} years</p>
                    </div>
                    @endif
                    @if($bid->guide->average_rating)
                    <div>
                        <p class="text-gray-500">Rating</p>
                        <p class="font-medium text-gray-900">{{ number_format($bid->guide->average_rating, 1) }}/5.0</p>
                    </div>
                    @endif
                    @if($bid->guide->total_bookings)
                    <div>
                        <p class="text-gray-500">Completed Tours</p>
                        <p class="font-medium text-gray-900">{{ $bid->guide->total_bookings }} tours</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Request Summary -->
            <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                <h3 class="font-semibold text-gray-900 mb-4">Your Request</h3>

                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Budget Range</p>
                        <p class="font-medium text-gray-900">${{ number_format($touristRequest->budget_min) }} - ${{ number_format($touristRequest->budget_max) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Duration</p>
                        <p class="font-medium text-gray-900">{{ $touristRequest->duration_days }} days</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Start Date</p>
                        <p class="font-medium text-gray-900">{{ $touristRequest->start_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Total Proposals</p>
                        <p class="font-medium text-gray-900">{{ $touristRequest->bid_count }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
