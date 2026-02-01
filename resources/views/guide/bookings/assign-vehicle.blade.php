@extends('layouts.dashboard')

@section('page-title', 'Assign Vehicle')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('guide.bookings.show', $booking) }}" class="inline-flex items-center text-slate-600 hover:text-amber-600 transition-colors group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Booking
    </a>
</div>

<!-- Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Assign Vehicle</h1>
    <p class="text-slate-500 mt-1">Booking: <strong class="text-slate-700">{{ $booking->booking_number }}</strong></p>
</div>

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

<!-- Booking Summary -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h2 class="text-lg font-bold text-slate-900">Booking Summary</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-slate-50 rounded-xl p-4">
            <span class="text-sm text-slate-500">Tour</span>
            <p class="font-semibold text-slate-900 mt-1">
                @if($booking->guidePlan)
                    {{ $booking->guidePlan->title }}
                @elseif($booking->touristRequest)
                    {{ $booking->touristRequest->title }}
                @else
                    Booking #{{ $booking->booking_number }}
                @endif
            </p>
        </div>
        <div class="bg-slate-50 rounded-xl p-4">
            <span class="text-sm text-slate-500">Tour Date</span>
            <p class="font-semibold text-slate-900 mt-1">{{ $booking->start_date->format('M d, Y') }}</p>
        </div>
        <div class="bg-slate-50 rounded-xl p-4">
            <span class="text-sm text-slate-500">Travelers</span>
            <p class="font-semibold text-slate-900 mt-1">
                {{ $booking->num_adults }} {{ Str::plural('Adult', $booking->num_adults) }}
                @if($booking->num_children > 0)
                    , {{ $booking->num_children }} {{ Str::plural('Child', $booking->num_children) }}
                @endif
            </p>
        </div>
        <div class="bg-slate-50 rounded-xl p-4">
            <span class="text-sm text-slate-500">Minimum Capacity</span>
            <p class="font-bold text-amber-600 mt-1">{{ $requiredCapacity }} seats</p>
        </div>
    </div>

    @if($planRequirements)
        <div class="mt-4 pt-4 border-t border-slate-200">
            <h3 class="text-sm font-semibold text-slate-700 mb-2">Tour Plan Vehicle Requirements</h3>
            <div class="flex flex-wrap gap-2">
                @if($planRequirements['vehicle_type'])
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-sm font-medium rounded-lg">
                        Type: {{ ucfirst(str_replace('_', ' ', $planRequirements['vehicle_type'])) }}
                    </span>
                @endif
                @if($planRequirements['seating_capacity'])
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-sm font-medium rounded-lg">
                        Min Capacity: {{ $planRequirements['seating_capacity'] }} seats
                    </span>
                @endif
                <span class="px-3 py-1 {{ $planRequirements['has_ac'] ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }} text-sm font-medium rounded-lg">
                    {{ $planRequirements['has_ac'] ? 'AC Required' : 'AC Not Required' }}
                </span>
            </div>
        </div>
    @endif
</div>

<!-- Days Until Start Warning -->
@if($booking->days_until_start <= 3)
    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-sm text-amber-800">
                <strong>Tour starts in {{ $booking->days_until_start }} {{ Str::plural('day', $booking->days_until_start) }}!</strong>
                Please assign a vehicle as soon as possible.
            </p>
        </div>
    </div>
@endif

<!-- Tabs -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ activeTab: 'saved' }">
    <div class="border-b border-slate-200">
        <nav class="flex">
            <button @click="activeTab = 'saved'"
                    :class="activeTab === 'saved' ? 'border-amber-500 text-amber-600 bg-amber-50/50' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                    class="flex-1 py-4 px-6 text-center border-b-2 font-semibold text-sm transition-colors">
                Choose from My Vehicles
                @if($vehicles->count() > 0)
                    <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-lg">{{ $vehicles->count() }}</span>
                @endif
            </button>
            <button @click="activeTab = 'temporary'"
                    :class="activeTab === 'temporary' ? 'border-amber-500 text-amber-600 bg-amber-50/50' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-50'"
                    class="flex-1 py-4 px-6 text-center border-b-2 font-semibold text-sm transition-colors">
                Use Temporary Vehicle
            </button>
        </nav>
    </div>

    <div class="p-6">
        <!-- Saved Vehicles Tab -->
        <div x-show="activeTab === 'saved'" x-transition>
            @if($allVehicles->isEmpty())
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">No vehicles yet</h3>
                    <p class="text-slate-600 max-w-md mx-auto mb-6">You haven't added any vehicles to your fleet.</p>
                    <a href="{{ route('guide.vehicles.create') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
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
                        <div class="border {{ $hasEnoughCapacity ? 'border-slate-200 hover:border-amber-300' : 'border-amber-300 bg-amber-50' }} rounded-xl p-4 transition-colors">
                            <form action="{{ route('guide.bookings.vehicle.assign-saved', $booking) }}" method="POST" class="flex items-center justify-between">
                                @csrf
                                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                                <div class="flex items-center gap-4">
                                    @if($vehicle->primaryPhoto)
                                        <img src="{{ Storage::url($vehicle->primaryPhoto->photo_path) }}" alt="{{ $vehicle->display_name }}" class="w-20 h-16 object-cover rounded-xl">
                                    @else
                                        <div class="w-20 h-16 bg-slate-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div>
                                        <h3 class="font-bold text-slate-900">{{ $vehicle->display_name }}</h3>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            <span class="text-xs px-2 py-0.5 bg-slate-100 text-slate-700 rounded-lg font-medium">{{ $vehicle->vehicle_type_label }}</span>
                                            <span class="text-xs px-2 py-0.5 {{ $hasEnoughCapacity ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }} rounded-lg font-medium">
                                                {{ $vehicle->seating_capacity }} seats
                                            </span>
                                            <span class="text-xs px-2 py-0.5 {{ $vehicle->has_ac ? 'bg-cyan-100 text-cyan-700' : 'bg-slate-100 text-slate-600' }} rounded-lg font-medium">
                                                {{ $vehicle->has_ac ? 'AC' : 'Non-AC' }}
                                            </span>
                                            <span class="text-xs px-2 py-0.5 bg-slate-100 text-slate-700 rounded-lg font-medium">{{ $vehicle->license_plate }}</span>
                                        </div>
                                        @if(!$hasEnoughCapacity)
                                            <p class="text-xs text-amber-700 mt-1 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Capacity is less than required ({{ $requiredCapacity }} seats needed)
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit"
                                        class="px-5 py-2.5 {{ $hasEnoughCapacity ? 'bg-gradient-to-r from-amber-500 to-orange-500 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40' : 'bg-amber-600 hover:bg-amber-700' }} text-white font-semibold rounded-xl transition-all whitespace-nowrap"
                                        onclick="return {{ $hasEnoughCapacity ? 'true' : "confirm('This vehicle has less capacity than required. Are you sure you want to assign it?')" }}">
                                    {{ $hasEnoughCapacity ? 'Assign Vehicle' : 'Assign Anyway' }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('guide.vehicles.create') }}" class="text-amber-600 hover:text-amber-700 font-semibold">
                        + Add a New Vehicle
                    </a>
                </div>
            @endif
        </div>

        <!-- Temporary Vehicle Tab -->
        <div x-show="activeTab === 'temporary'" x-transition>
            <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-amber-800">
                        <strong>Using a temporary vehicle?</strong>
                        Enter the vehicle details below. This vehicle won't be saved to your fleet for future bookings.
                    </p>
                </div>
            </div>

            <form action="{{ route('guide.bookings.vehicle.assign-temporary', $booking) }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="vehicle_type" class="block text-sm font-semibold text-slate-700 mb-2">Vehicle Type *</label>
                        <select name="vehicle_type" id="vehicle_type" required
                                class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                            <option value="">Select type</option>
                            @foreach(\App\Models\Vehicle::VEHICLE_TYPES as $key => $label)
                                <option value="{{ $key }}" {{ old('vehicle_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_type')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="seating_capacity" class="block text-sm font-semibold text-slate-700 mb-2">Seating Capacity *</label>
                        <input type="number" name="seating_capacity" id="seating_capacity" required min="1" max="50"
                               value="{{ old('seating_capacity', $requiredCapacity) }}"
                               class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                        @error('seating_capacity')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="make" class="block text-sm font-semibold text-slate-700 mb-2">Make *</label>
                        <input type="text" name="make" id="make" required maxlength="100"
                               value="{{ old('make') }}" placeholder="e.g., Toyota"
                               class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                        @error('make')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-semibold text-slate-700 mb-2">Model *</label>
                        <input type="text" name="model" id="model" required maxlength="100"
                               value="{{ old('model') }}" placeholder="e.g., HiAce"
                               class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                        @error('model')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="license_plate" class="block text-sm font-semibold text-slate-700 mb-2">License Plate *</label>
                        <input type="text" name="license_plate" id="license_plate" required maxlength="20"
                               value="{{ old('license_plate') }}" placeholder="e.g., CAB-1234"
                               class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                        @error('license_plate')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <div class="flex items-center h-full pt-6">
                            <input type="checkbox" name="has_ac" id="has_ac" value="1"
                                   {{ old('has_ac') ? 'checked' : '' }}
                                   class="h-5 w-5 text-amber-600 focus:ring-amber-500 border-slate-300 rounded">
                            <label for="has_ac" class="ml-3 block text-sm text-slate-700 font-medium">Has Air Conditioning</label>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Description (Optional)</label>
                        <textarea name="description" id="description" rows="3" maxlength="500"
                                  class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500"
                                  placeholder="Any additional details about the vehicle...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                        Assign Temporary Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
