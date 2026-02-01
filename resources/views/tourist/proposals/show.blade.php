@extends('layouts.dashboard')

@section('page-title', 'Proposal Details')

@section('content')
<!-- Header -->
<div class="mb-6">
    <a href="{{ route('tourist.proposals.index') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium group">
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to My Proposals
    </a>
</div>

@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex items-center gap-3">
        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <p class="text-emerald-700">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-center gap-3">
        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-red-700">{{ session('error') }}</p>
    </div>
@endif

@php
    $statusColors = [
        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
        'accepted' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
        'rejected' => 'bg-red-100 text-red-700 border-red-200',
        'cancelled' => 'bg-slate-100 text-slate-700 border-slate-200',
    ];
    $statusColor = $statusColors[$proposal->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
@endphp

<!-- Status Banner -->
@if($proposal->status === 'accepted')
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 mb-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-emerald-900 text-lg">Proposal Accepted!</h3>
                <p class="text-emerald-800 mt-1">The guide has accepted your proposal. A booking has been created for you.</p>
                @if($proposal->booking)
                    <a href="{{ route('tourist.bookings.show', $proposal->booking->id) }}"
                       class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all">
                        View Booking & Pay
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
@elseif($proposal->status === 'rejected')
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-red-900 text-lg">Proposal Rejected</h3>
                @if($proposal->rejection_reason)
                    <p class="text-red-800 mt-1"><strong>Reason:</strong> {{ $proposal->rejection_reason }}</p>
                @endif
                <p class="text-red-700 mt-2">You can submit a new proposal with different terms.</p>
            </div>
        </div>
    </div>
@elseif($proposal->status === 'pending')
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-amber-900 text-lg">Awaiting Guide's Response</h3>
                <p class="text-amber-800 mt-1">Your proposal is being reviewed by the guide. You'll be notified when they respond.</p>
            </div>
        </div>
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">{{ $proposal->guidePlan->title }}</h1>
                <p class="text-amber-100 mt-1">Proposal submitted {{ $proposal->created_at->format('M d, Y') }}</p>
            </div>
            <span class="px-4 py-2 rounded-xl text-sm font-semibold border {{ $statusColor }}">
                {{ ucfirst($proposal->status) }}
            </span>
        </div>
    </div>

    <div class="p-6 space-y-6">
        <!-- Tour Information -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Tour Information</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Tour</span>
                    <p class="font-semibold text-slate-900 mt-1">{{ $proposal->guidePlan->title }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Duration</span>
                    <p class="font-semibold text-slate-900 mt-1">{{ $proposal->guidePlan->num_days }} days / {{ $proposal->guidePlan->num_nights }} nights</p>
                </div>
                <div class="md:col-span-2 bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Destinations</span>
                    <p class="font-semibold text-slate-900 mt-1">
                        @if(is_array($proposal->guidePlan->destinations))
                            {{ implode(', ', $proposal->guidePlan->destinations) }}
                        @else
                            {{ $proposal->guidePlan->destinations }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Guide Information -->
        <div class="border-t border-slate-200 pt-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Guide Information</h3>
            </div>
            <div class="flex items-start space-x-4">
                @if($proposal->guidePlan->guide->profile_photo)
                    <img src="{{ Storage::url($proposal->guidePlan->guide->profile_photo) }}"
                         alt="{{ $proposal->guidePlan->guide->full_name }}"
                         class="w-16 h-16 rounded-2xl object-cover shadow-sm">
                @else
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/25">
                        <span class="text-white text-xl font-bold">{{ substr($proposal->guidePlan->guide->full_name, 0, 1) }}</span>
                    </div>
                @endif
                <div class="flex-1">
                    <p class="font-bold text-slate-900 text-lg">{{ $proposal->guidePlan->guide->full_name }}</p>
                    @if($proposal->guidePlan->guide->regions_can_guide)
                        <p class="text-sm text-slate-600">
                            {{ is_array($proposal->guidePlan->guide->regions_can_guide) ? implode(', ', $proposal->guidePlan->guide->regions_can_guide) : $proposal->guidePlan->guide->regions_can_guide }}
                        </p>
                    @endif

                    @php
                        $paymentCompleted = $proposal->booking && in_array($proposal->booking->status, ['confirmed', 'ongoing', 'completed']);
                    @endphp

                    @if($paymentCompleted)
                        <!-- Show contact details after payment -->
                        <div class="mt-3 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                            <p class="text-sm text-emerald-800 font-semibold mb-2">Contact Details</p>
                            <div class="space-y-1">
                                <p class="text-sm text-slate-700 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $proposal->guidePlan->guide->user->email }}
                                </p>
                                @if($proposal->guidePlan->guide->phone)
                                    <p class="text-sm text-slate-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $proposal->guidePlan->guide->phone }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Show message about contact details -->
                        <p class="text-sm text-slate-500 italic mt-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Contact details will be visible after payment is completed.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Proposal Details -->
        <div class="border-t border-slate-200 pt-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Your Proposal</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Proposed Dates</span>
                    <p class="font-semibold text-slate-900 mt-1">{{ $proposal->start_date->format('M d, Y') }} - {{ $proposal->end_date->format('M d, Y') }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <span class="text-sm text-slate-500">Travelers</span>
                    <p class="font-semibold text-slate-900 mt-1">
                        {{ $proposal->num_adults }} {{ Str::plural('Adult', $proposal->num_adults) }}
                        @if($proposal->num_children > 0)
                            , {{ $proposal->num_children }} {{ Str::plural('Child', $proposal->num_children) }}
                        @endif
                    </p>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-4 border border-amber-100">
                    <span class="text-sm text-amber-600">Proposed Price</span>
                    <p class="font-bold text-amber-600 text-2xl mt-1">${{ number_format($proposal->proposed_price, 2) }}</p>
                    <p class="text-sm text-slate-500 mt-1">
                        (Listed: ${{ number_format($proposal->guidePlan->price_per_adult, 2) }})
                        @if($proposal->discount_percentage > 0)
                            <span class="text-emerald-600 font-semibold">{{ $proposal->discount_percentage }}% off</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($proposal->modifications)
                <div class="mt-4">
                    <span class="text-sm text-slate-500 font-medium">Requested Modifications</span>
                    <div class="mt-2 bg-slate-50 rounded-xl p-4">
                        <p class="text-slate-900 whitespace-pre-wrap">{{ $proposal->modifications }}</p>
                    </div>
                </div>
            @endif

            @if($proposal->message)
                <div class="mt-4">
                    <span class="text-sm text-slate-500 font-medium">Message to Guide</span>
                    <div class="mt-2 bg-slate-50 rounded-xl p-4">
                        <p class="text-slate-900">{{ $proposal->message }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions -->
        @if($proposal->status === 'pending')
            <div class="border-t border-slate-200 pt-6">
                <form action="{{ route('proposals.cancel', $proposal->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to cancel this proposal?');">
                    @csrf
                    <button type="submit"
                            class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors">
                        Cancel Proposal
                    </button>
                </form>
            </div>
        @endif

        <!-- View Tour -->
        <div class="border-t border-slate-200 pt-6">
            <a href="{{ route('tour-packages.show', $proposal->guidePlan->id) }}"
               class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-semibold">
                View Tour Details
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
