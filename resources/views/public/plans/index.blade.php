@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl font-bold mb-4">Discover Your Perfect Tour</h1>
            <p class="text-xl text-blue-100">Browse {{ $plans->total() }} amazing tour plans across Sri Lanka</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Tours</h2>

                    <form method="GET" action="{{ route('plans.index') }}" class="space-y-6">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search tours...">
                        </div>

                        <!-- Destination -->
                        <div>
                            <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
                            <select name="destination" id="destination"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Destinations</option>
                                @foreach($allDestinations as $destination)
                                    <option value="{{ $destination }}" {{ request('destination') === $destination ? 'selected' : '' }}>
                                        {{ $destination }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Trip Focus -->
                        <div>
                            <label for="focus" class="block text-sm font-medium text-gray-700 mb-2">Trip Focus</label>
                            <select name="focus" id="focus"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Types</option>
                                @foreach($allFocusTags as $tag)
                                    <option value="{{ $tag }}" {{ request('focus') === $tag ? 'selected' : '' }}>
                                        {{ $tag }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Days)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="days_min" value="{{ request('days_min') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Min">
                                <input type="number" name="days_max" value="{{ request('days_max') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Max">
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range (USD)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="price_min" value="{{ request('price_min') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Min">
                                <input type="number" name="price_max" value="{{ request('price_max') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Max">
                            </div>
                        </div>

                        <!-- Group Size -->
                        <div>
                            <label for="group_size" class="block text-sm font-medium text-gray-700 mb-2">Group Size</label>
                            <input type="number" name="group_size" id="group_size" value="{{ request('group_size') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Number of people">
                        </div>

                        <!-- Availability -->
                        <div>
                            <label for="availability" class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                            <select name="availability" id="availability"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Tours</option>
                                <option value="always_available" {{ request('availability') === 'always_available' ? 'selected' : '' }}>Always Available</option>
                                <option value="date_range" {{ request('availability') === 'date_range' ? 'selected' : '' }}>Seasonal</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="space-y-2">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Apply Filters
                            </button>
                            <a href="{{ route('plans.index') }}" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-md hover:bg-gray-200 transition">
                                Clear All
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <!-- Sort & View Options -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <!-- Results Count -->
                        <div class="text-gray-600">
                            Showing <span class="font-semibold text-gray-900">{{ $plans->firstItem() ?? 0 }}-{{ $plans->lastItem() ?? 0 }}</span>
                            of <span class="font-semibold text-gray-900">{{ $plans->total() }}</span> tours
                        </div>

                        <div class="flex items-center gap-4">
                            <!-- Sort -->
                            <form method="GET" action="{{ route('plans.index') }}" class="flex items-center gap-2">
                                @foreach(request()->except(['sort', 'view']) as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <label for="sort" class="text-sm text-gray-600">Sort by:</label>
                                <select name="sort" id="sort" onchange="this.form.submit()"
                                    class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="duration_short" {{ request('sort') === 'duration_short' ? 'selected' : '' }}>Duration: Shortest</option>
                                    <option value="duration_long" {{ request('sort') === 'duration_long' ? 'selected' : '' }}>Duration: Longest</option>
                                </select>
                            </form>

                            <!-- View Toggle -->
                            <div class="flex border border-gray-300 rounded-md overflow-hidden">
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"
                                    class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"
                                    class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }} transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plans Grid/List -->
                @if($plans->isEmpty())
                    <div class="bg-white rounded-lg shadow p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No tours found</h3>
                        <p class="text-gray-600 mb-6">Try adjusting your filters or search criteria</p>
                        <a href="{{ route('plans.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Clear All Filters
                        </a>
                    </div>
                @else
                    @if($viewMode === 'grid')
                        <!-- Grid View -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($plans as $plan)
                                @include('public.plans.partials.plan-card-grid', ['plan' => $plan])
                            @endforeach
                        </div>
                    @else
                        <!-- List View -->
                        <div class="space-y-6">
                            @foreach($plans as $plan)
                                @include('public.plans.partials.plan-card-list', ['plan' => $plan])
                            @endforeach
                        </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $plans->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>
</div>
@endsection
