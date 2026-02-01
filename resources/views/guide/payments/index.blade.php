@extends('layouts.dashboard')

@section('page-title', 'My Payments')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">My Payments</h1>
    <p class="text-slate-500 mt-1">Track your earnings and payment history</p>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Earnings -->
    <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl shadow-lg shadow-amber-500/25 p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium text-amber-100">Total Earnings</h3>
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold">${{ number_format($totalEarnings, 2) }}</p>
        <p class="text-sm text-amber-100 mt-1">Total owed from all bookings</p>
    </div>

    <!-- Total Received -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg shadow-emerald-500/25 p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium text-emerald-100">Total Received</h3>
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold">${{ number_format($totalReceived, 2) }}</p>
        <p class="text-sm text-emerald-100 mt-1">{{ $totalEarnings > 0 ? number_format(($totalReceived / $totalEarnings) * 100, 1) : 0 }}% of total earnings</p>
    </div>

    <!-- Total Pending -->
    <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-lg shadow-cyan-500/25 p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium text-cyan-100">Total Pending</h3>
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold">${{ number_format($totalPending, 2) }}</p>
        <p class="text-sm text-cyan-100 mt-1">Awaiting payment from platform</p>
    </div>

    <!-- Payment Status Summary -->
    <div class="bg-gradient-to-br from-slate-600 to-slate-700 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-medium text-slate-200">Payment Status</h3>
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
        <div class="space-y-1">
            <p class="text-sm"><span class="font-bold">{{ $fullyPaidCount }}</span> Fully Paid</p>
            <p class="text-sm"><span class="font-bold">{{ $partiallyPaidCount }}</span> Partially Paid</p>
            <p class="text-sm"><span class="font-bold">{{ $unpaidCount }}</span> Unpaid</p>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200">
        <h2 class="text-xl font-bold text-slate-900">Payment History</h2>
    </div>

    @if($payments->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Booking Details
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Tourist
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Total Owed
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Received
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Remaining
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($payments as $payment)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900">
                                            {{ $payment->booking->booking_number }}
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            {{ $payment->booking->guidePlan ? $payment->booking->guidePlan->title : 'Custom Booking' }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            {{ $payment->created_at->format('M j, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-900">{{ $payment->booking->tourist->full_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-amber-600">
                                    ${{ number_format($payment->guide_payout, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-emerald-600">
                                    ${{ number_format($payment->amount_paid, 2) }}
                                </div>
                                @if($payment->amount_paid > 0)
                                    <div class="text-xs text-slate-500">
                                        {{ $payment->payment_progress }}% paid
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold {{ $payment->amount_remaining > 0 ? 'text-cyan-600' : 'text-slate-400' }}">
                                    ${{ number_format($payment->amount_remaining, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->isFullyPaid())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Fully Paid
                                    </span>
                                @elseif($payment->amount_paid > 0)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Partial
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Unpaid
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('guide.payments.show', $payment) }}"
                                   class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $payments->links() }}
        </div>
    @else
        <div class="px-6 py-12 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">No payments yet</h3>
            <p class="text-slate-600 max-w-md mx-auto">You haven't received any bookings yet.</p>
        </div>
    @endif
</div>
@endsection
