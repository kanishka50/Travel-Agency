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
            <a href="{{ route('tourist-requests.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to My Requests
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
                            <p class="text-sm text-gray-500 mt-1">Posted on {{ $touristRequest->created_at->format('M d, Y') }}</p>
                        </div>

                        <!-- Status Badge -->
                        @if($touristRequest->status === 'open')
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">Open</span>
                        @elseif($touristRequest->status === 'closed')
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">Closed</span>
                        @elseif($touristRequest->status === 'expired')
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">Expired</span>
                        @endif
                    </div>

                    <div class="prose max-w-none">
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
                                <p class="text-xs text-gray-500">Flexible</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Budget Range</p>
                            <p class="font-medium text-gray-900">${{ number_format($touristRequest->budget_min) }} - ${{ number_format($touristRequest->budget_max) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Travelers</p>
                            <p class="font-medium text-gray-900">{{ $touristRequest->num_adults }} {{ Str::plural('adult', $touristRequest->num_adults) }}
                            @if($touristRequest->num_children > 0), {{ $touristRequest->num_children }} {{ Str::plural('child', $touristRequest->num_children) }}@endif
                            </p>
                        </div>
                        @if($touristRequest->accommodation_preference)
                        <div>
                            <p class="text-sm text-gray-500">Accommodation</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($touristRequest->accommodation_preference) }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Received Bids -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">
                        Proposals Received ({{ $touristRequest->bids->count() }})
                    </h2>

                    @if($touristRequest->bids->count() > 0)
                        <div class="space-y-4">
                            @foreach($touristRequest->bids as $bid)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="font-semibold text-gray-900">{{ $bid->guide->full_name }}</h3>
                                            @if($bid->bid_number === 2)
                                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">Bid #2</span>
                                            @endif
                                            @if($bid->status === 'pending')
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Pending</span>
                                            @elseif($bid->status === 'accepted')
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Accepted</span>
                                            @elseif($bid->status === 'rejected')
                                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">Rejected</span>
                                            @endif
                                        </div>
                                        <p class="text-2xl font-bold text-blue-600 mb-2">${{ number_format($bid->total_price, 2) }}</p>
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $bid->proposal_message }}</p>
                                        <p class="text-xs text-gray-500 mt-2">Submitted {{ $bid->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('bids.show', [$touristRequest, $bid]) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium inline-block">
                                            View Proposal
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>No proposals received yet</p>
                            <p class="text-sm mt-1">Guides are viewing your request and preparing their proposals</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($touristRequest->status === 'open')
                            @if($touristRequest->bid_count === 0)
                            <a href="{{ route('tourist-requests.edit', $touristRequest->id) }}"
                               class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-center font-medium">
                                Edit Request
                            </a>
                            @endif

                            <form action="{{ route('tourist-requests.close', $touristRequest->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Are you sure you want to close this request?')"
                                        class="w-full px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 font-medium">
                                    Close Request
                                </button>
                            </form>
                        @endif

                        @if($touristRequest->bids()->where('status', 'pending')->count() === 0)
                        <form action="{{ route('tourist-requests.destroy', $touristRequest->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this request?')"
                                    class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-medium">
                                Delete Request
                            </button>
                        </form>
                        @endif
                    </div>

                    <!-- Expiry Info -->
                    @if($touristRequest->status === 'open')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500">Expires on</p>
                        <p class="font-medium text-gray-900">{{ $touristRequest->expires_at->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $touristRequest->expires_at->diffForHumans() }}</p>
                    </div>
                    @endif

                    <!-- Stats -->
                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Bids</span>
                            <span class="font-semibold text-gray-900">{{ $touristRequest->bid_count }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Pending</span>
                            <span class="font-semibold text-gray-900">{{ $touristRequest->bids()->where('status', 'pending')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
