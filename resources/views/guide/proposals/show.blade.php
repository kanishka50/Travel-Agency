@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('guide.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-emerald-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('guide.proposals.index') }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-emerald-600 md:ml-2">Tour Proposals</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-emerald-600 md:ml-2">{{ Str::limit($proposal->guidePlan->title, 25) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="ml-3 text-sm text-emerald-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="ml-3 text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @php
            $statusColors = [
                'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
                'accepted' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'rejected' => 'bg-red-100 text-red-800 border-red-300',
                'cancelled' => 'bg-gray-100 text-gray-800 border-gray-300',
            ];
            $statusColor = $statusColors[$proposal->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
        @endphp

        <!-- Status/Availability Warnings -->
        @if($proposal->status === 'pending')
            @if(!$datesAvailable)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div class="ml-3">
                            <h3 class="font-semibold text-red-900">Date Conflict</h3>
                            <p class="text-red-800 text-sm mt-1">You have an existing booking that overlaps with these dates. You cannot accept this proposal.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="ml-3">
                            <h3 class="font-semibold text-amber-900">Awaiting Your Response</h3>
                            <p class="text-amber-800 text-sm mt-1">Please review this proposal and accept or reject it.</p>
                        </div>
                    </div>
                </div>
            @endif
        @elseif($proposal->status === 'accepted')
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-emerald-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="font-semibold text-emerald-900">Proposal Accepted</h3>
                        <p class="text-emerald-800 text-sm mt-1">A booking has been created. Waiting for tourist to complete payment.</p>
                        @if($proposal->booking)
                            <a href="{{ route('guide.bookings.show', $proposal->booking->id) }}"
                               class="inline-flex items-center mt-3 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                View Booking
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $proposal->guidePlan->title }}</h1>
                        <p class="text-emerald-100 mt-1">Proposal from {{ $proposal->tourist->full_name }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusColor }}">
                        {{ ucfirst($proposal->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Tourist Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tourist Information</h3>
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 bg-emerald-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($proposal->tourist->full_name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-lg">{{ $proposal->tourist->full_name }}</h4>
                            <p class="text-gray-600">{{ $proposal->tourist->country }}</p>

                            @php
                                $paymentCompleted = $proposal->booking && in_array($proposal->booking->status, ['confirmed', 'ongoing', 'completed']);
                            @endphp

                            @if($paymentCompleted)
                                <!-- Show contact details after payment -->
                                <div class="mt-3 p-3 bg-emerald-50 rounded-lg">
                                    <p class="text-sm text-emerald-800 font-medium mb-2">Contact Details</p>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Email:</span> {{ $proposal->tourist->user->email }}
                                    </p>
                                    @if($proposal->tourist->phone)
                                        <p class="text-sm text-gray-700">
                                            <span class="font-medium">Phone:</span> {{ $proposal->tourist->phone }}
                                        </p>
                                    @endif
                                </div>
                            @else
                                <!-- Show message about contact details -->
                                <p class="text-sm text-gray-500 italic mt-2">
                                    Contact details will be visible after tourist completes payment.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Proposal Details -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Proposal Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Proposed Dates</span>
                            <p class="font-semibold text-gray-900">{{ $proposal->start_date->format('M d, Y') }} - {{ $proposal->end_date->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-500">({{ $proposal->duration_days }} days)</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Travelers</span>
                            <p class="font-semibold text-gray-900">
                                {{ $proposal->num_adults }} {{ Str::plural('Adult', $proposal->num_adults) }}
                                @if($proposal->num_children > 0)
                                    , {{ $proposal->num_children }} {{ Str::plural('Child', $proposal->num_children) }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Your Listed Price</span>
                            <p class="font-semibold text-gray-900">${{ number_format($proposal->guidePlan->price_per_adult, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Proposed Price</span>
                            <p class="font-semibold text-2xl {{ $proposal->isBelowMinimum() ? 'text-red-600' : 'text-emerald-600' }}">
                                ${{ number_format($proposal->proposed_price, 2) }}
                            </p>
                            @if($proposal->discount_percentage > 0)
                                <p class="text-sm text-gray-500">{{ $proposal->discount_percentage }}% below listed price</p>
                            @endif
                            @if($proposal->isBelowMinimum())
                                <p class="text-sm text-red-600 mt-1">Below your minimum (${{ number_format($proposal->guidePlan->min_proposal_price, 2) }})</p>
                            @endif
                        </div>
                    </div>

                    @if($proposal->modifications)
                        <div class="mt-6">
                            <span class="text-sm text-gray-500 font-semibold">Requested Modifications</span>
                            <div class="mt-2 bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $proposal->modifications }}</p>
                            </div>
                        </div>
                    @endif

                    @if($proposal->message)
                        <div class="mt-4">
                            <span class="text-sm text-gray-500 font-semibold">Message from Tourist</span>
                            <div class="mt-2 bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900">{{ $proposal->message }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Price Summary (if accepted) -->
                @if($proposal->status === 'accepted' && $proposal->booking)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Created</h3>
                        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Booking Number</span>
                                    <p class="font-semibold text-gray-900">{{ $proposal->booking->booking_number }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Status</span>
                                    <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $proposal->booking->status)) }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Your Payout</span>
                                    <p class="font-semibold text-emerald-600">${{ number_format($proposal->booking->guide_payout, 2) }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Total (incl. platform fee)</span>
                                    <p class="font-semibold text-gray-900">${{ number_format($proposal->booking->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions for Pending Proposals -->
                @if($proposal->status === 'pending')
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Response</h3>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Accept Button -->
                            @if($datesAvailable)
                                <form action="{{ route('guide.proposals.accept', $proposal->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Accept this proposal? A booking will be created for the tourist to pay.');"
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Accept Proposal
                                    </button>
                                </form>
                            @else
                                <div class="flex-1">
                                    <button disabled
                                            class="w-full bg-gray-400 cursor-not-allowed text-white font-semibold py-3 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Cannot Accept (Date Conflict)
                                    </button>
                                </div>
                            @endif

                            <!-- Reject Button -->
                            <button type="button"
                                    onclick="document.getElementById('reject-form').classList.toggle('hidden');"
                                    class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject Proposal
                            </button>
                        </div>

                        <!-- Rejection Form (Hidden by default) -->
                        <form id="reject-form" action="{{ route('guide.proposals.reject', $proposal->id) }}" method="POST" class="hidden mt-4">
                            @csrf
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <label for="rejection_reason" class="block text-sm font-medium text-red-800 mb-2">
                                    Reason for rejection (required)
                                </label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                                          placeholder="Please explain why you're rejecting this proposal..."
                                          class="w-full border border-red-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
                                @error('rejection_reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div class="mt-3 flex justify-end">
                                    <button type="submit"
                                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                                        Confirm Rejection
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Rejection Reason (if rejected) -->
                @if($proposal->status === 'rejected' && $proposal->rejection_reason)
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rejection Reason</h3>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800">{{ $proposal->rejection_reason }}</p>
                        </div>
                    </div>
                @endif

                <!-- View Tour Link -->
                <div class="border-t border-gray-200 pt-6">
                    <a href="{{ route('guide.plans.show', $proposal->guidePlan->id) }}"
                       class="text-emerald-600 hover:text-emerald-800 font-semibold">
                        View Tour Details →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
