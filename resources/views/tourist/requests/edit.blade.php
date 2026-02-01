@extends('layouts.dashboard')

@section('page-title', 'Edit Tour Request')

@section('content')
<!-- Header -->
<div class="mb-8">
    <a href="{{ route('tourist.requests.show', $touristRequest->id) }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium mb-4 group">
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Request
    </a>
    <h1 class="text-2xl font-bold text-slate-900">Edit Tour Request</h1>
    <p class="mt-2 text-slate-600">Update your tour request details</p>
</div>

@if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-center gap-3">
        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <p class="text-red-700">{{ session('error') }}</p>
    </div>
@endif

@if($touristRequest->status !== 'open')
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex items-center gap-3">
        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <p class="text-amber-800 font-medium">This request cannot be edited because it is no longer open.</p>
    </div>
@endif

@php
    $prefDestinations = is_array($touristRequest->preferred_destinations)
        ? $touristRequest->preferred_destinations
        : json_decode($touristRequest->preferred_destinations, true) ?? [''];
    $tripFocus = is_array($touristRequest->trip_focus)
        ? $touristRequest->trip_focus
        : json_decode($touristRequest->trip_focus, true) ?? [];
    $dietaryReqs = is_array($touristRequest->dietary_requirements)
        ? $touristRequest->dietary_requirements
        : json_decode($touristRequest->dietary_requirements, true) ?? [];
@endphp

<!-- Multi-step Form -->
<div x-data="{
    step: 1,
    totalSteps: 4,
    destinations: {{ json_encode(old('preferred_destinations', $prefDestinations)) }},
    tripFocus: {{ json_encode(old('trip_focus', $tripFocus)) }},
    dietaryRequirements: {{ json_encode(old('dietary_requirements', $dietaryReqs)) }},

    addDestination() {
        this.destinations.push('');
    },
    removeDestination(index) {
        this.destinations.splice(index, 1);
    },
    nextStep() {
        if (this.step < this.totalSteps) this.step++;
    },
    prevStep() {
        if (this.step > 1) this.step--;
    }
}">

    <!-- Progress Bar -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-slate-700">Step <span x-text="step"></span> of <span x-text="totalSteps"></span></span>
            <span class="text-sm text-slate-500" x-text="Math.round((step / totalSteps) * 100) + '%'"></span>
        </div>
        <div class="w-full bg-slate-200 rounded-full h-2">
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-2 rounded-full transition-all duration-300" :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
        </div>
    </div>

    <form action="{{ route('tourist.requests.update', $touristRequest->id) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        @csrf
        @method('PUT')

        <!-- Step 1: Basic Information -->
        <div x-show="step === 1" x-transition>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-amber-600 font-bold">1</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Basic Information</h2>
            </div>

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Tour Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $touristRequest->title) }}" required
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                           placeholder="e.g., 7-Day Cultural Adventure in Sri Lanka">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description *</label>
                    <textarea id="description" name="description" rows="6" required
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                              placeholder="Describe your ideal tour in detail...">{{ old('description', $touristRequest->description) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Be specific about what you want to help guides create the perfect proposal</p>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration_days" class="block text-sm font-medium text-slate-700 mb-2">Duration (Days) *</label>
                    <input type="number" id="duration_days" name="duration_days" value="{{ old('duration_days', $touristRequest->duration_days) }}" min="1" max="90" required
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    @error('duration_days')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Destinations -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Preferred Destinations *</label>
                    <template x-for="(dest, index) in destinations" :key="index">
                        <div class="flex gap-2 mb-2">
                            <input type="text" :name="'preferred_destinations[' + index + ']'" x-model="destinations[index]" required
                                   class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                   placeholder="e.g., Colombo, Kandy, Ella">
                            <button type="button" @click="removeDestination(index)" x-show="destinations.length > 1"
                                    class="px-4 py-3 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="addDestination()"
                            class="mt-2 text-sm text-amber-600 hover:text-amber-700 font-semibold flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Another Destination
                    </button>
                    @error('preferred_destinations')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Must Visit Places -->
                <div>
                    <label for="must_visit_places" class="block text-sm font-medium text-slate-700 mb-2">Must-Visit Places (Optional)</label>
                    <textarea id="must_visit_places" name="must_visit_places" rows="3"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                              placeholder="List specific attractions or places you must visit">{{ old('must_visit_places', $touristRequest->must_visit_places) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" @click="nextStep()"
                        class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:from-amber-600 hover:to-orange-600 font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
                    Next Step
                </button>
            </div>
        </div>

        <!-- Step 2: Travelers & Dates -->
        <div x-show="step === 2" x-transition>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-amber-600 font-bold">2</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Travelers & Dates</h2>
            </div>

            <div class="space-y-6">
                <!-- Number of Adults -->
                <div>
                    <label for="num_adults" class="block text-sm font-medium text-slate-700 mb-2">Number of Adults *</label>
                    <input type="number" id="num_adults" name="num_adults" value="{{ old('num_adults', $touristRequest->num_adults) }}" min="1" max="50" required
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    @error('num_adults')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Number of Children -->
                <div>
                    <label for="num_children" class="block text-sm font-medium text-slate-700 mb-2">Number of Children</label>
                    <input type="number" id="num_children" name="num_children" value="{{ old('num_children', $touristRequest->num_children) }}" min="0" max="50"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    @error('num_children')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preferred Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-slate-700 mb-2">Preferred Start Date *</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $touristRequest->start_date?->format('Y-m-d')) }}" required min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-slate-700 mb-2">Preferred End Date *</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $touristRequest->end_date?->format('Y-m-d')) }}" required min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Date Flexibility -->
                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="dates_flexible" value="1" {{ old('dates_flexible', $touristRequest->dates_flexible) ? 'checked' : '' }}
                               class="h-5 w-5 text-amber-600 focus:ring-amber-500 border-slate-300 rounded">
                        <span class="ml-3 text-sm text-slate-700">My dates are flexible</span>
                    </label>
                </div>

                <div>
                    <label for="flexibility_range" class="block text-sm font-medium text-slate-700 mb-2">Flexibility Details (if applicable)</label>
                    <input type="text" id="flexibility_range" name="flexibility_range" value="{{ old('flexibility_range', $touristRequest->flexibility_range) }}"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                           placeholder="e.g., Can travel anytime in March">
                </div>

                <!-- Budget Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="budget_min" class="block text-sm font-medium text-slate-700 mb-2">Minimum Budget (USD) *</label>
                        <input type="number" id="budget_min" name="budget_min" value="{{ old('budget_min', $touristRequest->budget_min) }}" min="50" step="50" required
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        @error('budget_min')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="budget_max" class="block text-sm font-medium text-slate-700 mb-2">Maximum Budget (USD) *</label>
                        <input type="number" id="budget_max" name="budget_max" value="{{ old('budget_max', $touristRequest->budget_max) }}" min="50" step="50" required
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        @error('budget_max')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <button type="button" @click="prevStep()"
                        class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 font-semibold transition-colors">
                    Previous
                </button>
                <button type="button" @click="nextStep()"
                        class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:from-amber-600 hover:to-orange-600 font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
                    Next Step
                </button>
            </div>
        </div>

        <!-- Step 3: Preferences -->
        <div x-show="step === 3" x-transition>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-amber-600 font-bold">3</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Your Preferences</h2>
            </div>

            <div class="space-y-6">
                <!-- Trip Focus -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-3">Trip Focus * (Select all that apply)</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach(['Cultural', 'Adventure', 'Wildlife', 'Beach & Relaxation', 'Food & Culinary', 'Photography', 'Historical Sites', 'Nature & Hiking', 'Spiritual', 'Nightlife'] as $focus)
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl hover:bg-amber-50 hover:border-amber-200 cursor-pointer transition-all">
                            <input type="checkbox" name="trip_focus[]" value="{{ $focus }}"
                                   {{ (is_array(old('trip_focus', $tripFocus)) && in_array($focus, old('trip_focus', $tripFocus))) ? 'checked' : '' }}
                                   class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300 rounded">
                            <span class="ml-2 text-sm text-slate-700">{{ $focus }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('trip_focus')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transport Preference -->
                <div>
                    <label for="transport_preference" class="block text-sm font-medium text-slate-700 mb-2">Transport Preference</label>
                    <select id="transport_preference" name="transport_preference"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        <option value="">No Preference</option>
                        <option value="public_transport" {{ old('transport_preference', $touristRequest->transport_preference) == 'public_transport' ? 'selected' : '' }}>Public Transport</option>
                        <option value="private_vehicle" {{ old('transport_preference', $touristRequest->transport_preference) == 'private_vehicle' ? 'selected' : '' }}>Private Vehicle</option>
                        <option value="luxury_vehicle" {{ old('transport_preference', $touristRequest->transport_preference) == 'luxury_vehicle' ? 'selected' : '' }}>Luxury Vehicle</option>
                        <option value="no_preference" {{ old('transport_preference', $touristRequest->transport_preference) == 'no_preference' ? 'selected' : '' }}>No Preference</option>
                    </select>
                </div>

                <!-- Accommodation Preference -->
                <div>
                    <label for="accommodation_preference" class="block text-sm font-medium text-slate-700 mb-2">Accommodation Preference</label>
                    <select id="accommodation_preference" name="accommodation_preference"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                        <option value="">No Preference</option>
                        <option value="budget" {{ old('accommodation_preference', $touristRequest->accommodation_preference) == 'budget' ? 'selected' : '' }}>Budget</option>
                        <option value="midrange" {{ old('accommodation_preference', $touristRequest->accommodation_preference) == 'midrange' ? 'selected' : '' }}>Mid-Range</option>
                        <option value="luxury" {{ old('accommodation_preference', $touristRequest->accommodation_preference) == 'luxury' ? 'selected' : '' }}>Luxury</option>
                        <option value="mixed" {{ old('accommodation_preference', $touristRequest->accommodation_preference) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                    </select>
                </div>

                <!-- Dietary Requirements -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-3">Dietary Requirements (Optional)</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach(['Vegetarian', 'Vegan', 'Halal', 'Kosher', 'Gluten-Free', 'Dairy-Free', 'No Pork', 'No Beef'] as $dietary)
                        <label class="flex items-center p-3 border border-slate-200 rounded-xl hover:bg-amber-50 hover:border-amber-200 cursor-pointer transition-all">
                            <input type="checkbox" name="dietary_requirements[]" value="{{ $dietary }}"
                                   {{ (is_array(old('dietary_requirements', $dietaryReqs)) && in_array($dietary, old('dietary_requirements', $dietaryReqs))) ? 'checked' : '' }}
                                   class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300 rounded">
                            <span class="ml-2 text-sm text-slate-700">{{ $dietary }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Accessibility Needs -->
                <div>
                    <label for="accessibility_needs" class="block text-sm font-medium text-slate-700 mb-2">Accessibility Needs (Optional)</label>
                    <textarea id="accessibility_needs" name="accessibility_needs" rows="3"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                              placeholder="Wheelchair access, mobility assistance, etc.">{{ old('accessibility_needs', $touristRequest->accessibility_needs) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <button type="button" @click="prevStep()"
                        class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 font-semibold transition-colors">
                    Previous
                </button>
                <button type="button" @click="nextStep()"
                        class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:from-amber-600 hover:to-orange-600 font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
                    Next Step
                </button>
            </div>
        </div>

        <!-- Step 4: Review & Submit -->
        <div x-show="step === 4" x-transition>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-amber-600 font-bold">4</span>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Additional Information & Submit</h2>
            </div>

            <div class="space-y-6">
                <!-- Special Requests -->
                <div>
                    <label for="special_requests" class="block text-sm font-medium text-slate-700 mb-2">Special Requests or Notes (Optional)</label>
                    <textarea id="special_requests" name="special_requests" rows="5"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                              placeholder="Any other information guides should know...">{{ old('special_requests', $touristRequest->special_requests) }}</textarea>
                    <p class="mt-1 text-xs text-slate-500">Maximum 1000 characters</p>
                </div>

                <!-- Important Info Box -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-amber-900 mb-1">Important Note</h3>
                            <p class="text-sm text-amber-800">
                                Updating your request will notify guides who have already submitted proposals.
                                Their proposals remain valid unless you close the request.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <button type="button" @click="prevStep()"
                        class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 font-semibold transition-colors">
                    Previous
                </button>
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:from-amber-600 hover:to-orange-600 font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ $touristRequest->status !== 'open' ? 'disabled' : '' }}>
                    Update Request
                </button>
            </div>
        </div>

    </form>
</div>
@endsection
