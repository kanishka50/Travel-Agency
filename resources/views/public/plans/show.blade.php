@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <nav class="flex text-sm text-gray-600">
                <a href="{{ route('plans.index') }}" class="hover:text-blue-600">Tours</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">{{ $plan->title }}</span>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title & Quick Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $plan->title }}</h1>

                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <!-- Duration -->
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">{{ $plan->num_days }} days / {{ $plan->num_nights }} nights</span>
                        </div>

                        <!-- Availability -->
                        @if($plan->availability_type === 'always_available')
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                Always Available
                            </span>
                        @else
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full">
                                {{ $plan->available_start_date?->format('M d') }} - {{ $plan->available_end_date?->format('M d, Y') }}
                            </span>
                        @endif

                        <!-- Stats -->
                        <div class="text-sm text-gray-500">
                            {{ $plan->view_count }} views • {{ $plan->booking_count }} bookings
                        </div>
                    </div>

                    <!-- Destinations -->
                    <div class="flex flex-wrap gap-2">
                        @foreach($plan->destinations as $destination)
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-sm font-medium rounded-full">
                                📍 {{ $destination }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Cover Photo -->
                @if($plan->cover_photo)
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <img src="{{ Storage::url($plan->cover_photo) }}" alt="{{ $plan->title }}"
                            class="w-full h-96 object-cover">
                    </div>
                @endif

                <!-- Description -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($plan->description)) !!}
                    </div>
                </div>

                <!-- Trip Focus Tags -->
                @if(count($plan->trip_focus_tags) > 0)
                    <div class="bg-white rounded-lg shadow p-6">
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

                <!-- Inclusions & Exclusions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">What's Included</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Inclusions -->
                        <div>
                            <h3 class="font-semibold text-green-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Included
                            </h3>
                            <div class="space-y-2 text-gray-700">
                                @foreach(explode("\n", $plan->inclusions) as $inclusion)
                                    @if(trim($inclusion))
                                        <div class="flex items-start">
                                            <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ trim($inclusion, '- ') }}</span>
                                        </div>
                                    @endif
                                @endforeach
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
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Information -->
                @if($plan->vehicle_type)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Transportation</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-sm text-gray-600 mb-1">Vehicle Type</div>
                                <div class="font-semibold text-gray-900">{{ $plan->vehicle_type }}</div>
                            </div>
                            @if($plan->vehicle_category)
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <div class="text-sm text-gray-600 mb-1">Category</div>
                                    <div class="font-semibold text-gray-900">{{ $plan->vehicle_category }}</div>
                                </div>
                            @endif
                            @if($plan->vehicle_capacity)
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <div class="text-sm text-gray-600 mb-1">Capacity</div>
                                    <div class="font-semibold text-gray-900">{{ $plan->vehicle_capacity }} people</div>
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
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Dietary Options Available</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($plan->dietary_options as $option)
                                <span class="px-3 py-2 bg-green-50 text-green-700 font-medium rounded-lg">
                                    {{ ucfirst(str_replace('_', ' ', $option)) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Accessibility -->
                @if($plan->accessibility_info)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Accessibility Information</h2>
                        <p class="text-gray-700">{{ $plan->accessibility_info }}</p>
                    </div>
                @endif

                <!-- Cancellation Policy -->
                @if($plan->cancellation_policy)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Cancellation Policy</h2>
                        <div class="text-gray-700 whitespace-pre-line">{{ $plan->cancellation_policy }}</div>
                    </div>
                @endif

                <!-- Guide Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Your Guide</h2>
                    <div class="flex items-start space-x-4">
                        @if($plan->guide->profile_photo)
                            <img src="{{ Storage::url($plan->guide->profile_photo) }}" alt="{{ $plan->guide->full_name }}"
                                class="w-20 h-20 rounded-full object-cover">
                        @else
                            <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-900">{{ $plan->guide->full_name }}</h3>
                            <div class="flex items-center mt-1 mb-2">
                                @if($plan->guide->average_rating > 0)
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $plan->guide->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">{{ number_format($plan->guide->average_rating, 1) }} ({{ $plan->guide->total_reviews }} reviews)</span>
                                    </div>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div>🗣️ Languages: {{ implode(', ', $plan->guide->languages) }}</div>
                                <div>📍 Regions: {{ implode(', ', $plan->guide->regions_can_guide) }}</div>
                                <div>⭐ {{ $plan->guide->years_experience }} years of experience</div>
                                <div>✅ {{ $plan->guide->total_bookings }} completed tours</div>
                            </div>
                            @if($plan->guide->bio)
                                <p class="mt-3 text-gray-700">{{ $plan->guide->bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Related Plans -->
                @if($relatedPlans->isNotEmpty())
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Tours</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($relatedPlans as $relatedPlan)
                                @include('public.plans.partials.plan-card-grid', ['plan' => $relatedPlan])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-4 space-y-6">
                    <!-- Price Calculator -->
                    @include('public.plans.partials.price-calculator', ['plan' => $plan])

                    <!-- Availability Calendar -->
                    @include('public.plans.partials.availability-calendar', ['plan' => $plan])

                    <!-- Quick Info -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Quick Info</h3>
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Group Size</dt>
                                <dd class="font-medium text-gray-900">{{ $plan->min_group_size }}-{{ $plan->max_group_size }} people</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Pickup</dt>
                                <dd class="font-medium text-gray-900">{{ Str::limit($plan->pickup_location, 20) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Dropoff</dt>
                                <dd class="font-medium text-gray-900">{{ Str::limit($plan->dropoff_location, 20) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Share Buttons -->
                    @include('public.plans.partials.share-buttons', ['plan' => $plan])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
