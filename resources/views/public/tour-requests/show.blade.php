@extends('layouts.public')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ url('/') }}" class="text-gray-500 hover:text-emerald-600">Home</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li><a href="{{ route('tour-requests.index') }}" class="text-gray-500 hover:text-emerald-600">Tour Requests</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li class="text-gray-900 font-medium">{{ Str::limit($request->title, 30) }}</li>
        </ol>
    </nav>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Status Banner -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-emerald-800">Open for Proposals</p>
                    <p class="text-sm text-emerald-600">Expires {{ $request->expires_at->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Request Details -->
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $request->title }}</h1>

                <div class="prose max-w-none text-gray-600 mb-6">
                    {!! nl2br(e($request->description)) !!}
                </div>

                <!-- Trip Focus -->
                @if($request->trip_focus && count($request->trip_focus) > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Trip Focus</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($request->trip_focus as $focus)
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm">
                                    {{ ucfirst(str_replace('_', ' ', $focus)) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Preferred Destinations -->
                @if($request->preferred_destinations && count($request->preferred_destinations) > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Preferred Destinations</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($request->preferred_destinations as $destination)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $destination }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Must Visit Places -->
                @if($request->must_visit_places)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Must Visit Places</h3>
                        <p class="text-gray-600">{{ $request->must_visit_places }}</p>
                    </div>
                @endif
            </div>

            <!-- Trip Details Card -->
            <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Trip Details</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Travel Dates</p>
                            <p class="font-medium text-gray-900">{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}</p>
                            @if($request->dates_flexible)
                                <p class="text-xs text-emerald-600">Flexible{{ $request->flexibility_range ? ': ' . $request->flexibility_range : '' }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Duration</p>
                            <p class="font-medium text-gray-900">{{ $request->duration_days }} {{ Str::plural('day', $request->duration_days) }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Group Size</p>
                            <p class="font-medium text-gray-900">
                                {{ $request->num_adults }} {{ Str::plural('adult', $request->num_adults) }}
                                @if($request->num_children > 0)
                                    , {{ $request->num_children }} {{ Str::plural('child', $request->num_children) }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Budget Range</p>
                            <p class="font-medium text-gray-900">${{ number_format($request->budget_min, 0) }} - ${{ number_format($request->budget_max, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Preferences & Requirements</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    @if($request->transport_preference)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Transport Preference</p>
                            <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $request->transport_preference)) }}</p>
                        </div>
                    @endif
                    @if($request->accommodation_preference)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Accommodation Preference</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($request->accommodation_preference) }}</p>
                        </div>
                    @endif
                    @if($request->dietary_requirements && count($request->dietary_requirements) > 0)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Dietary Requirements</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($request->dietary_requirements as $diet)
                                    <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded">{{ $diet }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if($request->accessibility_needs)
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Accessibility Needs</p>
                            <p class="font-medium text-gray-900">{{ $request->accessibility_needs }}</p>
                        </div>
                    @endif
                </div>
                @if($request->special_requests)
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-sm text-gray-500 mb-2">Special Requests</p>
                        <p class="text-gray-600">{{ $request->special_requests }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-24">
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-500 mb-1">Budget Range</p>
                    <p class="text-3xl font-bold text-emerald-600">
                        ${{ number_format($request->budget_min, 0) }} - ${{ number_format($request->budget_max, 0) }}
                    </p>
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Current Proposals</span>
                        <span class="font-semibold text-gray-900">{{ $request->bid_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Expires</span>
                        <span class="font-semibold text-gray-900">{{ $request->expires_at->format('M d, Y') }}</span>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->isGuide())
                        <a href="{{ route('guide.request-proposals.create', $request) }}"
                           class="block w-full bg-emerald-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-emerald-700 transition mb-4">
                            Submit a Proposal
                        </a>
                        <p class="text-xs text-gray-500 text-center">
                            Create a personalized proposal for this tour request
                        </p>
                    @elseif(auth()->user()->isTourist())
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                            <p class="text-sm text-blue-700">
                                This is a tour request from another traveler. As a tourist, you can create your own tour request!
                            </p>
                            <a href="{{ route('tourist.requests.create') }}" class="text-blue-600 hover:underline text-sm font-medium mt-2 inline-block">
                                Create Your Request
                            </a>
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}"
                       class="block w-full bg-emerald-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-emerald-700 transition mb-4">
                        Login to Submit Proposal
                    </a>
                    <p class="text-sm text-gray-500 text-center mb-4">
                        Are you a tour guide? <a href="{{ route('guide.register') }}" class="text-emerald-600 hover:underline">Join us</a>
                    </p>
                @endauth

                <!-- Quick Info -->
                <div class="border-t pt-4 mt-4 space-y-3">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Verified tourist request</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Secure payment through platform</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
