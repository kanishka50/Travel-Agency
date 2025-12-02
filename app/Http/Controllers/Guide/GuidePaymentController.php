<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\BookingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuidePaymentController extends Controller
{
    /**
     * Display a listing of guide payments
     */
    public function index()
    {
        $guide = Auth::user()->guide;

        // Get all payments for this guide's bookings with eager loading
        $payments = BookingPayment::with([
            'booking.guidePlan',
            'booking.tourist',
            'transactions.paidBy'
        ])
        ->whereHas('booking', function ($query) use ($guide) {
            $query->where('guide_id', $guide->id);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        // Calculate summary statistics
        $totalEarnings = BookingPayment::whereHas('booking', function ($query) use ($guide) {
            $query->where('guide_id', $guide->id);
        })->sum('guide_payout');

        $totalReceived = BookingPayment::whereHas('booking', function ($query) use ($guide) {
            $query->where('guide_id', $guide->id);
        })->sum('amount_paid');

        $totalPending = BookingPayment::whereHas('booking', function ($query) use ($guide) {
            $query->where('guide_id', $guide->id);
        })->sum('amount_remaining');

        $fullyPaidCount = BookingPayment::whereHas('booking', function ($query) use ($guide) {
            $query->where('guide_id', $guide->id);
        })->where('amount_remaining', '<=', 0)->count();

        $partiallyPaidCount = BookingPayment::whereHas('booking', function ($query) use ($guide) {
            $query->where('guide_id', $guide->id);
        })->where('amount_remaining', '>', 0)
          ->where('amount_paid', '>', 0)->count();

        $unpaidCount = BookingPayment::whereHas('booking', function ($query) use ($guide) {
            $query->where('guide_id', $guide->id);
        })->where('amount_paid', 0)->count();

        return view('guide.payments.index', compact(
            'payments',
            'totalEarnings',
            'totalReceived',
            'totalPending',
            'fullyPaidCount',
            'partiallyPaidCount',
            'unpaidCount'
        ));
    }

    /**
     * Display payment details for a specific booking
     */
    public function show(BookingPayment $payment)
    {
        $guide = Auth::user()->guide;

        // Verify this payment belongs to the guide
        if ($payment->booking->guide_id !== $guide->id) {
            abort(403, 'Unauthorized access to payment details.');
        }

        // Load relationships
        $payment->load([
            'booking.guidePlan',
            'booking.tourist',
            'transactions' => function ($query) {
                $query->orderBy('payment_date', 'desc');
            },
            'transactions.paidBy'
        ]);

        return view('guide.payments.show', compact('payment'));
    }
}
