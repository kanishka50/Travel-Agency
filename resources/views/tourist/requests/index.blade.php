@extends('layouts.dashboard')

@section('page-title', 'My Tour Requests')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">My Tour Requests</h1>
            <p class="text-slate-500 mt-1">View and manage your custom tour requests</p>
        </div>
        <a href="{{ route('tourist.requests.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Request
        </a>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <p class="text-emerald-800">{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </div>
    <p class="text-red-800">{{ session('error') }}</p>
</div>
@endif

<!-- Requests List -->
@if($requests->count() > 0)
    <div class="space-y-4">
        @foreach($requests as $request)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-lg hover:border-amber-200 transition-all duration-300 group">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <!-- Request Info -->
                <div class="flex-1">
                    <div class="flex items-center flex-wrap gap-3 mb-2">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-amber-600 transition-colors">{{ $request->title }}</h3>

                        <!-- Status Badge -->
                        @if($request->status === 'open')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg">Open</span>
                        @elseif($request->status === 'bids_received')
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-lg">{{ $request->bid_count }} Bids</span>
                        @elseif($request->status === 'bid_accepted')
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-lg">Accepted</span>
                        @elseif($request->status === 'closed')
                            <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg">Closed</span>
                        @elseif($request->status === 'expired')
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-lg">Expired</span>
                        @endif
                    </div>

                    <p class="text-slate-600 mb-4 line-clamp-2">{{ Str::limit($request->description, 150) }}</p>

                    <div class="flex flex-wrap gap-4 text-sm text-slate-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ is_array($request->preferred_destinations) ? implode(', ', $request->preferred_destinations) : $request->preferred_destinations }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $request->start_date->format('M d, Y') }} - {{ $request->end_date->format('M d, Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${{ number_format($request->budget_min) }} - ${{ number_format($request->budget_max) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $request->duration_days }} days
                        </span>
                    </div>

                    <!-- Bids Count -->
                    @if($request->bid_count > 0)
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 font-medium text-sm rounded-lg border border-amber-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                            {{ $request->bid_count }} {{ Str::plural('proposal', $request->bid_count) }} received
                        </span>
                    </div>
                    @endif

                    <!-- Expiry Warning -->
                    @if($request->status === 'open' && $request->expires_at->isPast())
                    <div class="mt-3 flex items-center gap-2 text-sm text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Expired on {{ $request->expires_at->format('M d, Y') }}
                    </div>
                    @elseif($request->status === 'open' && $request->expires_at->diffInDays(now()) <= 3)
                    <div class="mt-3 flex items-center gap-2 text-sm text-orange-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Expires in {{ $request->expires_at->diffInDays(now()) }} {{ Str::plural('day', $request->expires_at->diffInDays(now())) }}
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col gap-3">
                    <a href="{{ route('tourist.requests.show', $request->id) }}"
                       class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl text-center font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View Details
                    </a>

                    @if($request->status === 'open' && $request->bid_count === 0)
                    <a href="{{ route('tourist.requests.edit', $request->id) }}"
                       class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-center font-semibold hover:bg-slate-50 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    @endif
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">No tour requests yet</h3>
        <p class="text-slate-600 max-w-md mx-auto mb-6">Start by creating your first custom tour request. Guides will submit their best proposals for you to choose from.</p>
        <a href="{{ route('tourist.requests.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Your First Request
        </a>
    </div>
@endif
@endsection
