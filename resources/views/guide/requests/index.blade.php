@extends('layouts.dashboard')

@section('page-title', 'Browse Tour Requests')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Browse Tour Requests</h1>
    <p class="text-slate-500 mt-1">Find custom tour requests from tourists and submit your best proposals</p>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6" x-data="{ showFilters: true }">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">Filters</h2>
                <p class="text-sm text-slate-500">Narrow down your search</p>
            </div>
        </div>
        <button @click="showFilters = !showFilters" class="text-amber-600 hover:text-amber-700 text-sm font-semibold flex items-center gap-1">
            <span x-show="!showFilters">Show Filters</span>
            <span x-show="showFilters">Hide Filters</span>
            <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': showFilters}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
    </div>

    <form method="GET" action="{{ route('guide.requests.index') }}" x-show="showFilters" x-collapse>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <!-- Destination Filter -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Destination</label>
                <input type="text" name="destination" value="{{ request('destination') }}"
                       placeholder="e.g., Paris, Tokyo"
                       class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <!-- Budget Range -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Min Budget ($)</label>
                <input type="number" name="budget_min" value="{{ request('budget_min') }}"
                       placeholder="Min budget"
                       class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Max Budget ($)</label>
                <input type="number" name="budget_max" value="{{ request('budget_max') }}"
                       placeholder="Max budget"
                       class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <!-- Duration Range -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Min Duration (days)</label>
                <input type="number" name="duration_min" value="{{ request('duration_min') }}"
                       placeholder="Min days"
                       class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Max Duration (days)</label>
                <input type="number" name="duration_max" value="{{ request('duration_max') }}"
                       placeholder="Max days"
                       class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Start Date From</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <!-- Trip Focus -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Trip Focus</label>
                <select name="trip_focus" class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">All Types</option>
                    <option value="adventure" {{ request('trip_focus') == 'adventure' ? 'selected' : '' }}>Adventure</option>
                    <option value="culture" {{ request('trip_focus') == 'culture' ? 'selected' : '' }}>Culture</option>
                    <option value="relaxation" {{ request('trip_focus') == 'relaxation' ? 'selected' : '' }}>Relaxation</option>
                    <option value="food" {{ request('trip_focus') == 'food' ? 'selected' : '' }}>Food & Dining</option>
                    <option value="nature" {{ request('trip_focus') == 'nature' ? 'selected' : '' }}>Nature</option>
                    <option value="shopping" {{ request('trip_focus') == 'shopping' ? 'selected' : '' }}>Shopping</option>
                    <option value="photography" {{ request('trip_focus') == 'photography' ? 'selected' : '' }}>Photography</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
                Apply Filters
            </button>
            <a href="{{ route('guide.requests.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition">
                Clear Filters
            </a>
        </div>
    </form>
</div>

<!-- Results Count -->
<div class="mb-4 text-sm text-slate-600">
    Showing {{ $requests->count() }} of {{ $requests->total() }} open requests
</div>

<!-- Requests Grid -->
@if($requests->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($requests as $request)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-amber-200 transition-all duration-300">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $request->title }}</h3>
                        <p class="text-sm text-slate-500">Posted {{ $request->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg">
                        Open
                    </span>
                </div>

                <!-- Description Preview -->
                <p class="text-slate-600 mb-4 line-clamp-2">{{ Str::limit($request->description, 120) }}</p>

                <!-- Key Details Grid -->
                <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                    <div class="flex items-center text-slate-600">
                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="truncate">{{ is_array($request->preferred_destinations) ? implode(', ', array_slice($request->preferred_destinations, 0, 2)) : $request->preferred_destinations }}</span>
                    </div>

                    <div class="flex items-center text-slate-600">
                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $request->duration_days }} days
                    </div>

                    <div class="flex items-center text-slate-600">
                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $request->start_date->format('M d, Y') }}
                    </div>

                    <div class="flex items-center text-slate-600">
                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ $request->num_adults }} adults
                        @if($request->num_children > 0)
                            , {{ $request->num_children }} children
                        @endif
                    </div>
                </div>

                <!-- Budget -->
                <div class="mb-4 p-3 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-100">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Budget Range</span>
                        <span class="text-lg font-bold text-amber-600">
                            ${{ number_format($request->budget_min) }} - ${{ number_format($request->budget_max) }}
                        </span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                    <div class="text-sm">
                        <span class="text-slate-600">{{ $request->bid_count }} {{ Str::plural('bid', $request->bid_count) }}</span>
                        <span class="text-slate-400 mx-2">•</span>
                        <span class="text-orange-600">Expires {{ $request->expires_at->diffForHumans() }}</span>
                    </div>
                    <a href="{{ route('guide.requests.show', $request->id) }}"
                       class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold text-sm shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
                        View & Bid
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $requests->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">No requests found</h3>
        <p class="text-slate-600 max-w-md mx-auto mb-6">
            @if(request()->hasAny(['destination', 'budget_min', 'budget_max', 'duration_min', 'duration_max', 'start_date', 'trip_focus']))
                No requests match your current filters. Try adjusting your search criteria.
            @else
                There are currently no open tour requests. Check back later for new opportunities!
            @endif
        </p>
        @if(request()->hasAny(['destination', 'budget_min', 'budget_max', 'duration_min', 'duration_max', 'start_date', 'trip_focus']))
            <a href="{{ route('guide.requests.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                Clear All Filters
            </a>
        @endif
    </div>
@endif
@endsection
