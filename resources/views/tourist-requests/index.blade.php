<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tour Requests - Travel Agency</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Tour Requests</h1>
                <p class="mt-2 text-gray-600">View and manage your custom tour requests</p>
            </div>
            <a href="{{ route('tourist-requests.create') }}"
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow-md hover:shadow-lg transition-all">
                + New Request
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

        <!-- Requests List -->
        @if($requests->count() > 0)
            <div class="space-y-4">
                @foreach($requests as $request)
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <!-- Request Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $request->title }}</h3>

                                <!-- Status Badge -->
                                @if($request->status === 'open')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Open</span>
                                @elseif($request->status === 'bids_received')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">{{ $request->bid_count }} Bids</span>
                                @elseif($request->status === 'bid_accepted')
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">Accepted</span>
                                @elseif($request->status === 'closed')
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Closed</span>
                                @elseif($request->status === 'expired')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Expired</span>
                                @endif
                            </div>

                            <p class="text-gray-600 mb-3 line-clamp-2">{{ Str::limit($request->description, 150) }}</p>

                            <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ is_array($request->preferred_destinations) ? implode(', ', $request->preferred_destinations) : $request->preferred_destinations }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $request->start_date->format('M d, Y') }} - {{ $request->end_date->format('M d, Y') }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    ${{ number_format($request->budget_min) }} - ${{ number_format($request->budget_max) }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $request->duration_days }} days
                                </span>
                            </div>

                            <!-- Bids Count -->
                            @if($request->bid_count > 0)
                            <div class="mt-3 text-sm">
                                <span class="text-blue-600 font-medium">{{ $request->bid_count }} {{ Str::plural('proposal', $request->bid_count) }} received</span>
                            </div>
                            @endif

                            <!-- Expiry Warning -->
                            @if($request->status === 'open' && $request->expires_at->isPast())
                            <div class="mt-2 text-sm text-red-600">
                                Expired on {{ $request->expires_at->format('M d, Y') }}
                            </div>
                            @elseif($request->status === 'open' && $request->expires_at->diffInDays(now()) <= 3)
                            <div class="mt-2 text-sm text-orange-600">
                                Expires in {{ $request->expires_at->diffInDays(now()) }} {{ Str::plural('day', $request->expires_at->diffInDays(now())) }}
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 lg:mt-0 lg:ml-6 flex flex-col gap-2">
                            <a href="{{ route('tourist-requests.show', $request->id) }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center font-medium transition-colors">
                                View Details
                            </a>

                            @if($request->status === 'open' && $request->bid_count === 0)
                            <a href="{{ route('tourist-requests.edit', $request->id) }}"
                               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-center font-medium transition-colors">
                                Edit
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">No tour requests yet</h3>
                <p class="mt-2 text-gray-600 max-w-md mx-auto">Start by creating your first custom tour request. Guides will submit their best proposals for you to choose from.</p>
                <a href="{{ route('tourist-requests.create') }}"
                   class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow-md hover:shadow-lg transition-all">
                    Create Your First Request
                </a>
            </div>
        @endif
    </div>
</body>
</html>
