@extends('layouts.dashboard')

@section('page-title', 'My Vehicles')

@section('content')
<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">My Vehicles</h1>
        <p class="text-slate-500 mt-1">Manage your vehicles for tours</p>
    </div>
    <a href="{{ route('guide.vehicles.create') }}"
       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add New Vehicle
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Total Vehicles</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['total'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Active</p>
                <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $stats['active'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Inactive</p>
                <p class="text-3xl font-bold text-slate-600 mt-2">{{ $stats['inactive'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Total Capacity</p>
                <p class="text-3xl font-bold text-cyan-600 mt-2">{{ $stats['total_capacity'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
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

<!-- Vehicles List -->
@if($vehicles->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">No vehicles yet</h3>
        <p class="text-slate-600 max-w-md mx-auto mb-6">Get started by adding your first vehicle to use for tours.</p>
        <a href="{{ route('guide.vehicles.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Vehicle
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($vehicles as $vehicle)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden {{ !$vehicle->is_active ? 'opacity-75' : '' }}">
                <!-- Vehicle Image -->
                <div class="h-48 bg-slate-100 relative">
                    @php
                        $primaryPhoto = $vehicle->photos->where('is_primary', true)->first() ?? $vehicle->photos->first();
                    @endphp
                    @if($primaryPhoto)
                        <img src="{{ asset('storage/' . $primaryPhoto->photo_path) }}" alt="{{ $vehicle->make }} {{ $vehicle->model }}" class="w-full h-full object-cover">
                        @if($vehicle->photos->count() > 1)
                            <span class="absolute bottom-3 left-3 bg-black/50 text-white text-xs px-2.5 py-1 rounded-lg font-medium">
                                {{ $vehicle->photos->count() }} photos
                            </span>
                        @endif
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        @if($vehicle->is_active)
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-700">Active</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700">Inactive</span>
                        @endif
                    </div>

                    <!-- Vehicle Type Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-100 text-amber-700">
                            {{ \App\Models\Vehicle::VEHICLE_TYPES[$vehicle->vehicle_type] ?? $vehicle->vehicle_type }}
                        </span>
                    </div>
                </div>

                <!-- Vehicle Info -->
                <div class="p-5">
                    <h3 class="text-lg font-bold text-slate-900">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                    @if($vehicle->year)
                        <p class="text-sm text-slate-500">{{ $vehicle->year }}</p>
                    @endif

                    <div class="mt-3 flex items-center flex-wrap gap-3 text-sm text-slate-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            {{ $vehicle->license_plate }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $vehicle->seating_capacity }} seats
                        </div>
                        @if($vehicle->has_ac)
                            <div class="flex items-center text-amber-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                A/C
                            </div>
                        @endif
                    </div>

                    <!-- Assignment Stats -->
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                        <span class="text-slate-500">
                            {{ $vehicle->assignments_count }} total assignments
                        </span>
                        @if($vehicle->upcoming_assignments_count > 0)
                            <span class="text-amber-600 font-semibold">
                                {{ $vehicle->upcoming_assignments_count }} upcoming
                            </span>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 flex items-center gap-2">
                        <a href="{{ route('guide.vehicles.show', $vehicle) }}"
                           class="flex-1 text-center px-3 py-2 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                            View
                        </a>
                        @if($vehicle->canBeEdited())
                            <a href="{{ route('guide.vehicles.edit', $vehicle) }}"
                               class="flex-1 text-center px-3 py-2 bg-amber-100 text-amber-700 text-sm font-semibold rounded-xl hover:bg-amber-200 transition-colors">
                                Edit
                            </a>
                        @endif
                        <form action="{{ route('guide.vehicles.toggle-status', $vehicle) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="px-3 py-2 {{ $vehicle->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }} text-sm font-semibold rounded-xl transition-colors"
                                    title="{{ $vehicle->is_active ? 'Deactivate' : 'Activate' }}">
                                @if($vehicle->is_active)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
