@extends('layouts.dashboard')

@section('page-title', 'My Complaints')

@section('content')
<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">My Complaints</h1>
        <p class="text-slate-500 mt-1">Track and manage your complaints</p>
    </div>
    <a href="{{ route('guide.complaints.create') }}"
       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        File New Complaint
    </a>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Filed by Me</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['total_filed'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Against Me</p>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['against_me'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Open</p>
                <p class="text-3xl font-bold text-cyan-600 mt-2">{{ $stats['open'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-500 text-sm font-medium">Resolved</p>
                <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $stats['resolved'] }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Complaints Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    @if($complaints->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Complaint #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Filed Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($complaints as $complaint)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-amber-600">{{ $complaint->complaint_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-900">{{ Str::limit($complaint->subject, 40) }}</div>
                                @if($complaint->booking)
                                    <div class="text-xs text-slate-500">Booking: {{ $complaint->booking->booking_number }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium">
                                    {{ str_replace('_', ' ', ucwords($complaint->complaint_type, '_')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($complaint->filed_by == Auth::id())
                                    <span class="text-xs px-2.5 py-1 rounded-lg bg-amber-100 text-amber-700 font-semibold">Complainant</span>
                                @else
                                    <span class="text-xs px-2.5 py-1 rounded-lg bg-red-100 text-red-700 font-semibold">Defendant</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($complaint->status === 'open')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-amber-100 text-amber-700">
                                        Open
                                    </span>
                                @elseif($complaint->status === 'under_review')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-cyan-100 text-cyan-700">
                                        Under Review
                                    </span>
                                @elseif($complaint->status === 'resolved')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-emerald-100 text-emerald-700">
                                        Resolved
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-lg bg-slate-100 text-slate-700">
                                        Closed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($complaint->priority === 'urgent')
                                    <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-lg">Urgent</span>
                                @elseif($complaint->priority === 'high')
                                    <span class="px-2 py-1 text-xs font-semibold text-orange-700 bg-orange-100 rounded-lg">High</span>
                                @elseif($complaint->priority === 'medium')
                                    <span class="px-2 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-lg">Medium</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold text-slate-700 bg-slate-100 rounded-lg">Low</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $complaint->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('guide.complaints.show', $complaint) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $complaints->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">No complaints</h3>
            <p class="text-slate-600 max-w-md mx-auto">You haven't filed any complaints and no one has complained about you.</p>
        </div>
    @endif
</div>
@endsection
