@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-emerald-600">Home</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('tour-requests.index') }}" class="text-gray-500 hover:text-emerald-600">Tour Requests</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('tour-requests.show', $touristRequest) }}" class="text-gray-500 hover:text-emerald-600">{{ Str::limit($touristRequest->title, 30) }}</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li class="text-gray-900 font-medium">Submit Proposal</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-900">Submit Proposal #{{ $bidNumber }}</h1>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm font-semibold rounded-full">
                    Proposal {{ $bidNumber }} of 2
                </span>
            </div>
            <p class="text-gray-600">For: <strong>{{ $touristRequest->title }}</strong></p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <h3 class="text-red-800 font-semibold mb-2">Please correct the following errors:</h3>
            <ul class="list-disc list-inside text-red-700 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('guide.request-proposals.store', $touristRequest) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Proposal Message -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">1. Proposal Overview</h2>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Proposal Message <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-500 mb-2">Introduce yourself and explain why you're the best guide for this tour (100-2000 characters)</p>
                            <textarea name="proposal_message" rows="6" required
                                      class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                      placeholder="Dear traveler, I'm excited about your tour request! With over 10 years of experience guiding tours in Paris..."
                            >{{ old('proposal_message') }}</textarea>
                            @error('proposal_message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Day by Day Plan -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">2. Day-by-Day Itinerary</h2>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Detailed Day-by-Day Plan <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-500 mb-2">Provide a detailed breakdown of activities for each day (minimum 200 characters)</p>
                            <textarea name="day_by_day_plan" rows="12" required
                                      class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 font-mono text-sm"
                                      placeholder="Day 1: Arrival & City Orientation&#10;- 9:00 AM: Meet at hotel lobby&#10;- 10:00 AM: Visit Eiffel Tower&#10;- 12:00 PM: Lunch at local bistro&#10;&#10;Day 2: Historical Paris&#10;- 9:00 AM: Louvre Museum tour&#10;..."
                            >{{ old('day_by_day_plan') }}</textarea>
                            @error('day_by_day_plan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">3. Pricing</h2>

                        <div class="mb-4 p-4 bg-emerald-50 rounded-lg">
                            <p class="text-sm text-gray-700">
                                Tourist's Budget: <strong class="text-emerald-600">${{ number_format($touristRequest->budget_min) }} - ${{ number_format($touristRequest->budget_max) }}</strong>
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Total Price (USD) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="total_price" step="0.01" min="1" required
                                       value="{{ old('total_price') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="2500.00">
                                @error('total_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Estimated Days
                                </label>
                                <input type="number" name="estimated_days" min="1"
                                       value="{{ old('estimated_days', $touristRequest->duration_days) }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                       placeholder="{{ $touristRequest->duration_days }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Price Breakdown (Optional)
                            </label>
                            <p class="text-xs text-gray-500 mb-2">Itemize your costs for transparency</p>
                            <textarea name="price_breakdown" rows="4"
                                      class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 font-mono text-sm"
                                      placeholder="Accommodation: $800&#10;Transportation: $400&#10;Meals: $600&#10;Activities & Entry fees: $500&#10;Guide fee: $200"
                            >{{ old('price_breakdown') }}</textarea>
                        </div>
                    </div>

                    <!-- Destinations -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">4. Destinations Covered</h2>

                        <div x-data="{ destinations: {{ old('destinations_covered') ? json_encode(old('destinations_covered')) : json_encode(['']) }} }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Destinations <span class="text-red-500">*</span>
                            </label>
                            <p class="text-xs text-gray-500 mb-2">List all cities/destinations included in your tour</p>

                            <div class="space-y-2 mb-3">
                                <template x-for="(dest, index) in destinations" :key="index">
                                    <div class="flex gap-2">
                                        <input type="text" :name="'destinations_covered[' + index + ']'" x-model="destinations[index]" required
                                               class="flex-1 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                               placeholder="e.g., Paris, Rome, Barcelona">
                                        <button type="button" @click="destinations.splice(index, 1)" x-show="destinations.length > 1"
                                                class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">
                                            Remove
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="destinations.push('')"
                                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 text-sm font-medium">
                                + Add Destination
                            </button>

                            @error('destinations_covered')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">5. Additional Details</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Accommodation Details
                                </label>
                                <textarea name="accommodation_details" rows="3"
                                          class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                          placeholder="3-star hotels in city centers, double rooms with breakfast included..."
                                >{{ old('accommodation_details') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Transport Details
                                </label>
                                <textarea name="transport_details" rows="3"
                                          class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                          placeholder="Private air-conditioned vehicle, airport transfers included..."
                                >{{ old('transport_details') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Included Services
                                </label>
                                <textarea name="included_services" rows="3"
                                          class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                          placeholder="Professional guide, all breakfasts, museum entry tickets, travel insurance..."
                                >{{ old('included_services') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Excluded Services
                                </label>
                                <textarea name="excluded_services" rows="3"
                                          class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                          placeholder="International flights, lunches and dinners, personal expenses, tips..."
                                >{{ old('excluded_services') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex gap-4">
                            <button type="submit"
                                    class="flex-1 px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-semibold shadow-md hover:shadow-lg transition-all">
                                Submit Proposal #{{ $bidNumber }}
                            </button>
                            <a href="{{ route('tour-requests.show', $touristRequest) }}"
                               class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Request Summary -->
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Request Summary</h3>

                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500">Destinations</p>
                            <p class="font-medium text-gray-900">{{ is_array($touristRequest->preferred_destinations) ? implode(', ', $touristRequest->preferred_destinations) : $touristRequest->preferred_destinations }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Duration</p>
                            <p class="font-medium text-gray-900">{{ $touristRequest->duration_days }} days</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Dates</p>
                            <p class="font-medium text-gray-900">{{ $touristRequest->start_date->format('M d, Y') }}</p>
                            @if($touristRequest->dates_flexible)
                                <p class="text-xs text-emerald-600">Flexible (±{{ $touristRequest->flexibility_range }} days)</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-gray-500">Budget Range</p>
                            <p class="font-medium text-emerald-600">${{ number_format($touristRequest->budget_min) }} - ${{ number_format($touristRequest->budget_max) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Travelers</p>
                            <p class="font-medium text-gray-900">{{ $touristRequest->num_adults }} adults
                            @if($touristRequest->num_children > 0)
                                , {{ $touristRequest->num_children }} children
                            @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('tour-requests.show', $touristRequest) }}" class="text-sm text-emerald-600 hover:text-emerald-700">
                            View Full Request Details →
                        </a>
                    </div>
                </div>

                <!-- Previous Proposals (if any) -->
                @if($myBids->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Your Previous Proposal</h3>

                    @foreach($myBids as $bid)
                    <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Proposal #{{ $bid->bid_number }}</span>
                            <span class="text-lg font-bold text-emerald-600">${{ number_format($bid->total_price, 2) }}</span>
                        </div>
                        <p class="text-xs text-gray-600">Status:
                            <span class="font-semibold">{{ ucfirst($bid->status) }}</span>
                        </p>
                    </div>
                    @endforeach

                    <p class="text-xs text-gray-500 mt-3">
                        This is your {{ $bidNumber === 2 ? 'second and final' : 'first' }} proposal. Make it count!
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
