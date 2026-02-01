@extends('layouts.dashboard')

@section('page-title', 'Vehicle Assignment')

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
    <h1 class="text-2xl font-bold text-slate-900">Vehicle Assignment</h1>
    <p class="text-slate-500 mt-1">Booking: <strong class="text-slate-700">{{ $booking->booking_number }}</strong></p>
</div>

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

@php
    $assignment = $booking->vehicleAssignment;
    $vehicleDetails = $assignment->vehicle_details;
@endphp

<!-- Vehicle Details Card -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white">Assigned Vehicle</h2>
            </div>
            @if($assignment->is_temporary)
                <span class="px-3 py-1 bg-amber-300 text-amber-900 text-sm font-semibold rounded-lg">Temporary Vehicle</span>
            @else
                <span class="px-3 py-1 bg-emerald-400 text-emerald-900 text-sm font-semibold rounded-lg">Saved Vehicle</span>
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
                             class="w-full h-32 object-cover rounded-xl">
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Vehicle Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-slate-50 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Vehicle Information</h3>
                </div>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Vehicle</dt>
                        <dd class="font-semibold text-slate-900">{{ $assignment->vehicle_display_name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Type</dt>
                        <dd class="font-semibold text-slate-900">
                            {{ ucfirst(str_replace('_', ' ', $vehicleDetails['vehicle_type'] ?? 'N/A')) }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">License Plate</dt>
                        <dd class="font-semibold text-slate-900">{{ $assignment->license_plate }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Seating Capacity</dt>
                        <dd class="font-semibold text-slate-900">{{ $assignment->seating_capacity }} seats</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Air Conditioning</dt>
                        <dd>
                            @if($vehicleDetails['has_ac'] ?? false)
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-lg">Yes</span>
                            @else
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-sm font-semibold rounded-lg">No</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="bg-slate-50 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Assignment Details</h3>
                </div>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Assigned On</dt>
                        <dd class="font-semibold text-slate-900">{{ $assignment->assigned_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Assigned By</dt>
                        <dd class="font-semibold text-slate-900">
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
                        <dt class="text-slate-500">Tour Date</dt>
                        <dd class="font-semibold text-slate-900">{{ $booking->start_date->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-slate-500">Travelers</dt>
                        <dd class="font-semibold text-slate-900">
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
            <div class="mt-6 pt-6 border-t border-slate-200">
                <h3 class="text-sm font-semibold text-slate-700 mb-2">Vehicle Description</h3>
                <p class="text-slate-600">{{ $vehicleDetails['description'] }}</p>
            </div>
        @endif

        <!-- Actions -->
        @if($booking->start_date > now())
            <div class="mt-6 pt-6 border-t border-slate-200 flex justify-end">
                <form action="{{ route('guide.bookings.vehicle.remove', $booking) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to remove this vehicle assignment? You will need to assign a new vehicle.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-xl transition-colors">
                        Remove Assignment
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<!-- Booking Summary -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900">Booking Summary</h3>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div class="bg-slate-50 rounded-xl p-4">
            <span class="text-slate-500">Tour</span>
            <p class="font-semibold text-slate-900 mt-1">
                @if($booking->guidePlan)
                    {{ $booking->guidePlan->title }}
                @elseif($booking->touristRequest)
                    {{ $booking->touristRequest->title }}
                @else
                    #{{ $booking->booking_number }}
                @endif
            </p>
        </div>
        <div class="bg-slate-50 rounded-xl p-4">
            <span class="text-slate-500">Tourist</span>
            <p class="font-semibold text-slate-900 mt-1">{{ $booking->tourist->full_name }}</p>
        </div>
        <div class="bg-slate-50 rounded-xl p-4">
            <span class="text-slate-500">Status</span>
            <p class="font-semibold text-slate-900 mt-1">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</p>
        </div>
        <div class="bg-slate-50 rounded-xl p-4">
            <span class="text-slate-500">Your Earnings</span>
            <p class="font-bold text-amber-600 mt-1">${{ number_format($booking->guide_payout, 2) }}</p>
        </div>
    </div>
</div>
@endsection
