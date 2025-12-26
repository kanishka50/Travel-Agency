@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.4\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <!-- Gradient Orbs -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-orange-500/20 rounded-full blur-3xl"></div>

    <div class="relative w-full px-6 lg:px-[8%] py-20">
        <div class="max-w-4xl">
            <!-- Breadcrumb -->
            <nav class="mb-6">
                <ol class="flex items-center gap-2 text-sm">
                    <li><a href="{{ url('/') }}" class="text-slate-400 hover:text-amber-400 transition-colors">Home</a></li>
                    <li><span class="text-slate-600">/</span></li>
                    <li class="text-amber-400 font-medium">Tour Requests</li>
                </ol>
            </nav>

            <!-- Title -->
            <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                Open <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Tour Requests</span>
            </h1>
            <p class="text-xl text-slate-300 mb-8 max-w-2xl">
                Browse requests from travelers looking for local guides. Submit your proposal and create unforgettable experiences.
            </p>

            <!-- Stats -->
            <div class="flex flex-wrap gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">{{ $requests->total() }}</p>
                        <p class="text-sm text-slate-400">Open Requests</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white">24/7</p>
                        <p class="text-sm text-slate-400">New Requests Daily</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="bg-slate-50 py-12">
    <div class="w-full px-6 lg:px-[8%]">
        <!-- Search & Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
            <form action="{{ route('tour-requests.index') }}" method="GET">
                <div class="grid md:grid-cols-12 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                                   placeholder="Search by title, destination...">
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Duration</label>
                        <select name="duration" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all appearance-none bg-white">
                            <option value="">All durations</option>
                            <option value="short" {{ request('duration') == 'short' ? 'selected' : '' }}>1-3 days</option>
                            <option value="medium" {{ request('duration') == 'medium' ? 'selected' : '' }}>4-7 days</option>
                            <option value="long" {{ request('duration') == 'long' ? 'selected' : '' }}>8+ days</option>
                        </select>
                    </div>

                    <!-- Min Budget -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Min Budget</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">$</span>
                            <input type="number" name="min_budget" value="{{ request('min_budget') }}"
                                   class="w-full pl-8 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                                   placeholder="0">
                        </div>
                    </div>

                    <!-- Max Budget -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Max Budget</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">$</span>
                            <input type="number" name="max_budget" value="{{ request('max_budget') }}"
                                   class="w-full pl-8 pr-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                                   placeholder="10000">
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="md:col-span-2 flex items-end">
                        <button type="submit" class="w-full py-3 px-6 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-0.5">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Search
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request('search') || request('duration') || request('min_budget') || request('max_budget'))
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-3 flex-wrap">
                        <span class="text-sm text-slate-500">Active filters:</span>
                        <div class="flex flex-wrap gap-2">
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm">
                                    "{{ request('search') }}"
                                    <a href="{{ route('tour-requests.index', array_merge(request()->except('search'))) }}" class="hover:text-amber-900">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif
                            @if(request('duration'))
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm">
                                    {{ request('duration') == 'short' ? '1-3 days' : (request('duration') == 'medium' ? '4-7 days' : '8+ days') }}
                                    <a href="{{ route('tour-requests.index', array_merge(request()->except('duration'))) }}" class="hover:text-amber-900">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('tour-requests.index') }}" class="ml-auto text-sm text-amber-600 hover:text-amber-700 font-medium">
                            Clear all
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Results Count -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-slate-600">
                Showing <span class="font-semibold text-slate-900">{{ $requests->firstItem() ?? 0 }}</span> to <span class="font-semibold text-slate-900">{{ $requests->lastItem() ?? 0 }}</span> of <span class="font-semibold text-slate-900">{{ $requests->total() }}</span> requests
            </p>
        </div>

        <!-- Tour Requests Grid -->
        @if($requests->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($requests as $request)
                    <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:border-amber-200 transition-all duration-300">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-slate-50 to-amber-50/50 px-6 py-4 border-b border-slate-100">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-bold rounded-full shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $request->duration_days }} {{ Str::plural('Day', $request->duration_days) }}
                                </span>
                                <div class="flex items-center gap-1.5 text-sm">
                                    @if($request->expires_at->diffInDays(now()) <= 3)
                                        <span class="flex items-center gap-1 text-red-600 font-medium">
                                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                            Expires {{ $request->expires_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-slate-500">
                                            Expires {{ $request->expires_at->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <h3 class="font-display text-lg font-bold text-slate-900 mb-2 line-clamp-2 group-hover:text-amber-600 transition-colors">
                                {{ $request->title }}
                            </h3>
                            <p class="text-slate-600 text-sm mb-4 line-clamp-2">{{ Str::limit($request->description, 100) }}</p>

                            <!-- Trip Details -->
                            <div class="space-y-2.5 mb-4">
                                <div class="flex items-center gap-2 text-sm">
                                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-slate-600">{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-slate-600">
                                        {{ $request->num_adults }} {{ Str::plural('adult', $request->num_adults) }}{{ $request->num_children > 0 ? ', ' . $request->num_children . ' ' . Str::plural('child', $request->num_children) : '' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Budget -->
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-4 mb-4 border border-amber-100">
                                <p class="text-xs text-amber-700 font-medium mb-1">Budget Range</p>
                                <p class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-600">
                                    ${{ number_format($request->budget_min, 0) }} - ${{ number_format($request->budget_max, 0) }}
                                </p>
                            </div>

                            <!-- Destinations -->
                            @if($request->preferred_destinations && count($request->preferred_destinations) > 0)
                                <div class="flex flex-wrap gap-1.5 mb-4">
                                    @foreach(array_slice($request->preferred_destinations, 0, 3) as $dest)
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-lg">{{ $dest }}</span>
                                    @endforeach
                                    @if(count($request->preferred_destinations) > 3)
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-lg">+{{ count($request->preferred_destinations) - 3 }} more</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1 text-sm text-slate-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    <span>{{ $request->bid_count ?? 0 }} {{ Str::plural('proposal', $request->bid_count ?? 0) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('tour-requests.show', $request) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-sm hover:shadow-md group-hover:-translate-y-0.5">
                                View Details
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $requests->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-100 shadow-sm">
                <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">No Open Requests Found</h3>
                <p class="text-slate-500 mb-6 max-w-md mx-auto">There are currently no tour requests matching your criteria. Try adjusting your filters or check back later!</p>
                <a href="{{ route('tour-requests.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/25">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Clear All Filters
                </a>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section for Guides -->
@guest
    <section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-20 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>

        <div class="relative w-full px-6 lg:px-[8%]">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500/20 rounded-full text-amber-400 text-sm font-medium mb-6">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Join 500+ Local Guides
                </div>

                <h2 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6">
                    Are You a <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Tour Guide?</span>
                </h2>
                <p class="text-xl text-slate-300 mb-10 max-w-2xl mx-auto">
                    Connect with travelers from around the world. Submit proposals, grow your business, and share your local expertise.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('guide.register') }}"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Become a Guide
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 px-8 py-4 border-2 border-slate-600 text-white font-semibold rounded-xl hover:bg-white/5 hover:border-slate-500 transition-all">
                        Already a Guide? Login
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="mt-12 flex flex-wrap items-center justify-center gap-8 text-slate-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-sm">Verified Guides</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm">Secure Payments</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                        <span class="text-sm">24/7 Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endguest
@endsection
