{{-- Availability Calendar --}}
<div class="bg-white rounded-xl shadow-sm border p-6">
    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
        <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Check Availability
    </h3>

    <!-- Calendar Container -->
    <div id="availability-calendar" class="mb-4"></div>

    <!-- Tour Duration Notice -->
    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg">
        <p class="text-sm text-emerald-800">
            <strong>Tour Duration:</strong> {{ $plan->num_days }} day{{ $plan->num_days > 1 ? 's' : '' }} / {{ $plan->num_nights }} night{{ $plan->num_nights > 1 ? 's' : '' }}
        </p>
        <p class="text-xs text-emerald-600 mt-1">
            Click on an available date to select your tour start date
        </p>
    </div>

    <!-- Availability Result -->
    <div id="availability-result" class="hidden mb-4">
        <div id="availability-message" class="p-4 rounded-lg"></div>
        <div id="selected-dates" class="mt-2 text-sm text-gray-600"></div>
    </div>

    <!-- Book Now Button -->
    <button id="book-now-btn" class="w-full px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed" disabled>
        <span id="book-btn-text">Select dates to continue</span>
    </button>

    <!-- Calendar Legend -->
    <div class="mt-4 pt-4 border-t">
        <p class="text-xs font-medium text-gray-700 mb-2">Legend:</p>
        <div class="flex flex-wrap gap-3 text-xs">
            <div class="flex items-center">
                <div class="w-3 h-3 rounded bg-emerald-100 border-2 border-emerald-500 mr-1"></div>
                <span class="text-gray-600">Available</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded bg-red-100 border-2 border-red-500 mr-1"></div>
                <span class="text-gray-600">Booked</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded bg-gray-200 mr-1"></div>
                <span class="text-gray-600">Past/Unavailable</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 rounded bg-emerald-500 mr-1"></div>
                <span class="text-gray-600">Selected</span>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS & JS (Combined in one script) -->
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
        firstDay: 1, // Start week on Monday
        selectable: false,
        selectMirror: false,
        dayMaxEvents: false,
        validRange: function(nowDate) {
            // Set valid range based on availability type
            let startDate = new Date();
            let endDate = null;

            if (availabilityType === 'date_range' && availableStartDate && availableEndDate) {
                startDate = new Date(availableStartDate);
                endDate = new Date(availableEndDate);
                endDate.setDate(endDate.getDate() + 1); // Make it inclusive
            }

            return {
                start: startDate < new Date() ? new Date() : startDate,
                end: endDate
            };
        },
        datesSet: function(info) {
            // Load availability when month changes
            loadAvailability(info.start, info.end);
        },
        dateClick: function(info) {
            handleDateClick(info.date);
        },
        dayCellClassNames: function(arg) {
            return getDayCellClass(arg.date);
        },
        dayCellDidMount: function(arg) {
            // Add tooltip or additional styling if needed
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

    // Load availability data from API
    async function loadAvailability(startDate, endDate) {
        try {
            const month = new Date(startDate.getFullYear(), startDate.getMonth(), 15);
            const response = await fetch(`/api/plans/${planId}/availability?month=${month.toISOString()}`);
            const data = await response.json();

            bookedDates = data.booked_dates || [];

            // Refresh calendar rendering
            calendar.render();
        } catch (error) {
            console.error('Error loading availability:', error);
        }
    }

    // Get CSS class for day cell based on its status
    function getDayCellClass(date) {
        const dateStr = formatDateToYMD(date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        date.setHours(0, 0, 0, 0);

        // Check if date is in the past
        if (date < today) {
            return 'fc-day-past';
        }

        // Check if date is outside available range (for seasonal plans)
        if (availabilityType === 'date_range' && availableStartDate && availableEndDate) {
            if (dateStr < availableStartDate || dateStr > availableEndDate) {
                return 'fc-day-unavailable';
            }
        }

        // Check if date is within a booked range
        if (isDateBooked(dateStr)) {
            return 'fc-day-booked';
        }

        // Check if date is selected
        if (selectedStartDate && selectedEndDate) {
            const selectedStart = new Date(selectedStartDate);
            const selectedEnd = new Date(selectedEndDate);
            if (date >= selectedStart && date <= selectedEnd) {
                return 'fc-day-selected';
            }
        }

        // Date is available
        return 'fc-day-available';
    }

    // Check if a date is booked
    function isDateBooked(dateStr) {
        for (let booking of bookedDates) {
            const bookingStart = new Date(booking.start);
            const bookingEnd = new Date(booking.end);
            const checkDate = new Date(dateStr);

            // Adjust for FullCalendar's exclusive end date
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

        // Check if date is clickable
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

        // Check availability via API
        await checkAvailability(dateStr);
    }

    // Check availability for selected start date
    async function checkAvailability(startDate) {
        // Show loading state
        bookNowBtn.disabled = true;
        bookBtnText.textContent = 'Checking availability...';
        availabilityResult.classList.remove('hidden');
        availabilityMessage.className = 'p-4 rounded-lg bg-gray-100';
        availabilityMessage.innerHTML = '<p class="text-gray-600">Checking availability...</p>';

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
                // Dates are available
                selectedStartDate = data.start_date;
                selectedEndDate = data.end_date;

                // Update calendar to show selected dates
                calendar.render();

                showMessage('success', data.message);
                selectedDates.innerHTML = `
                    <strong>Selected dates:</strong> ${formatDate(data.start_date)} - ${formatDate(data.end_date)}
                `;
                bookNowBtn.disabled = false;
                bookBtnText.textContent = 'Book This Tour';

                // Scroll to result
                availabilityResult.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                // Update button to proceed to booking
                bookNowBtn.onclick = function() {
                    @auth
                        @if(auth()->user() && auth()->user()->isTourist())
                            // Redirect to booking page with plan_id and start_date as query params
                            window.location.href = `/tourist/bookings/create?plan_id={{ $plan->id }}&start_date=${data.start_date}`;
                        @else
                            // Not a tourist
                            alert('Only tourists can book tours.');
                        @endif
                    @else
                        // Redirect to login with return URL
                        window.location.href = `{{ route('login') }}?redirect={{ urlencode(route('tour-packages.show', $plan)) }}`;
                    @endauth
                };
            } else {
                // Dates are not available
                selectedStartDate = null;
                selectedEndDate = null;
                calendar.render();

                showMessage('error', data.message);
                selectedDates.innerHTML = '';
                bookNowBtn.disabled = true;
                bookBtnText.textContent = 'Select available dates';
            }
        } catch (error) {
            console.error('Error checking availability:', error);
            showMessage('error', 'Error checking availability. Please try again.');
            bookNowBtn.disabled = true;
            bookBtnText.textContent = 'Error - try again';
        }
    }

    // Show message helper
    function showMessage(type, message) {
        if (type === 'success') {
            availabilityMessage.className = 'p-4 rounded-lg bg-emerald-100 border border-emerald-300';
            availabilityMessage.innerHTML = `<p class="text-emerald-800 font-medium">${message}</p>`;
        } else {
            availabilityMessage.className = 'p-4 rounded-lg bg-red-100 border border-red-300';
            availabilityMessage.innerHTML = `<p class="text-red-800 font-medium">${message}</p>`;
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
/* Custom calendar styling */
#availability-calendar {
    font-size: 0.875rem;
}

/* Past dates */
.fc-day-past {
    background-color: #f3f4f6 !important;
    color: #9ca3af !important;
    cursor: not-allowed !important;
}

/* Unavailable dates (outside seasonal range) */
.fc-day-unavailable {
    background-color: #f3f4f6 !important;
    color: #d1d5db !important;
    cursor: not-allowed !important;
}

/* Booked dates */
.fc-day-booked {
    background-color: #fee2e2 !important;
    border: 2px solid #ef4444 !important;
    color: #991b1b !important;
    cursor: not-allowed !important;
}

/* Available dates */
.fc-day-available {
    background-color: #ecfdf5 !important;
    border: 2px solid #10b981 !important;
    cursor: pointer !important;
    transition: all 0.2s;
}

.fc-day-available:hover {
    background-color: #d1fae5 !important;
    transform: scale(1.05);
    box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
}

/* Selected dates */
.fc-day-selected {
    background-color: #d1fae5 !important;
    border: 2px solid #059669 !important;
    color: #065f46 !important;
    font-weight: 600 !important;
}

/* Calendar header styling */
.fc .fc-toolbar-title {
    font-size: 1.125rem !important;
    font-weight: 600 !important;
    color: #1f2937;
}

.fc .fc-button {
    background-color: #10b981 !important;
    border-color: #10b981 !important;
    font-size: 0.875rem !important;
    padding: 0.375rem 0.75rem !important;
}

.fc .fc-button:hover {
    background-color: #059669 !important;
    border-color: #059669 !important;
}

.fc .fc-button:disabled {
    background-color: #9ca3af !important;
    border-color: #9ca3af !important;
}

/* Day headers */
.fc .fc-col-header-cell {
    background-color: #f9fafb;
    font-weight: 600;
    color: #374151;
    padding: 0.5rem;
}

/* Day cells */
.fc .fc-daygrid-day-frame {
    min-height: 50px;
    padding: 4px;
}

.fc .fc-daygrid-day-number {
    padding: 4px;
    font-weight: 500;
}

/* Today's date */
.fc .fc-day-today {
    background-color: #fffbeb !important;
}

/* Remove default today highlight if it's not available */
.fc .fc-day-today.fc-day-past {
    background-color: #f3f4f6 !important;
}

/* Mobile responsiveness */
@media (max-width: 640px) {
    .fc .fc-toolbar {
        flex-direction: column;
        gap: 0.5rem;
    }

    .fc .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
    }

    .fc .fc-daygrid-day-frame {
        min-height: 40px;
    }
}
</style>
