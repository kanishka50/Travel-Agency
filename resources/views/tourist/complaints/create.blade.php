@extends('layouts.dashboard')

@section('page-title', 'File a Complaint')

@section('content')
<!-- Header -->
<div class="mb-8">
    <a href="{{ route('tourist.complaints.index') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium mb-4 group">
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Complaints
    </a>
    <h1 class="text-2xl font-bold text-slate-900">File a Complaint</h1>
    <p class="text-slate-600 mt-2">Please provide detailed information about your complaint</p>
</div>

<form action="{{ route('tourist.complaints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Booking Selection -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">Related Booking (Optional)</h2>
                <p class="text-sm text-slate-500">If this complaint is related to a specific booking, please select it below.</p>
            </div>
        </div>

        <div>
            <label for="booking_id" class="block text-sm font-medium text-slate-700 mb-2">Select Booking</label>
            <select name="booking_id" id="booking_id"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                <option value="">No specific booking</option>
                @foreach($bookings as $booking)
                    <option value="{{ $booking->id }}"
                            data-guide-id="{{ $booking->guide->user_id }}"
                            {{ $selectedBooking && $selectedBooking->id == $booking->id ? 'selected' : '' }}>
                        {{ $booking->booking_number }} -
                        {{ $booking->guidePlan ? $booking->guidePlan->title : 'Custom Booking' }} -
                        {{ $booking->start_date->format('M j, Y') }}
                        ({{ ucfirst($booking->status) }})
                    </option>
                @endforeach
            </select>
            @error('booking_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Complaint Details -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-slate-900">Complaint Details</h2>
        </div>

        <div class="space-y-6">
            <!-- Guide Selection (shown when no booking selected) -->
            <div id="guide-selection-div" class="mb-4" style="display: {{ $selectedBooking ? 'none' : 'block' }};">
                <label for="guide_selection" class="block text-sm font-medium text-slate-700 mb-2">
                    Select Guide *
                    <span class="text-slate-500 font-normal">(Required if no booking selected)</span>
                </label>
                <select name="guide_selection" id="guide_selection"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    <option value="">Select a guide</option>
                    @foreach($guides as $guide)
                        <option value="{{ $guide['id'] }}" {{ old('guide_selection') == $guide['id'] ? 'selected' : '' }}>
                            {{ $guide['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('against_user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Against User (Hidden, auto-filled by booking or guide selection) -->
            <input type="hidden" name="against_user_id" id="against_user_id" value="{{ $selectedBooking ? $selectedBooking->guide->user_id : old('against_user_id') }}">

            <!-- Complaint Type -->
            <div>
                <label for="complaint_type" class="block text-sm font-medium text-slate-700 mb-2">Complaint Type *</label>
                <select name="complaint_type" id="complaint_type" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    <option value="">Select complaint type</option>
                    <option value="service_quality" {{ old('complaint_type') == 'service_quality' ? 'selected' : '' }}>Service Quality</option>
                    <option value="safety_concern" {{ old('complaint_type') == 'safety_concern' ? 'selected' : '' }}>Safety Concern</option>
                    <option value="unprofessional_behavior" {{ old('complaint_type') == 'unprofessional_behavior' ? 'selected' : '' }}>Unprofessional Behavior</option>
                    <option value="payment_issue" {{ old('complaint_type') == 'payment_issue' ? 'selected' : '' }}>Payment Issue</option>
                    <option value="cancellation_dispute" {{ old('complaint_type') == 'cancellation_dispute' ? 'selected' : '' }}>Cancellation Dispute</option>
                    <option value="other" {{ old('complaint_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('complaint_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject -->
            <div>
                <label for="subject" class="block text-sm font-medium text-slate-700 mb-2">Subject *</label>
                <input type="text" name="subject" id="subject" required
                       value="{{ old('subject') }}"
                       placeholder="Brief summary of your complaint"
                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                    Detailed Description *
                    <span class="text-slate-500 font-normal">(minimum 50 characters)</span>
                </label>
                <textarea name="description" id="description" required rows="8"
                          placeholder="Please provide a detailed description of your complaint. Include dates, times, and any relevant details."
                          class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">{{ old('description') }}</textarea>
                <p class="mt-1 text-sm text-slate-500">
                    Character count: <span id="char-count" class="font-semibold text-amber-600">0</span> / 50 minimum
                </p>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Evidence Files -->
            <div>
                <label for="evidence_files" class="block text-sm font-medium text-slate-700 mb-2">
                    Supporting Evidence (Optional)
                </label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-amber-400 transition-colors cursor-pointer" onclick="document.getElementById('evidence_files').click()">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <input type="file" name="evidence_files[]" id="evidence_files" multiple
                           accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                           class="hidden">
                    <label for="evidence_files" class="block text-sm font-semibold text-amber-600 cursor-pointer hover:text-amber-700">
                        Click to upload files
                    </label>
                    <p class="text-xs text-slate-500 mt-1">
                        JPG, PNG, PDF, DOC up to 10MB each
                    </p>
                </div>
                <div id="file-list" class="mt-3 space-y-2"></div>
                @error('evidence_files.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Important Notice -->
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-amber-900">Important Notice</h3>
                <ul class="mt-2 text-sm text-amber-800 space-y-1 list-disc list-inside">
                    <li>Please provide accurate and honest information</li>
                    <li>False complaints may result in account suspension</li>
                    <li>Our support team will review your complaint within 24-48 hours</li>
                    <li>You can add additional information through the complaint thread</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('tourist.complaints.index') }}"
           class="px-6 py-3 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 font-semibold transition-colors">
            Cancel
        </a>
        <button type="submit"
                class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
            Submit Complaint
        </button>
    </div>
</form>

<script>
// Character count for description
const description = document.getElementById('description');
const charCount = document.getElementById('char-count');

description.addEventListener('input', function() {
    charCount.textContent = this.value.length;
});

// Auto-fill against_user_id when booking is selected
const bookingSelect = document.getElementById('booking_id');
const againstUserInput = document.getElementById('against_user_id');
const guideSelectionDiv = document.getElementById('guide-selection-div');
const guideSelect = document.getElementById('guide_selection');

bookingSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const guideId = selectedOption.getAttribute('data-guide-id');

    if (guideId) {
        // Booking selected - hide guide dropdown and set against_user_id
        againstUserInput.value = guideId;
        guideSelectionDiv.style.display = 'none';
        guideSelect.value = '';
    } else {
        // No booking - show guide dropdown
        againstUserInput.value = '';
        guideSelectionDiv.style.display = 'block';
    }
});

// Auto-fill against_user_id when guide is selected manually
guideSelect.addEventListener('change', function() {
    againstUserInput.value = this.value;
});

// File upload preview
const fileInput = document.getElementById('evidence_files');
const fileList = document.getElementById('file-list');

fileInput.addEventListener('change', function() {
    fileList.innerHTML = '';
    if (this.files.length > 0) {
        Array.from(this.files).forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between bg-slate-50 p-3 rounded-xl border border-slate-200';
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm text-slate-700 font-medium">${file.name}</span>
                </div>
                <span class="text-xs text-slate-500">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
            `;
            fileList.appendChild(fileItem);
        });
    }
});
</script>
@endsection
