<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Assignment - {{ $booking->booking_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('guide.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Booking
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Vehicle Assignment</h1>
            <p class="text-gray-600 mt-2">Booking: {{ $booking->booking_number }}</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @php
            $assignment = $booking->vehicleAssignment;
            $vehicleDetails = $assignment->vehicle_details;
        @endphp

        <!-- Vehicle Details Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-white">Assigned Vehicle</h2>
                    @if($assignment->is_temporary)
                        <span class="px-3 py-1 bg-yellow-400 text-yellow-900 text-sm font-semibold rounded-full">Temporary Vehicle</span>
                    @else
                        <span class="px-3 py-1 bg-green-400 text-green-900 text-sm font-semibold rounded-full">Saved Vehicle</span>
                    @endif
                </div>
            </div>

            <div class="p-6">
                <!-- Vehicle Photo(s) -->
                @if(!$assignment->is_temporary && $assignment->vehicle && $assignment->vehicle->photos->count() > 0)
                    <div class="mb-6">
                        <div class="grid grid-cols-3 gap-4">
                            @foreach($assignment->vehicle->photos->take(3) as $photo)
                                <img src="{{ Storage::url($photo->photo_path) }}"
                                     alt="Vehicle photo"
                                     class="w-full h-32 object-cover rounded-lg">
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Vehicle Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Vehicle</dt>
                                <dd class="font-semibold text-gray-900">{{ $assignment->vehicle_display_name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Type</dt>
                                <dd class="font-semibold text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $vehicleDetails['vehicle_type'] ?? 'N/A')) }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">License Plate</dt>
                                <dd class="font-semibold text-gray-900">{{ $assignment->license_plate }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Seating Capacity</dt>
                                <dd class="font-semibold text-gray-900">{{ $assignment->seating_capacity }} seats</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Air Conditioning</dt>
                                <dd>
                                    @if($vehicleDetails['has_ac'] ?? false)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded">Yes</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded">No</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Assignment Details</h3>
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Assigned On</dt>
                                <dd class="font-semibold text-gray-900">{{ $assignment->assigned_at->format('M d, Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Assigned By</dt>
                                <dd class="font-semibold text-gray-900">
                                    @if($assignment->assignedByUser)
                                        {{ $assignment->assignedByUser->name }}
                                        @if($assignment->wasAssignedByAdmin())
                                            <span class="text-xs text-purple-600">(Admin)</span>
                                        @endif
                                    @else
                                        Unknown
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Tour Date</dt>
                                <dd class="font-semibold text-gray-900">{{ $booking->start_date->format('M d, Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Travelers</dt>
                                <dd class="font-semibold text-gray-900">
                                    {{ $booking->num_adults + $booking->num_children }}
                                    ({{ $booking->num_adults }} {{ Str::plural('adult', $booking->num_adults) }}
                                    @if($booking->num_children > 0)
                                        , {{ $booking->num_children }} {{ Str::plural('child', $booking->num_children) }}
                                    @endif)
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if(!empty($vehicleDetails['description']))
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Vehicle Description</h3>
                        <p class="text-gray-600">{{ $vehicleDetails['description'] }}</p>
                    </div>
                @endif

                <!-- Actions -->
                @if($booking->start_date > now())
                    <div class="mt-6 pt-6 border-t border-gray-200 flex justify-end space-x-4">
                        <form action="{{ route('guide.bookings.vehicle.remove', $booking) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to remove this vehicle assignment? You will need to assign a new vehicle.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition-colors">
                                Remove Assignment
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Summary</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Tour</span>
                    <p class="font-semibold text-gray-900">
                        @if($booking->guidePlan)
                            {{ $booking->guidePlan->title }}
                        @elseif($booking->touristRequest)
                            {{ $booking->touristRequest->title }}
                        @else
                            #{{ $booking->booking_number }}
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-gray-500">Tourist</span>
                    <p class="font-semibold text-gray-900">{{ $booking->tourist->full_name }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Status</span>
                    <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Your Earnings</span>
                    <p class="font-semibold text-green-600">${{ number_format($booking->guide_payout, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
