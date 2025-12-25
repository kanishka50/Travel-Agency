@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[400px] lg:min-h-[480px] overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg?auto=compress&cs=tinysrgb&w=1920"
             alt="Sri Lanka Landscape"
             class="w-full h-full object-cover">
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/80 to-slate-900/60"></div>
    </div>

    <div class="relative w-full px-6 lg:px-[8%] py-20 lg:py-28">
        <div class="max-w-3xl">
            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm mb-6">
                <a href="{{ route('welcome') }}" class="text-white/60 hover:text-amber-400 transition-colors">Home</a>
                <svg class="w-4 h-4 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-amber-400">Tour Packages</span>
            </div>

            <!-- Title -->
            <h1 class="font-display text-4xl lg:text-6xl font-bold text-white mb-6">
                Discover Amazing
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-400">Tour Packages</span>
            </h1>

            <!-- Description -->
            <p class="text-xl text-white/70 leading-relaxed max-w-2xl">
                Explore curated travel experiences crafted by expert local guides. From ancient temples to pristine beaches, find your perfect Sri Lankan adventure.
            </p>

            <!-- Stats - Redesigned -->
            <div class="flex flex-wrap gap-10 mt-12">
                <div class="flex items-center gap-4">
                    <div class="text-4xl font-display font-bold text-amber-400">{{ $plans->total() }}+</div>
                    <div class="text-sm text-white/60 leading-tight">Tour<br>Packages</div>
                </div>
                <div class="w-px h-12 bg-white/20"></div>
                <div class="flex items-center gap-4">
                    <div class="text-4xl font-display font-bold text-amber-400">50+</div>
                    <div class="text-sm text-white/60 leading-tight">Expert<br>Guides</div>
                </div>
                <div class="w-px h-12 bg-white/20"></div>
                <div class="flex items-center gap-4">
                    <div class="text-4xl font-display font-bold text-amber-400">4.9</div>
                    <div class="flex flex-col">
                        <div class="flex gap-0.5">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-xs text-white/50 mt-0.5">Avg Rating</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="bg-slate-50 min-h-screen">
    <div class="w-full px-6 lg:px-[8%] py-12">
        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 mb-10 -mt-16 relative z-10">
            <form action="{{ route('tour-packages.index') }}" method="GET">
                <div class="grid md:grid-cols-12 gap-4 items-end">
                    <!-- Search Input -->
                    <div class="md:col-span-5">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Search Packages</label>
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all"
                                   placeholder="Search by name, location...">
                        </div>
                    </div>

                    <!-- Duration Filter -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Duration</label>
                        <select name="duration" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-amber-500 appearance-none cursor-pointer">
                            <option value="">All durations</option>
                            <option value="short" {{ request('duration') == 'short' ? 'selected' : '' }}>1-3 days</option>
                            <option value="medium" {{ request('duration') == 'medium' ? 'selected' : '' }}>4-7 days</option>
                            <option value="long" {{ request('duration') == 'long' ? 'selected' : '' }}>8+ days</option>
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Max Price</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">$</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                   class="w-full pl-8 pr-4 py-3 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-amber-500"
                                   placeholder="Any">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Sort By</label>
                        <select name="sort" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-amber-500 appearance-none cursor-pointer">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div class="md:col-span-1">
                        <button type="submit" class="w-full h-[50px] bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl font-semibold transition-all shadow-lg shadow-amber-500/30 hover:shadow-amber-500/40 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request('search') || request('duration') || request('max_price'))
                <div class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                    <span class="text-sm text-slate-500">Active filters:</span>
                    @if(request('search'))
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm">
                            "{{ request('search') }}"
                            <a href="{{ route('tour-packages.index', array_merge(request()->except('search'))) }}" class="hover:text-amber-900">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        </span>
                    @endif
                    @if(request('duration'))
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm">
                            {{ request('duration') == 'short' ? '1-3 days' : (request('duration') == 'medium' ? '4-7 days' : '8+ days') }}
                            <a href="{{ route('tour-packages.index', array_merge(request()->except('duration'))) }}" class="hover:text-amber-900">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        </span>
                    @endif
                    @if(request('max_price'))
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm">
                            Under ${{ request('max_price') }}
                            <a href="{{ route('tour-packages.index', array_merge(request()->except('max_price'))) }}" class="hover:text-amber-900">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        </span>
                    @endif
                    <a href="{{ route('tour-packages.index') }}" class="text-sm text-slate-500 hover:text-amber-600 ml-2">Clear all</a>
                </div>
                @endif
            </form>
        </div>

        <!-- Results Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-display font-bold text-slate-900">
                    @if(request('search'))
                        Results for "{{ request('search') }}"
                    @else
                        All Tour Packages
                    @endif
                </h2>
                <p class="text-slate-500 mt-1">Showing {{ $plans->firstItem() ?? 0 }}-{{ $plans->lastItem() ?? 0 }} of {{ $plans->total() }} packages</p>
            </div>

            <!-- View Toggle (optional future feature) -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-slate-500">View:</span>
                <button class="p-2 bg-amber-500 text-white rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button class="p-2 bg-slate-100 text-slate-400 rounded-lg hover:bg-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Tour Packages Grid -->
        @if($plans->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($plans as $index => $plan)
                    <a href="{{ route('tour-packages.show', $plan) }}"
                       class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
                        <!-- Image -->
                        <div class="relative h-64 overflow-hidden">
                            @if($plan->cover_photo)
                                <img src="{{ Storage::url($plan->cover_photo) }}" alt="{{ $plan->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-amber-400 via-orange-500 to-amber-600 flex items-center justify-center">
                                    <svg class="w-20 h-20 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @endif

                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                            <!-- Top Badges -->
                            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                <span class="px-3 py-1.5 bg-white/95 backdrop-blur-sm rounded-full text-xs font-semibold text-slate-700 shadow-sm">
                                    <svg class="w-3.5 h-3.5 inline mr-1 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $plan->num_days }} {{ Str::plural('Day', $plan->num_days) }}
                                </span>
                                @if($index < 3)
                                    <span class="px-3 py-1.5 bg-amber-500 rounded-full text-xs font-bold text-white shadow-sm">
                                        Popular
                                    </span>
                                @endif
                            </div>

                            <!-- Wishlist Button -->
                            <button class="absolute top-4 right-4 w-10 h-10 bg-white/95 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm opacity-0 group-hover:opacity-100 transition-all hover:bg-white hover:scale-110"
                                    onclick="event.preventDefault(); event.stopPropagation();">
                                <svg class="w-5 h-5 text-slate-400 hover:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>

                            <!-- Price Tag -->
                            <div class="absolute bottom-4 right-4">
                                <div class="px-4 py-2 bg-white rounded-xl shadow-lg">
                                    <span class="text-xs text-slate-500">From</span>
                                    <div class="flex items-baseline gap-0.5">
                                        <span class="text-xl font-bold text-slate-900">${{ number_format($plan->price_per_adult) }}</span>
                                        <span class="text-xs text-slate-400">/person</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Location -->
                            <div class="flex items-center gap-1.5 text-slate-500 mb-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-sm">Sri Lanka</span>
                            </div>

                            <!-- Title -->
                            <h3 class="font-display text-xl font-bold text-slate-900 mb-3 group-hover:text-amber-600 transition-colors line-clamp-2">
                                {{ $plan->title }}
                            </h3>

                            <!-- Description -->
                            <p class="text-slate-500 text-sm mb-4 line-clamp-2 leading-relaxed">
                                {{ Str::limit($plan->description, 120) }}
                            </p>

                            <!-- Guide Info -->
                            @if($plan->guide)
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-semibold text-sm overflow-hidden">
                                        @if($plan->guide->profile_photo)
                                            <img src="{{ Storage::url($plan->guide->profile_photo) }}" alt="{{ $plan->guide->full_name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($plan->guide->full_name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">{{ $plan->guide->full_name }}</p>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="text-xs text-slate-500">{{ number_format($plan->guide->average_rating ?? 4.5, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 text-amber-600 font-medium text-sm group-hover:gap-2 transition-all">
                                    View
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $plans->withQueryString()->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl p-16 text-center">
                <div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-display font-bold text-slate-900 mb-3">No packages found</h3>
                <p class="text-slate-500 mb-8 max-w-md mx-auto">
                    We couldn't find any tour packages matching your criteria. Try adjusting your filters or explore all our packages.
                </p>
                <a href="{{ route('tour-packages.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/30">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset Filters
                </a>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-20">
    <div class="w-full px-6 lg:px-[8%]">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="font-display text-3xl lg:text-4xl font-bold text-white mb-4">
                Can't Find What You're Looking For?
            </h2>
            <p class="text-white/60 text-lg mb-8">
                Create a custom tour request and let our expert guides craft the perfect itinerary for you.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('tour-requests.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/30">
                    Create Custom Request
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-4 border-2 border-white/20 text-white rounded-xl font-semibold hover:bg-white/10 transition-all">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
