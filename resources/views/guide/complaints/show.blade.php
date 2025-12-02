@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-green-700 font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <p class="text-red-700 font-semibold">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <a href="{{ route('guide.complaints.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-6">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Complaints
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Complaint Header -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $complaint->subject }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Complaint #{{ $complaint->complaint_number }}</p>
                    </div>
                    <div class="text-right">
                        @if($complaint->status === 'open')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Open
                            </span>
                        @elseif($complaint->status === 'under_review')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                Under Review
                            </span>
                        @elseif($complaint->status === 'resolved')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Resolved
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                Closed
                            </span>
                        @endif
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Complaint Description</h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $complaint->description }}</p>
                </div>

                @if($complaint->evidence_files && count($complaint->evidence_files) > 0)
                    <div class="border-t pt-4 mt-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Evidence Files</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($complaint->evidence_files as $file)
                                <a href="{{ Storage::url($file) }}" target="_blank"
                                   class="flex items-center p-3 border rounded-lg hover:bg-gray-50">
                                    <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm text-blue-600">{{ basename($file) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($complaint->resolution_summary)
                    <div class="border-t pt-4 mt-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Resolution Summary</h3>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-gray-700">{{ $complaint->resolution_summary }}</p>
                            @if($complaint->resolved_at)
                                <p class="text-sm text-gray-500 mt-2">Resolved on {{ $complaint->resolved_at->format('M j, Y g:i A') }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Communication Thread -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Communication Thread</h2>

                @if($complaint->responses->count() > 0)
                    <div class="space-y-4">
                        @foreach($complaint->responses as $response)
                            <div class="border rounded-lg p-4 {{ $response->responder_type === 'admin' ? 'bg-blue-50 border-blue-200' : 'bg-gray-50' }}">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if($response->responder_type === 'admin')
                                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-semibold text-gray-900">{{ $response->getResponderName() }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($response->responder_type ?? 'admin') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs px-2 py-1 rounded-full {{ $response->responder_type === 'admin' ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-800' }}">
                                            {{ $response->getResponseTypeLabel() }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">{{ $response->created_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>

                                <div class="ml-13">
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $response->response_text }}</p>

                                    @if($response->attachments && count($response->attachments) > 0)
                                        <div class="mt-3 space-y-2">
                                            @foreach($response->attachments as $attachment)
                                                <a href="{{ Storage::url($attachment) }}" target="_blank"
                                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
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
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No responses yet. Our support team will respond soon.</p>
                    </div>
                @endif
            </div>

            <!-- Add Response Form -->
            @if(!in_array($complaint->status, ['closed']))
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Your Response</h3>
                    <form action="{{ route('guide.complaints.addResponse', $complaint) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="response_text" class="block text-sm font-medium text-gray-700 mb-2">Your Message</label>
                            <textarea name="response_text" id="response_text" required rows="4"
                                      placeholder="Type your response here..."
                                      class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            @error('response_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Attachments (Optional)</label>
                            <input type="file" name="attachments[]" id="attachments" multiple
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG, PDF, DOC up to 10MB each</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
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
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Complaint Status</h3>

                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            {{ str_replace('_', ' ', ucwords($complaint->complaint_type, '_')) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Priority</dt>
                        <dd class="mt-1">
                            @if($complaint->priority === 'urgent')
                                <span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded">Urgent</span>
                            @elseif($complaint->priority === 'high')
                                <span class="px-2 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded">High</span>
                            @elseif($complaint->priority === 'medium')
                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded">Medium</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded">Low</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-gray-500">Filed On</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">
                            {{ $complaint->created_at->format('M j, Y g:i A') }}
                        </dd>
                    </div>

                    @if($complaint->assignedAdmin)
                        <div>
                            <dt class="text-sm text-gray-500">Assigned To</dt>
                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                {{ $complaint->assignedAdmin->name }}
                            </dd>
                        </div>
                    @endif

                    <div>
                        <dt class="text-sm text-gray-500">Your Role</dt>
                        <dd class="mt-1">
                            @if($isComplainant)
                                <span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded">Complainant</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded">Defendant</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Related Booking -->
            @if($complaint->booking)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Related Booking</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">Booking Number</p>
                        <p class="font-medium text-blue-600">{{ $complaint->booking->booking_number }}</p>

                        @if($complaint->booking->guidePlan)
                            <p class="text-sm text-gray-600 mt-3">Tour</p>
                            <p class="font-medium">{{ $complaint->booking->guidePlan->title }}</p>
                        @endif

                        <a href="{{ route('guide.bookings.show', $complaint->booking) }}"
                           class="inline-block mt-3 text-sm text-blue-600 hover:text-blue-800">
                            View Booking Details →
                        </a>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            @if($isComplainant && $complaint->status === 'open')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Actions</h3>
                    <form action="{{ route('guide.complaints.withdraw', $complaint) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to withdraw this complaint? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-medium transition-colors">
                            Withdraw Complaint
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
