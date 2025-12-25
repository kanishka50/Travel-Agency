@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ url('/') }}" class="text-gray-500 hover:text-emerald-600">Home</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('tour-packages.index') }}" class="text-gray-500 hover:text-emerald-600">Tour Packages</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Payment Successful</li>
            </ol>
        </nav>

        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-emerald-100">
                <svg class="h-16 w-16 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="mt-6 text-3xl font-bold text-gray-900">Payment Successful!</h1>
            <p class="mt-2 text-lg text-gray-600">Thank you for your booking</p>
        </div>

        <!-- Booking Details Card -->
        <div class="bg-white rounded-xl shadow-sm border p-8 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Booking Confirmed</h2>

            <div class="space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">Booking Number</span>
                    <span class="font-semibold text-gray-900">{{ $booking->booking_number }}</span>
                </div>

                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">Tour</span>
                    <span class="font-semibold text-gray-900">
                        @if($booking->guidePlan)
                            {{ $booking->guidePlan->title }}
                        @elseif($booking->touristRequest)
                            {{ $booking->touristRequest->title }} (Custom Tour)
                        @else
                            Tour Booking
                        @endif
                    </span>
                </div>

                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">Start Date</span>
                    <span class="font-semibold text-gray-900">{{ $booking->start_date->format('M d, Y') }}</span>
                </div>

                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">End Date</span>
                    <span class="font-semibold text-gray-900">{{ $booking->end_date->format('M d, Y') }}</span>
                </div>

                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">Total Amount</span>
                    <span class="text-2xl font-bold text-emerald-600">${{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-semibold text-emerald-900 mb-3">What's Next?</h3>
            <ul class="space-y-2 text-emerald-800">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>We've sent a confirmation email to {{ $booking->tourist->user->email }}</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Your guide will contact you shortly with trip details</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>You can download your booking agreement from your bookings page</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('tourist.bookings.show', $booking->id) }}"
               class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                View Booking Details
            </a>
            <a href="{{ route('tourist.dashboard') }}"
               class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-white border-2 border-gray-300 hover:border-emerald-500 text-gray-700 hover:text-emerald-600 font-semibold rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Go to Dashboard
            </a>
        </div>

        <!-- Continue Exploring -->
        <div class="mt-8 text-center">
            <p class="text-gray-600 mb-4">Want to explore more tours?</p>
            <a href="{{ route('tour-packages.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
                Browse Tour Packages
            </a>
        </div>
    </div>
</div>
@endsection
