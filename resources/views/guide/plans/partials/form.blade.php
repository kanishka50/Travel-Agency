{{-- Basic Information --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>

    <div class="space-y-4">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Tour Title *</label>
            <input type="text" name="title" id="title" value="{{ old('title', $plan?->title ?? '') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="e.g., 7-Day Cultural Tour of Sri Lanka">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
            <textarea name="description" id="description" rows="5" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="Provide a detailed description of your tour...">{{ old('description', $plan?->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="num_days" class="block text-sm font-medium text-gray-700 mb-1">Number of Days *</label>
                <input type="number" name="num_days" id="num_days" value="{{ old('num_days', $plan?->num_days ?? 1) }}" required min="1" max="30"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label for="num_nights" class="block text-sm font-medium text-gray-700 mb-1">Number of Nights *</label>
                <input type="number" name="num_nights" id="num_nights" value="{{ old('num_nights', $plan?->num_nights ?? 0) }}" required min="0" max="30"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
        </div>
    </div>
</div>

{{-- Locations & Destinations --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Locations & Destinations</h2>

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-1">Pickup Location *</label>
                <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location', $plan?->pickup_location ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    placeholder="e.g., Colombo International Airport">
            </div>
            <div>
                <label for="dropoff_location" class="block text-sm font-medium text-gray-700 mb-1">Dropoff Location *</label>
                <input type="text" name="dropoff_location" id="dropoff_location" value="{{ old('dropoff_location', $plan?->dropoff_location ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    placeholder="e.g., Colombo International Airport">
            </div>
        </div>

        <div>
            <label for="destinations" class="block text-sm font-medium text-gray-700 mb-1">Destinations (comma-separated) *</label>
            <input type="text" name="destinations_text" id="destinations_text" value="{{ old('destinations_text', is_array($plan?->destinations ?? null) ? implode(', ', $plan?->destinations) : '') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="e.g., Kandy, Nuwara Eliya, Ella, Galle">
            <input type="hidden" name="destinations" id="destinations">
        </div>

        <div>
            <label for="trip_focus_tags" class="block text-sm font-medium text-gray-700 mb-1">Trip Focus/Tags (comma-separated) *</label>
            <input type="text" name="trip_focus_tags_text" id="trip_focus_tags_text" value="{{ old('trip_focus_tags_text', is_array($plan?->trip_focus_tags ?? null) ? implode(', ', $plan?->trip_focus_tags) : '') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="e.g., Culture, Adventure, Wildlife, Beach">
            <input type="hidden" name="trip_focus_tags" id="trip_focus_tags">
        </div>
    </div>
</div>

{{-- Day-by-Day Itinerary --}}
<div class="bg-white rounded-lg shadow p-6" x-data="itineraryManager()">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Day-by-Day Itinerary</h2>
            <p class="text-sm text-gray-600 mt-1">Add detailed information for each day of the tour. This helps tourists understand what to expect.</p>
        </div>
        <button type="button" @click="addDay()" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Day
        </button>
    </div>

    {{-- Itinerary Days Container --}}
    <div id="itinerary-days" class="space-y-6">
        <template x-for="(day, index) in days" :key="index">
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                {{-- Day Header --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium">Day</span>
                        <input type="number" x-model="day.day_number" :name="'itineraries['+index+'][day_number]'" min="1" required
                            class="ml-2 w-16 px-2 py-1 border border-gray-300 rounded text-center focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </h3>
                    <button type="button" @click="removeDay(index)" class="text-red-600 hover:text-red-800 transition-colors" title="Remove this day">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>

                {{-- Hidden ID field for existing itineraries --}}
                <input type="hidden" x-model="day.id" :name="'itineraries['+index+'][id]'">

                {{-- Day Title --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Day Title *</label>
                    <input type="text" x-model="day.day_title" :name="'itineraries['+index+'][day_title]'" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="e.g., Arrival in Colombo & City Tour">
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Day Description *</label>
                    <textarea x-model="day.description" :name="'itineraries['+index+'][description]'" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Describe the activities, attractions, and experiences for this day..."></textarea>
                </div>

                {{-- Accommodation Section --}}
                <div class="bg-white rounded-lg p-4 mb-4 border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Accommodation
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Accommodation Name</label>
                            <input type="text" x-model="day.accommodation_name" :name="'itineraries['+index+'][accommodation_name]'"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="e.g., Cinnamon Grand">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select x-model="day.accommodation_type" :name="'itineraries['+index+'][accommodation_type]'"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select type</option>
                                <option value="hotel">Hotel</option>
                                <option value="guesthouse">Guesthouse</option>
                                <option value="resort">Resort</option>
                                <option value="homestay">Homestay</option>
                                <option value="camping">Camping</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tier</label>
                            <select x-model="day.accommodation_tier" :name="'itineraries['+index+'][accommodation_tier]'"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select tier</option>
                                <option value="budget">Budget</option>
                                <option value="midrange">Mid-Range</option>
                                <option value="luxury">Luxury</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Meals Section --}}
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Meals Included
                    </h4>
                    <div class="flex flex-wrap gap-6 mb-3">
                        <label class="inline-flex items-center">
                            <input type="hidden" :name="'itineraries['+index+'][breakfast_included]'" value="0">
                            <input type="checkbox" x-model="day.breakfast_included" :name="'itineraries['+index+'][breakfast_included]'" value="1"
                                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Breakfast</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="hidden" :name="'itineraries['+index+'][lunch_included]'" value="0">
                            <input type="checkbox" x-model="day.lunch_included" :name="'itineraries['+index+'][lunch_included]'" value="1"
                                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Lunch</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="hidden" :name="'itineraries['+index+'][dinner_included]'" value="0">
                            <input type="checkbox" x-model="day.dinner_included" :name="'itineraries['+index+'][dinner_included]'" value="1"
                                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Dinner</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meal Notes (Optional)</label>
                        <input type="text" x-model="day.meal_notes" :name="'itineraries['+index+'][meal_notes]'"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="e.g., Special local cuisine experience, dietary options available">
                    </div>
                </div>
            </div>
        </template>

        {{-- Empty State --}}
        <div x-show="days.length === 0" class="text-center py-8 text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="mt-2">No itinerary days added yet.</p>
            <p class="text-sm">Click "Add Day" to start building your tour itinerary.</p>
        </div>
    </div>
</div>

<script>
function itineraryManager() {
    return {
        days: @json($plan?->itineraries ?? []),

        init() {
            // Transform existing itineraries data if needed
            if (this.days.length > 0) {
                this.days = this.days.map(day => ({
                    id: day.id || '',
                    day_number: day.day_number || 1,
                    day_title: day.day_title || '',
                    description: day.description || '',
                    accommodation_name: day.accommodation_name || '',
                    accommodation_type: day.accommodation_type || '',
                    accommodation_tier: day.accommodation_tier || '',
                    breakfast_included: day.breakfast_included || false,
                    lunch_included: day.lunch_included || false,
                    dinner_included: day.dinner_included || false,
                    meal_notes: day.meal_notes || ''
                }));
            }
        },

        addDay() {
            const nextDayNumber = this.days.length > 0
                ? Math.max(...this.days.map(d => d.day_number)) + 1
                : 1;

            this.days.push({
                id: '',
                day_number: nextDayNumber,
                day_title: '',
                description: '',
                accommodation_name: '',
                accommodation_type: '',
                accommodation_tier: '',
                breakfast_included: false,
                lunch_included: false,
                dinner_included: false,
                meal_notes: ''
            });
        },

        removeDay(index) {
            if (confirm('Are you sure you want to remove this day from the itinerary?')) {
                this.days.splice(index, 1);
            }
        }
    }
}
</script>

{{-- Pricing & Group Size --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Pricing & Group Size</h2>

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="price_per_adult" class="block text-sm font-medium text-gray-700 mb-1">Price per Adult (USD) *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                    <input type="number" name="price_per_adult" id="price_per_adult" value="{{ old('price_per_adult', $plan?->price_per_adult ?? '') }}" required min="0" step="0.01"
                        class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div>
                <label for="price_per_child" class="block text-sm font-medium text-gray-700 mb-1">Price per Child (USD) *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                    <input type="number" name="price_per_child" id="price_per_child" value="{{ old('price_per_child', $plan?->price_per_child ?? '') }}" required min="0" step="0.01"
                        class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="min_group_size" class="block text-sm font-medium text-gray-700 mb-1">Minimum Group Size *</label>
                <input type="number" name="min_group_size" id="min_group_size" value="{{ old('min_group_size', $plan?->min_group_size ?? 1) }}" required min="1" max="50"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label for="max_group_size" class="block text-sm font-medium text-gray-700 mb-1">Maximum Group Size *</label>
                <input type="number" name="max_group_size" id="max_group_size" value="{{ old('max_group_size', $plan?->max_group_size ?? '') }}" required min="1" max="50"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
        </div>
    </div>
</div>

{{-- Availability --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Availability</h2>

    <div class="space-y-4">
        <div>
            <label for="availability_type" class="block text-sm font-medium text-gray-700 mb-1">Availability Type *</label>
            <select name="availability_type" id="availability_type" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="always_available" {{ old('availability_type', $plan?->availability_type ?? 'always_available') === 'always_available' ? 'selected' : '' }}>Always Available</option>
                <option value="date_range" {{ old('availability_type', $plan?->availability_type ?? 'always_available') === 'date_range' ? 'selected' : '' }}>Specific Date Range</option>
            </select>
        </div>

        <div id="date_range_fields" class="grid grid-cols-2 gap-4 {{ (old('availability_type', $plan?->availability_type ?? 'always_available') === 'date_range') ? '' : 'hidden' }}">
            <div>
                <label for="available_start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="available_start_date" id="available_start_date" value="{{ old('available_start_date', optional($plan)->available_start_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label for="available_end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="available_end_date" id="available_end_date" value="{{ old('available_end_date', optional($plan)->available_end_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
        </div>
    </div>
</div>

{{-- Vehicle Requirements --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Requirements</h2>
    <p class="text-sm text-gray-600 mb-4">Describe what type of vehicle is suitable for this tour. The actual vehicle will be assigned when a booking is confirmed.</p>

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                <select name="vehicle_type" id="vehicle_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Select type</option>
                    <option value="Car" {{ old('vehicle_type', $plan?->vehicle_type ?? '') === 'Car' ? 'selected' : '' }}>Car</option>
                    <option value="Van" {{ old('vehicle_type', $plan?->vehicle_type ?? '') === 'Van' ? 'selected' : '' }}>Van</option>
                    <option value="Mini Bus" {{ old('vehicle_type', $plan?->vehicle_type ?? '') === 'Mini Bus' ? 'selected' : '' }}>Mini Bus</option>
                    <option value="Bus" {{ old('vehicle_type', $plan?->vehicle_type ?? '') === 'Bus' ? 'selected' : '' }}>Bus</option>
                    <option value="SUV" {{ old('vehicle_type', $plan?->vehicle_type ?? '') === 'SUV' ? 'selected' : '' }}>SUV</option>
                    <option value="4WD" {{ old('vehicle_type', $plan?->vehicle_type ?? '') === '4WD' ? 'selected' : '' }}>4WD</option>
                </select>
            </div>
            <div>
                <label for="vehicle_capacity" class="block text-sm font-medium text-gray-700 mb-1">Seating Capacity</label>
                <input type="number" name="vehicle_capacity" id="vehicle_capacity" value="{{ old('vehicle_capacity', $plan?->vehicle_capacity ?? '') }}" min="1" max="50"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    placeholder="Number of passengers">
            </div>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="vehicle_ac" id="vehicle_ac" value="1" {{ old('vehicle_ac', $plan?->vehicle_ac ?? true) ? 'checked' : '' }}
                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
            <label for="vehicle_ac" class="ml-2 block text-sm text-gray-900">Has Air Conditioning</label>
        </div>

        <div>
            <label for="vehicle_description" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Description (Optional)</label>
            <textarea name="vehicle_description" id="vehicle_description" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="Optional description of vehicle features...">{{ old('vehicle_description', $plan?->vehicle_description ?? '') }}</textarea>
        </div>
    </div>
</div>

{{-- Dietary & Accessibility --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Dietary & Accessibility</h2>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Dietary Options Available</label>
            <div class="grid grid-cols-3 gap-3">
                @php
                    $dietaryOptions = ['vegetarian' => 'Vegetarian', 'vegan' => 'Vegan', 'halal' => 'Halal', 'kosher' => 'Kosher', 'gluten_free' => 'Gluten-Free', 'lactose_free' => 'Lactose-Free', 'nut_free' => 'Nut-Free'];
                    $selectedDietary = old('dietary_options', $plan?->dietary_options ?? []);
                @endphp
                @foreach($dietaryOptions as $value => $label)
                    <div class="flex items-center">
                        <input type="checkbox" name="dietary_options[]" id="dietary_{{ $value }}" value="{{ $value }}" {{ in_array($value, (array)$selectedDietary) ? 'checked' : '' }}
                            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <label for="dietary_{{ $value }}" class="ml-2 block text-sm text-gray-900">{{ $label }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <label for="accessibility_info" class="block text-sm font-medium text-gray-700 mb-1">Accessibility Information</label>
            <textarea name="accessibility_info" id="accessibility_info" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="Describe accessibility features (wheelchair access, mobility assistance, etc.)...">{{ old('accessibility_info', $plan?->accessibility_info ?? '') }}</textarea>
        </div>
    </div>
</div>

{{-- Inclusions & Exclusions --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Inclusions & Exclusions</h2>

    <div class="space-y-4">
        <div>
            <label for="inclusions" class="block text-sm font-medium text-gray-700 mb-1">What's Included *</label>
            <textarea name="inclusions" id="inclusions" rows="5" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="List what's included (one per line):&#10;- Accommodation&#10;- Meals&#10;- Transportation&#10;- Entrance fees">{{ old('inclusions', $plan?->inclusions ?? '') }}</textarea>
        </div>

        <div>
            <label for="exclusions" class="block text-sm font-medium text-gray-700 mb-1">What's NOT Included *</label>
            <textarea name="exclusions" id="exclusions" rows="5" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                placeholder="List what's NOT included (one per line):&#10;- International flights&#10;- Personal expenses&#10;- Travel insurance&#10;- Tips">{{ old('exclusions', $plan?->exclusions ?? '') }}</textarea>
        </div>
    </div>
</div>

{{-- Optional Add-ons --}}
<div class="bg-white rounded-lg shadow p-6" x-data="addonsManager()">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Optional Add-ons</h2>
            <p class="text-sm text-gray-600 mt-1">Add optional activities or services that tourists can add to their booking for an extra fee.</p>
        </div>
        <button type="button" @click="addAddon()" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Add-on
        </button>
    </div>

    {{-- Add-ons Container --}}
    <div id="addons-container" class="space-y-4">
        <template x-for="(addon, index) in addons" :key="index">
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                {{-- Add-on Header --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium mr-2">Add-on</span>
                        <span x-text="addon.addon_name || 'New Add-on'" class="text-gray-600"></span>
                    </h3>
                    <button type="button" @click="removeAddon(index)" class="text-red-600 hover:text-red-800 transition-colors" title="Remove this add-on">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>

                {{-- Hidden ID field for existing add-ons --}}
                <input type="hidden" x-model="addon.id" :name="'addons['+index+'][id]'">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    {{-- Add-on Name --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Add-on Name *</label>
                        <input type="text" x-model="addon.addon_name" :name="'addons['+index+'][addon_name]'" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            placeholder="e.g., Hot Air Balloon Ride, Cooking Class">
                    </div>

                    {{-- Day Number --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Available on Day</label>
                        <select x-model="addon.day_number" :name="'addons['+index+'][day_number]'"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="0">Any Day</option>
                            @for($i = 1; $i <= 30; $i++)
                                <option value="{{ $i }}">Day {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea x-model="addon.addon_description" :name="'addons['+index+'][addon_description]'" rows="3" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                        placeholder="Describe what's included in this add-on..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Price Per Person --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price per Person (USD) *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" x-model="addon.price_per_person" :name="'addons['+index+'][price_per_person]'"
                                min="0" step="0.01" required
                                class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="0.00">
                        </div>
                    </div>

                    {{-- Max Participants --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Participants</label>
                        <input type="number" x-model="addon.max_participants" :name="'addons['+index+'][max_participants]'"
                            min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            placeholder="Leave empty for no limit">
                    </div>

                    {{-- Require All Participants --}}
                    <div class="flex items-end pb-2">
                        <label class="inline-flex items-center">
                            <input type="hidden" :name="'addons['+index+'][require_all_participants]'" value="0">
                            <input type="checkbox" x-model="addon.require_all_participants" :name="'addons['+index+'][require_all_participants]'" value="1"
                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Require all participants</span>
                        </label>
                    </div>
                </div>
            </div>
        </template>

        {{-- Empty State --}}
        <div x-show="addons.length === 0" class="text-center py-8 text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <p class="mt-2">No add-ons created yet.</p>
            <p class="text-sm">Click "Add Add-on" to offer optional extras for this tour.</p>
        </div>
    </div>
</div>

<script>
function addonsManager() {
    return {
        addons: @json($plan?->addons ?? []),

        init() {
            // Transform existing add-ons data if needed
            if (this.addons.length > 0) {
                this.addons = this.addons.map(addon => ({
                    id: addon.id || '',
                    addon_name: addon.addon_name || '',
                    addon_description: addon.addon_description || '',
                    day_number: addon.day_number || 0,
                    price_per_person: addon.price_per_person || '',
                    require_all_participants: addon.require_all_participants || false,
                    max_participants: addon.max_participants || ''
                }));
            }
        },

        addAddon() {
            this.addons.push({
                id: '',
                addon_name: '',
                addon_description: '',
                day_number: 0,
                price_per_person: '',
                require_all_participants: false,
                max_participants: ''
            });
        },

        removeAddon(index) {
            if (confirm('Are you sure you want to remove this add-on?')) {
                this.addons.splice(index, 1);
            }
        }
    }
}
</script>

{{-- Proposal Settings --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Proposal Settings</h2>
    <p class="text-gray-600 text-sm mb-4">Allow tourists to send price proposals for this tour. They can request modifications and negotiate pricing.</p>

    <div class="space-y-4">
        <div class="flex items-center">
            <input type="hidden" name="allow_proposals" value="0">
            <input type="checkbox" name="allow_proposals" id="allow_proposals" value="1"
                {{ old('allow_proposals', $plan?->allow_proposals ?? true) ? 'checked' : '' }}
                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
            <label for="allow_proposals" class="ml-2 block text-sm text-gray-900 font-medium">Allow tourists to send proposals</label>
        </div>

        <div id="proposal_settings" class="{{ old('allow_proposals', $plan?->allow_proposals ?? true) ? '' : 'hidden' }}">
            <label for="min_proposal_price" class="block text-sm font-medium text-gray-700 mb-1">Minimum Acceptable Price (USD)</label>
            <div class="relative max-w-xs">
                <span class="absolute left-3 top-2 text-gray-500">$</span>
                <input type="number" name="min_proposal_price" id="min_proposal_price"
                    value="{{ old('min_proposal_price', $plan?->min_proposal_price ?? '') }}"
                    min="0" step="0.01"
                    class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    placeholder="Leave empty for no minimum">
            </div>
            <p class="mt-1 text-sm text-gray-500">Proposals below this price will show a warning to tourists, but they can still submit.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const allowProposals = document.getElementById('allow_proposals');
    const proposalSettings = document.getElementById('proposal_settings');

    if (allowProposals && proposalSettings) {
        allowProposals.addEventListener('change', function() {
            if (this.checked) {
                proposalSettings.classList.remove('hidden');
            } else {
                proposalSettings.classList.add('hidden');
            }
        });
    }
});
</script>

{{-- Cover Photo --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Cover Photo</h2>
    <p class="text-gray-600 text-sm mb-4">This is the main photo that will be displayed in tour listings and as the primary image.</p>

    <div>
        <label for="cover_photo" class="block text-sm font-medium text-gray-700 mb-1">Upload Cover Photo</label>
        @if(isset($plan) && $plan?->cover_photo)
            <div class="mb-4">
                <img src="{{ Storage::url($plan?->cover_photo) }}" alt="Current cover" class="h-32 w-auto rounded shadow">
                <p class="text-sm text-gray-500 mt-2">Current cover photo</p>
            </div>
        @endif
        <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        <p class="mt-1 text-sm text-gray-500">Max size: 5MB. Recommended: 1920x1080px</p>
    </div>
</div>

{{-- Gallery Photos --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Gallery Photos</h2>
    <p class="text-gray-600 text-sm mb-4">Add additional photos to showcase your tour. You can upload up to 25 photos. These will be displayed in a gallery on the tour page.</p>

    {{-- Existing Photos --}}
    @if(isset($plan) && $plan->photos && $plan->photos->count() > 0)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Current Gallery Photos ({{ $plan->photos->count() }})</label>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4" id="existing-photos">
                @foreach($plan->photos as $photo)
                    <div class="relative group" data-photo-id="{{ $photo->id }}">
                        <img src="{{ $photo->url }}" alt="Gallery photo" class="w-full h-24 object-cover rounded-lg shadow">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all rounded-lg flex items-center justify-center">
                            <label class="hidden group-hover:flex items-center cursor-pointer">
                                <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <span class="ml-2 text-white text-sm font-medium">Delete</span>
                            </label>
                        </div>
                        <input type="hidden" name="photo_order[]" value="{{ $photo->id }}">
                    </div>
                @endforeach
            </div>
            <p class="mt-2 text-sm text-gray-500">Hover over a photo and check the box to mark it for deletion.</p>
        </div>
    @endif

    {{-- Upload New Photos --}}
    <div>
        <label for="gallery_photos" class="block text-sm font-medium text-gray-700 mb-1">Upload New Photos</label>
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-emerald-500 transition-colors" id="gallery-dropzone">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                    <label for="gallery_photos" class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                        <span>Upload photos</span>
                        <input id="gallery_photos" name="gallery_photos[]" type="file" class="sr-only" multiple accept="image/*">
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB each (max 25 photos)</p>
            </div>
        </div>

        {{-- Preview of new uploads --}}
        <div id="gallery-preview" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 hidden"></div>
        <p id="gallery-count" class="mt-2 text-sm text-gray-500 hidden"></p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const galleryInput = document.getElementById('gallery_photos');
    const galleryPreview = document.getElementById('gallery-preview');
    const galleryCount = document.getElementById('gallery-count');
    const galleryDropzone = document.getElementById('gallery-dropzone');

    if (galleryInput) {
        galleryInput.addEventListener('change', function(e) {
            updateGalleryPreview(this.files);
        });
    }

    // Drag and drop functionality
    if (galleryDropzone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            galleryDropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            galleryDropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            galleryDropzone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            galleryDropzone.classList.add('border-emerald-500', 'bg-emerald-50');
        }

        function unhighlight(e) {
            galleryDropzone.classList.remove('border-emerald-500', 'bg-emerald-50');
        }

        galleryDropzone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            galleryInput.files = files;
            updateGalleryPreview(files);
        }
    }

    function updateGalleryPreview(files) {
        galleryPreview.innerHTML = '';

        if (files.length > 0) {
            galleryPreview.classList.remove('hidden');
            galleryCount.classList.remove('hidden');
            galleryCount.textContent = `${files.length} photo(s) selected for upload`;

            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-lg shadow">
                            <span class="absolute bottom-1 right-1 bg-emerald-600 text-white text-xs px-2 py-1 rounded">New</span>
                        `;
                        galleryPreview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            galleryPreview.classList.add('hidden');
            galleryCount.classList.add('hidden');
        }
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Convert comma-separated inputs to arrays before form submission
    const form = document.querySelector('form');

    function convertTextToJSON() {
        // Destinations
        const destinationsText = document.getElementById('destinations_text').value;
        const destinationsArray = destinationsText.split(',').map(item => item.trim()).filter(item => item);
        document.getElementById('destinations').value = JSON.stringify(destinationsArray);

        // Trip focus tags
        const tagsText = document.getElementById('trip_focus_tags_text').value;
        const tagsArray = tagsText.split(',').map(item => item.trim()).filter(item => item);
        document.getElementById('trip_focus_tags').value = JSON.stringify(tagsArray);
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            convertTextToJSON();
        });
    }
});
</script>
