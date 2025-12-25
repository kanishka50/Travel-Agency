<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Vehicle - {{ $booking->booking_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('guide.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Booking
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Assign Vehicle</h1>
            <p class="text-gray-600 mt-2">Booking: {{ $booking->booking_number }}</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Booking Summary -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Booking Summary</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Tour</span>
                    <p class="font-semibold text-gray-900">
                        @if($booking->guidePlan)
                            {{ $booking->guidePlan->title }}
                        @elseif($booking->touristRequest)
                            {{ $booking->touristRequest->title }}
                        @else
                            Booking #{{ $booking->booking_number }}
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Tour Date</span>
                    <p class="font-semibold text-gray-900">{{ $booking->start_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Travelers</span>
                    <p class="font-semibold text-gray-900">
                        {{ $booking->num_adults }} {{ Str::plural('Adult', $booking->num_adults) }}
                        @if($booking->num_children > 0)
                            , {{ $booking->num_children }} {{ Str::plural('Child', $booking->num_children) }}
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Minimum Capacity Needed</span>
                    <p class="font-semibold text-blue-600">{{ $requiredCapacity }} seats</p>
                </div>
            </div>

            @if($planRequirements)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Tour Plan Vehicle Requirements</h3>
                    <div class="flex flex-wrap gap-2">
                        @if($planRequirements['vehicle_type'])
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                                Type: {{ ucfirst(str_replace('_', ' ', $planRequirements['vehicle_type'])) }}
                            </span>
                        @endif
                        @if($planRequirements['seating_capacity'])
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                                Min Capacity: {{ $planRequirements['seating_capacity'] }} seats
                            </span>
                        @endif
                        <span class="px-3 py-1 {{ $planRequirements['has_ac'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} text-sm rounded-full">
                            {{ $planRequirements['has_ac'] ? 'AC Required' : 'AC Not Required' }}
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Days Until Start Warning -->
        @if($booking->days_until_start <= 3)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Tour starts in {{ $booking->days_until_start }} {{ Str::plural('day', $booking->days_until_start) }}!</strong>
                            Please assign a vehicle as soon as possible.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex" x-data="{ activeTab: 'saved' }">
                    <button @click="activeTab = 'saved'"
                            :class="activeTab === 'saved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                        Choose from My Vehicles
                        @if($vehicles->count() > 0)
                            <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $vehicles->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'temporary'"
                            :class="activeTab === 'temporary' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="flex-1 py-4 px-6 text-center border-b-2 font-medium text-sm transition-colors">
                        Use Temporary Vehicle
                    </button>
                </nav>
            </div>

            <div x-data="{ activeTab: 'saved' }" class="p-6">
                <!-- Saved Vehicles Tab -->
                <div x-show="activeTab === 'saved'" x-transition>
                    @if($allVehicles->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No vehicles yet</h3>
                            <p class="mt-2 text-gray-500">You haven't added any vehicles to your fleet.</p>
                            <a href="{{ route('guide.vehicles.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Add Your First Vehicle
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($allVehicles as $vehicle)
                                @php
                                    $hasEnoughCapacity = $vehicle->seating_capacity >= $requiredCapacity;
                                    $meetsACRequirement = !$planRequirements || !$planRequirements['has_ac'] || $vehicle->has_ac;
                                    $meetsTypeRequirement = !$planRequirements || !$planRequirements['vehicle_type'] || $vehicle->vehicle_type === $planRequirements['vehicle_type'];
                                @endphp
                                <div class="border {{ $hasEnoughCapacity ? 'border-gray-200 hover:border-blue-300' : 'border-yellow-300 bg-yellow-50' }} rounded-lg p-4 transition-colors">
                                    <form action="{{ route('guide.bookings.vehicle.assign-saved', $booking) }}" method="POST" class="flex items-center justify-between">
                                        @csrf
                                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                                        <div class="flex items-center space-x-4">
                                            @if($vehicle->primaryPhoto)
                                                <img src="{{ Storage::url($vehicle->primaryPhoto->photo_path) }}" alt="{{ $vehicle->display_name }}" class="w-20 h-16 object-cover rounded-lg">
                                            @else
                                                <div class="w-20 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1"/>
                                                    </svg>
                                                </div>
                                            @endif

                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $vehicle->display_name }}</h3>
                                                <div class="flex flex-wrap gap-2 mt-1">
                                                    <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-700 rounded">{{ $vehicle->vehicle_type_label }}</span>
                                                    <span class="text-xs px-2 py-0.5 {{ $hasEnoughCapacity ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded">
                                                        {{ $vehicle->seating_capacity }} seats
                                                    </span>
                                                    <span class="text-xs px-2 py-0.5 {{ $vehicle->has_ac ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }} rounded">
                                                        {{ $vehicle->has_ac ? 'AC' : 'Non-AC' }}
                                                    </span>
                                                    <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-700 rounded">{{ $vehicle->license_plate }}</span>
                                                </div>
                                                @if(!$hasEnoughCapacity)
                                                    <p class="text-xs text-yellow-700 mt-1">
                                                        <svg class="w-4 h-4 inline" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Capacity is less than required ({{ $requiredCapacity }} seats needed)
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <button type="submit"
                                                class="px-4 py-2 {{ $hasEnoughCapacity ? 'bg-blue-600 hover:bg-blue-700' : 'bg-yellow-600 hover:bg-yellow-700' }} text-white font-medium rounded-lg transition-colors whitespace-nowrap"
                                                onclick="return {{ $hasEnoughCapacity ? 'true' : "confirm('This vehicle has less capacity than required. Are you sure you want to assign it?')" }}">
                                            {{ $hasEnoughCapacity ? 'Assign Vehicle' : 'Assign Anyway' }}
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 text-center">
                            <a href="{{ route('guide.vehicles.create') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                + Add a New Vehicle
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Temporary Vehicle Tab -->
                <div x-show="activeTab === 'temporary'" x-transition>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-800">
                            <strong>Using a temporary vehicle?</strong>
                            Enter the vehicle details below. This vehicle won't be saved to your fleet for future bookings.
                        </p>
                    </div>

                    <form action="{{ route('guide.bookings.vehicle.assign-temporary', $booking) }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type <span class="text-red-500">*</span></label>
                                <select name="vehicle_type" id="vehicle_type" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select type</option>
                                    @foreach(\App\Models\Vehicle::VEHICLE_TYPES as $key => $label)
                                        <option value="{{ $key }}" {{ old('vehicle_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('vehicle_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="seating_capacity" class="block text-sm font-medium text-gray-700 mb-1">Seating Capacity <span class="text-red-500">*</span></label>
                                <input type="number" name="seating_capacity" id="seating_capacity" required min="1" max="50"
                                       value="{{ old('seating_capacity', $requiredCapacity) }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('seating_capacity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="make" class="block text-sm font-medium text-gray-700 mb-1">Make <span class="text-red-500">*</span></label>
                                <input type="text" name="make" id="make" required maxlength="100"
                                       value="{{ old('make') }}" placeholder="e.g., Toyota"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('make')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model <span class="text-red-500">*</span></label>
                                <input type="text" name="model" id="model" required maxlength="100"
                                       value="{{ old('model') }}" placeholder="e.g., HiAce"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('model')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">License Plate <span class="text-red-500">*</span></label>
                                <input type="text" name="license_plate" id="license_plate" required maxlength="20"
                                       value="{{ old('license_plate') }}" placeholder="e.g., CAB-1234"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('license_plate')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <div class="flex items-center h-full pt-6">
                                    <input type="checkbox" name="has_ac" id="has_ac" value="1"
                                           {{ old('has_ac') ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="has_ac" class="ml-2 block text-sm text-gray-700">Has Air Conditioning</label>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                                <textarea name="description" id="description" rows="2" maxlength="500"
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Any additional details about the vehicle...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Assign Temporary Vehicle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple tab switching (fallback if Alpine isn't loaded)
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('nav button');
            const panels = document.querySelectorAll('[x-show]');

            if (typeof Alpine === 'undefined') {
                tabs.forEach((tab, index) => {
                    tab.addEventListener('click', () => {
                        tabs.forEach(t => {
                            t.classList.remove('border-blue-500', 'text-blue-600');
                            t.classList.add('border-transparent', 'text-gray-500');
                        });
                        tab.classList.remove('border-transparent', 'text-gray-500');
                        tab.classList.add('border-blue-500', 'text-blue-600');

                        panels.forEach((panel, i) => {
                            panel.style.display = i === index ? 'block' : 'none';
                        });
                    });
                });
            }
        });
    </script>
</body>
</html>
