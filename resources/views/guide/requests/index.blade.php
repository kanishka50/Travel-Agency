<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Tour Requests - Travel Agency</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Browse Tour Requests</h1>
            <p class="mt-2 text-gray-600">Find custom tour requests from tourists and submit your best proposals</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6" x-data="{ showFilters: false }">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
                <button @click="showFilters = !showFilters" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    <span x-show="!showFilters">Show Filters</span>
                    <span x-show="showFilters">Hide Filters</span>
                </button>
            </div>

            <form method="GET" action="{{ route('guide.requests.index') }}" x-show="showFilters" x-collapse>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <!-- Destination Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                        <input type="text" name="destination" value="{{ request('destination') }}"
                               placeholder="e.g., Paris, Tokyo"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Budget Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Budget ($)</label>
                        <input type="number" name="budget_min" value="{{ request('budget_min') }}"
                               placeholder="Min budget"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Budget ($)</label>
                        <input type="number" name="budget_max" value="{{ request('budget_max') }}"
                               placeholder="Max budget"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Duration Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Duration (days)</label>
                        <input type="number" name="duration_min" value="{{ request('duration_min') }}"
                               placeholder="Min days"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Duration (days)</label>
                        <input type="number" name="duration_max" value="{{ request('duration_max') }}"
                               placeholder="Max days"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date From</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Trip Focus -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trip Focus</label>
                        <select name="trip_focus" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
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
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Apply Filters
                    </button>
                    <a href="{{ route('guide.requests.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        <div class="mb-4 text-sm text-gray-600">
            Showing {{ $requests->count() }} of {{ $requests->total() }} open requests
        </div>

        <!-- Requests Grid -->
        @if($requests->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($requests as $request)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $request->title }}</h3>
                                <p class="text-sm text-gray-500">Posted {{ $request->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                Open
                            </span>
                        </div>

                        <!-- Description Preview -->
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($request->description, 120) }}</p>

                        <!-- Key Details Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="truncate">{{ is_array($request->preferred_destinations) ? implode(', ', array_slice($request->preferred_destinations, 0, 2)) : $request->preferred_destinations }}</span>
                            </div>

                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $request->duration_days }} days
                            </div>

                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $request->start_date->format('M d, Y') }}
                            </div>

                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $request->num_adults }} adults
                                @if($request->num_children > 0)
                                    , {{ $request->num_children }} children
                                @endif
                            </div>
                        </div>

                        <!-- Budget -->
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Budget Range</span>
                                <span class="text-lg font-bold text-blue-600">
                                    ${{ number_format($request->budget_min) }} - ${{ number_format($request->budget_max) }}
                                </span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="text-sm">
                                <span class="text-gray-600">{{ $request->bid_count }} {{ Str::plural('bid', $request->bid_count) }}</span>
                                <span class="text-gray-400 mx-2">•</span>
                                <span class="text-orange-600">Expires {{ $request->expires_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('guide.requests.show', $request->id) }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition-colors">
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
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">No requests found</h3>
                <p class="mt-2 text-gray-600 max-w-md mx-auto">
                    @if(request()->hasAny(['destination', 'budget_min', 'budget_max', 'duration_min', 'duration_max', 'start_date', 'trip_focus']))
                        No requests match your current filters. Try adjusting your search criteria.
                    @else
                        There are currently no open tour requests. Check back later for new opportunities!
                    @endif
                </p>
                @if(request()->hasAny(['destination', 'budget_min', 'budget_max', 'duration_min', 'duration_max', 'start_date', 'trip_focus']))
                <a href="{{ route('guide.requests.index') }}"
                   class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow-md hover:shadow-lg transition-all">
                    Clear All Filters
                </a>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
