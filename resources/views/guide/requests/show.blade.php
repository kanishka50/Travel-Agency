<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $touristRequest->title }} - Travel Agency</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('guide.requests.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Requests
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Details -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $touristRequest->title }}</h1>
                            <p class="text-sm text-gray-500 mt-1">Posted {{ $touristRequest->created_at->diffForHumans() }}</p>
                        </div>

                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">Open</span>
                    </div>

                    <div class="prose max-w-none">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $touristRequest->description }}</p>
                    </div>

                    <!-- Key Details -->
                    <div class="mt-6 grid grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                        <div>
                            <p class="text-sm text-gray-500">Destinations</p>
                            <p class="font-medium text-gray-900">{{ is_array($touristRequest->preferred_destinations) ? implode(', ', $touristRequest->preferred_destinations) : $touristRequest->preferred_destinations }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Duration</p>
                            <p class="font-medium text-gray-900">{{ $touristRequest->duration_days }} days</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Dates</p>
                            <p class="font-medium text-gray-900">{{ $touristRequest->start_date->format('M d, Y') }} - {{ $touristRequest->end_date->format('M d, Y') }}</p>
                            @if($touristRequest->dates_flexible)
                                <p class="text-xs text-green-600 mt-1">Flexible (±{{ $touristRequest->flexibility_range }} days)</p>
                            @else
                                <p class="text-xs text-gray-500 mt-1">Fixed dates</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Budget Range</p>
                            <p class="font-medium text-gray-900">${{ number_format($touristRequest->budget_min) }} - ${{ number_format($touristRequest->budget_max) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Travelers</p>
                            <p class="font-medium text-gray-900">{{ $touristRequest->num_adults }} {{ Str::plural('adult', $touristRequest->num_adults) }}
                            @if($touristRequest->num_children > 0)
                                , {{ $touristRequest->num_children }} {{ Str::plural('child', $touristRequest->num_children) }}
                                @if($touristRequest->children_ages)
                                    (ages: {{ is_array($touristRequest->children_ages) ? implode(', ', $touristRequest->children_ages) : $touristRequest->children_ages }})
                                @endif
                            @endif
                            </p>
                        </div>
                        @if($touristRequest->trip_focus)
                        <div>
                            <p class="text-sm text-gray-500">Trip Focus</p>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach(is_array($touristRequest->trip_focus) ? $touristRequest->trip_focus : [$touristRequest->trip_focus] as $focus)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">{{ ucfirst($focus) }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Additional Preferences -->
                    @if($touristRequest->transport_preference || $touristRequest->accommodation_preference || $touristRequest->dietary_requirements || $touristRequest->accessibility_needs)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Preferences & Requirements</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @if($touristRequest->transport_preference)
                            <div>
                                <p class="text-sm text-gray-500">Transport</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($touristRequest->transport_preference) }}</p>
                            </div>
                            @endif

                            @if($touristRequest->accommodation_preference)
                            <div>
                                <p class="text-sm text-gray-500">Accommodation</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($touristRequest->accommodation_preference) }}</p>
                            </div>
                            @endif

                            @if($touristRequest->dietary_requirements)
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500">Dietary Requirements</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach(is_array($touristRequest->dietary_requirements) ? $touristRequest->dietary_requirements : [$touristRequest->dietary_requirements] as $diet)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ ucfirst($diet) }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($touristRequest->accessibility_needs)
                            <div class="col-span-2">
                                <p class="text-sm text-gray-500">Accessibility Needs</p>
                                <p class="font-medium text-gray-900">{{ $touristRequest->accessibility_needs }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Special Requests -->
                    @if($touristRequest->special_requests)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Special Requests</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $touristRequest->special_requests }}</p>
                    </div>
                    @endif
                </div>

                <!-- My Bids Section -->
                @if($myBids->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        My Proposals ({{ $myBids->count() }}/2)
                    </h2>

                    <div class="space-y-4">
                        @foreach($myBids as $bid)
                        <div class="border-2 {{ $bid->status === 'accepted' ? 'border-green-300 bg-green-50' : ($bid->status === 'rejected' ? 'border-red-300 bg-red-50' : 'border-blue-300 bg-blue-50') }} rounded-lg p-4">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-gray-900">Bid #{{ $bid->bid_number }}</h3>
                                        @if($bid->status === 'pending')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Pending</span>
                                        @elseif($bid->status === 'accepted')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Accepted</span>
                                        @elseif($bid->status === 'rejected')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Rejected</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Submitted {{ $bid->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-2xl font-bold text-blue-600">${{ number_format($bid->total_price, 2) }}</p>
                            </div>

                            <p class="text-sm text-gray-700 mb-2">{{ Str::limit($bid->proposal_message, 150) }}</p>

                            @if($bid->status === 'rejected' && $bid->rejection_reason)
                            <div class="mt-3 p-3 bg-red-100 rounded">
                                <p class="text-sm text-red-800"><strong>Rejection Reason:</strong> {{ $bid->rejection_reason }}</p>
                            </div>
                            @endif

                            @if($bid->status === 'accepted')
                            <div class="mt-3 p-3 bg-green-100 rounded">
                                <p class="text-sm text-green-800"><strong>Congratulations!</strong> Your proposal was accepted. The tourist will contact you to finalize the booking.</p>
                            </div>
                            @endif

                            @if($bid->status === 'pending')
                            <div class="mt-3">
                                <form action="{{ route('guide.bids.withdraw', $bid) }}" method="POST" onsubmit="return confirm('Are you sure you want to withdraw this proposal?')">
                                    @csrf
                                    <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200 text-sm font-medium">
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
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Bidding Status</h3>

                    @if($canSubmitBid)
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800 mb-3">
                            @if($bidCount === 0)
                                You can submit up to 2 proposals for this request.
                            @else
                                You have submitted {{ $bidCount }} {{ Str::plural('proposal', $bidCount) }}. You can submit {{ 2 - $bidCount }} more.
                            @endif
                        </p>
                        <a href="{{ route('guide.bids.create', $touristRequest) }}" class="block w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 text-center font-semibold shadow-md hover:shadow-lg transition-all">
                            Submit {{ $bidCount === 0 ? 'First' : 'Second' }} Proposal
                        </a>
                    </div>
                    @else
                    <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="text-sm text-gray-600">
                            You have already submitted the maximum of 2 proposals for this request.
                        </p>
                    </div>
                    @endif

                    <!-- Request Stats -->
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Bids</span>
                            <span class="font-semibold text-gray-900">{{ $touristRequest->bid_count }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Your Bids</span>
                            <span class="font-semibold text-gray-900">{{ $bidCount }}/2</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Expires</span>
                            <span class="font-semibold text-orange-600">{{ $touristRequest->expires_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Expire Date</span>
                            <span class="font-semibold text-gray-900">{{ $touristRequest->expires_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <!-- Competition Alert -->
                    @if($touristRequest->bid_count > 5)
                    <div class="mt-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-xs text-orange-800">
                            <strong>High Competition:</strong> This request has {{ $touristRequest->bid_count }} bids. Make your proposal stand out!
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
