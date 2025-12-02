{{-- Basic Information --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>

    <div class="space-y-4">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Tour Title *</label>
            <input type="text" name="title" id="title" value="{{ old('title', $plan?->title ?? '') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="e.g., 7-Day Cultural Tour of Sri Lanka">
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
            <textarea name="description" id="description" rows="5" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Provide a detailed description of your tour...">{{ old('description', $plan?->description ?? '') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="num_days" class="block text-sm font-medium text-gray-700 mb-1">Number of Days *</label>
                <input type="number" name="num_days" id="num_days" value="{{ old('num_days', $plan?->num_days ?? 1) }}" required min="1" max="30"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="num_nights" class="block text-sm font-medium text-gray-700 mb-1">Number of Nights *</label>
                <input type="number" name="num_nights" id="num_nights" value="{{ old('num_nights', $plan?->num_nights ?? 0) }}" required min="0" max="30"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., Colombo International Airport">
            </div>
            <div>
                <label for="dropoff_location" class="block text-sm font-medium text-gray-700 mb-1">Dropoff Location *</label>
                <input type="text" name="dropoff_location" id="dropoff_location" value="{{ old('dropoff_location', $plan?->dropoff_location ?? '') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., Colombo International Airport">
            </div>
        </div>

        <div>
            <label for="destinations" class="block text-sm font-medium text-gray-700 mb-1">Destinations (comma-separated) *</label>
            <input type="text" name="destinations_text" id="destinations_text" value="{{ old('destinations_text', is_array($plan?->destinations ?? null) ? implode(', ', $plan?->destinations) : '') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="e.g., Kandy, Nuwara Eliya, Ella, Galle">
            <input type="hidden" name="destinations" id="destinations">
        </div>

        <div>
            <label for="trip_focus_tags" class="block text-sm font-medium text-gray-700 mb-1">Trip Focus/Tags (comma-separated) *</label>
            <input type="text" name="trip_focus_tags_text" id="trip_focus_tags_text" value="{{ old('trip_focus_tags_text', is_array($plan?->trip_focus_tags ?? null) ? implode(', ', $plan?->trip_focus_tags) : '') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="e.g., Culture, Adventure, Wildlife, Beach">
            <input type="hidden" name="trip_focus_tags" id="trip_focus_tags">
        </div>
    </div>
</div>

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
                        class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div>
                <label for="price_per_child" class="block text-sm font-medium text-gray-700 mb-1">Price per Child (USD) *</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                    <input type="number" name="price_per_child" id="price_per_child" value="{{ old('price_per_child', $plan?->price_per_child ?? '') }}" required min="0" step="0.01"
                        class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="min_group_size" class="block text-sm font-medium text-gray-700 mb-1">Minimum Group Size *</label>
                <input type="number" name="min_group_size" id="min_group_size" value="{{ old('min_group_size', $plan?->min_group_size ?? 1) }}" required min="1" max="50"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="max_group_size" class="block text-sm font-medium text-gray-700 mb-1">Maximum Group Size *</label>
                <input type="number" name="max_group_size" id="max_group_size" value="{{ old('max_group_size', $plan?->max_group_size ?? '') }}" required min="1" max="50"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="always_available" {{ old('availability_type', $plan?->availability_type ?? 'always_available') === 'always_available' ? 'selected' : '' }}>Always Available</option>
                <option value="date_range" {{ old('availability_type', $plan?->availability_type ?? 'always_available') === 'date_range' ? 'selected' : '' }}>Specific Date Range</option>
            </select>
        </div>

        <div id="date_range_fields" class="grid grid-cols-2 gap-4 {{ (old('availability_type', $plan?->availability_type ?? 'always_available') === 'date_range') ? '' : 'hidden' }}">
            <div>
                <label for="available_start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="available_start_date" id="available_start_date" value="{{ old('available_start_date', optional($plan)->available_start_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="available_end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="available_end_date" id="available_end_date" value="{{ old('available_end_date', optional($plan)->available_end_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>
</div>

{{-- Vehicle Information --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Information</h2>

    <div class="space-y-4">
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                <select name="vehicle_type" id="vehicle_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                <label for="vehicle_category" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Category</label>
                <select name="vehicle_category" id="vehicle_category"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select category</option>
                    <option value="Economy" {{ old('vehicle_category', $plan?->vehicle_category ?? '') === 'Economy' ? 'selected' : '' }}>Economy</option>
                    <option value="Standard" {{ old('vehicle_category', $plan?->vehicle_category ?? '') === 'Standard' ? 'selected' : '' }}>Standard</option>
                    <option value="Luxury" {{ old('vehicle_category', $plan?->vehicle_category ?? '') === 'Luxury' ? 'selected' : '' }}>Luxury</option>
                    <option value="Premium" {{ old('vehicle_category', $plan?->vehicle_category ?? '') === 'Premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>
            <div>
                <label for="vehicle_capacity" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Capacity</label>
                <input type="number" name="vehicle_capacity" id="vehicle_capacity" value="{{ old('vehicle_capacity', $plan?->vehicle_capacity ?? '') }}" min="1" max="50"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Number of passengers">
            </div>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="vehicle_ac" id="vehicle_ac" value="1" {{ old('vehicle_ac', $plan?->vehicle_ac ?? true) ? 'checked' : '' }}
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="vehicle_ac" class="ml-2 block text-sm text-gray-900">Air Conditioning Available</label>
        </div>

        <div>
            <label for="vehicle_description" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Description</label>
            <textarea name="vehicle_description" id="vehicle_description" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Describe the vehicle features and comfort...">{{ old('vehicle_description', $plan?->vehicle_description ?? '') }}</textarea>
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
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="dietary_{{ $value }}" class="ml-2 block text-sm text-gray-900">{{ $label }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <label for="accessibility_info" class="block text-sm font-medium text-gray-700 mb-1">Accessibility Information</label>
            <textarea name="accessibility_info" id="accessibility_info" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="List what's included (one per line):&#10;- Accommodation&#10;- Meals&#10;- Transportation&#10;- Entrance fees">{{ old('inclusions', $plan?->inclusions ?? '') }}</textarea>
        </div>

        <div>
            <label for="exclusions" class="block text-sm font-medium text-gray-700 mb-1">What's NOT Included *</label>
            <textarea name="exclusions" id="exclusions" rows="5" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="List what's NOT included (one per line):&#10;- International flights&#10;- Personal expenses&#10;- Travel insurance&#10;- Tips">{{ old('exclusions', $plan?->exclusions ?? '') }}</textarea>
        </div>
    </div>
</div>

{{-- Cancellation Policy --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Cancellation Policy</h2>

    <div>
        <label for="cancellation_policy" class="block text-sm font-medium text-gray-700 mb-1">Cancellation Policy</label>
        <textarea name="cancellation_policy" id="cancellation_policy" rows="4"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Describe your cancellation policy and refund terms...">{{ old('cancellation_policy', $plan?->cancellation_policy ?? '') }}</textarea>
    </div>
</div>

{{-- Proposal Settings --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Proposal Settings</h2>
    <p class="text-gray-600 text-sm mb-4">Allow tourists to send price proposals for this tour. They can request modifications and negotiate pricing.</p>

    <div class="space-y-4">
        <div class="flex items-center">
            <input type="hidden" name="allow_proposals" value="0">
            <input type="checkbox" name="allow_proposals" id="allow_proposals" value="1"
                {{ old('allow_proposals', $plan?->allow_proposals ?? true) ? 'checked' : '' }}
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <label for="allow_proposals" class="ml-2 block text-sm text-gray-900 font-medium">Allow tourists to send proposals</label>
        </div>

        <div id="proposal_settings" class="{{ old('allow_proposals', $plan?->allow_proposals ?? true) ? '' : 'hidden' }}">
            <label for="min_proposal_price" class="block text-sm font-medium text-gray-700 mb-1">Minimum Acceptable Price (USD)</label>
            <div class="relative max-w-xs">
                <span class="absolute left-3 top-2 text-gray-500">$</span>
                <input type="number" name="min_proposal_price" id="min_proposal_price"
                    value="{{ old('min_proposal_price', $plan?->min_proposal_price ?? '') }}"
                    min="0" step="0.01"
                    class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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

    <div>
        <label for="cover_photo" class="block text-sm font-medium text-gray-700 mb-1">Upload Cover Photo</label>
        @if(isset($plan) && $plan?->cover_photo)
            <div class="mb-4">
                <img src="{{ Storage::url($plan?->cover_photo) }}" alt="Current cover" class="h-32 w-auto rounded">
                <p class="text-sm text-gray-500 mt-2">Current cover photo</p>
            </div>
        @endif
        <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <p class="mt-1 text-sm text-gray-500">Max size: 5MB. Recommended: 1920x1080px</p>
    </div>
</div>

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
