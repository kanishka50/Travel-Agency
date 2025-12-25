@extends('layouts.public')

@section('content')
<div class="bg-emerald-600 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-white mb-2">Tour Requests</h1>
        <p class="text-emerald-100">Browse open tour requests from travelers looking for guides</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
        <form action="{{ route('tour-requests.index') }}" method="GET" class="grid md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Search requests...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                <select name="duration" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">All durations</option>
                    <option value="short" {{ request('duration') == 'short' ? 'selected' : '' }}>1-3 days</option>
                    <option value="medium" {{ request('duration') == 'medium' ? 'selected' : '' }}>4-7 days</option>
                    <option value="long" {{ request('duration') == 'long' ? 'selected' : '' }}>8+ days</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Min Budget ($)</label>
                <input type="number" name="min_budget" value="{{ request('min_budget') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Min budget">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-emerald-600 text-white py-2 px-4 rounded-lg hover:bg-emerald-700 transition">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-gray-600">Showing {{ $requests->count() }} of {{ $requests->total() }} open requests</p>
    </div>

    <!-- Tour Requests Grid -->
    @if($requests->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($requests as $request)
                <div class="bg-white rounded-xl shadow-sm border p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded">
                            {{ $request->duration_days }} {{ Str::plural('day', $request->duration_days) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Expires {{ $request->expires_at->diffForHumans() }}
                        </span>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $request->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($request->description, 120) }}</p>

                    <!-- Trip Details -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>{{ $request->num_adults }} {{ Str::plural('adult', $request->num_adults) }}{{ $request->num_children > 0 ? ', ' . $request->num_children . ' ' . Str::plural('child', $request->num_children) : '' }}</span>
                        </div>
                    </div>

                    <!-- Budget -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                        <p class="text-sm text-gray-500">Budget Range</p>
                        <p class="text-lg font-bold text-emerald-600">
                            ${{ number_format($request->budget_min, 0) }} - ${{ number_format($request->budget_max, 0) }}
                        </p>
                    </div>

                    <!-- Destinations -->
                    @if($request->preferred_destinations)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach(array_slice($request->preferred_destinations, 0, 3) as $dest)
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">{{ $dest }}</span>
                            @endforeach
                            @if(count($request->preferred_destinations) > 3)
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">+{{ count($request->preferred_destinations) - 3 }} more</span>
                            @endif
                        </div>
                    @endif

                    <div class="flex items-center justify-between pt-4 border-t">
                        <div class="text-sm text-gray-500">
                            {{ $request->bid_count ?? 0 }} {{ Str::plural('proposal', $request->bid_count ?? 0) }}
                        </div>
                        <a href="{{ route('tour-requests.show', $request) }}"
                           class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $requests->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl p-12 text-center border">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No open requests</h3>
            <p class="text-gray-500 mb-6">There are currently no open tour requests. Check back later!</p>
            <a href="{{ route('tour-requests.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                Clear all filters
            </a>
        </div>
    @endif

    <!-- CTA for Guides -->
    @guest
        <div class="mt-12 bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl p-8 text-center text-white">
            <h2 class="text-2xl font-bold mb-4">Are You a Tour Guide?</h2>
            <p class="text-emerald-100 mb-6 max-w-2xl mx-auto">
                Join our platform to connect with travelers and grow your business. Submit proposals to tour requests and build your reputation.
            </p>
            <a href="{{ route('guide.register') }}" class="inline-block px-6 py-3 bg-white text-emerald-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                Become a Guide
            </a>
        </div>
    @endguest
</div>
@endsection
