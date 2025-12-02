<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PlanProposal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuideController extends Controller
{
    public function dashboard()
    {
        // Get the logged-in guide's profile
        $guide = Auth::user()->guide;

        // Get bookings statistics
        $upcomingBookings = Booking::where('guide_id', $guide->id)
            ->whereIn('status', ['pending_payment', 'confirmed'])
            ->where('start_date', '>', Carbon::now())
            ->orderBy('start_date', 'asc')
            ->with(['tourist.user', 'guidePlan', 'touristRequest'])
            ->take(5)
            ->get();

        $ongoingBookings = Booking::where('guide_id', $guide->id)
            ->where('status', 'ongoing')
            ->with(['tourist.user', 'guidePlan', 'touristRequest'])
            ->get();

        $totalBookings = Booking::where('guide_id', $guide->id)
            ->whereIn('status', ['confirmed', 'ongoing', 'completed'])
            ->count();

        $pendingPayment = Booking::where('guide_id', $guide->id)
            ->where('status', 'pending_payment')
            ->count();

        $totalEarnings = Booking::where('guide_id', $guide->id)
            ->whereIn('status', ['confirmed', 'ongoing', 'completed'])
            ->sum('guide_payout');

        $thisMonthEarnings = Booking::where('guide_id', $guide->id)
            ->whereIn('status', ['confirmed', 'ongoing', 'completed'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('guide_payout');

        // Get pending proposals count
        $pendingProposals = PlanProposal::whereHas('guidePlan', function ($q) use ($guide) {
            $q->where('guide_id', $guide->id);
        })->where('status', 'pending')->count();

        // Get calendar events (all bookings)
        $calendarBookings = Booking::where('guide_id', $guide->id)
            ->whereIn('status', ['pending_payment', 'confirmed', 'ongoing'])
            ->with(['guidePlan', 'touristRequest'])
            ->get()
            ->map(function ($booking) {
                // Determine title based on booking type
                if ($booking->booking_type === 'custom_request' && $booking->touristRequest) {
                    $title = $booking->touristRequest->title . ' (Custom)';
                } elseif ($booking->guidePlan) {
                    $title = $booking->guidePlan->title;
                } else {
                    $title = 'Booking #' . $booking->booking_number;
                }

                return [
                    'id' => $booking->id,
                    'title' => $title,
                    'start' => $booking->start_date->format('Y-m-d'),
                    'end' => $booking->end_date->addDay()->format('Y-m-d'),
                    'color' => $booking->status === 'confirmed' ? '#10b981' : ($booking->status === 'ongoing' ? '#8b5cf6' : '#f59e0b'),
                    'status' => $booking->status,
                ];
            });

        // Return the dashboard view with guide data
        return view('guide.dashboard', compact(
            'guide',
            'upcomingBookings',
            'ongoingBookings',
            'totalBookings',
            'pendingPayment',
            'totalEarnings',
            'thisMonthEarnings',
            'calendarBookings',
            'pendingProposals'
        ));
    }

    public function bookings(Request $request)
    {
        $guide = Auth::user()->guide;

        $query = Booking::where('guide_id', $guide->id)
            ->with(['tourist.user', 'guidePlan', 'touristRequest', 'addons']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_filter')) {
            switch ($request->date_filter) {
                case 'upcoming':
                    $query->where('start_date', '>', Carbon::now());
                    break;
                case 'past':
                    $query->where('end_date', '<', Carbon::now());
                    break;
                case 'ongoing':
                    $query->where('start_date', '<=', Carbon::now())
                          ->where('end_date', '>=', Carbon::now());
                    break;
            }
        }

        $bookings = $query->orderBy('start_date', 'desc')->paginate(10);

        return view('guide.bookings.index', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        $guide = Auth::user()->guide;

        // Ensure the booking belongs to this guide
        if ($booking->guide_id !== $guide->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        $booking->load(['tourist.user', 'guidePlan', 'touristRequest', 'acceptedBid', 'addons']);

        return view('guide.bookings.show', compact('booking'));
    }
}