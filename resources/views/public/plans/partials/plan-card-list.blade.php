{{-- List View Card --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <a href="{{ route('plans.show', $plan->id) }}" class="block">
        <div class="flex flex-col sm:flex-row">
            <!-- Cover Image -->
            <div class="relative w-full sm:w-72 h-48 bg-gray-200 flex-shrink-0 overflow-hidden">
                @if($plan->cover_photo)
                    <img src="{{ Storage::url($plan->cover_photo) }}" alt="{{ $plan->title }}"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600">
                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                @endif

                <!-- Availability Badge -->
                @if($plan->availability_type === 'always_available')
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                            Always Available
                        </span>
                    </div>
                @else
                    <div class="absolute top-3 right-3">
                        <span class="px-2 py-1 bg-orange-500 text-white text-xs font-semibold rounded-full">
                            Seasonal
                        </span>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="flex-1 p-6">
                <div class="flex flex-col h-full">
                    <!-- Title -->
                    <h3 class="text-xl font-semibold text-gray-900 mb-2 hover:text-blue-600 transition">
                        {{ $plan->title }}
                    </h3>

                    <!-- Description -->
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        {{ Str::limit(strip_tags($plan->description), 150) }}
                    </p>

                    <!-- Meta Info -->
                    <div class="flex flex-wrap gap-4 mb-4">
                        <!-- Duration -->
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $plan->num_days }}D/{{ $plan->num_nights }}N
                        </div>

                        <!-- Group Size -->
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {{ $plan->min_group_size }}-{{ $plan->max_group_size }} people
                        </div>

                        <!-- Vehicle -->
                        @if($plan->vehicle_type)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                {{ $plan->vehicle_type }}
                            </div>
                        @endif
                    </div>

                    <!-- Destinations -->
                    <div class="flex flex-wrap gap-1 mb-4">
                        @foreach(array_slice($plan->destinations, 0, 5) as $destination)
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">
                                {{ $destination }}
                            </span>
                        @endforeach
                        @if(count($plan->destinations) > 5)
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                +{{ count($plan->destinations) - 5 }} more
                            </span>
                        @endif
                    </div>

                    <div class="mt-auto">
                        <!-- Guide Info & Stats -->
                        <div class="flex items-center justify-between mb-4 pb-4 border-b">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                By {{ $plan->guide->full_name }}
                                @if($plan->guide->average_rating > 0)
                                    <span class="ml-2 flex items-center">
                                        <svg class="w-4 h-4 text-yellow-400 mr-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ number_format($plan->guide->average_rating, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $plan->view_count }} views • {{ $plan->booking_count }} bookings
                            </div>
                        </div>

                        <!-- Price & CTA -->
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-3xl font-bold text-gray-900">
                                    ${{ number_format($plan->price_per_adult, 0) }}
                                </span>
                                <span class="text-sm text-gray-600">/person</span>
                                @if($plan->price_per_child < $plan->price_per_adult)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Children: ${{ number_format($plan->price_per_child, 0) }}
                                    </div>
                                @endif
                            </div>
                            <span class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                View Details →
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
