@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
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
                    <a href="{{ route('guide.plans.index') }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-emerald-600 md:ml-2">My Tour Plans</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-emerald-600 md:ml-2">{{ Str::limit($plan->title, 30) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $plan->title }}</h1>
                    @if($plan->status === 'active')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-emerald-100 text-emerald-800">Active</span>
                    @elseif($plan->status === 'draft')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-amber-100 text-amber-800">Draft</span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </div>
                <p class="text-gray-600 mt-2">{{ $plan->num_days }} days / {{ $plan->num_nights }} nights</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('guide.plans.edit', $plan) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Plan
                </a>
                <a href="{{ route('guide.plans.index') }}" class="text-emerald-600 hover:text-emerald-800">Back to List</a>
            </div>
        </div>
    </div>

    <!-- Status Management -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Plan Status</h3>
        <form action="{{ route('guide.plans.status', $plan) }}" method="POST" class="flex items-center space-x-4">
            @csrf
            @method('PATCH')
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="draft" {{ $plan->status === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="active" {{ $plan->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $plan->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
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
                        class="w-full h-96 object-cover rounded-lg shadow cursor-pointer hover:opacity-95 transition-opacity"
                        onclick="openLightbox(0)" id="main-photo">
                </div>
            @endif

            {{-- Gallery Thumbnails --}}
            @if($plan->photos->count() > 0)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-900">Gallery Photos</h3>
                        <span class="text-sm text-gray-500">{{ $plan->photos->count() }} photos</span>
                    </div>
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                        @foreach($plan->photos as $index => $photo)
                            <div class="relative group cursor-pointer" onclick="openLightbox({{ $index + ($plan->cover_photo ? 1 : 0) }})">
                                <img src="{{ $photo->url }}" alt="Gallery photo {{ $index + 1 }}"
                                    class="w-full h-16 object-cover rounded-lg hover:opacity-90 transition-opacity">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Lightbox Modal --}}
        <div id="lightbox" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center">
            <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <button onclick="prevPhoto()" class="absolute left-4 text-white hover:text-gray-300 z-10">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button onclick="nextPhoto()" class="absolute right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <img id="lightbox-image" src="" alt="Lightbox" class="max-h-[90vh] max-w-[90vw] object-contain">
            <div id="lightbox-counter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-sm"></div>
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

            // Close lightbox on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight') nextPhoto();
                if (e.key === 'ArrowLeft') prevPhoto();
            });

            // Close lightbox when clicking outside the image
            document.getElementById('lightbox').addEventListener('click', function(e) {
                if (e.target === this) closeLightbox();
            });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($plan->description)) !!}
                </div>
            </div>

            <!-- Locations & Destinations -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Locations & Destinations</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Pickup Location</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $plan->pickup_location }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dropoff Location</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $plan->dropoff_location }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Destinations</dt>
                        <dd class="mt-1 flex flex-wrap gap-2">
                            @foreach($plan->destinations as $destination)
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm rounded-full">{{ $destination }}</span>
                            @endforeach
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Trip Focus</dt>
                        <dd class="mt-1 flex flex-wrap gap-2">
                            @foreach($plan->trip_focus_tags as $tag)
                                <span class="px-3 py-1 bg-teal-100 text-teal-800 text-sm rounded-full">{{ $tag }}</span>
                            @endforeach
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Day-by-Day Itinerary -->
            @if($plan->itineraries && $plan->itineraries->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Day-by-Day Itinerary
                    </h2>
                    <div class="space-y-6">
                        @foreach($plan->itineraries->sortBy('day_number') as $itinerary)
                            <div class="border-l-4 border-emerald-500 pl-4 pb-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                <div class="flex items-center mb-2">
                                    <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-semibold mr-3">
                                        Day {{ $itinerary->day_number }}
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $itinerary->day_title }}</h3>
                                </div>

                                <div class="prose prose-sm max-w-none text-gray-700 mb-4">
                                    {!! nl2br(e($itinerary->description)) !!}
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    {{-- Accommodation Info --}}
                                    @if($itinerary->hasAccommodation())
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                                <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                Accommodation
                                            </div>
                                            <p class="text-sm text-gray-900 font-medium">{{ $itinerary->accommodation_name }}</p>
                                            @if($itinerary->accommodation_type_label || $itinerary->accommodation_tier_label)
                                                <p class="text-xs text-gray-500 mt-1">
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
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex items-center text-sm font-medium text-gray-700 mb-1">
                                                <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                Meals Included
                                            </div>
                                            @if($itinerary->hasMealsIncluded())
                                                <div class="flex flex-wrap gap-2 mb-1">
                                                    @foreach($itinerary->included_meals as $meal)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                                            {{ $meal }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if($itinerary->meal_notes)
                                                <p class="text-xs text-gray-500">{{ $itinerary->meal_notes }}</p>
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
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Information</h2>
                    <dl class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Vehicle Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $plan->vehicle_type }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Seating Capacity</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $plan->vehicle_capacity ?? 'N/A' }} passengers</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Has AC</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $plan->vehicle_ac ? 'Yes' : 'No' }}</dd>
                            </div>
                        </div>
                        @if($plan->vehicle_description)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $plan->vehicle_description }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif

            <!-- Inclusions & Exclusions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">What's Included & Excluded</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-emerald-700 mb-2">Included</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $plan->inclusions }}</div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Not Included</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $plan->exclusions }}</div>
                    </div>
                </div>
            </div>

            <!-- Optional Add-ons -->
            @if($plan->addons && $plan->addons->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Optional Add-ons
                        <span class="ml-2 text-sm font-normal text-gray-500">({{ $plan->addons->count() }} available)</span>
                    </h2>
                    <div class="space-y-4">
                        @foreach($plan->addons as $addon)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $addon->addon_name }}</h3>
                                            @if($addon->day_number > 0)
                                                <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                                                    Day {{ $addon->day_number }}
                                                </span>
                                            @else
                                                <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                                    Any Day
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 mb-3">{{ $addon->addon_description }}</p>
                                        <div class="flex items-center gap-4 text-xs text-gray-500">
                                            @if($addon->max_participants)
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    Max {{ $addon->max_participants }} participants
                                                </span>
                                            @endif
                                            @if($addon->require_all_participants)
                                                <span class="flex items-center text-amber-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Requires all participants
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right ml-4">
                                        <p class="text-2xl font-bold text-purple-600">${{ number_format($addon->price_per_person, 2) }}</p>
                                        <p class="text-xs text-gray-500">per person</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Dietary & Accessibility -->
            @if($plan->dietary_options || $plan->accessibility_info)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Special Requirements</h2>
                    @if($plan->dietary_options)
                        <div class="mb-4">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Dietary Options</dt>
                            <dd class="flex flex-wrap gap-2">
                                @foreach($plan->dietary_options as $option)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm rounded-full">{{ ucfirst(str_replace('_', ' ', $option)) }}</span>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                    @if($plan->accessibility_info)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Accessibility</dt>
                            <dd class="text-sm text-gray-700">{{ $plan->accessibility_info }}</dd>
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Views</dt>
                        <dd class="mt-1 text-2xl font-bold text-emerald-600">{{ $plan->view_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bookings</dt>
                        <dd class="mt-1 text-2xl font-bold text-teal-600">{{ $plan->booking_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $plan->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $plan->updated_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Pricing -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Price per Adult</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">${{ number_format($plan->price_per_adult, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Price per Child</dt>
                        <dd class="mt-1 text-xl font-semibold text-gray-900">${{ number_format($plan->price_per_child, 2) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Group Size -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Group Size</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Minimum</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $plan->min_group_size }} people</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Maximum</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $plan->max_group_size }} people</dd>
                    </div>
                </dl>
            </div>

            <!-- Availability -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Availability</h2>
                @if($plan->availability_type === 'always_available')
                    <p class="text-sm text-emerald-600 font-medium">Always Available</p>
                @else
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">From</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $plan->available_start_date?->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">To</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $plan->available_end_date?->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
