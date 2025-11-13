<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\TouristRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    /**
     * Display a listing of open tourist requests that guides can bid on
     */
    public function index(Request $request)
    {
        $query = TouristRequest::query()
            ->with(['tourist.user', 'bids'])
            ->open() // Only show open requests
            ->orderBy('created_at', 'desc');

        // Filter by destination
        if ($request->filled('destination')) {
            $query->whereJsonContains('preferred_destinations', $request->destination);
        }

        // Filter by budget range
        if ($request->filled('budget_min')) {
            $query->where('budget_max', '>=', $request->budget_min);
        }
        if ($request->filled('budget_max')) {
            $query->where('budget_min', '<=', $request->budget_max);
        }

        // Filter by duration
        if ($request->filled('duration_min')) {
            $query->where('duration_days', '>=', $request->duration_min);
        }
        if ($request->filled('duration_max')) {
            $query->where('duration_days', '<=', $request->duration_max);
        }

        // Filter by start date
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        // Filter by trip focus
        if ($request->filled('trip_focus')) {
            $query->whereJsonContains('trip_focus', $request->trip_focus);
        }

        $requests = $query->paginate(12);

        return view('guide.requests.index', compact('requests'));
    }

    /**
     * Display the specified tourist request
     */
    public function show(TouristRequest $touristRequest)
    {
        // Check if request is still open
        if (!$touristRequest->isOpen()) {
            return redirect()->route('guide.requests.index')
                ->with('error', 'This request is no longer accepting bids.');
        }

        // Load relationships
        $touristRequest->load(['tourist.user', 'bids.guide.user']);

        // Get current guide's bids for this request
        $guide = Auth::user()->guide;
        $myBids = $touristRequest->bids()
            ->where('guide_id', $guide->id)
            ->orderBy('bid_number', 'asc')
            ->get();

        // Check how many bids the guide has submitted
        $bidCount = $myBids->count();
        $canSubmitBid = $bidCount < 2; // Maximum 2 bids per guide

        return view('guide.requests.show', compact('touristRequest', 'myBids', 'canSubmitBid', 'bidCount'));
    }
}
