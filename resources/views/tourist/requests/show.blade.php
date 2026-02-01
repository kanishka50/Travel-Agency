@extends('layouts.dashboard')

@section('page-title', 'Request Details')

@section('content')
<!-- Back Button & Title -->
<div class="mb-6">
    <a href="{{ route('tourist.requests.index') }}" class="inline-flex items-center text-amber-600 hover:text-amber-700 font-medium transition-colors">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to My Requests
    </a>
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Request Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $touristRequest->title }}</h1>
                    <p class="text-sm text-slate-500 mt-1">Posted on {{ $touristRequest->created_at->format('M d, Y') }}</p>
                </div>

                <!-- Status Badge -->
                @if($touristRequest->status === 'open')
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-lg">Open</span>
                @elseif($touristRequest->status === 'closed')
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-sm font-semibold rounded-lg">Closed</span>
                @elseif($touristRequest->status === 'expired')
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-lg">Expired</span>
                @endif
            </div>

            <div class="prose max-w-none">
                <p class="text-slate-700 whitespace-pre-line">{{ $touristRequest->description }}</p>
            </div>

            <!-- Key Details -->
            <div class="mt-6 grid grid-cols-2 gap-4 pt-6 border-t border-slate-200">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500 mb-1">Destinations</p>
                    <p class="font-semibold text-slate-900">{{ is_array($touristRequest->preferred_destinations) ? implode(', ', $touristRequest->preferred_destinations) : $touristRequest->preferred_destinations }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500 mb-1">Duration</p>
                    <p class="font-semibold text-slate-900">{{ $touristRequest->duration_days }} days</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500 mb-1">Dates</p>
                    <p class="font-semibold text-slate-900">{{ $touristRequest->start_date->format('M d, Y') }} - {{ $touristRequest->end_date->format('M d, Y') }}</p>
                    @if($touristRequest->dates_flexible)
                        <p class="text-xs text-amber-600 mt-1">Flexible dates</p>
                    @endif
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500 mb-1">Budget Range</p>
                    <p class="font-semibold text-slate-900">${{ number_format($touristRequest->budget_min) }} - ${{ number_format($touristRequest->budget_max) }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500 mb-1">Travelers</p>
                    <p class="font-semibold text-slate-900">{{ $touristRequest->num_adults }} {{ Str::plural('adult', $touristRequest->num_adults) }}
                    @if($touristRequest->num_children > 0), {{ $touristRequest->num_children }} {{ Str::plural('child', $touristRequest->num_children) }}@endif
                    </p>
                </div>
                @if($touristRequest->accommodation_preference)
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500 mb-1">Accommodation</p>
                    <p class="font-semibold text-slate-900">{{ ucfirst($touristRequest->accommodation_preference) }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Received Bids -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">
                Proposals Received ({{ $touristRequest->bids->count() }})
            </h2>

            @if($touristRequest->bids->count() > 0)
                <div class="space-y-4">
                    @foreach($touristRequest->bids as $bid)
                    <div class="border border-slate-200 rounded-xl p-4 hover:border-amber-200 hover:shadow-md transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="font-semibold text-slate-900">{{ $bid->guide->full_name }}</h3>
                                    @if($bid->bid_number === 2)
                                        <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-lg">Bid #2</span>
                                    @endif
                                    @if($bid->status === 'pending')
                                        <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg">Pending</span>
                                    @elseif($bid->status === 'accepted')
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg">Accepted</span>
                                    @elseif($bid->status === 'rejected')
                                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-lg">Rejected</span>
                                    @endif
                                </div>
                                <p class="text-2xl font-bold text-amber-600 mb-2">${{ number_format($bid->total_price, 2) }}</p>
                                <p class="text-sm text-slate-600 line-clamp-2">{{ $bid->proposal_message }}</p>
                                <p class="text-xs text-slate-500 mt-2">Submitted {{ $bid->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('tourist.bids.show', [$touristRequest, $bid]) }}" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:shadow-lg hover:shadow-amber-500/25 text-sm font-semibold inline-block transition-all duration-300">
                                    View Proposal
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-slate-700 font-medium">No proposals received yet</p>
                    <p class="text-sm text-slate-500 mt-1">Guides are viewing your request and preparing their proposals</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-6">
            <h3 class="font-bold text-slate-900 mb-4">Actions</h3>
            <div class="space-y-3">
                @if($touristRequest->status === 'open')
                    @if($touristRequest->bid_count === 0)
                    <a href="{{ route('tourist.requests.edit', $touristRequest->id) }}"
                       class="block w-full px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 text-center font-semibold transition-colors">
                        Edit Request
                    </a>
                    @endif

                    <form action="{{ route('tourist.requests.close', $touristRequest->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure you want to close this request?')"
                                class="w-full px-4 py-2.5 bg-orange-100 text-orange-700 rounded-xl hover:bg-orange-200 font-semibold transition-colors">
                            Close Request
                        </button>
                    </form>
                @endif

                @if($touristRequest->bids()->where('status', 'pending')->count() === 0)
                <form action="{{ route('tourist.requests.destroy', $touristRequest->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this request?')"
                            class="w-full px-4 py-2.5 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 font-semibold transition-colors">
                        Delete Request
                    </button>
                </form>
                @endif
            </div>

            <!-- Expiry Info -->
            @if($touristRequest->status === 'open')
            <div class="mt-6 pt-6 border-t border-slate-200">
                <p class="text-sm text-slate-500">Expires on</p>
                <p class="font-semibold text-slate-900">{{ $touristRequest->expires_at->format('M d, Y') }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ $touristRequest->expires_at->diffForHumans() }}</p>
            </div>
            @endif

            <!-- Stats -->
            <div class="mt-6 pt-6 border-t border-slate-200 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Total Bids</span>
                    <span class="font-bold text-slate-900 bg-slate-100 px-3 py-1 rounded-lg">{{ $touristRequest->bid_count }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Pending</span>
                    <span class="font-bold text-amber-600 bg-amber-100 px-3 py-1 rounded-lg">{{ $touristRequest->bids()->where('status', 'pending')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
