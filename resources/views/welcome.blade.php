@extends('layouts.public')

@php
    // Fetch featured tour packages (6 most recent active plans)
    $featuredPackages = \App\Models\GuidePlan::with('guide')
        ->where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->take(6)
        ->get();

    // Fetch featured tour requests (4 most recent open requests)
    $featuredRequests = \App\Models\TouristRequest::with('tourist')
        ->where('status', 'open')
        ->where('expires_at', '>', now())
        ->orderBy('created_at', 'desc')
        ->take(4)
        ->get();

    // Stats
    $totalPackages = \App\Models\GuidePlan::where('status', 'active')->count();
    $totalGuides = \App\Models\Guide::whereHas('user', fn ($q) => $q->where('status', 'active'))->count();
    $totalTravelers = \App\Models\Tourist::count();
@endphp

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 text-white py-24">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Discover the Beauty of <span class="text-emerald-300">Sri Lanka</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-emerald-100 max-w-3xl mx-auto">
                Explore unique tours with experienced local guides who know every hidden gem
            </p>

            <!-- Search Bar -->
            <div class="max-w-3xl mx-auto">
                <form action="{{ route('tour-packages.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" placeholder="Where do you want to go?"
                        class="flex-1 px-6 py-4 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-300 shadow-lg"
                        value="{{ request('search') }}">
                    <button type="submit" class="px-8 py-4 bg-white text-emerald-700 font-semibold rounded-xl hover:bg-gray-100 transition shadow-lg">
                        Search Tours
                    </button>
                </form>
            </div>

            <!-- Quick Links -->
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('tour-packages.index') }}" class="px-6 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white hover:bg-white/20 transition">
                    Browse Packages
                </a>
                <a href="{{ route('tour-requests.index') }}" class="px-6 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white hover:bg-white/20 transition">
                    View Requests
                </a>
                @guest
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-emerald-500 rounded-full text-white hover:bg-emerald-400 transition">
                        Get Started
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-12 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="text-4xl font-bold text-emerald-600 mb-2">{{ $totalPackages }}+</div>
                <div class="text-gray-600 font-medium">Tour Packages</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-emerald-600 mb-2">{{ $totalGuides }}+</div>
                <div class="text-gray-600 font-medium">Expert Guides</div>
            </div>
            <div>
                <div class="text-4xl font-bold text-emerald-600 mb-2">{{ $totalTravelers }}+</div>
                <div class="text-gray-600 font-medium">Happy Travelers</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Tour Packages -->
@if($featuredPackages->isNotEmpty())
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Featured Tour Packages</h2>
                <p class="text-gray-600">Curated experiences from our expert local guides</p>
            </div>
            <a href="{{ route('tour-packages.index') }}" class="hidden md:inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                View All Packages
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredPackages as $plan)
                <a href="{{ route('tour-packages.show', $plan) }}" class="group">
                    <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-lg transition">
                        @if($plan->cover_photo)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ Storage::url($plan->cover_photo) }}" alt="{{ $plan->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                        @else
                            <div class="h-48 bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded">
                                    {{ $plan->num_days }} {{ Str::plural('day', $plan->num_days) }}
                                </span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-emerald-600 transition">
                                {{ $plan->title }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($plan->description, 100) }}</p>

                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-2xl font-bold text-emerald-600">${{ number_format($plan->price_per_adult, 0) }}</span>
                                    <span class="text-gray-500 text-sm">/person</span>
                                </div>
                            </div>

                            @if($plan->guide)
                                <div class="mt-4 pt-4 border-t flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                        @if($plan->guide->profile_photo)
                                            <img src="{{ Storage::url($plan->guide->profile_photo) }}" alt="{{ $plan->guide->full_name }}"
                                                 class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <span class="text-emerald-600 font-semibold text-xs">
                                                {{ strtoupper(substr($plan->guide->full_name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $plan->guide->full_name }}</p>
                                        @if($plan->guide->average_rating > 0)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                <span class="text-xs text-gray-500">{{ number_format($plan->guide->average_rating, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-10 md:hidden">
            <a href="{{ route('tour-packages.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                View All Packages
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Featured Tour Requests -->
@if($featuredRequests->isNotEmpty())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Latest Tour Requests</h2>
                <p class="text-gray-600">Travelers looking for guides - submit your proposal</p>
            </div>
            <a href="{{ route('tour-requests.index') }}" class="hidden md:inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                View All Requests
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredRequests as $request)
                <a href="{{ route('tour-requests.show', $request) }}" class="group">
                    <div class="bg-gray-50 rounded-xl p-5 border hover:border-emerald-300 hover:shadow-md transition h-full flex flex-col">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded">
                                {{ $request->duration_days }} {{ Str::plural('day', $request->duration_days) }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $request->expires_at->diffForHumans() }}
                            </span>
                        </div>

                        <h3 class="text-base font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-emerald-600 transition">
                            {{ $request->title }}
                        </h3>

                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d') }}</span>
                        </div>

                        <div class="mt-auto">
                            <div class="bg-white rounded-lg p-3 border">
                                <p class="text-xs text-gray-500">Budget</p>
                                <p class="text-lg font-bold text-emerald-600">
                                    ${{ number_format($request->budget_min, 0) }} - ${{ number_format($request->budget_max, 0) }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t flex items-center justify-between">
                            <span class="text-xs text-gray-500">{{ $request->bid_count ?? 0 }} {{ Str::plural('proposal', $request->bid_count ?? 0) }}</span>
                            <span class="text-emerald-600 text-sm font-medium group-hover:underline">View Details</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-10 md:hidden">
            <a href="{{ route('tour-requests.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 font-medium">
                View All Requests
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

<!-- How It Works -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
            <p class="text-xl text-gray-600">Book your perfect tour in three simple steps</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Search & Discover</h3>
                <p class="text-gray-600">Browse tour packages or create a custom tour request</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Book Your Tour</h3>
                <p class="text-gray-600">Choose your dates and securely book online</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">3. Enjoy Your Adventure</h3>
                <p class="text-gray-600">Meet your expert guide and create unforgettable memories</p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Choose Us</h2>
            <p class="text-xl text-gray-600">We make your travel experience unforgettable</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-gray-50 rounded-xl p-6 border hover:shadow-md transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Verified Guides</h3>
                <p class="text-gray-600 text-sm">All our guides are carefully vetted and verified professionals</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 border hover:shadow-md transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Best Prices</h3>
                <p class="text-gray-600 text-sm">Competitive pricing with no hidden fees</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 border hover:shadow-md transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">24/7 Support</h3>
                <p class="text-gray-600 text-sm">Our team is always here to help you</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 border hover:shadow-md transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Custom Tours</h3>
                <p class="text-gray-600 text-sm">Create your own tour request and get proposals</p>
            </div>
        </div>
    </div>
</section>
@endsection
