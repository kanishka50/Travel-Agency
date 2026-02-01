@extends('layouts.dashboard')

@section('page-title', $touristRequest->title)

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <a href="{{ route('guide.requests.index') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium mb-4 group">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Requests
    </a>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <p class="text-emerald-800">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                    <p class="text-sm text-slate-500 mt-1">Posted {{ $touristRequest->created_at->diffForHumans() }}</p>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-lg">Open</span>
            </div>

            <div class="prose max-w-none">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Description</h3>
                </div>
                <p class="text-slate-700 whitespace-pre-line">{{ $touristRequest->description }}</p>
            </div>

            <!-- Key Details -->
            <div class="mt-6 grid grid-cols-2 gap-4 pt-6 border-t border-slate-200">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500">Destinations</p>
                    <p class="font-semibold text-slate-900 mt-1">{{ is_array($touristRequest->preferred_destinations) ? implode(', ', $touristRequest->preferred_destinations) : $touristRequest->preferred_destinations }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500">Duration</p>
                    <p class="font-semibold text-slate-900 mt-1">{{ $touristRequest->duration_days }} days</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500">Dates</p>
                    <p class="font-semibold text-slate-900 mt-1">{{ $touristRequest->start_date->format('M d, Y') }} - {{ $touristRequest->end_date->format('M d, Y') }}</p>
                    @if($touristRequest->dates_flexible)
                        <p class="text-xs text-emerald-600 mt-1">Flexible (±{{ $touristRequest->flexibility_range }} days)</p>
                    @else
                        <p class="text-xs text-slate-500 mt-1">Fixed dates</p>
                    @endif
                </div>
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-4 border border-amber-100">
                    <p class="text-sm text-slate-500">Budget Range</p>
                    <p class="font-bold text-amber-600 mt-1">${{ number_format($touristRequest->budget_min) }} - ${{ number_format($touristRequest->budget_max) }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500">Travelers</p>
                    <p class="font-semibold text-slate-900 mt-1">{{ $touristRequest->num_adults }} {{ Str::plural('adult', $touristRequest->num_adults) }}
                    @if($touristRequest->num_children > 0)
                        , {{ $touristRequest->num_children }} {{ Str::plural('child', $touristRequest->num_children) }}
                        @if($touristRequest->children_ages)
                            (ages: {{ is_array($touristRequest->children_ages) ? implode(', ', $touristRequest->children_ages) : $touristRequest->children_ages }})
                        @endif
                    @endif
                    </p>
                </div>
                @if($touristRequest->trip_focus)
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500">Trip Focus</p>
                    <div class="flex flex-wrap gap-1 mt-1">
                        @foreach(is_array($touristRequest->trip_focus) ? $touristRequest->trip_focus : [$touristRequest->trip_focus] as $focus)
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-lg font-medium">{{ ucfirst($focus) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Additional Preferences -->
            @if($touristRequest->transport_preference || $touristRequest->accommodation_preference || $touristRequest->dietary_requirements || $touristRequest->accessibility_needs)
            <div class="mt-6 pt-6 border-t border-slate-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Preferences & Requirements</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @if($touristRequest->transport_preference)
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500">Transport</p>
                        <p class="font-semibold text-slate-900 mt-1">{{ ucfirst($touristRequest->transport_preference) }}</p>
                    </div>
                    @endif

                    @if($touristRequest->accommodation_preference)
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500">Accommodation</p>
                        <p class="font-semibold text-slate-900 mt-1">{{ ucfirst($touristRequest->accommodation_preference) }}</p>
                    </div>
                    @endif

                    @if($touristRequest->dietary_requirements)
                    <div class="col-span-2 bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500">Dietary Requirements</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach(is_array($touristRequest->dietary_requirements) ? $touristRequest->dietary_requirements : [$touristRequest->dietary_requirements] as $diet)
                                <span class="px-2 py-1 bg-slate-200 text-slate-700 text-xs rounded-lg font-medium">{{ ucfirst($diet) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($touristRequest->accessibility_needs)
                    <div class="col-span-2 bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500">Accessibility Needs</p>
                        <p class="font-semibold text-slate-900 mt-1">{{ $touristRequest->accessibility_needs }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Special Requests -->
            @if($touristRequest->special_requests)
            <div class="mt-6 pt-6 border-t border-slate-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Special Requests</h3>
                </div>
                <p class="text-slate-700 whitespace-pre-line">{{ $touristRequest->special_requests }}</p>
            </div>
            @endif
        </div>

        <!-- My Bids Section -->
        @if($myBids->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">My Proposals ({{ $myBids->count() }}/2)</h2>
                    <p class="text-sm text-slate-500">Proposals you've submitted for this request</p>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($myBids as $bid)
                @php
                    $bidColors = [
                        'pending' => 'bg-amber-50 border-amber-200',
                        'accepted' => 'bg-emerald-50 border-emerald-200',
                        'rejected' => 'bg-red-50 border-red-200',
                    ];
                    $bidColor = $bidColors[$bid->status] ?? 'bg-slate-50 border-slate-200';
                @endphp
                <div class="border-2 {{ $bidColor }} rounded-xl p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-slate-900">Bid #{{ $bid->bid_number }}</h3>
                                @if($bid->status === 'pending')
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-lg">Pending</span>
                                @elseif($bid->status === 'accepted')
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg">Accepted</span>
                                @elseif($bid->status === 'rejected')
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-lg">Rejected</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Submitted {{ $bid->created_at->diffForHumans() }}</p>
                        </div>
                        <p class="text-2xl font-bold text-amber-600">${{ number_format($bid->total_price, 2) }}</p>
                    </div>

                    <p class="text-sm text-slate-700 mb-2">{{ Str::limit($bid->proposal_message, 150) }}</p>

                    @if($bid->status === 'rejected' && $bid->rejection_reason)
                    <div class="mt-3 p-3 bg-red-100 rounded-xl">
                        <p class="text-sm text-red-800"><span class="font-semibold">Rejection Reason:</span> {{ $bid->rejection_reason }}</p>
                    </div>
                    @endif

                    @if($bid->status === 'accepted')
                    <div class="mt-3 p-3 bg-emerald-100 rounded-xl">
                        <p class="text-sm text-emerald-800"><span class="font-semibold">Congratulations!</span> Your proposal was accepted. The tourist will contact you to finalize the booking.</p>
                    </div>
                    @endif

                    @if($bid->status === 'pending')
                    <div class="mt-3">
                        <form action="{{ route('guide.request-proposals.withdraw', $bid) }}" method="POST" onsubmit="return confirm('Are you sure you want to withdraw this proposal?')">
                            @csrf
                            <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 text-sm font-semibold transition">
                                Withdraw Proposal
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900">Bidding Status</h3>
            </div>

            @if($canSubmitBid)
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                <p class="text-sm text-emerald-800 mb-3">
                    @if($bidCount === 0)
                        You can submit up to 2 proposals for this request.
                    @else
                        You have submitted {{ $bidCount }} {{ Str::plural('proposal', $bidCount) }}. You can submit {{ 2 - $bidCount }} more.
                    @endif
                </p>
                <a href="{{ route('guide.request-proposals.create', $touristRequest) }}"
                   class="block w-full px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold text-center shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
                    Submit {{ $bidCount === 0 ? 'First' : 'Second' }} Proposal
                </a>
            </div>
            @else
            <div class="mb-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                <p class="text-sm text-slate-600">
                    You have already submitted the maximum of 2 proposals for this request.
                </p>
            </div>
            @endif

            <!-- Request Stats -->
            <div class="pt-4 border-t border-slate-200 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Total Bids</span>
                    <span class="font-semibold text-slate-900">{{ $touristRequest->bid_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Your Bids</span>
                    <span class="font-semibold text-slate-900">{{ $bidCount }}/2</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Expires</span>
                    <span class="font-semibold text-orange-600">{{ $touristRequest->expires_at->diffForHumans() }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Expire Date</span>
                    <span class="font-semibold text-slate-900">{{ $touristRequest->expires_at->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Competition Alert -->
            @if($touristRequest->bid_count > 5)
            <div class="mt-4 p-3 bg-orange-50 border border-orange-200 rounded-xl">
                <p class="text-xs text-orange-800">
                    <span class="font-semibold">High Competition:</span> This request has {{ $touristRequest->bid_count }} bids. Make your proposal stand out!
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
