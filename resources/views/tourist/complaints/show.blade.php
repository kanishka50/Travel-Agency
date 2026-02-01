@extends('layouts.dashboard')

@section('page-title', 'Complaint Details')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex items-center gap-3">
        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <p class="text-emerald-700 font-medium">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-center gap-3">
        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <p class="text-red-700 font-medium">{{ session('error') }}</p>
    </div>
@endif

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('tourist.complaints.index') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium group">
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Complaints
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Complaint Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $complaint->subject }}</h1>
                    <p class="text-sm text-amber-600 font-semibold mt-1">Complaint #{{ $complaint->complaint_number }}</p>
                </div>
                <div class="text-right">
                    @if($complaint->status === 'open')
                        <span class="px-3 py-1.5 inline-flex text-sm font-semibold rounded-xl bg-orange-100 text-orange-700 border border-orange-200">
                            Open
                        </span>
                    @elseif($complaint->status === 'under_review')
                        <span class="px-3 py-1.5 inline-flex text-sm font-semibold rounded-xl bg-cyan-100 text-cyan-700 border border-cyan-200">
                            Under Review
                        </span>
                    @elseif($complaint->status === 'resolved')
                        <span class="px-3 py-1.5 inline-flex text-sm font-semibold rounded-xl bg-emerald-100 text-emerald-700 border border-emerald-200">
                            Resolved
                        </span>
                    @else
                        <span class="px-3 py-1.5 inline-flex text-sm font-semibold rounded-xl bg-slate-100 text-slate-700 border border-slate-200">
                            Closed
                        </span>
                    @endif
                </div>
            </div>

            <div class="border-t border-slate-200 pt-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900">Complaint Description</h3>
                </div>
                <p class="text-slate-700 whitespace-pre-wrap bg-slate-50 rounded-xl p-4">{{ $complaint->description }}</p>
            </div>

            @if($complaint->evidence_files && count($complaint->evidence_files) > 0)
                <div class="border-t border-slate-200 pt-4 mt-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900">Evidence Files</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($complaint->evidence_files as $file)
                            <a href="{{ Storage::url($file) }}" target="_blank"
                               class="flex items-center p-3 border border-slate-200 rounded-xl hover:bg-amber-50 hover:border-amber-200 transition-all group">
                                <svg class="w-6 h-6 text-slate-400 group-hover:text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm text-amber-600 font-medium truncate">{{ basename($file) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($complaint->resolution_summary)
                <div class="border-t border-slate-200 pt-4 mt-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900">Resolution Summary</h3>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                        <p class="text-slate-700">{{ $complaint->resolution_summary }}</p>
                        @if($complaint->resolved_at)
                            <p class="text-sm text-emerald-600 mt-2 font-medium">Resolved on {{ $complaint->resolved_at->format('M j, Y g:i A') }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Communication Thread -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900">Communication Thread</h2>
            </div>

            @if($complaint->responses->count() > 0)
                <div class="space-y-4">
                    @foreach($complaint->responses as $response)
                        <div class="border rounded-xl p-4 {{ $response->responder_type === 'admin' ? 'bg-cyan-50 border-cyan-200' : 'bg-slate-50 border-slate-200' }}">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($response->responder_type === 'admin')
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-cyan-500/25">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/25">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-semibold text-slate-900">{{ $response->getResponderName() }}</p>
                                        <p class="text-xs text-slate-500">{{ ucfirst($response->responder_type ?? 'admin') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs px-2.5 py-1 rounded-lg font-semibold {{ $response->responder_type === 'admin' ? 'bg-cyan-200 text-cyan-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $response->getResponseTypeLabel() }}
                                    </span>
                                    <p class="text-xs text-slate-500 mt-1">{{ $response->created_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>

                            <div class="ml-13">
                                <p class="text-slate-700 whitespace-pre-wrap">{{ $response->response_text }}</p>

                                @if($response->attachments && count($response->attachments) > 0)
                                    <div class="mt-3 space-y-2">
                                        @foreach($response->attachments as $attachment)
                                            <a href="{{ Storage::url($attachment) }}" target="_blank"
                                               class="inline-flex items-center text-sm text-amber-600 hover:text-amber-700 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                </svg>
                                                {{ basename($attachment) }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-slate-600">No responses yet. Our support team will respond soon.</p>
                </div>
            @endif
        </div>

        <!-- Add Response Form -->
        @if(!in_array($complaint->status, ['closed']))
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Add Your Response</h3>
                </div>

                <form action="{{ route('tourist.complaints.addResponse', $complaint) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="response_text" class="block text-sm font-medium text-slate-700 mb-2">Your Message</label>
                        <textarea name="response_text" id="response_text" required rows="4"
                                  placeholder="Type your response here..."
                                  class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"></textarea>
                        @error('response_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="attachments" class="block text-sm font-medium text-slate-700 mb-2">Attachments (Optional)</label>
                        <input type="file" name="attachments[]" id="attachments" multiple
                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                               class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all">
                        <p class="mt-1 text-xs text-slate-500">JPG, PNG, PDF, DOC up to 10MB each</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
                            Send Response
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Complaint Status</h3>
            </div>

            <dl class="space-y-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm text-slate-500">Type</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">
                        {{ str_replace('_', ' ', ucwords($complaint->complaint_type, '_')) }}
                    </dd>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm text-slate-500">Priority</dt>
                    <dd class="mt-1">
                        @if($complaint->priority === 'urgent')
                            <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-lg">Urgent</span>
                        @elseif($complaint->priority === 'high')
                            <span class="px-3 py-1 text-xs font-semibold text-orange-700 bg-orange-100 rounded-lg">High</span>
                        @elseif($complaint->priority === 'medium')
                            <span class="px-3 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-lg">Medium</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold text-slate-700 bg-slate-100 rounded-lg">Low</span>
                        @endif
                    </dd>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm text-slate-500">Filed On</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $complaint->created_at->format('M j, Y g:i A') }}
                    </dd>
                </div>

                @if($complaint->assignedAdmin)
                    <div class="bg-slate-50 rounded-xl p-4">
                        <dt class="text-sm text-slate-500">Assigned To</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $complaint->assignedAdmin->name }}
                        </dd>
                    </div>
                @endif

                <div class="bg-slate-50 rounded-xl p-4">
                    <dt class="text-sm text-slate-500">Your Role</dt>
                    <dd class="mt-1">
                        @if($isComplainant)
                            <span class="px-3 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-lg">Complainant</span>
                        @else
                            <span class="px-3 py-1 text-xs font-semibold text-orange-700 bg-orange-100 rounded-lg">Defendant</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Related Booking -->
        @if($complaint->booking)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Related Booking</h3>
                </div>
                <div class="space-y-3">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm text-slate-500">Booking Number</p>
                        <p class="font-semibold text-amber-600 mt-1">{{ $complaint->booking->booking_number }}</p>
                    </div>

                    @if($complaint->booking->guidePlan)
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-sm text-slate-500">Tour</p>
                            <p class="font-semibold text-slate-900 mt-1">{{ $complaint->booking->guidePlan->title }}</p>
                        </div>
                    @endif

                    <a href="{{ route('tourist.bookings.show', $complaint->booking) }}"
                       class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-semibold text-sm">
                        View Booking Details
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endif

        <!-- Actions -->
        @if($isComplainant && $complaint->status === 'open')
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-100 to-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Actions</h3>
                </div>
                <form action="{{ route('tourist.complaints.withdraw', $complaint) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to withdraw this complaint? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-xl font-semibold transition-colors">
                        Withdraw Complaint
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
