@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('guide.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-emerald-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-emerald-600 md:ml-2">Tour Proposals</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tour Proposals</h1>
                <p class="text-gray-600 mt-2">Review and respond to proposals from tourists</p>
            </div>
            @if($pendingCount > 0)
                <div class="bg-amber-100 text-amber-800 px-4 py-2 rounded-full font-semibold">
                    {{ $pendingCount }} Pending
                </div>
            @endif
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <a href="{{ route('guide.proposals.index') }}"
                       class="border-b-2 {{ !request('status') ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                        All
                    </a>
                    <a href="{{ route('guide.proposals.index', ['status' => 'pending']) }}"
                       class="border-b-2 {{ request('status') === 'pending' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold flex items-center">
                        Pending
                        @if($pendingCount > 0)
                            <span class="ml-2 bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('guide.proposals.index', ['status' => 'accepted']) }}"
                       class="border-b-2 {{ request('status') === 'accepted' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                        Accepted
                    </a>
                    <a href="{{ route('guide.proposals.index', ['status' => 'rejected']) }}"
                       class="border-b-2 {{ request('status') === 'rejected' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-semibold">
                        Rejected
                    </a>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="ml-3 text-sm text-emerald-700">{{ session('success') }}</p>
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
                <p class="mt-2 text-gray-600">You haven't received any proposals for your tours yet.</p>
                <div class="mt-6">
                    <a href="{{ route('guide.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        @else
            <!-- Proposals List -->
            <div class="space-y-4">
                @foreach($proposals as $proposal)
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
                            'accepted' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                            'rejected' => 'bg-red-100 text-red-800 border-red-300',
                            'cancelled' => 'bg-gray-100 text-gray-800 border-gray-300',
                        ];
                        $statusColor = $statusColors[$proposal->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                    @endphp

                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow {{ $proposal->status === 'pending' ? 'ring-2 ring-amber-400' : '' }}">
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
                                                From: {{ $proposal->tourist->full_name }} ({{ $proposal->tourist->country }})
                                            </p>
                                        </div>
                                        <span class="ml-4 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                                            {{ ucfirst($proposal->status) }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Proposed Dates</span>
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
                                            <p class="font-semibold {{ $proposal->isBelowMinimum() ? 'text-red-600' : 'text-emerald-600' }}">
                                                ${{ number_format($proposal->proposed_price, 2) }}
                                                @if($proposal->discount_percentage > 0)
                                                    <span class="text-xs text-gray-500">({{ $proposal->discount_percentage }}% off)</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Received</span>
                                            <p class="font-semibold text-gray-900">{{ $proposal->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>

                                    @if($proposal->modifications)
                                        <div class="mt-3 bg-cyan-50 border border-cyan-200 rounded-lg p-3">
                                            <p class="text-sm text-cyan-800">
                                                <span class="font-semibold">Modifications:</span> {{ Str::limit($proposal->modifications, 100) }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Right: Actions -->
                                <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col items-end space-y-3">
                                    <a href="{{ route('guide.proposals.show', $proposal->id) }}"
                                       class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                        {{ $proposal->status === 'pending' ? 'Review & Respond' : 'View Details' }}
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
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
</div>
@endsection
