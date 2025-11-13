<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - {{ $booking->booking_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Header with Booking Number and Status -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Booking Details</h1>
                    <p class="text-xl text-gray-600 mt-2">{{ $booking->booking_number }}</p>
                </div>
                <div class="text-right">
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
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold border-2 {{ $statusColor }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                    <p class="text-sm text-gray-500 mt-2">Booked on {{ $booking->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content (Left Column) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tour Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Tour Information</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900">{{ $booking->guidePlan->title }}</h3>
                            <p class="text-gray-600 mt-1">{{ $booking->guidePlan->description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <span class="text-sm text-gray-500">Start Date</span>
                                <p class="font-semibold text-gray-900">{{ $booking->start_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">End Date</span>
                                <p class="font-semibold text-gray-900">{{ $booking->end_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Duration</span>
                                <p class="font-semibold text-gray-900">{{ $booking->guidePlan->num_days }} {{ Str::plural('day', $booking->guidePlan->num_days) }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Location</span>
                                <p class="font-semibold text-gray-900">{{ $booking->guidePlan->location }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guide Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Guide</h2>
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($booking->guide->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-900">{{ $booking->guide->user->name }}</h3>

                            @if(in_array($booking->status, ['confirmed', 'ongoing', 'completed']))
                                {{-- Show contact details only after payment --}}
                                <p class="text-gray-600">{{ $booking->guide->user->email }}</p>
                                @if($booking->guide->phone)
                                    <p class="text-gray-600">{{ $booking->guide->phone }}</p>
                                @endif
                            @else
                                {{-- Hide contact details before payment --}}
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2">
                                    <p class="text-sm text-yellow-800">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Contact details will be revealed after payment
                                    </p>
                                </div>
                            @endif

                            @if($booking->guide->bio)
                                <p class="text-sm text-gray-600 mt-2">{{ Str::limit($booking->guide->bio, 150) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Traveler Details -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Traveler Details</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Adults</span>
                            <p class="font-semibold text-gray-900">{{ $booking->num_adults }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Children</span>
                            <p class="font-semibold text-gray-900">{{ $booking->num_children }}</p>
                        </div>
                    </div>

                    @if($booking->children_ages && count($booking->children_ages) > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <span class="text-sm text-gray-500">Children's Ages</span>
                            <p class="font-semibold text-gray-900">{{ implode(', ', $booking->children_ages) }} years</p>
                        </div>
                    @endif

                    @if($booking->tourist_notes)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <span class="text-sm text-gray-500 block mb-2">Special Requests / Notes</span>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $booking->tourist_notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Add-ons -->
                @if($booking->addons && $booking->addons->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Add-ons</h2>
                        <div class="space-y-3">
                            @foreach($booking->addons as $addon)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $addon->addon_name }}</p>
                                        <p class="text-sm text-gray-500">Quantity: {{ $addon->quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">${{ number_format($addon->total_price, 2) }}</p>
                                        <p class="text-sm text-gray-500">${{ number_format($addon->price_per_unit, 2) }} each</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar (Right Column) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Price Summary -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Price Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Base Price</span>
                            <span class="font-semibold text-gray-900">${{ number_format($booking->base_price, 2) }}</span>
                        </div>

                        @if($booking->addons_total > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Add-ons</span>
                                <span class="font-semibold text-gray-900">${{ number_format($booking->addons_total, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-sm pt-2 border-t border-gray-200">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold text-gray-900">${{ number_format($booking->subtotal, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Platform Fee (10%)</span>
                            <span class="text-gray-700">${{ number_format($booking->platform_fee, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-lg font-bold pt-3 border-t-2 border-gray-300">
                            <span class="text-gray-900">Total Amount</span>
                            <span class="text-blue-600">${{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status & Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Status</h2>

                    @if($booking->status === 'pending_payment')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-yellow-800 font-semibold mb-2">Payment Required</p>
                            <p class="text-xs text-yellow-700">Please complete payment to confirm your booking.</p>
                        </div>

                        <form action="{{ route('payment.checkout', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition-colors shadow-md hover:shadow-lg flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Proceed to Payment
                            </button>
                        </form>

                        <p class="text-xs text-center text-gray-500 mt-3">
                            <svg class="w-4 h-4 inline text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Secure payment via Stripe
                        </p>
                    @elseif($booking->status === 'payment_failed')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-red-800 font-semibold mb-1">Payment Failed</p>
                            <p class="text-xs text-red-700">Your payment was not successful. Please try again.</p>
                        </div>

                        <form action="{{ route('payment.checkout', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors shadow-md hover:shadow-lg flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Retry Payment
                            </button>
                        </form>

                        <p class="text-xs text-center text-gray-500 mt-3">
                            <svg class="w-4 h-4 inline text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Secure payment via Stripe
                        </p>
                    @elseif($booking->status === 'confirmed')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-green-800 font-semibold mb-1">Booking Confirmed</p>
                            <p class="text-xs text-green-700">Your tour is confirmed! Check your email for details.</p>
                        </div>
                    @elseif(str_starts_with($booking->status, 'cancelled_'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-sm text-red-800 font-semibold mb-1">Booking Cancelled</p>
                            <p class="text-xs text-red-700">This booking has been cancelled.</p>
                        </div>
                    @endif

                    @if($booking->payment_intent_id)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-xs text-gray-500">Payment ID</p>
                            <p class="text-sm font-mono text-gray-700 break-all">{{ $booking->payment_intent_id }}</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    <div class="space-y-2">
                        <a href="{{ route('bookings.index') }}" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-lg transition-colors">
                            View All Bookings
                        </a>
                        <a href="{{ route('plans.show', $booking->guidePlan->id) }}" class="block w-full text-center bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold py-2 rounded-lg transition-colors">
                            View Tour Details
                        </a>
                        @if(in_array($booking->status, ['pending_payment', 'confirmed']))
                            <button class="block w-full text-center bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2 rounded-lg transition-colors">
                                Cancel Booking
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Download Options -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Documents</h2>
                    <div class="space-y-2">
                        <a href="{{ route('bookings.download-agreement', $booking->id) }}" class="flex items-center justify-between w-full bg-gray-50 hover:bg-gray-100 p-3 rounded-lg transition-colors">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Booking Agreement</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </a>

                        @if($booking->status !== 'pending_payment')
                            <button class="flex items-center justify-between w-full bg-gray-50 hover:bg-gray-100 p-3 rounded-lg transition-colors" disabled>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-500">Receipt / Invoice</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </button>
                            <p class="text-xs text-gray-500 text-center">Coming soon</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Bookings -->
        <div class="mt-8 text-center">
            <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                ← Back to All Bookings
            </a>
        </div>
    </div>
</body>
</html>
