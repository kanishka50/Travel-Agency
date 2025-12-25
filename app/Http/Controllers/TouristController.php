<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TouristRequest;
use App\Models\PlanProposal;

class TouristController extends Controller
{
    public function dashboard()
    {
        // Get the logged-in tourist's profile
        $tourist = auth()->user()->tourist;

        // Get statistics
        $totalBookings = Booking::where('tourist_id', $tourist->id)->count();
        $upcomingBookings = Booking::where('tourist_id', $tourist->id)
            ->whereIn('status', ['pending_payment', 'confirmed', 'upcoming'])
            ->count();
        $completedBookings = Booking::where('tourist_id', $tourist->id)
            ->where('status', 'completed')
            ->count();

        $activeRequests = TouristRequest::where('tourist_id', $tourist->id)
            ->where('status', 'open')
            ->count();

        $pendingProposals = PlanProposal::where('tourist_id', $tourist->id)
            ->where('status', 'pending')
            ->count();

        // Get recent bookings
        $recentBookings = Booking::where('tourist_id', $tourist->id)
            ->with([
                'guidePlan.guide',
                'acceptedProposal.guidePlan.guide',
                'acceptedBid.touristRequest'
            ])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Return the dashboard view with tourist data
        return view('tourist.dashboard', [
            'tourist' => $tourist,
            'totalBookings' => $totalBookings,
            'upcomingBookings' => $upcomingBookings,
            'completedBookings' => $completedBookings,
            'activeRequests' => $activeRequests,
            'pendingProposals' => $pendingProposals,
            'recentBookings' => $recentBookings,
        ]);
    }

    public function settings()
    {
        $tourist = auth()->user()->tourist;

        return view('tourist.settings', [
            'tourist' => $tourist,
            'user' => auth()->user(),
        ]);
    }
}