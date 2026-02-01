@extends('layouts.dashboard')

@section('page-title', $vehicle->make . ' ' . $vehicle->model)

@section('content')
<!-- Header -->
<div class="mb-8">
    <a href="{{ route('guide.vehicles.index') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium mb-4 group">
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to My Vehicles
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $vehicle->make }} {{ $vehicle->model }}</h1>
            <div class="flex items-center gap-3 mt-2">
                <span class="px-3 py-1 text-xs font-semibold rounded-lg {{ $vehicle->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                    {{ $vehicle->is_active ? 'Active' : 'Inactive' }}
                </span>
                <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-cyan-100 text-cyan-700">
                    {{ \App\Models\Vehicle::VEHICLE_TYPES[$vehicle->vehicle_type] ?? $vehicle->vehicle_type }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($vehicle->canBeEdited())
                <a href="{{ route('guide.vehicles.edit', $vehicle) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            @endif
            <form action="{{ route('guide.vehicles.toggle-status', $vehicle) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 {{ $vehicle->is_active ? 'bg-amber-500 hover:bg-amber-600' : 'bg-emerald-500 hover:bg-emerald-600' }} text-white font-semibold rounded-xl transition-colors">
                    @if($vehicle->is_active)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Deactivate
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3">
        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="text-emerald-800">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center gap-3">
        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <p class="text-red-800">{{ session('error') }}</p>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Vehicle Photos -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            @if($vehicle->photos->count() > 0)
                <!-- Primary Photo -->
                @php $primaryPhoto = $vehicle->primary_photo; @endphp
                <div class="relative">
                    <img src="{{ asset('storage/' . $primaryPhoto->photo_path) }}" alt="{{ $vehicle->make }} {{ $vehicle->model }}" class="w-full h-64 object-cover" id="main-photo">
                    @if($vehicle->photos->count() > 1)
                        <span class="absolute bottom-3 right-3 bg-black/50 text-white text-xs px-3 py-1.5 rounded-lg">
                            {{ $vehicle->photos->count() }} photos
                        </span>
                    @endif
                </div>

                <!-- Photo Thumbnails -->
                @if($vehicle->photos->count() > 1)
                    <div class="p-4 bg-slate-50 border-t border-slate-200">
                        <div class="flex gap-3 overflow-x-auto pb-2">
                            @foreach($vehicle->photos as $photo)
                                <button type="button"
                                    onclick="changeMainPhoto('{{ asset('storage/' . $photo->photo_path) }}')"
                                    class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden ring-2 {{ $photo->is_primary ? 'ring-amber-500' : 'ring-transparent hover:ring-amber-300' }} transition">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <div class="w-full h-64 bg-slate-100 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="mx-auto h-20 w-20 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                        <p class="mt-2 text-sm text-slate-500">No photos uploaded</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Vehicle Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Vehicle Details</h2>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm font-medium text-slate-500">Make</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $vehicle->make }}</dd>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm font-medium text-slate-500">Model</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $vehicle->model }}</dd>
                </div>
                @if($vehicle->year)
                    <div class="bg-slate-50 rounded-xl p-4">
                        <dt class="text-sm font-medium text-slate-500">Year</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $vehicle->year }}</dd>
                    </div>
                @endif
                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm font-medium text-slate-500">License Plate</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900 font-mono">{{ $vehicle->license_plate }}</dd>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm font-medium text-slate-500">Seating Capacity</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $vehicle->seating_capacity }} passengers</dd>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm font-medium text-slate-500">Air Conditioning</dt>
                    <dd class="mt-1 text-sm font-semibold">
                        @if($vehicle->has_ac)
                            <span class="inline-flex items-center text-emerald-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Yes
                            </span>
                        @else
                            <span class="inline-flex items-center text-slate-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                No
                            </span>
                        @endif
                    </dd>
                </div>
            </div>

            @if($vehicle->description)
                <div class="mt-4 bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm font-medium text-slate-500 mb-2">Description</dt>
                    <dd class="text-sm text-slate-900">{{ $vehicle->description }}</dd>
                </div>
            @endif
        </div>

        <!-- Documents Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Documents</h2>
            </div>

            @php
                $registrationDoc = $vehicle->registration_document;
                $insuranceDoc = $vehicle->insurance_document;
            @endphp

            @if(!$registrationDoc && !$insuranceDoc)
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="mt-2 text-sm text-slate-500">No documents uploaded</p>
                    <a href="{{ route('guide.vehicles.edit', $vehicle) }}" class="mt-2 inline-block text-amber-600 hover:text-amber-700 text-sm font-medium">Upload documents</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Registration Document -->
                    <div class="border rounded-xl p-4 {{ $registrationDoc ? 'border-slate-200' : 'border-dashed border-slate-300' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-10 h-10 {{ $registrationDoc ? 'text-emerald-500' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-slate-900">Vehicle Registration</h3>
                                @if($registrationDoc)
                                    <a href="{{ asset('storage/' . $registrationDoc->document_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm text-amber-600 hover:text-amber-700 font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Document
                                    </a>
                                @else
                                    <p class="text-xs text-slate-500 mt-1">Not uploaded</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Insurance Document -->
                    <div class="border rounded-xl p-4 {{ $insuranceDoc ? 'border-slate-200' : 'border-dashed border-slate-300' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-10 h-10 {{ $insuranceDoc ? 'text-cyan-500' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-slate-900">Vehicle Insurance</h3>
                                @if($insuranceDoc)
                                    <a href="{{ asset('storage/' . $insuranceDoc->document_path) }}" target="_blank" class="inline-flex items-center mt-2 text-sm text-amber-600 hover:text-amber-700 font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Document
                                    </a>
                                @else
                                    <p class="text-xs text-slate-500 mt-1">Not uploaded</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Assignments -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Recent Assignments</h2>
            </div>

            @if($vehicle->assignments->isEmpty())
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="mt-2 text-sm text-slate-500">No assignments yet</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($vehicle->assignments as $assignment)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ $assignment->booking->guidePlan->title ?? 'Custom Tour' }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ $assignment->booking->tourist->full_name ?? 'Tourist' }} &bull;
                                        {{ $assignment->booking->start_date->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-lg {{ $assignment->booking->start_date->isPast() ? 'bg-slate-100 text-slate-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $assignment->booking->start_date->isPast() ? 'Completed' : 'Upcoming' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Stats Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Statistics</h2>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Total Assignments</span>
                    <span class="text-lg font-bold text-slate-900">{{ $stats['total_assignments'] }}</span>
                </div>
                <div class="flex justify-between items-center bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Upcoming Tours</span>
                    <span class="text-lg font-bold text-emerald-600">{{ $stats['upcoming_assignments'] }}</span>
                </div>
                <div class="flex justify-between items-center bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Completed Tours</span>
                    <span class="text-lg font-bold text-cyan-600">{{ $stats['completed_tours'] }}</span>
                </div>
            </div>
        </div>

        <!-- Media -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Media</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500">Photos</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $vehicle->photos->count() }} / 5</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500">Documents</span>
                    <span class="text-sm font-semibold text-slate-900">{{ $vehicle->documents->count() }} / 2</span>
                </div>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Quick Info</h2>
            <div class="space-y-3">
                <div class="flex items-center text-sm gap-3">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-slate-600">Added {{ $vehicle->created_at->format('M d, Y') }}</span>
                </div>
                @if($vehicle->updated_at->ne($vehicle->created_at))
                    <div class="flex items-center text-sm gap-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span class="text-slate-600">Updated {{ $vehicle->updated_at->diffForHumans() }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Warning if has upcoming assignments -->
        @if($stats['upcoming_assignments'] > 0)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm text-amber-800">
                            This vehicle has <strong>{{ $stats['upcoming_assignments'] }}</strong> upcoming assignment(s).
                            Editing and deactivation are restricted until tours are completed.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function changeMainPhoto(src) {
        document.getElementById('main-photo').src = src;
    }
</script>
@endsection
