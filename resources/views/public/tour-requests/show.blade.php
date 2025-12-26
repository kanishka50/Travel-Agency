@extends('layouts.public')

@section('content')
<!-- Hero Header -->
<div class="relative bg-slate-900 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23f59e0b\" fill-opacity=\"0.05\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
        <!-- Gradient Orbs -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full px-6 lg:px-[8%] py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ url('/') }}" class="text-slate-400 hover:text-amber-400 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Home
                    </a>
                </li>
                <li><span class="text-slate-600">/</span></li>
                <li><a href="{{ route('tour-requests.index') }}" class="text-slate-400 hover:text-amber-400 transition-colors">Tour Requests</a></li>
                <li><span class="text-slate-600">/</span></li>
                <li class="text-amber-400 font-medium">{{ Str::limit($request->title, 30) }}</li>
            </ol>
        </nav>

        <!-- Title & Status -->
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <!-- Duration Badge -->
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-bold rounded-full shadow-lg shadow-amber-500/25">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $request->duration_days }} {{ Str::plural('Day', $request->duration_days) }}
                    </span>

                    <!-- Group Size Badge -->
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-700/50 text-slate-300 text-sm font-medium rounded-full border border-slate-600">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $request->num_adults }} {{ Str::plural('Adult', $request->num_adults) }}@if($request->num_children > 0), {{ $request->num_children }} {{ Str::plural('Child', $request->num_children) }}@endif
                    </span>

                    <!-- Status Badge -->
                    @php
                        $daysUntilExpiry = now()->diffInDays($request->expires_at, false);
                    @endphp
                    @if($daysUntilExpiry <= 3 && $daysUntilExpiry > 0)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500/20 text-red-400 text-sm font-medium rounded-full border border-red-500/30">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            Expires Soon
                        </span>
                    @elseif($request->status === 'open')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500/20 text-emerald-400 text-sm font-medium rounded-full border border-emerald-500/30">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                            Open for Proposals
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl lg:text-4xl font-bold text-white mb-4">{{ $request->title }}</h1>

                <!-- Quick Stats -->
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>{{ $request->bid_count ?? 0 }} {{ Str::plural('Proposal', $request->bid_count ?? 0) }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Expires {{ $request->expires_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Budget Display -->
            <div class="bg-gradient-to-br from-amber-500/10 to-orange-500/10 rounded-2xl border border-amber-500/20 p-6 text-center lg:min-w-[200px]">
                <p class="text-sm text-amber-400/80 mb-1">Budget Range</p>
                <p class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">
                    ${{ number_format($request->budget_min, 0) }}
                </p>
                <p class="text-slate-400 text-sm">to ${{ number_format($request->budget_max, 0) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="w-full px-6 lg:px-[8%] py-10 bg-gradient-to-b from-slate-50 to-white">
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Left Column - Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-amber-50/30 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                        </div>
                        Trip Description
                    </h2>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none text-slate-600 leading-relaxed">
                        {!! nl2br(e($request->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Trip Focus & Destinations -->
            @if(($request->trip_focus && count($request->trip_focus) > 0) || ($request->preferred_destinations && count($request->preferred_destinations) > 0))
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-amber-50/30 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        Interests & Destinations
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Trip Focus -->
                    @if($request->trip_focus && count($request->trip_focus) > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            Trip Focus
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($request->trip_focus as $focus)
                                <span class="px-4 py-2 bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 rounded-xl text-sm font-medium border border-amber-200">
                                    {{ ucfirst(str_replace('_', ' ', $focus)) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Preferred Destinations -->
                    @if($request->preferred_destinations && count($request->preferred_destinations) > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Preferred Destinations
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($request->preferred_destinations as $destination)
                                <span class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-medium border border-slate-200">
                                    {{ $destination }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Must Visit Places -->
                    @if($request->must_visit_places)
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Must Visit Places
                        </h3>
                        <p class="text-slate-600 bg-amber-50/50 rounded-xl p-4 border border-amber-100">{{ $request->must_visit_places }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Trip Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-amber-50/30 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        Trip Details
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Travel Dates -->
                        <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/25">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Travel Dates</p>
                                <p class="font-semibold text-slate-800">{{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}</p>
                                @if($request->dates_flexible)
                                    <p class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Flexible{{ $request->flexibility_range ? ': ' . $request->flexibility_range : '' }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Duration -->
                        <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/25">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Duration</p>
                                <p class="font-semibold text-slate-800">{{ $request->duration_days }} {{ Str::plural('day', $request->duration_days) }}</p>
                            </div>
                        </div>

                        <!-- Group Size -->
                        <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/25">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Group Size</p>
                                <p class="font-semibold text-slate-800">
                                    {{ $request->num_adults }} {{ Str::plural('adult', $request->num_adults) }}
                                    @if($request->num_children > 0)
                                        , {{ $request->num_children }} {{ Str::plural('child', $request->num_children) }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Budget -->
                        <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/25">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Budget Range</p>
                                <p class="font-semibold text-slate-800">${{ number_format($request->budget_min, 0) }} - ${{ number_format($request->budget_max, 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preferences & Requirements -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-amber-50/30 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        Preferences & Requirements
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        @if($request->transport_preference)
                            <div class="p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    <p class="text-sm text-slate-500">Transport Preference</p>
                                </div>
                                <p class="font-semibold text-slate-800">{{ ucfirst(str_replace('_', ' ', $request->transport_preference)) }}</p>
                            </div>
                        @endif

                        @if($request->accommodation_preference)
                            <div class="p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="text-sm text-slate-500">Accommodation Preference</p>
                                </div>
                                <p class="font-semibold text-slate-800">{{ ucfirst($request->accommodation_preference) }}</p>
                            </div>
                        @endif

                        @if($request->dietary_requirements && count($request->dietary_requirements) > 0)
                            <div class="p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <p class="text-sm text-slate-500">Dietary Requirements</p>
                                </div>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($request->dietary_requirements as $diet)
                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm rounded-lg font-medium">{{ $diet }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($request->accessibility_needs)
                            <div class="p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <p class="text-sm text-slate-500">Accessibility Needs</p>
                                </div>
                                <p class="font-semibold text-slate-800">{{ $request->accessibility_needs }}</p>
                            </div>
                        @endif
                    </div>

                    @if($request->special_requests)
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <p class="text-sm font-semibold text-slate-700">Special Requests</p>
                            </div>
                            <p class="text-slate-600 bg-amber-50/50 rounded-xl p-4 border border-amber-100">{{ $request->special_requests }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-24">
                <!-- Budget Header -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-500 p-6 text-center">
                    <p class="text-amber-100 text-sm mb-1">Budget Range</p>
                    <p class="text-3xl font-bold text-white">
                        ${{ number_format($request->budget_min, 0) }} - ${{ number_format($request->budget_max, 0) }}
                    </p>
                </div>

                <div class="p-6">
                    <!-- Stats -->
                    <div class="mb-6 p-4 bg-gradient-to-br from-slate-50 to-amber-50/30 rounded-xl border border-slate-100">
                        <div class="flex justify-between text-sm mb-3 pb-3 border-b border-slate-200">
                            <span class="text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                </svg>
                                Current Proposals
                            </span>
                            <span class="font-bold text-slate-800">{{ $request->bid_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Expires
                            </span>
                            <span class="font-bold text-slate-800">{{ $request->expires_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->isGuide())
                            <a href="{{ route('guide.request-proposals.create', $request) }}"
                               class="block w-full bg-gradient-to-r from-amber-500 to-orange-500 text-white text-center py-4 rounded-xl font-bold hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 mb-4">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Submit a Proposal
                                </span>
                            </a>
                            <p class="text-xs text-slate-500 text-center">
                                Create a personalized proposal for this tour request
                            </p>
                        @elseif(auth()->user()->isTourist())
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-blue-700 mb-3">
                                    This is a tour request from another traveler. As a tourist, you can create your own tour request!
                                </p>
                                <a href="{{ route('tourist.requests.create') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-semibold">
                                    Create Your Request
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}"
                           class="block w-full bg-gradient-to-r from-amber-500 to-orange-500 text-white text-center py-4 rounded-xl font-bold hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 mb-4">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Login to Submit Proposal
                            </span>
                        </a>
                        <p class="text-sm text-slate-500 text-center mb-4">
                            Are you a tour guide? <a href="{{ route('guide.register') }}" class="text-amber-600 hover:text-amber-700 font-semibold">Join us</a>
                        </p>
                    @endauth

                    <!-- Trust Badges -->
                    <div class="border-t border-slate-100 pt-5 mt-5 space-y-3">
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <span>Verified tourist request</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <span>Secure payment through platform</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-600">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span>24/7 customer support</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('tour-requests.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-amber-600 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to All Requests
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
