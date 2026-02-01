@extends('layouts.dashboard')

@section('page-title', 'Send Proposal')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('tour-packages.show', $plan) }}" class="inline-flex items-center text-slate-600 hover:text-amber-600 transition-colors group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Tour Package
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <!-- Plan Summary Header -->
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold">Send a Proposal</h1>
        </div>
        <p class="text-amber-100">{{ $plan->title }}</p>
        <div class="mt-3 flex flex-wrap gap-4 text-sm">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $plan->num_days }} days / {{ $plan->num_nights }} nights
            </span>
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Listed Price: ${{ number_format($plan->price_per_adult, 2) }}/person
            </span>
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ is_array($plan->destinations) ? implode(', ', $plan->destinations) : $plan->destinations }}
            </span>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('proposals.store', $plan->id) }}" method="POST" class="p-6 space-y-6">
        @csrf

        <!-- Date Selection -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg font-bold text-amber-600">1</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Select Your Dates</h3>
            </div>
            <div class="bg-slate-50 rounded-xl p-4">
                <div class="mb-4">
                    <p class="text-sm text-slate-600">
                        Tour Duration: <span class="font-semibold">{{ $plan->num_days }} days / {{ $plan->num_nights }} nights</span>
                    </p>
                    <p class="text-sm text-slate-500 mt-1">Click on an available date to select your tour start date. The end date will be calculated automatically.</p>
                </div>

                <!-- Calendar Legend -->
                <div class="flex flex-wrap gap-4 mb-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-amber-100 border-2 border-amber-500 rounded mr-2"></div>
                        <span class="text-slate-600">Available</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-100 border-2 border-red-500 rounded mr-2"></div>
                        <span class="text-slate-600">Booked</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-amber-500 rounded mr-2"></div>
                        <span class="text-slate-600">Selected</span>
                    </div>
                </div>

                <div id="calendar" class="mb-4"></div>

                <div id="selected-dates" class="hidden bg-amber-50 border border-amber-200 rounded-xl p-4 mt-4">
                    <p class="text-sm font-semibold text-amber-900">Selected Dates:</p>
                    <p class="text-amber-800">
                        <span id="display-start-date"></span> to <span id="display-end-date"></span>
                        (<span id="display-duration"></span>)
                    </p>
                </div>

                <div id="availability-message" class="mt-4"></div>

                <input type="hidden" name="start_date" id="start_date" value="{{ old('start_date') }}">
                @error('start_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Travelers -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg font-bold text-amber-600">2</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Number of Travelers</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="num_adults" class="block text-sm font-semibold text-slate-700 mb-2">Adults</label>
                    <select name="num_adults" id="num_adults" class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                        @for($i = 1; $i <= min(10, $plan->max_group_size); $i++)
                            <option value="{{ $i }}" {{ old('num_adults', 1) == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Adult' : 'Adults' }}</option>
                        @endfor
                    </select>
                    @error('num_adults')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="num_children" class="block text-sm font-semibold text-slate-700 mb-2">Children (under 18)</label>
                    <select name="num_children" id="num_children" class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                        @for($i = 0; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('num_children', 0) == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Child' : 'Children' }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <p class="text-sm text-slate-500 mt-2">Maximum group size: {{ $plan->max_group_size }} travelers</p>
        </div>

        <!-- Proposed Price -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg font-bold text-amber-600">3</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Your Proposed Price</h3>
            </div>
            <div class="bg-slate-50 rounded-xl p-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <label for="proposed_price" class="block text-sm font-semibold text-slate-700 mb-2">Total Price (USD)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500">$</span>
                            <input type="number" name="proposed_price" id="proposed_price"
                                   value="{{ old('proposed_price', $plan->price_per_adult) }}"
                                   step="0.01" min="1"
                                   class="w-full pl-8 rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
                        </div>
                        @error('proposed_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-slate-500">Listed Price</p>
                        <p class="text-lg font-bold text-amber-600">${{ number_format($plan->price_per_adult, 2) }}</p>
                    </div>
                </div>

                @if($plan->min_proposal_price)
                    <div id="min-price-warning" class="hidden mt-3 bg-amber-50 border border-amber-200 rounded-xl p-3">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-amber-800">Price below guide's minimum</p>
                                <p class="text-sm text-amber-700 mt-1">The guide's minimum acceptable price is ${{ number_format($plan->min_proposal_price, 2) }}. You can still submit this proposal, but it may be less likely to be accepted.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <p class="text-sm text-slate-500 mt-3">
                    <strong>Note:</strong> This is your proposed total price for the tour. A 10% platform fee will be added at checkout if your proposal is accepted.
                </p>
            </div>
        </div>

        <!-- Modifications -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg font-bold text-amber-600">4</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Requested Modifications (Optional)</h3>
            </div>
            <div>
                <label for="modifications" class="block text-sm font-semibold text-slate-700 mb-2">
                    What changes would you like to make to this tour?
                </label>
                <textarea name="modifications" id="modifications" rows="4"
                          placeholder="E.g., Skip the temple visit and add an extra beach day, include a cooking class, etc."
                          class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">{{ old('modifications') }}</textarea>
                <p class="text-sm text-slate-500 mt-1">Describe any locations you'd like to add or remove, or any other modifications to the itinerary.</p>
                @error('modifications')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Message -->
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-lg font-bold text-amber-600">5</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Message to Guide (Optional)</h3>
            </div>
            <div>
                <label for="message" class="block text-sm font-semibold text-slate-700 mb-2">
                    Anything else you'd like the guide to know?
                </label>
                <textarea name="message" id="message" rows="3"
                          placeholder="E.g., We prefer a relaxed pace, interested in local food experiences, etc."
                          class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit -->
        <div class="border-t border-slate-200 pt-6">
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                <h4 class="font-semibold text-amber-900">What happens next?</h4>
                <ul class="text-sm text-amber-800 mt-2 space-y-1">
                    <li>1. The guide will review your proposal</li>
                    <li>2. If accepted, a booking will be created for you</li>
                    <li>3. You can then proceed to payment to confirm your booking</li>
                </ul>
            </div>

            <button type="submit" id="submit-btn" disabled
                    class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:shadow-amber-500/40 disabled:from-slate-400 disabled:to-slate-400 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl shadow-lg shadow-amber-500/25 transition-all duration-300">
                Submit Proposal
            </button>
        </div>
    </form>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planId = {{ $plan->id }};
        const numDays = {{ $plan->num_days }};
        const minPrice = {{ $plan->min_proposal_price ?? 0 }};
        const guideId = {{ $plan->guide_id }};

        let bookedDates = [];
        let selectedDate = null;
        let calendar;

        // Initialize calendar
        const calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            firstDay: 1,
            validRange: {
                start: new Date().toISOString().split('T')[0],
                @if($plan->available_end_date)
                end: '{{ $plan->available_end_date->format('Y-m-d') }}'
                @endif
            },
            dateClick: function(info) {
                handleDateClick(info.dateStr);
            },
            dayCellDidMount: function(info) {
                styleDayCell(info.el, info.date);
            },
            datesSet: function(info) {
                loadAvailability(info.start.toISOString().split('T')[0].slice(0, 7));
            }
        });
        calendar.render();

        // Load initial availability
        loadAvailability(new Date().toISOString().split('T')[0].slice(0, 7));

        function loadAvailability(month) {
            fetch(`/api/plans/${planId}/availability?month=${month}`)
                .then(response => response.json())
                .then(data => {
                    bookedDates = data.booked_dates || [];
                    calendar.render();
                });
        }

        function styleDayCell(el, date) {
            const dateStr = date.toISOString().split('T')[0];
            const today = new Date().toISOString().split('T')[0];

            // Remove existing classes
            el.classList.remove('fc-day-available', 'fc-day-booked', 'fc-day-past', 'fc-day-selected');

            if (dateStr < today) {
                el.classList.add('fc-day-past');
                el.style.backgroundColor = '#f1f5f9';
                el.style.cursor = 'not-allowed';
            } else if (isDateBooked(dateStr)) {
                el.classList.add('fc-day-booked');
                el.style.backgroundColor = '#fef2f2';
                el.style.border = '2px solid #ef4444';
                el.style.cursor = 'not-allowed';
            } else if (selectedDate === dateStr) {
                el.classList.add('fc-day-selected');
                el.style.backgroundColor = '#f59e0b';
                el.style.color = 'white';
            } else {
                el.classList.add('fc-day-available');
                el.style.backgroundColor = '#fffbeb';
                el.style.border = '2px solid #f59e0b';
                el.style.cursor = 'pointer';
            }
        }

        function isDateBooked(dateStr) {
            return bookedDates.some(booking => {
                return dateStr >= booking.start && dateStr < booking.end;
            });
        }

        function handleDateClick(dateStr) {
            const today = new Date().toISOString().split('T')[0];

            if (dateStr < today) {
                showMessage('Cannot select past dates.', 'error');
                return;
            }

            if (isDateBooked(dateStr)) {
                showMessage('This date is already booked. Please select another date.', 'error');
                return;
            }

            // Check availability via API
            checkAvailability(dateStr);
        }

        function checkAvailability(startDate) {
            showMessage('Checking availability...', 'info');

            fetch(`/api/plans/${planId}/check-dates`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ start_date: startDate })
            })
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    selectedDate = startDate;
                    document.getElementById('start_date').value = startDate;

                    // Show selected dates
                    const startDateObj = new Date(startDate);
                    const endDateObj = new Date(data.end_date);

                    document.getElementById('display-start-date').textContent = startDateObj.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
                    document.getElementById('display-end-date').textContent = endDateObj.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
                    document.getElementById('display-duration').textContent = numDays + ' days';
                    document.getElementById('selected-dates').classList.remove('hidden');

                    showMessage('Dates are available!', 'success');
                    document.getElementById('submit-btn').disabled = false;

                    calendar.render();
                } else {
                    showMessage(data.message || 'Dates are not available.', 'error');
                    document.getElementById('submit-btn').disabled = true;
                }
            })
            .catch(error => {
                showMessage('Error checking availability. Please try again.', 'error');
            });
        }

        function showMessage(message, type) {
            const messageEl = document.getElementById('availability-message');
            const colors = {
                success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
                error: 'bg-red-50 border-red-200 text-red-800',
                info: 'bg-amber-50 border-amber-200 text-amber-800'
            };

            messageEl.innerHTML = `<div class="border rounded-xl p-3 ${colors[type]}">${message}</div>`;
        }

        // Min price warning
        const priceInput = document.getElementById('proposed_price');
        const minPriceWarning = document.getElementById('min-price-warning');

        if (priceInput && minPriceWarning && minPrice > 0) {
            priceInput.addEventListener('input', function() {
                if (parseFloat(this.value) < minPrice) {
                    minPriceWarning.classList.remove('hidden');
                } else {
                    minPriceWarning.classList.add('hidden');
                }
            });

            // Check on page load
            if (parseFloat(priceInput.value) < minPrice) {
                minPriceWarning.classList.remove('hidden');
            }
        }
    });
</script>

<style>
    /* Calendar styling */
    .fc .fc-button {
        background-color: #f59e0b !important;
        border-color: #f59e0b !important;
    }
    .fc .fc-button:hover {
        background-color: #d97706 !important;
        border-color: #d97706 !important;
    }
    .fc-day-available:hover {
        background-color: #fef3c7 !important;
        transform: scale(1.02);
        transition: all 0.2s;
    }
    .fc-day-selected .fc-daygrid-day-number {
        color: white !important;
    }
</style>
@endsection
