<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal Details</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('tourist.proposals.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to My Proposals
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
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
                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                'accepted' => 'bg-green-100 text-green-800 border-green-300',
                'rejected' => 'bg-red-100 text-red-800 border-red-300',
                'cancelled' => 'bg-gray-100 text-gray-800 border-gray-300',
            ];
            $statusColor = $statusColors[$proposal->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
        @endphp

        <!-- Status Banner -->
        @if($proposal->status === 'accepted')
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="font-semibold text-green-900">Proposal Accepted!</h3>
                        <p class="text-green-800 text-sm mt-1">The guide has accepted your proposal. A booking has been created for you.</p>
                        @if($proposal->booking)
                            <a href="{{ route('bookings.show', $proposal->booking->id) }}"
                               class="inline-flex items-center mt-3 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                View Booking & Pay
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($proposal->status === 'rejected')
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="font-semibold text-red-900">Proposal Rejected</h3>
                        @if($proposal->rejection_reason)
                            <p class="text-red-800 text-sm mt-1"><strong>Reason:</strong> {{ $proposal->rejection_reason }}</p>
                        @endif
                        <p class="text-red-700 text-sm mt-2">You can submit a new proposal with different terms.</p>
                    </div>
                </div>
            </div>
        @elseif($proposal->status === 'pending')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="ml-3">
                        <h3 class="font-semibold text-yellow-900">Awaiting Guide's Response</h3>
                        <p class="text-yellow-800 text-sm mt-1">Your proposal is being reviewed by the guide. You'll be notified when they respond.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $proposal->guidePlan->title }}</h1>
                        <p class="text-blue-100 mt-1">Proposal submitted {{ $proposal->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusColor }}">
                        {{ ucfirst($proposal->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <!-- Tour Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tour Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Tour</span>
                            <p class="font-semibold text-gray-900">{{ $proposal->guidePlan->title }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Duration</span>
                            <p class="font-semibold text-gray-900">{{ $proposal->guidePlan->num_days }} days / {{ $proposal->guidePlan->num_nights }} nights</p>
                        </div>
                        <div class="md:col-span-2">
                            <span class="text-sm text-gray-500">Destinations</span>
                            <p class="font-semibold text-gray-900">
                                @if(is_array($proposal->guidePlan->destinations))
                                    {{ implode(', ', $proposal->guidePlan->destinations) }}
                                @else
                                    {{ $proposal->guidePlan->destinations }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Guide Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Guide Information</h3>
                    <div class="flex items-start space-x-4">
                        @if($proposal->guidePlan->guide->profile_photo)
                            <img src="{{ Storage::url($proposal->guidePlan->guide->profile_photo) }}"
                                 alt="{{ $proposal->guidePlan->guide->full_name }}"
                                 class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 text-xl font-bold">{{ substr($proposal->guidePlan->guide->full_name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-lg">{{ $proposal->guidePlan->guide->full_name }}</p>
                            @if($proposal->guidePlan->guide->regions_can_guide)
                                <p class="text-sm text-gray-600">
                                    {{ is_array($proposal->guidePlan->guide->regions_can_guide) ? implode(', ', $proposal->guidePlan->guide->regions_can_guide) : $proposal->guidePlan->guide->regions_can_guide }}
                                </p>
                            @endif

                            @php
                                $paymentCompleted = $proposal->booking && in_array($proposal->booking->status, ['confirmed', 'ongoing', 'completed']);
                            @endphp

                            @if($paymentCompleted)
                                <!-- Show contact details after payment -->
                                <div class="mt-3 p-3 bg-green-50 rounded-lg">
                                    <p class="text-sm text-green-800 font-medium mb-2">Contact Details</p>
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">Email:</span> {{ $proposal->guidePlan->guide->user->email }}
                                    </p>
                                    @if($proposal->guidePlan->guide->phone)
                                        <p class="text-sm text-gray-700">
                                            <span class="font-medium">Phone:</span> {{ $proposal->guidePlan->guide->phone }}
                                        </p>
                                    @endif
                                </div>
                            @else
                                <!-- Show message about contact details -->
                                <p class="text-sm text-gray-500 italic mt-2">
                                    Contact details will be visible after payment is completed.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Proposal Details -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Proposal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Proposed Dates</span>
                            <p class="font-semibold text-gray-900">{{ $proposal->start_date->format('M d, Y') }} - {{ $proposal->end_date->format('M d, Y') }}</p>
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
                            <span class="text-sm text-gray-500">Proposed Price</span>
                            <p class="font-semibold text-gray-900 text-xl">${{ number_format($proposal->proposed_price, 2) }}</p>
                            <p class="text-sm text-gray-500">
                                (Listed: ${{ number_format($proposal->guidePlan->price_per_adult, 2) }})
                                @if($proposal->discount_percentage > 0)
                                    <span class="text-green-600">{{ $proposal->discount_percentage }}% off</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($proposal->modifications)
                        <div class="mt-4">
                            <span class="text-sm text-gray-500">Requested Modifications</span>
                            <div class="mt-1 bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $proposal->modifications }}</p>
                            </div>
                        </div>
                    @endif

                    @if($proposal->message)
                        <div class="mt-4">
                            <span class="text-sm text-gray-500">Message to Guide</span>
                            <div class="mt-1 bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900">{{ $proposal->message }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                @if($proposal->status === 'pending')
                    <div class="border-t border-gray-200 pt-6">
                        <form action="{{ route('proposals.cancel', $proposal->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to cancel this proposal?');">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                                Cancel Proposal
                            </button>
                        </form>
                    </div>
                @endif

                <!-- View Tour -->
                <div class="border-t border-gray-200 pt-6">
                    <a href="{{ route('plans.show', $proposal->guidePlan->id) }}"
                       class="text-blue-600 hover:text-blue-800 font-semibold">
                        View Tour Details →
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
