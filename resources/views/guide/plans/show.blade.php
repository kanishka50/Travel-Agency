@extends('layouts.dashboard')

@section('page-title', 'Tour Plan Details')

@section('content')
<!-- Header -->
<div class="mb-8">
    <a href="{{ route('guide.plans.index') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium mb-4 group">
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to My Plans
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center flex-wrap gap-3">
                <h1 class="text-2xl font-bold text-slate-900">{{ $plan->title }}</h1>
                @if($plan->status === 'active')
                    <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-700">Active</span>
                @elseif($plan->status === 'draft')
                    <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-amber-100 text-amber-700">Draft</span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-700">Inactive</span>
                @endif
            </div>
            <p class="text-slate-600 mt-2">{{ $plan->num_days }} days / {{ $plan->num_nights }} nights</p>
        </div>
        <a href="{{ route('guide.plans.edit', $plan) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Plan
        </a>
    </div>
</div>

<!-- Status Management -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h2 class="text-lg font-bold text-slate-900">Plan Status</h2>
    </div>
    <form action="{{ route('guide.plans.status', $plan) }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
        @csrf
        @method('PATCH')
        <select name="status" class="px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
            <option value="draft" {{ $plan->status === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="active" {{ $plan->status === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $plan->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
            Update Status
        </button>
    </form>
</div>

<!-- Photo Gallery -->
@if($plan->cover_photo || $plan->photos->count() > 0)
    <div class="mb-6">
        {{-- Main/Cover Photo --}}
        @if($plan->cover_photo)
            <div class="mb-4">
                <img src="{{ Storage::url($plan->cover_photo) }}" alt="{{ $plan->title }}"
                    class="w-full h-80 object-cover rounded-2xl shadow-sm cursor-pointer hover:opacity-95 transition-opacity"
                    onclick="openLightbox(0)" id="main-photo">
            </div>
        @endif

        {{-- Gallery Thumbnails --}}
        @if($plan->photos->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900">Gallery Photos</h3>
                    <span class="text-sm text-slate-500">{{ $plan->photos->count() }} photos</span>
                </div>
                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                    @foreach($plan->photos as $index => $photo)
                        <div class="relative group cursor-pointer" onclick="openLightbox({{ $index + ($plan->cover_photo ? 1 : 0) }})">
                            <img src="{{ $photo->url }}" alt="Gallery photo {{ $index + 1 }}"
                                class="w-full h-16 object-cover rounded-xl hover:opacity-90 transition-opacity ring-2 ring-transparent group-hover:ring-amber-400">
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Lightbox Modal --}}
    <div id="lightbox" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-amber-400 z-10 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <button onclick="prevPhoto()" class="absolute left-4 text-white hover:text-amber-400 z-10 transition-colors">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button onclick="nextPhoto()" class="absolute right-4 text-white hover:text-amber-400 z-10 transition-colors">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        <img id="lightbox-image" src="" alt="Lightbox" class="max-h-[90vh] max-w-[90vw] object-contain rounded-lg">
        <div id="lightbox-counter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-sm bg-black/50 px-3 py-1 rounded-lg"></div>
    </div>

    <script>
        const photos = [
            @if($plan->cover_photo)
                "{{ Storage::url($plan->cover_photo) }}",
            @endif
            @foreach($plan->photos as $photo)
                "{{ $photo->url }}",
            @endforeach
        ];
        let currentPhotoIndex = 0;

        function openLightbox(index) {
            currentPhotoIndex = index;
            updateLightboxImage();
            document.getElementById('lightbox').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function nextPhoto() {
            currentPhotoIndex = (currentPhotoIndex + 1) % photos.length;
            updateLightboxImage();
        }

        function prevPhoto() {
            currentPhotoIndex = (currentPhotoIndex - 1 + photos.length) % photos.length;
            updateLightboxImage();
        }

        function updateLightboxImage() {
            document.getElementById('lightbox-image').src = photos[currentPhotoIndex];
            document.getElementById('lightbox-counter').textContent = `${currentPhotoIndex + 1} / ${photos.length}`;
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') nextPhoto();
            if (e.key === 'ArrowLeft') prevPhoto();
        });

        document.getElementById('lightbox').addEventListener('click', function(e) {
            if (e.target === this) closeLightbox();
        });
    </script>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Description -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Description</h2>
            </div>
            <div class="prose max-w-none text-slate-700">
                {!! nl2br(e($plan->description)) !!}
            </div>
        </div>

        <!-- Locations & Destinations -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Locations & Destinations</h2>
            </div>
            <dl class="space-y-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm font-medium text-slate-500">Pickup Location</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $plan->pickup_location }}</dd>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm font-medium text-slate-500">Dropoff Location</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $plan->dropoff_location }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500 mb-2">Destinations</dt>
                    <dd class="flex flex-wrap gap-2">
                        @foreach($plan->destinations as $destination)
                            <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-sm font-medium rounded-lg">{{ $destination }}</span>
                        @endforeach
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500 mb-2">Trip Focus</dt>
                    <dd class="flex flex-wrap gap-2">
                        @foreach($plan->trip_focus_tags as $tag)
                            <span class="px-3 py-1.5 bg-cyan-100 text-cyan-700 text-sm font-medium rounded-lg">{{ $tag }}</span>
                        @endforeach
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Day-by-Day Itinerary -->
        @if($plan->itineraries && $plan->itineraries->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Day-by-Day Itinerary</h2>
                </div>
                <div class="space-y-6">
                    @foreach($plan->itineraries->sortBy('day_number') as $itinerary)
                        <div class="border-l-4 border-amber-500 pl-5 pb-5 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-3 py-1 rounded-lg text-sm font-semibold">
                                    Day {{ $itinerary->day_number }}
                                </span>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $itinerary->day_title }}</h3>
                            </div>

                            <div class="prose prose-sm max-w-none text-slate-700 mb-4">
                                {!! nl2br(e($itinerary->description)) !!}
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                {{-- Accommodation Info --}}
                                @if($itinerary->hasAccommodation())
                                    <div class="bg-slate-50 rounded-xl p-4">
                                        <div class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            Accommodation
                                        </div>
                                        <p class="text-sm text-slate-900 font-semibold">{{ $itinerary->accommodation_name }}</p>
                                        @if($itinerary->accommodation_type_label || $itinerary->accommodation_tier_label)
                                            <p class="text-xs text-slate-500 mt-1">
                                                @if($itinerary->accommodation_type_label)
                                                    {{ $itinerary->accommodation_type_label }}
                                                @endif
                                                @if($itinerary->accommodation_type_label && $itinerary->accommodation_tier_label)
                                                    •
                                                @endif
                                                @if($itinerary->accommodation_tier_label)
                                                    {{ $itinerary->accommodation_tier_label }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                {{-- Meals Info --}}
                                @if($itinerary->hasMealsIncluded() || $itinerary->meal_notes)
                                    <div class="bg-slate-50 rounded-xl p-4">
                                        <div class="flex items-center gap-2 text-sm font-medium text-slate-700 mb-2">
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                            Meals Included
                                        </div>
                                        @if($itinerary->hasMealsIncluded())
                                            <div class="flex flex-wrap gap-2 mb-1">
                                                @foreach($itinerary->included_meals as $meal)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">
                                                        {{ $meal }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if($itinerary->meal_notes)
                                            <p class="text-xs text-slate-500">{{ $itinerary->meal_notes }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Vehicle Information -->
        @if($plan->vehicle_type)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Vehicle Information</h2>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <dt class="text-sm font-medium text-slate-500">Vehicle Type</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $plan->vehicle_type }}</dd>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <dt class="text-sm font-medium text-slate-500">Seating Capacity</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $plan->vehicle_capacity ?? 'N/A' }} passengers</dd>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <dt class="text-sm font-medium text-slate-500">Has AC</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $plan->vehicle_ac ? 'Yes' : 'No' }}</dd>
                    </div>
                </div>
                @if($plan->vehicle_description)
                    <div class="mt-4 bg-slate-50 rounded-xl p-4">
                        <dt class="text-sm font-medium text-slate-500">Description</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $plan->vehicle_description }}</dd>
                    </div>
                @endif
            </div>
        @endif

        <!-- Inclusions & Exclusions -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-teal-100 to-teal-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">What's Included & Excluded</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-200">
                    <h3 class="text-sm font-bold text-emerald-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Included
                    </h3>
                    <div class="text-sm text-emerald-900 whitespace-pre-line">{{ $plan->inclusions }}</div>
                </div>
                <div class="bg-red-50 rounded-xl p-4 border border-red-200">
                    <h3 class="text-sm font-bold text-red-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Not Included
                    </h3>
                    <div class="text-sm text-red-900 whitespace-pre-line">{{ $plan->exclusions }}</div>
                </div>
            </div>
        </div>

        <!-- Optional Add-ons -->
        @if($plan->addons && $plan->addons->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Optional Add-ons</h2>
                        <p class="text-sm text-slate-500">{{ $plan->addons->count() }} available</p>
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($plan->addons as $addon)
                        <div class="border border-slate-200 rounded-xl p-4 hover:border-purple-300 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-lg font-semibold text-slate-900">{{ $addon->addon_name }}</h3>
                                        @if($addon->day_number > 0)
                                            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs font-medium rounded-lg">
                                                Day {{ $addon->day_number }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-xs font-medium rounded-lg">
                                                Any Day
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-slate-600 mb-3">{{ $addon->addon_description }}</p>
                                    <div class="flex items-center gap-4 text-xs text-slate-500">
                                        @if($addon->max_participants)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                Max {{ $addon->max_participants }} participants
                                            </span>
                                        @endif
                                        @if($addon->require_all_participants)
                                            <span class="flex items-center gap-1 text-amber-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Requires all participants
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <p class="text-2xl font-bold text-purple-600">${{ number_format($addon->price_per_person, 2) }}</p>
                                    <p class="text-xs text-slate-500">per person</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Dietary & Accessibility -->
        @if($plan->dietary_options || $plan->accessibility_info)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-rose-100 to-rose-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Special Requirements</h2>
                </div>
                @if($plan->dietary_options)
                    <div class="mb-4">
                        <dt class="text-sm font-medium text-slate-500 mb-2">Dietary Options</dt>
                        <dd class="flex flex-wrap gap-2">
                            @foreach($plan->dietary_options as $option)
                                <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-sm font-medium rounded-lg">{{ ucfirst(str_replace('_', ' ', $option)) }}</span>
                            @endforeach
                        </dd>
                    </div>
                @endif
                @if($plan->accessibility_info)
                    <div class="bg-slate-50 rounded-xl p-4">
                        <dt class="text-sm font-medium text-slate-500 mb-2">Accessibility</dt>
                        <dd class="text-sm text-slate-900">{{ $plan->accessibility_info }}</dd>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Statistics</h2>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-amber-600">{{ $plan->view_count }}</p>
                    <p class="text-sm text-slate-500">Views</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-emerald-600">{{ $plan->booking_count }}</p>
                    <p class="text-sm text-slate-500">Bookings</p>
                </div>
            </div>
            <div class="mt-4 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Created</span>
                    <span class="font-semibold text-slate-900">{{ $plan->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Last Updated</span>
                    <span class="font-semibold text-slate-900">{{ $plan->updated_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl shadow-lg p-6 text-white">
            <h2 class="text-lg font-bold mb-4">Pricing</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-amber-100 text-sm">Price per Adult</p>
                    <p class="text-3xl font-bold">${{ number_format($plan->price_per_adult, 2) }}</p>
                </div>
                <div>
                    <p class="text-amber-100 text-sm">Price per Child</p>
                    <p class="text-2xl font-semibold">${{ number_format($plan->price_per_child, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Group Size -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Group Size</h2>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-slate-900">{{ $plan->min_group_size }}</p>
                    <p class="text-sm text-slate-500">Minimum</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-slate-900">{{ $plan->max_group_size }}</p>
                    <p class="text-sm text-slate-500">Maximum</p>
                </div>
            </div>
        </div>

        <!-- Availability -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Availability</h2>
            </div>
            @if($plan->availability_type === 'always_available')
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center">
                    <p class="text-sm font-semibold text-emerald-700">Always Available</p>
                </div>
            @else
                <div class="space-y-3">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500">From</p>
                        <p class="font-semibold text-slate-900">{{ $plan->available_start_date?->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500">To</p>
                        <p class="font-semibold text-slate-900">{{ $plan->available_end_date?->format('M d, Y') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
