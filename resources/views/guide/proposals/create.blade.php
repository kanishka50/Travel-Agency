@extends('layouts.dashboard')

@section('page-title', 'Submit Proposal')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('tour-requests.show', $touristRequest) }}" class="inline-flex items-center text-slate-600 hover:text-amber-600 transition-colors group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Tour Request
    </a>
</div>

<!-- Header -->
<div class="mb-8">
    <div class="flex items-center gap-3 mb-2">
        <h1 class="text-2xl font-bold text-slate-900">Submit Proposal #{{ $bidNumber }}</h1>
        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-sm font-semibold rounded-lg">
            Proposal {{ $bidNumber }} of 2
        </span>
    </div>
    <p class="text-slate-500">For: <strong class="text-slate-700">{{ $touristRequest->title }}</strong></p>
</div>

<!-- Error Messages -->
@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-red-800 font-semibold mb-2">Please correct the following errors:</h3>
                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Form -->
    <div class="lg:col-span-2 space-y-6">
        <form action="{{ route('guide.request-proposals.store', $touristRequest) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Proposal Message -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                        <span class="text-lg font-bold text-amber-600">1</span>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Proposal Overview</h2>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Proposal Message *
                    </label>
                    <p class="text-xs text-slate-500 mb-2">Introduce yourself and explain why you're the best guide for this tour (100-2000 characters)</p>
                    <textarea name="proposal_message" rows="6" required
                              class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500"
                              placeholder="Dear traveler, I'm excited about your tour request! With over 10 years of experience guiding tours..."
                    >{{ old('proposal_message') }}</textarea>
                    @error('proposal_message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Day by Day Plan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                        <span class="text-lg font-bold text-amber-600">2</span>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Day-by-Day Itinerary</h2>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Detailed Day-by-Day Plan *
                    </label>
                    <p class="text-xs text-slate-500 mb-2">Provide a detailed breakdown of activities for each day (minimum 200 characters)</p>
                    <textarea name="day_by_day_plan" rows="12" required
                              class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 font-mono text-sm"
                              placeholder="Day 1: Arrival & City Orientation&#10;- 9:00 AM: Meet at hotel lobby&#10;- 10:00 AM: Visit Eiffel Tower&#10;- 12:00 PM: Lunch at local bistro&#10;&#10;Day 2: Historical Paris&#10;- 9:00 AM: Louvre Museum tour&#10;..."
                    >{{ old('day_by_day_plan') }}</textarea>
                    @error('day_by_day_plan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Pricing -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                        <span class="text-lg font-bold text-amber-600">3</span>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Pricing</h2>
                </div>

                <div class="mb-4 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                    <p class="text-sm text-slate-700">
                        Tourist's Budget: <strong class="text-amber-600">${{ number_format($touristRequest->budget_min) }} - ${{ number_format($touristRequest->budget_max) }}</strong>
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Total Price (USD) *
                        </label>
                        <input type="number" name="total_price" step="0.01" min="1" required
                               value="{{ old('total_price') }}"
                               class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500"
                               placeholder="2500.00">
                        @error('total_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Estimated Days
                        </label>
                        <input type="number" name="estimated_days" min="1"
                               value="{{ old('estimated_days', $touristRequest->duration_days) }}"
                               class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500"
                               placeholder="{{ $touristRequest->duration_days }}">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Price Breakdown (Optional)
                    </label>
                    <p class="text-xs text-slate-500 mb-2">Itemize your costs for transparency</p>
                    <textarea name="price_breakdown" rows="4"
                              class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 font-mono text-sm"
                              placeholder="Accommodation: $800&#10;Transportation: $400&#10;Meals: $600&#10;Activities & Entry fees: $500&#10;Guide fee: $200"
                    >{{ old('price_breakdown') }}</textarea>
                </div>
            </div>

            <!-- Destinations -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                        <span class="text-lg font-bold text-amber-600">4</span>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Destinations Covered</h2>
                </div>

                <div x-data="{ destinations: {{ old('destinations_covered') ? json_encode(old('destinations_covered')) : json_encode(['']) }} }">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Destinations *
                    </label>
                    <p class="text-xs text-slate-500 mb-3">List all cities/destinations included in your tour</p>

                    <div class="space-y-2 mb-3">
                        <template x-for="(dest, index) in destinations" :key="index">
                            <div class="flex gap-2">
                                <input type="text" :name="'destinations_covered[' + index + ']'" x-model="destinations[index]" required
                                       class="flex-1 rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500"
                                       placeholder="e.g., Paris, Rome, Barcelona">
                                <button type="button" @click="destinations.splice(index, 1)" x-show="destinations.length > 1"
                                        class="px-3 py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="destinations.push('')"
                            class="px-4 py-2 bg-amber-100 text-amber-700 rounded-xl hover:bg-amber-200 text-sm font-semibold transition-colors">
                        + Add Destination
                    </button>

                    @error('destinations_covered')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                        <span class="text-lg font-bold text-amber-600">5</span>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Additional Details</h2>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Accommodation Details
                        </label>
                        <textarea name="accommodation_details" rows="3"
                                  class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500"
                                  placeholder="3-star hotels in city centers, double rooms with breakfast included..."
                        >{{ old('accommodation_details') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Transport Details
                        </label>
                        <textarea name="transport_details" rows="3"
                                  class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500"
                                  placeholder="Private air-conditioned vehicle, airport transfers included..."
                        >{{ old('transport_details') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Included Services
                        </label>
                        <textarea name="included_services" rows="3"
                                  class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500"
                                  placeholder="Professional guide, all breakfasts, museum entry tickets, travel insurance..."
                        >{{ old('included_services') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Excluded Services
                        </label>
                        <textarea name="excluded_services" rows="3"
                                  class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500"
                                  placeholder="International flights, lunches and dinners, personal expenses, tips..."
                        >{{ old('excluded_services') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('tour-requests.show', $touristRequest) }}"
                   class="px-6 py-3 border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50 font-semibold transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                    Submit Proposal #{{ $bidNumber }}
                </button>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Request Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Request Summary</h3>
            </div>

            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-slate-500 mb-1">Destinations</p>
                    <p class="font-semibold text-slate-900">{{ is_array($touristRequest->preferred_destinations) ? implode(', ', $touristRequest->preferred_destinations) : $touristRequest->preferred_destinations }}</p>
                </div>
                <div>
                    <p class="text-slate-500 mb-1">Duration</p>
                    <p class="font-semibold text-slate-900">{{ $touristRequest->duration_days }} days</p>
                </div>
                <div>
                    <p class="text-slate-500 mb-1">Dates</p>
                    <p class="font-semibold text-slate-900">{{ $touristRequest->start_date->format('M d, Y') }}</p>
                    @if($touristRequest->dates_flexible)
                        <p class="text-xs text-amber-600 font-medium">Flexible (±{{ $touristRequest->flexibility_range }} days)</p>
                    @endif
                </div>
                <div>
                    <p class="text-slate-500 mb-1">Budget Range</p>
                    <p class="font-bold text-amber-600">${{ number_format($touristRequest->budget_min) }} - ${{ number_format($touristRequest->budget_max) }}</p>
                </div>
                <div>
                    <p class="text-slate-500 mb-1">Travelers</p>
                    <p class="font-semibold text-slate-900">{{ $touristRequest->num_adults }} adults
                    @if($touristRequest->num_children > 0)
                        , {{ $touristRequest->num_children }} children
                    @endif
                    </p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('tour-requests.show', $touristRequest) }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                    View Full Request Details →
                </a>
            </div>
        </div>

        <!-- Previous Proposals (if any) -->
        @if($myBids->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Your Previous Proposal</h3>
            </div>

            @foreach($myBids as $bid)
            <div class="p-4 bg-slate-50 rounded-xl">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-slate-700">Proposal #{{ $bid->bid_number }}</span>
                    <span class="text-xl font-bold text-amber-600">${{ number_format($bid->total_price, 2) }}</span>
                </div>
                <p class="text-sm text-slate-600">Status:
                    <span class="font-semibold">{{ ucfirst($bid->status) }}</span>
                </p>
            </div>
            @endforeach

            <p class="text-xs text-slate-500 mt-4">
                This is your {{ $bidNumber === 2 ? 'second and final' : 'first' }} proposal. Make it count!
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
