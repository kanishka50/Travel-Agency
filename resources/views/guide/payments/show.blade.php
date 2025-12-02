@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('guide.payments.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Payments
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">Payment Details</h1>
                        <p class="mt-2 text-blue-100">Booking #{{ $payment->booking->booking_number }}</p>
                    </div>
                    @if($payment->isFullyPaid())
                        <div class="flex items-center bg-green-500 px-4 py-2 rounded-lg">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-bold">Fully Paid</span>
                        </div>
                    @else
                        <div class="flex items-center bg-orange-500 px-4 py-2 rounded-lg">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-bold">{{ $payment->amount_paid > 0 ? 'Partially Paid' : 'Unpaid' }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking Information -->
            <div class="px-6 py-6 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Booking Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Tour Name</p>
                        <p class="text-base font-medium text-gray-900">{{ $payment->booking->guidePlan ? $payment->booking->guidePlan->title : 'Custom Booking' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tourist</p>
                        <p class="text-base font-medium text-gray-900">{{ $payment->booking->tourist->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Booking Date</p>
                        <p class="text-base font-medium text-gray-900">{{ $payment->booking->created_at->format('F j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Booking Status</p>
                        <p class="text-base font-medium text-gray-900">{{ ucfirst($payment->booking->status) }}</p>
                    </div>
                    @if($payment->booking->booking_type)
                        <div>
                            <p class="text-sm text-gray-500">Booking Type</p>
                            <p class="text-base font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->booking->booking_type)) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="px-6 py-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Payment Summary</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-blue-600 font-medium">Total Owed</p>
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-blue-700">${{ number_format($payment->guide_payout, 2) }}</p>
                        <p class="text-xs text-blue-600 mt-1">Your earnings for this booking</p>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-green-600 font-medium">Amount Received</p>
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-green-700">${{ number_format($payment->amount_paid, 2) }}</p>
                        <div class="flex items-center mt-1">
                            <div class="flex-1 bg-green-200 rounded-full h-2 mr-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $payment->payment_progress }}%"></div>
                            </div>
                            <span class="text-xs text-green-600 font-medium">{{ $payment->payment_progress }}%</span>
                        </div>
                    </div>

                    <div class="bg-orange-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm text-orange-600 font-medium">Amount Remaining</p>
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold {{ $payment->amount_remaining > 0 ? 'text-orange-700' : 'text-gray-400' }}">
                            ${{ number_format($payment->amount_remaining, 2) }}
                        </p>
                        <p class="text-xs text-orange-600 mt-1">{{ $payment->amount_remaining > 0 ? 'Awaiting payment' : 'All paid' }}</p>
                    </div>
                </div>

                <!-- Tourist Payment Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Tourist Payment Breakdown</h3>
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Tourist Paid</p>
                                <p class="font-bold text-gray-900">${{ number_format($payment->total_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Platform Fee (10%)</p>
                                <p class="font-bold text-gray-900">${{ number_format($payment->platform_fee, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Your Share (90%)</p>
                                <p class="font-bold text-green-600">${{ number_format($payment->guide_payout, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Transaction History -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Payment Transaction History</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $payment->transactions->count() }} {{ $payment->transactions->count() === 1 ? 'Transaction' : 'Transactions' }}
                    </span>
                </div>
            </div>

            @if($payment->transactions->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($payment->transactions as $transaction)
                        <div class="px-6 py-5 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full mr-4">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-lg font-bold text-gray-900">
                                                    ${{ number_format($transaction->amount, 2) }}
                                                </h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ ucwords(str_replace('_', ' ', $transaction->payment_method)) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $transaction->payment_date->format('F j, Y g:i A') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="ml-14 space-y-2">
                                        @if($transaction->transaction_reference)
                                            <div class="flex items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <span class="text-gray-500">Reference:</span>
                                                <span class="ml-2 font-medium text-gray-700">{{ $transaction->transaction_reference }}</span>
                                            </div>
                                        @endif

                                        @if($transaction->paidBy)
                                            <div class="flex items-center text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <span class="text-gray-500">Processed by:</span>
                                                <span class="ml-2 font-medium text-gray-700">{{ $transaction->paidBy->full_name }}</span>
                                            </div>
                                        @endif

                                        @if($transaction->notes)
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <span class="text-gray-500">Notes:</span>
                                                    <p class="mt-1 text-gray-700">{{ $transaction->notes }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No payment transactions yet</h3>
                    <p class="mt-1 text-sm text-gray-500">The admin hasn't processed any payments for this booking yet.</p>
                </div>
            @endif
        </div>

        <!-- Help Section -->
        @if($payment->amount_remaining > 0)
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Payment Information</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>Payments are processed by the platform admin. If you have questions about your payment schedule, please contact support.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
