<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Proposals</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('layouts.navigation')

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Proposals</h1>
            <p class="text-gray-600 mt-2">View and manage your tour proposals</p>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <a href="{{ route('tourist.proposals.index') }}"
                       class="border-b-2 {{ !request('status') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                        All
                    </a>
                    <a href="{{ route('tourist.proposals.index', ['status' => 'pending']) }}"
                       class="border-b-2 {{ request('status') === 'pending' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                        Pending
                    </a>
                    <a href="{{ route('tourist.proposals.index', ['status' => 'accepted']) }}"
                       class="border-b-2 {{ request('status') === 'accepted' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                        Accepted
                    </a>
                    <a href="{{ route('tourist.proposals.index', ['status' => 'rejected']) }}"
                       class="border-b-2 {{ request('status') === 'rejected' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                        Rejected
                    </a>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($proposals->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">No proposals yet</h3>
                <p class="mt-2 text-gray-600">You haven't sent any proposals yet. Browse tours and send a proposal to negotiate your perfect trip!</p>
                <div class="mt-6">
                    <a href="{{ route('plans.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                        Browse Tours
                    </a>
                </div>
            </div>
        @else
            <!-- Proposals List -->
            <div class="space-y-4">
                @foreach($proposals as $proposal)
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                            'accepted' => 'bg-green-100 text-green-800 border-green-300',
                            'rejected' => 'bg-red-100 text-red-800 border-red-300',
                            'cancelled' => 'bg-gray-100 text-gray-800 border-gray-300',
                        ];
                        $statusColor = $statusColors[$proposal->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                    @endphp

                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                <!-- Left: Proposal Info -->
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-900">
                                                {{ $proposal->guidePlan->title }}
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Guide: {{ $proposal->guidePlan->guide->full_name }}
                                            </p>
                                        </div>
                                        <span class="ml-4 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                            {{ ucfirst($proposal->status) }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Dates</span>
                                            <p class="font-semibold text-gray-900">{{ $proposal->start_date->format('M d') }} - {{ $proposal->end_date->format('M d, Y') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Travelers</span>
                                            <p class="font-semibold text-gray-900">
                                                {{ $proposal->num_adults }} {{ Str::plural('Adult', $proposal->num_adults) }}
                                                @if($proposal->num_children > 0)
                                                    , {{ $proposal->num_children }} {{ Str::plural('Child', $proposal->num_children) }}
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Proposed Price</span>
                                            <p class="font-semibold text-gray-900">${{ number_format($proposal->proposed_price, 2) }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Submitted</span>
                                            <p class="font-semibold text-gray-900">{{ $proposal->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>

                                    @if($proposal->status === 'rejected' && $proposal->rejection_reason)
                                        <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3">
                                            <p class="text-sm text-red-800">
                                                <span class="font-semibold">Rejection reason:</span> {{ $proposal->rejection_reason }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Right: Actions -->
                                <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col items-end space-y-3">
                                    <a href="{{ route('tourist.proposals.show', $proposal->id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                        View Details
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>

                                    @if($proposal->status === 'accepted' && $proposal->booking)
                                        <a href="{{ route('bookings.show', $proposal->booking->id) }}"
                                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                            View Booking
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $proposals->links() }}
            </div>
        @endif
    </div>
</body>
</html>
