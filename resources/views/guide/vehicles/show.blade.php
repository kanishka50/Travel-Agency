@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
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
                        <a href="{{ route('guide.vehicles.index') }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-emerald-600 md:ml-2">My Vehicles</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-emerald-600 md:ml-2">{{ $vehicle->make }} {{ $vehicle->model }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</h1>
                    <div class="flex items-center mt-2 space-x-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $vehicle->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $vehicle->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">
                            {{ \App\Models\Vehicle::VEHICLE_TYPES[$vehicle->vehicle_type] ?? $vehicle->vehicle_type }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if($vehicle->canBeEdited())
                        <a href="{{ route('guide.vehicles.edit', $vehicle) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    @endif
                    <form action="{{ route('guide.vehicles.toggle-status', $vehicle) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 {{ $vehicle->is_active ? 'bg-amber-500 hover:bg-amber-600' : 'bg-teal-500 hover:bg-teal-600' }} text-white font-semibold rounded-lg transition">
                            @if($vehicle->is_active)
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Deactivate
                            @else
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Activate
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Vehicle Photos -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    @if($vehicle->photos->count() > 0)
                        <!-- Primary Photo -->
                        @php $primaryPhoto = $vehicle->primary_photo; @endphp
                        <div class="relative">
                            <img src="{{ asset('storage/' . $primaryPhoto->photo_path) }}" alt="{{ $vehicle->make }} {{ $vehicle->model }}" class="w-full h-64 object-cover" id="main-photo">
                            @if($vehicle->photos->count() > 1)
                                <span class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                    {{ $vehicle->photos->count() }} photos
                                </span>
                            @endif
                        </div>

                        <!-- Photo Thumbnails -->
                        @if($vehicle->photos->count() > 1)
                            <div class="p-4 bg-gray-50 border-t">
                                <div class="flex space-x-2 overflow-x-auto pb-2">
                                    @foreach($vehicle->photos as $photo)
                                        <button type="button"
                                            onclick="changeMainPhoto('{{ asset('storage/' . $photo->photo_path) }}')"
                                            class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 {{ $photo->is_primary ? 'border-emerald-500' : 'border-transparent hover:border-gray-300' }} transition">
                                            <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No photos uploaded</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Vehicle Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Details</h2>

                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Make</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->make }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Model</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->model }}</dd>
                        </div>
                        @if($vehicle->year)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Year</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->year }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">License Plate</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $vehicle->license_plate }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Seating Capacity</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->seating_capacity }} passengers</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Air Conditioning</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($vehicle->has_ac)
                                    <span class="inline-flex items-center text-emerald-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        No
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    @if($vehicle->description)
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Description</dt>
                            <dd class="text-sm text-gray-900">{{ $vehicle->description }}</dd>
                        </div>
                    @endif
                </div>

                <!-- Documents Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Documents</h2>

                    @php
                        $registrationDoc = $vehicle->registration_document;
                        $insuranceDoc = $vehicle->insurance_document;
                    @endphp

                    @if(!$registrationDoc && !$insuranceDoc)
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No documents uploaded</p>
                            <a href="{{ route('guide.vehicles.edit', $vehicle) }}" class="mt-2 inline-block text-emerald-600 hover:text-emerald-800 text-sm font-medium">Upload documents</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Registration Document -->
                            <div class="border rounded-lg p-4 {{ $registrationDoc ? '' : 'border-dashed border-gray-300' }}">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-10 h-10 {{ $registrationDoc ? 'text-emerald-500' : 'text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">Vehicle Registration</h3>
                                        @if($registrationDoc)
                                            <a href="{{ asset('storage/' . $registrationDoc->document_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm text-emerald-600 hover:text-emerald-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View Document
                                            </a>
                                        @else
                                            <p class="text-xs text-gray-500 mt-1">Not uploaded</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Insurance Document -->
                            <div class="border rounded-lg p-4 {{ $insuranceDoc ? '' : 'border-dashed border-gray-300' }}">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-10 h-10 {{ $insuranceDoc ? 'text-teal-500' : 'text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">Vehicle Insurance</h3>
                                        @if($insuranceDoc)
                                            <a href="{{ asset('storage/' . $insuranceDoc->document_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm text-emerald-600 hover:text-emerald-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View Document
                                            </a>
                                        @else
                                            <p class="text-xs text-gray-500 mt-1">Not uploaded</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Recent Assignments -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Assignments</h2>

                    @if($vehicle->assignments->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No assignments yet</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($vehicle->assignments as $assignment)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $assignment->booking->guidePlan->title ?? 'Custom Tour' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $assignment->booking->tourist->full_name ?? 'Tourist' }} &bull;
                                                {{ $assignment->booking->start_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $assignment->booking->start_date->isPast() ? 'bg-gray-100 text-gray-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ $assignment->booking->start_date->isPast() ? 'Completed' : 'Upcoming' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Stats Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Total Assignments</span>
                            <span class="text-lg font-semibold text-gray-900">{{ $stats['total_assignments'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Upcoming Tours</span>
                            <span class="text-lg font-semibold text-emerald-600">{{ $stats['upcoming_assignments'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Completed Tours</span>
                            <span class="text-lg font-semibold text-teal-600">{{ $stats['completed_tours'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Photos Count -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Media</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Photos</span>
                            <span class="text-sm font-medium text-gray-900">{{ $vehicle->photos->count() }} / 5</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Documents</span>
                            <span class="text-sm font-medium text-gray-900">{{ $vehicle->documents->count() }} / 2</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Info</h2>
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-600">Added {{ $vehicle->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($vehicle->updated_at->ne($vehicle->created_at))
                            <div class="flex items-center text-sm">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span class="text-gray-600">Updated {{ $vehicle->updated_at->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Warning if has upcoming assignments -->
                @if($stats['upcoming_assignments'] > 0)
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-amber-700">
                                    This vehicle has <strong>{{ $stats['upcoming_assignments'] }}</strong> upcoming assignment(s).
                                    Editing and deactivation are restricted until tours are completed.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function changeMainPhoto(src) {
        document.getElementById('main-photo').src = src;
    }
</script>
@endpush
@endsection
