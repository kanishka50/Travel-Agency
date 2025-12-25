@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <nav class="flex text-sm text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-emerald-600">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('tour-packages.index') }}" class="hover:text-emerald-600">Tour Packages</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">{{ Str::limit($plan->title, 30) }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title & Quick Info -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $plan->title }}</h1>

                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <!-- Duration -->
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">{{ $plan->num_days }} days / {{ $plan->num_nights }} nights</span>
                        </div>

                        <!-- Availability -->
                        @if($plan->availability_type === 'always_available')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm font-medium rounded-full">
                                Always Available
                            </span>
                        @else
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full">
                                {{ $plan->available_start_date?->format('M d') }} - {{ $plan->available_end_date?->format('M d, Y') }}
                            </span>
                        @endif

                        <!-- Stats -->
                        <div class="text-sm text-gray-500">
                            {{ $plan->view_count ?? 0 }} views | {{ $plan->booking_count ?? 0 }} bookings
                        </div>
                    </div>

                    <!-- Destinations -->
                    @if($plan->destinations && count($plan->destinations) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($plan->destinations as $destination)
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-sm font-medium rounded-full">
                                    {{ $destination }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Photo Gallery -->
                @if($plan->cover_photo || $plan->photos->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                        {{-- Main Photo Display --}}
                        <div class="relative">
                            @if($plan->cover_photo)
                                <img src="{{ Storage::url($plan->cover_photo) }}" alt="{{ $plan->title }}"
                                    class="w-full h-96 object-cover cursor-pointer hover:opacity-95 transition-opacity"
                                    onclick="openGallery(0)" id="main-gallery-photo">
                            @elseif($plan->photos->count() > 0)
                                <img src="{{ $plan->photos->first()->url }}" alt="{{ $plan->title }}"
                                    class="w-full h-96 object-cover cursor-pointer hover:opacity-95 transition-opacity"
                                    onclick="openGallery(0)" id="main-gallery-photo">
                            @endif

                            {{-- Photo count badge --}}
                            @php
                                $totalPhotos = ($plan->cover_photo ? 1 : 0) + $plan->photos->count();
                            @endphp
                            @if($totalPhotos > 1)
                                <button onclick="openGallery(0)" class="absolute bottom-4 right-4 bg-black bg-opacity-70 text-white px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-opacity-80 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>View all {{ $totalPhotos }} photos</span>
                                </button>
                            @endif
                        </div>

                        {{-- Thumbnail Strip --}}
                        @if($plan->photos->count() > 0)
                            <div class="p-3 bg-gray-50 border-t">
                                <div class="flex gap-2 overflow-x-auto pb-2">
                                    @if($plan->cover_photo)
                                        <div class="flex-shrink-0 cursor-pointer" onclick="openGallery(0)">
                                            <img src="{{ Storage::url($plan->cover_photo) }}" alt="Cover"
                                                class="h-16 w-24 object-cover rounded-lg hover:opacity-80 transition-opacity ring-2 ring-emerald-500">
                                        </div>
                                    @endif
                                    @foreach($plan->photos as $index => $photo)
                                        <div class="flex-shrink-0 cursor-pointer" onclick="openGallery({{ $index + ($plan->cover_photo ? 1 : 0) }})">
                                            <img src="{{ $photo->url }}" alt="Photo {{ $index + 1 }}"
                                                class="h-16 w-24 object-cover rounded-lg hover:opacity-80 transition-opacity">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Lightbox Modal --}}
                    <div id="gallery-lightbox" class="fixed inset-0 z-50 hidden bg-black bg-opacity-95">
                        <div class="absolute inset-0 flex items-center justify-center">
                            {{-- Close button --}}
                            <button onclick="closeGallery()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-20 p-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>

                            {{-- Navigation buttons --}}
                            <button onclick="prevGalleryPhoto()" class="absolute left-4 text-white hover:text-gray-300 z-20 p-2 bg-black bg-opacity-50 rounded-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button onclick="nextGalleryPhoto()" class="absolute right-4 text-white hover:text-gray-300 z-20 p-2 bg-black bg-opacity-50 rounded-full">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>

                            {{-- Main image --}}
                            <img id="gallery-lightbox-image" src="" alt="Gallery" class="max-h-[85vh] max-w-[90vw] object-contain">

                            {{-- Counter --}}
                            <div id="gallery-counter" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm"></div>
                        </div>

                        {{-- Thumbnail strip in lightbox --}}
                        <div class="absolute bottom-16 left-0 right-0 px-4">
                            <div class="flex justify-center gap-2 overflow-x-auto py-2" id="gallery-thumbnails">
                                @if($plan->cover_photo)
                                    <div class="flex-shrink-0 cursor-pointer gallery-thumb" onclick="goToGalleryPhoto(0)">
                                        <img src="{{ Storage::url($plan->cover_photo) }}" alt="Cover"
                                            class="h-12 w-16 object-cover rounded opacity-60 hover:opacity-100 transition-opacity">
                                    </div>
                                @endif
                                @foreach($plan->photos as $index => $photo)
                                    <div class="flex-shrink-0 cursor-pointer gallery-thumb" onclick="goToGalleryPhoto({{ $index + ($plan->cover_photo ? 1 : 0) }})">
                                        <img src="{{ $photo->url }}" alt="Photo {{ $index + 1 }}"
                                            class="h-12 w-16 object-cover rounded opacity-60 hover:opacity-100 transition-opacity">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <script>
                        const galleryPhotos = [
                            @if($plan->cover_photo)
                                "{{ Storage::url($plan->cover_photo) }}",
                            @endif
                            @foreach($plan->photos as $photo)
                                "{{ $photo->url }}",
                            @endforeach
                        ];
                        let currentGalleryIndex = 0;

                        function openGallery(index) {
                            currentGalleryIndex = index;
                            updateGalleryImage();
                            document.getElementById('gallery-lightbox').classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        }

                        function closeGallery() {
                            document.getElementById('gallery-lightbox').classList.add('hidden');
                            document.body.style.overflow = '';
                        }

                        function nextGalleryPhoto() {
                            currentGalleryIndex = (currentGalleryIndex + 1) % galleryPhotos.length;
                            updateGalleryImage();
                        }

                        function prevGalleryPhoto() {
                            currentGalleryIndex = (currentGalleryIndex - 1 + galleryPhotos.length) % galleryPhotos.length;
                            updateGalleryImage();
                        }

                        function goToGalleryPhoto(index) {
                            currentGalleryIndex = index;
                            updateGalleryImage();
                        }

                        function updateGalleryImage() {
                            document.getElementById('gallery-lightbox-image').src = galleryPhotos[currentGalleryIndex];
                            document.getElementById('gallery-counter').textContent = `${currentGalleryIndex + 1} / ${galleryPhotos.length}`;

                            // Update thumbnail highlighting
                            document.querySelectorAll('.gallery-thumb img').forEach((thumb, index) => {
                                if (index === currentGalleryIndex) {
                                    thumb.classList.remove('opacity-60');
                                    thumb.classList.add('opacity-100', 'ring-2', 'ring-white');
                                } else {
                                    thumb.classList.add('opacity-60');
                                    thumb.classList.remove('opacity-100', 'ring-2', 'ring-white');
                                }
                            });
                        }

                        // Keyboard navigation
                        document.addEventListener('keydown', function(e) {
                            const lightbox = document.getElementById('gallery-lightbox');
                            if (!lightbox.classList.contains('hidden')) {
                                if (e.key === 'Escape') closeGallery();
                                if (e.key === 'ArrowRight') nextGalleryPhoto();
                                if (e.key === 'ArrowLeft') prevGalleryPhoto();
                            }
                        });

                        // Close on background click
                        document.getElementById('gallery-lightbox').addEventListener('click', function(e) {
                            if (e.target === this || e.target.classList.contains('flex')) {
                                closeGallery();
                            }
                        });
                    </script>
                @endif

                <!-- Description -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($plan->description)) !!}
                    </div>
                </div>

                <!-- Trip Focus Tags -->
                @if($plan->trip_focus_tags && count($plan->trip_focus_tags) > 0)
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Trip Focus</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($plan->trip_focus_tags as $tag)
                                <span class="px-4 py-2 bg-purple-50 text-purple-700 font-medium rounded-lg">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Day-by-Day Itinerary -->
                @if($plan->itineraries && $plan->itineraries->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-7 h-7 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Day-by-Day Itinerary
                        </h2>

                        <div class="relative">
                            {{-- Timeline line --}}
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-emerald-200"></div>

                            <div class="space-y-8">
                                @foreach($plan->itineraries->sortBy('day_number') as $itinerary)
                                    <div class="relative pl-12">
                                        {{-- Day circle --}}
                                        <div class="absolute left-0 w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm font-bold shadow-md">
                                            {{ $itinerary->day_number }}
                                        </div>

                                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                                            {{-- Day Title --}}
                                            <h3 class="text-lg font-bold text-gray-900 mb-3">
                                                Day {{ $itinerary->day_number }}: {{ $itinerary->day_title }}
                                            </h3>

                                            {{-- Description --}}
                                            <div class="prose prose-sm max-w-none text-gray-700 mb-4">
                                                {!! nl2br(e($itinerary->description)) !!}
                                            </div>

                                            {{-- Accommodation & Meals Info --}}
                                            <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-gray-200">
                                                {{-- Accommodation Badge --}}
                                                @if($itinerary->hasAccommodation())
                                                    <div class="flex items-center bg-white px-4 py-2 rounded-lg shadow-sm border">
                                                        <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                        <div>
                                                            <span class="font-medium text-gray-900">{{ $itinerary->accommodation_name }}</span>
                                                            @if($itinerary->accommodation_type_label || $itinerary->accommodation_tier_label)
                                                                <span class="text-sm text-gray-500 ml-1">
                                                                    ({{ collect([$itinerary->accommodation_type_label, $itinerary->accommodation_tier_label])->filter()->implode(' • ') }})
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- Meals Badges --}}
                                                @if($itinerary->hasMealsIncluded())
                                                    <div class="flex items-center bg-white px-4 py-2 rounded-lg shadow-sm border">
                                                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                        </svg>
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($itinerary->included_meals as $meal)
                                                                <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-full">{{ $meal }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Meal Notes --}}
                                            @if($itinerary->meal_notes)
                                                <p class="text-sm text-gray-500 mt-3 italic">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $itinerary->meal_notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Inclusions & Exclusions -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">What's Included</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Inclusions -->
                        <div>
                            <h3 class="font-semibold text-emerald-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Included
                            </h3>
                            <div class="space-y-2 text-gray-700">
                                @if($plan->inclusions)
                                    @foreach(explode("\n", $plan->inclusions) as $inclusion)
                                        @if(trim($inclusion))
                                            <div class="flex items-start">
                                                <svg class="w-4 h-4 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                <span>{{ trim($inclusion, '- ') }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Exclusions -->
                        <div>
                            <h3 class="font-semibold text-red-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Not Included
                            </h3>
                            <div class="space-y-2 text-gray-700">
                                @if($plan->exclusions)
                                    @foreach(explode("\n", $plan->exclusions) as $exclusion)
                                        @if(trim($exclusion))
                                            <div class="flex items-start">
                                                <svg class="w-4 h-4 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                <span>{{ trim($exclusion, '- ') }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Optional Add-ons -->
                @if($plan->addons && $plan->addons->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg class="w-7 h-7 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Optional Add-ons
                        </h2>
                        <p class="text-gray-600 mb-6">Enhance your experience with these optional activities and services. Add-ons can be selected when booking.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($plan->addons as $addon)
                                <div class="border border-gray-200 rounded-xl p-5 hover:border-purple-300 hover:shadow-md transition-all bg-gradient-to-br from-white to-purple-50">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900">{{ $addon->addon_name }}</h3>
                                            @if($addon->day_number > 0)
                                                <span class="inline-block mt-1 px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                                                    Day {{ $addon->day_number }}
                                                </span>
                                            @else
                                                <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                                    Available Any Day
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-purple-600">${{ number_format($addon->price_per_person, 2) }}</p>
                                            <p class="text-xs text-gray-500">per person</p>
                                        </div>
                                    </div>

                                    <p class="text-sm text-gray-600 mb-3">{{ $addon->addon_description }}</p>

                                    <div class="flex items-center gap-3 text-xs text-gray-500">
                                        @if($addon->max_participants)
                                            <span class="flex items-center bg-white px-2 py-1 rounded-full border">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                Max {{ $addon->max_participants }}
                                            </span>
                                        @endif
                                        @if($addon->require_all_participants)
                                            <span class="flex items-center bg-amber-50 text-amber-700 px-2 py-1 rounded-full border border-amber-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                All must participate
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Transportation -->
                @if($plan->vehicle_type)
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Transportation</h2>
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-sm text-gray-600 mb-1">Vehicle Type</div>
                                <div class="font-semibold text-gray-900">{{ $plan->vehicle_type }}</div>
                            </div>
                            @if($plan->vehicle_capacity)
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <div class="text-sm text-gray-600 mb-1">Seats</div>
                                    <div class="font-semibold text-gray-900">{{ $plan->vehicle_capacity }} passengers</div>
                                </div>
                            @endif
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-sm text-gray-600 mb-1">Air Conditioning</div>
                                <div class="font-semibold text-gray-900">{{ $plan->vehicle_ac ? 'Yes' : 'No' }}</div>
                            </div>
                        </div>
                        @if($plan->vehicle_description)
                            <p class="text-gray-700">{{ $plan->vehicle_description }}</p>
                        @endif
                    </div>
                @endif

                <!-- Dietary Options -->
                @if($plan->dietary_options && count($plan->dietary_options) > 0)
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Dietary Options Available</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($plan->dietary_options as $option)
                                <span class="px-3 py-2 bg-emerald-50 text-emerald-700 font-medium rounded-lg">
                                    {{ ucfirst(str_replace('_', ' ', $option)) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Accessibility -->
                @if($plan->accessibility_info)
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Accessibility Information</h2>
                        <p class="text-gray-700">{{ $plan->accessibility_info }}</p>
                    </div>
                @endif

                <!-- Guide Information -->
                <div class="bg-white rounded-xl shadow-sm border p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Your Guide</h2>
                    <div class="flex items-start space-x-4">
                        @if($plan->guide->profile_photo)
                            <img src="{{ Storage::url($plan->guide->profile_photo) }}" alt="{{ $plan->guide->full_name }}"
                                class="w-20 h-20 rounded-full object-cover">
                        @else
                            <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $plan->guide->full_name }}</h3>
                            <span class="inline-block px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full mb-2">{{ $plan->guide->guide_type_label ?? 'Guide' }}</span>
                            <div class="flex items-center mt-1 mb-2">
                                @if($plan->guide->average_rating > 0)
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $plan->guide->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">{{ number_format($plan->guide->average_rating, 1) }} ({{ $plan->guide->total_reviews ?? 0 }} reviews)</span>
                                    </div>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                @if($plan->guide->languages && count($plan->guide->languages) > 0)
                                    <div>Languages: {{ implode(', ', $plan->guide->languages) }}</div>
                                @endif
                                @if($plan->guide->regions_can_guide && count($plan->guide->regions_can_guide) > 0)
                                    <div>Regions: {{ implode(', ', $plan->guide->regions_can_guide) }}</div>
                                @endif
                                @if($plan->guide->years_experience)
                                    <div>{{ $plan->guide->years_experience }} years of experience</div>
                                @endif
                                @if($plan->guide->total_bookings)
                                    <div>{{ $plan->guide->total_bookings }} completed tours</div>
                                @endif
                            </div>
                            @if($plan->guide->bio)
                                <p class="mt-3 text-gray-700">{{ $plan->guide->bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                @if($plan->reviews && $plan->reviews->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Reviews</h2>
                        <div class="space-y-4">
                            @foreach($plan->reviews->take(5) as $review)
                                <div class="border-b pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-gray-600">{{ $review->review_text }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Related Plans -->
                @if(isset($relatedPlans) && $relatedPlans->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Tours</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($relatedPlans as $relatedPlan)
                                @include('public.tour-packages.partials.package-card', ['plan' => $relatedPlan])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-4 space-y-6">
                    <!-- Price Calculator -->
                    @include('public.tour-packages.partials.price-calculator', ['plan' => $plan])

                    <!-- Availability Calendar -->
                    @include('public.tour-packages.partials.availability-calendar', ['plan' => $plan])

                    <!-- Quick Info -->
                    <div class="bg-white rounded-xl shadow-sm border p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Quick Info</h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Group Size</dt>
                                <dd class="font-medium text-gray-900">{{ $plan->min_group_size }}-{{ $plan->max_group_size }} people</dd>
                            </div>
                            @if($plan->pickup_location)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Pickup</dt>
                                    <dd class="font-medium text-gray-900">{{ Str::limit($plan->pickup_location, 20) }}</dd>
                                </div>
                            @endif
                            @if($plan->dropoff_location)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Dropoff</dt>
                                    <dd class="font-medium text-gray-900">{{ Str::limit($plan->dropoff_location, 20) }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Share Buttons -->
                    @include('public.tour-packages.partials.share-buttons', ['plan' => $plan])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
