@extends('layouts.dashboard')

@section('page-title', 'Tour Proposals')

@section('content')
<!-- Page Header -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Tour Proposals</h1>
        <p class="text-slate-500 mt-1">Review and respond to proposals from tourists</p>
    </div>
    @if($pendingCount > 0)
        <div class="px-4 py-2 bg-amber-100 text-amber-800 rounded-xl font-semibold">
            {{ $pendingCount }} Pending
        </div>
    @endif
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
    <div class="border-b border-slate-200">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            <a href="{{ route('guide.proposals.index') }}"
               class="border-b-2 {{ !request('status') ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} py-4 px-1 text-sm font-semibold">
                All
            </a>
            <a href="{{ route('guide.proposals.index', ['status' => 'pending']) }}"
               class="border-b-2 {{ request('status') === 'pending' ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} py-4 px-1 text-sm font-semibold flex items-center">
                Pending
                @if($pendingCount > 0)
                    <span class="ml-2 bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('guide.proposals.index', ['status' => 'accepted']) }}"
               class="border-b-2 {{ request('status') === 'accepted' ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} py-4 px-1 text-sm font-semibold">
                Accepted
            </a>
            <a href="{{ route('guide.proposals.index', ['status' => 'rejected']) }}"
               class="border-b-2 {{ request('status') === 'rejected' ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} py-4 px-1 text-sm font-semibold">
                Rejected
            </a>
        </nav>
    </div>
</div>

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

@if($proposals->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">No proposals yet</h3>
        <p class="text-slate-600 max-w-md mx-auto mb-6">You haven't received any proposals for your tours yet.</p>
        <a href="{{ route('guide.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
            Back to Dashboard
        </a>
    </div>
@else
    <!-- Proposals List -->
    <div class="space-y-4">
        @foreach($proposals as $proposal)
            @php
                $statusColors = [
                    'pending' => 'bg-amber-100 text-amber-700',
                    'accepted' => 'bg-emerald-100 text-emerald-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    'cancelled' => 'bg-slate-100 text-slate-700',
                ];
                $statusColor = $statusColors[$proposal->status] ?? 'bg-slate-100 text-slate-700';
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-amber-200 transition-all duration-300 {{ $proposal->status === 'pending' ? 'ring-2 ring-amber-400' : '' }}">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <!-- Left: Proposal Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900">
                                        {{ $proposal->guidePlan->title }}
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1">
                                        From: {{ $proposal->tourist->full_name }} ({{ $proposal->tourist->country }})
                                    </p>
                                </div>
                                <span class="ml-4 px-3 py-1 rounded-lg text-xs font-semibold {{ $statusColor }}">
                                    {{ ucfirst($proposal->status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div class="bg-slate-50 rounded-xl p-3">
                                    <span class="text-slate-500 text-xs">Proposed Dates</span>
                                    <p class="font-semibold text-slate-900">{{ $proposal->start_date->format('M d') }} - {{ $proposal->end_date->format('M d, Y') }}</p>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-3">
                                    <span class="text-slate-500 text-xs">Travelers</span>
                                    <p class="font-semibold text-slate-900">
                                        {{ $proposal->num_adults }} {{ Str::plural('Adult', $proposal->num_adults) }}
                                        @if($proposal->num_children > 0)
                                            , {{ $proposal->num_children }} {{ Str::plural('Child', $proposal->num_children) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-3">
                                    <span class="text-slate-500 text-xs">Proposed Price</span>
                                    <p class="font-semibold {{ $proposal->isBelowMinimum() ? 'text-red-600' : 'text-amber-600' }}">
                                        ${{ number_format($proposal->proposed_price, 2) }}
                                        @if($proposal->discount_percentage > 0)
                                            <span class="text-xs text-slate-500">({{ $proposal->discount_percentage }}% off)</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-3">
                                    <span class="text-slate-500 text-xs">Received</span>
                                    <p class="font-semibold text-slate-900">{{ $proposal->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            @if($proposal->modifications)
                                <div class="mt-3 bg-cyan-50 border border-cyan-200 rounded-xl p-3">
                                    <p class="text-sm text-cyan-800">
                                        <span class="font-semibold">Modifications:</span> {{ Str::limit($proposal->modifications, 100) }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Right: Actions -->
                        <div class="mt-6 lg:mt-0 lg:ml-6 flex flex-col items-end space-y-3">
                            <a href="{{ route('guide.proposals.show', $proposal->id) }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                                {{ $proposal->status === 'pending' ? 'Review & Respond' : 'View Details' }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
@endsection
