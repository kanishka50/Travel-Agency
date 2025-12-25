{{-- Availability Calendar --}}
<div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-5 text-white">
        <h3 class="font-display text-lg font-semibold flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            Check Availability
        </h3>
    </div>

    <div class="p-6">
        <!-- Calendar Container -->
        <div id="availability-calendar" class="mb-5"></div>

        <!-- Tour Duration Notice -->
        <div class="mb-5 p-4 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-800">
                        Tour Duration: {{ $plan->num_days }} day{{ $plan->num_days > 1 ? 's' : '' }} / {{ $plan->num_nights ?? $plan->num_days - 1 }} night{{ ($plan->num_nights ?? $plan->num_days - 1) > 1 ? 's' : '' }}
                    </p>
                    <p class="text-xs text-amber-700 mt-1">
                        Click on an available date to select your tour start date
                    </p>
                </div>
            </div>
        </div>

        <!-- Availability Result -->
        <div id="availability-result" class="hidden mb-5">
            <div id="availability-message" class="p-4 rounded-xl"></div>
            <div id="selected-dates" class="mt-3 px-4 py-3 bg-slate-50 rounded-lg text-sm text-slate-700"></div>
        </div>

        <!-- Book Now Button -->
        <button id="book-now-btn"
                class="w-full py-4 bg-slate-200 text-slate-400 font-semibold rounded-xl transition-all duration-300 disabled:cursor-not-allowed"
                disabled>
            <span id="book-btn-text" class="flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Select dates to continue
            </span>
        </button>

        <!-- Calendar Legend -->
        <div class="mt-6 pt-5 border-t border-slate-100">
            <p class="text-xs font-semibold text-slate-700 mb-3 uppercase tracking-wide">Legend</p>
            <div class="grid grid-cols-2 gap-3">
                <div class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg">
                    <div class="w-4 h-4 rounded bg-gradient-to-br from-amber-100 to-amber-200 border-2 border-amber-500"></div>
                    <span class="text-xs text-slate-600">Available</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg">
                    <div class="w-4 h-4 rounded bg-red-100 border-2 border-red-400"></div>
                    <span class="text-xs text-slate-600">Booked</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg">
                    <div class="w-4 h-4 rounded bg-slate-200"></div>
                    <span class="text-xs text-slate-600">Unavailable</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg">
                    <div class="w-4 h-4 rounded bg-gradient-to-br from-amber-500 to-orange-500"></div>
                    <span class="text-xs text-slate-600">Selected</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const planId = {{ $plan->id }};
    const planDuration = {{ $plan->num_days }};
    const availabilityType = '{{ $plan->availability_type }}';
    const availableStartDate = @json($plan->available_start_date ? $plan->available_start_date->format('Y-m-d') : null);
    const availableEndDate = @json($plan->available_end_date ? $plan->available_end_date->format('Y-m-d') : null);

    const calendarEl = document.getElementById('availability-calendar');
    const bookNowBtn = document.getElementById('book-now-btn');
    const bookBtnText = document.getElementById('book-btn-text');
    const availabilityResult = document.getElementById('availability-result');
    const availabilityMessage = document.getElementById('availability-message');
    const selectedDates = document.getElementById('selected-dates');

    let selectedStartDate = null;
    let selectedEndDate = null;
    let bookedDates = [];
    let calendar = null;

    // Initialize FullCalendar
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        height: 'auto',
        firstDay: 1,
        selectable: false,
        selectMirror: false,
        dayMaxEvents: false,
        validRange: function(nowDate) {
            let startDate = new Date();
            let endDate = null;

            if (availabilityType === 'date_range' && availableStartDate && availableEndDate) {
                startDate = new Date(availableStartDate);
                endDate = new Date(availableEndDate);
                endDate.setDate(endDate.getDate() + 1);
            }

            return {
                start: startDate < new Date() ? new Date() : startDate,
                end: endDate
            };
        },
        datesSet: function(info) {
            loadAvailability(info.start, info.end);
        },
        dateClick: function(info) {
            handleDateClick(info.date);
        },
        dayCellClassNames: function(arg) {
            return getDayCellClass(arg.date);
        },
        dayCellDidMount: function(arg) {
            const cellClass = getDayCellClass(arg.date);
            if (cellClass.includes('booked')) {
                arg.el.title = 'This date is already booked';
            } else if (cellClass.includes('past')) {
                arg.el.title = 'Past date';
            } else if (cellClass.includes('available')) {
                arg.el.title = 'Click to select this date';
                arg.el.style.cursor = 'pointer';
            }
        }
    });

    calendar.render();

    // Load availability data
    async function loadAvailability(startDate, endDate) {
        try {
            const month = new Date(startDate.getFullYear(), startDate.getMonth(), 15);
            const response = await fetch(`/api/plans/${planId}/availability?month=${month.toISOString()}`);
            const data = await response.json();
            bookedDates = data.booked_dates || [];
            calendar.render();
        } catch (error) {
            console.error('Error loading availability:', error);
        }
    }

    // Get CSS class for day cell
    function getDayCellClass(date) {
        const dateStr = formatDateToYMD(date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        date.setHours(0, 0, 0, 0);

        if (date < today) {
            return 'fc-day-past';
        }

        if (availabilityType === 'date_range' && availableStartDate && availableEndDate) {
            if (dateStr < availableStartDate || dateStr > availableEndDate) {
                return 'fc-day-unavailable';
            }
        }

        if (isDateBooked(dateStr)) {
            return 'fc-day-booked';
        }

        if (selectedStartDate && selectedEndDate) {
            const selectedStart = new Date(selectedStartDate);
            const selectedEnd = new Date(selectedEndDate);
            if (date >= selectedStart && date <= selectedEnd) {
                return 'fc-day-selected';
            }
        }

        return 'fc-day-available';
    }

    // Check if date is booked
    function isDateBooked(dateStr) {
        for (let booking of bookedDates) {
            const bookingStart = new Date(booking.start);
            const bookingEnd = new Date(booking.end);
            const checkDate = new Date(dateStr);
            bookingEnd.setDate(bookingEnd.getDate() - 1);
            if (checkDate >= bookingStart && checkDate <= bookingEnd) {
                return true;
            }
        }
        return false;
    }

    // Handle date click
    async function handleDateClick(date) {
        const dateStr = formatDateToYMD(date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        date.setHours(0, 0, 0, 0);

        if (date < today) {
            showMessage('error', 'Cannot select past dates.');
            return;
        }

        if (availabilityType === 'date_range' && availableStartDate && availableEndDate) {
            if (dateStr < availableStartDate || dateStr > availableEndDate) {
                showMessage('error', 'This date is outside the available season.');
                return;
            }
        }

        if (isDateBooked(dateStr)) {
            showMessage('error', 'This date is already booked.');
            return;
        }

        await checkAvailability(dateStr);
    }

    // Check availability
    async function checkAvailability(startDate) {
        bookNowBtn.disabled = true;
        bookBtnText.innerHTML = `
            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Checking availability...
        `;
        availabilityResult.classList.remove('hidden');
        availabilityMessage.className = 'p-4 rounded-xl bg-slate-100';
        availabilityMessage.innerHTML = '<p class="text-slate-600 flex items-center gap-2"><svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Checking availability...</p>';

        try {
            const response = await fetch(`/api/plans/${planId}/check-dates`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ start_date: startDate })
            });

            const data = await response.json();

            if (data.available) {
                selectedStartDate = data.start_date;
                selectedEndDate = data.end_date;
                calendar.render();

                showMessage('success', data.message);
                selectedDates.innerHTML = `
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <strong>Selected:</strong> ${formatDate(data.start_date)} - ${formatDate(data.end_date)}
                    </div>
                `;

                // Enable and style button
                bookNowBtn.disabled = false;
                bookNowBtn.className = 'w-full py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-lg shadow-amber-500/30 hover:shadow-amber-500/40 hover:-translate-y-0.5';
                bookBtnText.innerHTML = `
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Book This Tour
                `;

                availabilityResult.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                bookNowBtn.onclick = function() {
                    @auth
                        @if(auth()->user() && auth()->user()->isTourist())
                            window.location.href = `/tourist/bookings/create?plan_id={{ $plan->id }}&start_date=${data.start_date}`;
                        @else
                            alert('Only tourists can book tours.');
                        @endif
                    @else
                        window.location.href = `{{ route('login') }}?redirect={{ urlencode(route('tour-packages.show', $plan)) }}`;
                    @endauth
                };
            } else {
                selectedStartDate = null;
                selectedEndDate = null;
                calendar.render();

                showMessage('error', data.message);
                selectedDates.innerHTML = '';
                bookNowBtn.disabled = true;
                bookNowBtn.className = 'w-full py-4 bg-slate-200 text-slate-400 font-semibold rounded-xl transition-all duration-300 disabled:cursor-not-allowed';
                bookBtnText.innerHTML = `
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Select available dates
                `;
            }
        } catch (error) {
            console.error('Error checking availability:', error);
            showMessage('error', 'Error checking availability. Please try again.');
            bookNowBtn.disabled = true;
            bookNowBtn.className = 'w-full py-4 bg-slate-200 text-slate-400 font-semibold rounded-xl transition-all duration-300 disabled:cursor-not-allowed';
            bookBtnText.innerHTML = `
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Error - try again
            `;
        }
    }

    // Show message helper
    function showMessage(type, message) {
        if (type === 'success') {
            availabilityMessage.className = 'p-4 rounded-xl bg-green-50 border border-green-200';
            availabilityMessage.innerHTML = `
                <p class="text-green-800 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ${message}
                </p>
            `;
        } else {
            availabilityMessage.className = 'p-4 rounded-xl bg-red-50 border border-red-200';
            availabilityMessage.innerHTML = `
                <p class="text-red-800 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ${message}
                </p>
            `;
        }
        availabilityResult.classList.remove('hidden');
    }

    // Format date to YYYY-MM-DD
    function formatDateToYMD(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Format date for display
    function formatDate(dateString) {
        const date = new Date(dateString + 'T00:00:00');
        return date.toLocaleDateString('en-US', {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
});
</script>

<style>
/* Calendar Container */
#availability-calendar {
    font-family: 'DM Sans', system-ui, sans-serif;
}

/* Calendar Header */
.fc .fc-toolbar {
    margin-bottom: 1.25rem;
    gap: 0.75rem;
}

.fc .fc-toolbar-title {
    font-family: 'Outfit', system-ui, sans-serif;
    font-size: 1.125rem !important;
    font-weight: 600 !important;
    color: #1e293b;
}

/* Navigation Buttons */
.fc .fc-button-primary {
    background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%) !important;
    border: none !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    padding: 0.5rem 1rem !important;
    border-radius: 0.75rem !important;
    box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3) !important;
    transition: all 0.2s !important;
}

.fc .fc-button-primary:hover {
    background: linear-gradient(135deg, #d97706 0%, #c2410c 100%) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 10px -1px rgba(245, 158, 11, 0.4) !important;
}

.fc .fc-button-primary:disabled {
    background: #e2e8f0 !important;
    box-shadow: none !important;
    color: #94a3b8 !important;
}

.fc .fc-button-primary:focus {
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.3) !important;
}

.fc .fc-prev-button,
.fc .fc-next-button {
    padding: 0.5rem !important;
    min-width: 2.5rem !important;
}

.fc .fc-today-button {
    background: #f1f5f9 !important;
    color: #475569 !important;
    box-shadow: none !important;
}

.fc .fc-today-button:hover {
    background: #e2e8f0 !important;
    transform: none !important;
}

/* Day Headers */
.fc .fc-col-header {
    background: linear-gradient(to bottom, #fefce8, #fef9c3);
    border-radius: 0.75rem 0.75rem 0 0;
    overflow: hidden;
}

.fc .fc-col-header-cell {
    padding: 0.75rem 0.5rem !important;
    font-weight: 600 !important;
    color: #92400e !important;
    font-size: 0.75rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    border: none !important;
}

/* Day Grid */
.fc .fc-daygrid-body {
    border: 1px solid #fde68a;
    border-radius: 0 0 0.75rem 0.75rem;
    overflow: hidden;
}

.fc .fc-daygrid-day {
    border-color: #fef3c7 !important;
}

.fc .fc-daygrid-day-frame {
    min-height: 52px !important;
    padding: 4px !important;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fc .fc-daygrid-day-number {
    padding: 0 !important;
    font-weight: 500 !important;
    font-size: 0.875rem !important;
}

/* Past dates */
.fc-day-past {
    background-color: #f8fafc !important;
}

.fc-day-past .fc-daygrid-day-number {
    color: #cbd5e1 !important;
}

/* Unavailable dates */
.fc-day-unavailable {
    background-color: #f1f5f9 !important;
}

.fc-day-unavailable .fc-daygrid-day-number {
    color: #94a3b8 !important;
}

/* Booked dates */
.fc-day-booked {
    background: linear-gradient(135deg, #fef2f2, #fee2e2) !important;
    position: relative;
}

.fc-day-booked::after {
    content: '';
    position: absolute;
    inset: 3px;
    border: 2px solid #f87171;
    border-radius: 0.5rem;
    pointer-events: none;
}

.fc-day-booked .fc-daygrid-day-number {
    color: #dc2626 !important;
    font-weight: 600 !important;
}

/* Available dates */
.fc-day-available {
    background: linear-gradient(135deg, #fefce8, #fef9c3) !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    position: relative;
}

.fc-day-available::after {
    content: '';
    position: absolute;
    inset: 3px;
    border: 2px solid #f59e0b;
    border-radius: 0.5rem;
    pointer-events: none;
    transition: all 0.2s ease;
}

.fc-day-available:hover {
    background: linear-gradient(135deg, #fef3c7, #fde68a) !important;
    transform: scale(1.02);
    z-index: 10;
    box-shadow: 0 8px 16px -4px rgba(245, 158, 11, 0.3);
}

.fc-day-available:hover::after {
    border-color: #d97706;
    border-width: 3px;
}

.fc-day-available .fc-daygrid-day-number {
    color: #92400e !important;
    font-weight: 600 !important;
}

/* Selected dates */
.fc-day-selected {
    background: linear-gradient(135deg, #f59e0b, #ea580c) !important;
    position: relative;
}

.fc-day-selected::after {
    content: '';
    position: absolute;
    inset: 3px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-radius: 0.5rem;
    pointer-events: none;
}

.fc-day-selected .fc-daygrid-day-number {
    color: white !important;
    font-weight: 700 !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
}

/* Today's date */
.fc .fc-day-today:not(.fc-day-past) {
    background: linear-gradient(135deg, #fffbeb, #fef3c7) !important;
}

.fc .fc-day-today:not(.fc-day-past)::before {
    content: '';
    position: absolute;
    top: 4px;
    right: 4px;
    width: 6px;
    height: 6px;
    background: #f59e0b;
    border-radius: 50%;
}

.fc .fc-day-today.fc-day-past {
    background-color: #f8fafc !important;
}

/* Scrollbar for calendar */
.fc-scroller::-webkit-scrollbar {
    width: 6px;
}

.fc-scroller::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.fc-scroller::-webkit-scrollbar-thumb {
    background: #f59e0b;
    border-radius: 3px;
}

/* Mobile responsiveness */
@media (max-width: 640px) {
    .fc .fc-toolbar {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
    }

    .fc .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
    }

    .fc .fc-toolbar-chunk:first-child {
        order: 2;
    }

    .fc .fc-toolbar-chunk:nth-child(2) {
        order: 1;
    }

    .fc .fc-daygrid-day-frame {
        min-height: 44px !important;
    }

    .fc .fc-col-header-cell {
        padding: 0.5rem 0.25rem !important;
        font-size: 0.65rem !important;
    }

    .fc .fc-daygrid-day-number {
        font-size: 0.75rem !important;
    }
}

/* Animation for selection */
@keyframes pulse-border {
    0%, 100% { border-color: #f59e0b; }
    50% { border-color: #fbbf24; }
}

.fc-day-selected::after {
    animation: pulse-border 2s ease-in-out infinite;
}
</style>
