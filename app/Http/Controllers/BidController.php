<?php

namespace App\Http\Controllers;

use App\Mail\BidAccepted;
use App\Mail\BidRejected;
use App\Models\Bid;
use App\Models\TouristRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BidController extends Controller
{
    /**
     * Show the detailed bid proposal
     */
    public function show(TouristRequest $touristRequest, Bid $bid)
    {
        // Authorization check - tourist can only view bids for their own requests
        if ($touristRequest->tourist_id !== Auth::user()->tourist->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check that the bid belongs to this request
        if ($bid->tourist_request_id !== $touristRequest->id) {
            abort(404, 'Bid not found for this request.');
        }

        // Load relationships
        $bid->load(['guide.user']);

        return view('tourist-requests.bids.show', compact('touristRequest', 'bid'));
    }

    /**
     * Accept a bid
     */
    public function accept(TouristRequest $touristRequest, Bid $bid)
    {
        // Authorization check
        if ($touristRequest->tourist_id !== Auth::user()->tourist->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check that the bid belongs to this request
        if ($bid->tourist_request_id !== $touristRequest->id) {
            abort(404, 'Bid not found for this request.');
        }

        // Can only accept pending bids
        if ($bid->status !== 'pending') {
            return back()->with('error', 'This proposal cannot be accepted. It may have already been processed.');
        }

        // Check if request is still open
        if (!$touristRequest->isOpen()) {
            return back()->with('error', 'This request is no longer open for accepting bids.');
        }

        try {
            DB::beginTransaction();

            // Accept the bid
            $bid->accept();

            // Reject all other pending bids for this request
            Bid::where('tourist_request_id', $touristRequest->id)
                ->where('id', '!=', $bid->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'rejection_reason' => 'Another proposal was accepted by the tourist.',
                    'responded_at' => now(),
                ]);

            // Update request status
            $touristRequest->update([
                'status' => 'bid_accepted',
            ]);

            DB::commit();

            // Send email notification to accepted guide
            Mail::to($bid->guide->user->email)->send(new BidAccepted($bid));

            // Send email notifications to rejected guides
            $rejectedBids = Bid::where('tourist_request_id', $touristRequest->id)
                ->where('id', '!=', $bid->id)
                ->where('status', 'rejected')
                ->with('guide.user')
                ->get();

            foreach ($rejectedBids as $rejectedBid) {
                Mail::to($rejectedBid->guide->user->email)->send(new BidRejected($rejectedBid));
            }

            return redirect()->route('tourist-requests.show', $touristRequest)
                ->with('success', 'Proposal accepted! You can now proceed to create a booking with this guide.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to accept proposal. Please try again.');
        }
    }

    /**
     * Reject a bid
     */
    public function reject(Request $request, TouristRequest $touristRequest, Bid $bid)
    {
        // Authorization check
        if ($touristRequest->tourist_id !== Auth::user()->tourist->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check that the bid belongs to this request
        if ($bid->tourist_request_id !== $touristRequest->id) {
            abort(404, 'Bid not found for this request.');
        }

        // Can only reject pending bids
        if ($bid->status !== 'pending') {
            return back()->with('error', 'This proposal cannot be rejected. It may have already been processed.');
        }

        // Validate rejection reason
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        try {
            // Reject the bid
            $bid->reject($validated['rejection_reason']);

            // Send email notification to guide
            Mail::to($bid->guide->user->email)->send(new BidRejected($bid));

            return redirect()->route('tourist-requests.show', $touristRequest)
                ->with('success', 'Proposal has been rejected. The guide has been notified.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject proposal. Please try again.');
        }
    }
}
