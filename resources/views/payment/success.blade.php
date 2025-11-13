<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100">
                <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="mt-6 text-3xl font-bold text-gray-900">Payment Successful!</h1>
            <p class="mt-2 text-lg text-gray-600">Thank you for your booking</p>
        </div>

        <!-- Booking Details Card -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Booking Confirmed</h2>

            <div class="space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">Booking Number</span>
                    <span class="font-semibold text-gray-900">{{ $booking->booking_number }}</span>
                </div>

                <div class="flex justify-between py-3 border-b border-gray-200">
                    <span class="text-gray-600">Tour</span>
                    <span class="font-semibold text-gray-900">{{ $booking->guidePlan->title }}</span>
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
                    <span class="text-2xl font-bold text-green-600">${{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-3">What's Next?</h3>
            <ul class="space-y-2 text-blue-800">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>We've sent a confirmation email to {{ $booking->tourist->user->email }}</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Your guide will contact you shortly with trip details</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>You can download your booking agreement from your bookings page</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('bookings.show', $booking->id) }}"
               class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                View Booking Details
            </a>
            <a href="{{ route('tourist.dashboard') }}"
               class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
