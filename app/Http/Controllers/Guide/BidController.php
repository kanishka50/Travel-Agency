<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Mail\BidReceived;
use App\Models\Bid;
use App\Models\TouristRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BidController extends Controller
{
    /**
     * Show the form for creating a new bid
     */
    public function create(TouristRequest $touristRequest)
    {
        // Check if request is still open
        if (!$touristRequest->isOpen()) {
            return redirect()->route('guide.requests.index')
                ->with('error', 'This request is no longer accepting bids.');
        }

        $guide = Auth::user()->guide;

        // Check how many bids this guide has already submitted
        $existingBidsCount = Bid::where('tourist_request_id', $touristRequest->id)
            ->where('guide_id', $guide->id)
            ->count();

        // Maximum 2 bids per guide per request
        if ($existingBidsCount >= 2) {
            return redirect()->route('guide.requests.show', $touristRequest)
                ->with('error', 'You have already submitted the maximum of 2 proposals for this request.');
        }

        // Calculate the next bid number
        $bidNumber = $existingBidsCount + 1;

        // Load existing bids to show context
        $myBids = Bid::where('tourist_request_id', $touristRequest->id)
            ->where('guide_id', $guide->id)
            ->orderBy('bid_number', 'asc')
            ->get();

        return view('guide.proposals.create', compact('touristRequest', 'bidNumber', 'myBids'));
    }

    /**
     * Store a newly created bid
     */
    public function store(Request $request, TouristRequest $touristRequest)
    {
        // Check if request is still open
        if (!$touristRequest->isOpen()) {
            return redirect()->route('guide.requests.index')
                ->with('error', 'This request is no longer accepting bids.');
        }

        $guide = Auth::user()->guide;

        // Check how many bids this guide has already submitted
        $existingBidsCount = Bid::where('tourist_request_id', $touristRequest->id)
            ->where('guide_id', $guide->id)
            ->count();

        if ($existingBidsCount >= 2) {
            return redirect()->route('guide.requests.show', $touristRequest)
                ->with('error', 'You have already submitted the maximum of 2 proposals for this request.');
        }

        // Validate the request
        $validated = $request->validate([
            'proposal_message' => 'required|string|min:100|max:2000',
            'day_by_day_plan' => 'required|string|min:200',
            'total_price' => 'required|numeric|min:1',
            'price_breakdown' => 'nullable|string',
            'destinations_covered' => 'required|array|min:1',
            'destinations_covered.*' => 'required|string',
            'accommodation_details' => 'nullable|string',
            'transport_details' => 'nullable|string',
            'included_services' => 'nullable|string',
            'excluded_services' => 'nullable|string',
            'estimated_days' => 'nullable|integer|min:1',
        ]);

        // Check if price is within tourist's budget
        if ($validated['total_price'] < $touristRequest->budget_min || $validated['total_price'] > $touristRequest->budget_max) {
            return back()->withInput()->withErrors([
                'total_price' => "The price should be within the tourist's budget range ($" . number_format($touristRequest->budget_min) . " - $" . number_format($touristRequest->budget_max) . ")."
            ]);
        }

        try {
            DB::beginTransaction();

            // Calculate bid number
            $bidNumber = $existingBidsCount + 1;

            // Create the bid
            $bid = Bid::create([
                'tourist_request_id' => $touristRequest->id,
                'guide_id' => $guide->id,
                'bid_number' => $bidNumber,
                'proposal_message' => $validated['proposal_message'],
                'day_by_day_plan' => $validated['day_by_day_plan'],
                'total_price' => $validated['total_price'],
                'price_breakdown' => $validated['price_breakdown'],
                'destinations_covered' => $validated['destinations_covered'],
                'accommodation_details' => $validated['accommodation_details'],
                'transport_details' => $validated['transport_details'],
                'included_services' => $validated['included_services'],
                'excluded_services' => $validated['excluded_services'],
                'estimated_days' => $validated['estimated_days'] ?? $touristRequest->duration_days,
                'status' => 'pending',
                'submitted_at' => now(),
            ]);

            // Increment the bid count on the tourist request
            $touristRequest->incrementBidCount();

            DB::commit();

            // Send email notification to tourist
            Mail::to($touristRequest->tourist->user->email)->send(new BidReceived($bid));

            return redirect()->route('guide.requests.show', $touristRequest)
                ->with('success', 'Your proposal has been submitted successfully! The tourist will review it and get back to you.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Failed to submit proposal. Please try again.');
        }
    }

    /**
     * Withdraw a pending bid
     */
    public function withdraw(Bid $bid)
    {
        $guide = Auth::user()->guide;

        // Authorization check
        if ($bid->guide_id !== $guide->id) {
            abort(403, 'Unauthorized action.');
        }

        // Can only withdraw pending bids
        if ($bid->status !== 'pending') {
            return back()->with('error', 'Only pending proposals can be withdrawn.');
        }

        try {
            DB::beginTransaction();

            // Update bid status
            $bid->update([
                'status' => 'withdrawn',
                'responded_at' => now(),
            ]);

            // Decrement bid count
            $bid->touristRequest->decrement('bid_count');

            DB::commit();

            return back()->with('success', 'Your proposal has been withdrawn.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to withdraw proposal. Please try again.');
        }
    }
}
