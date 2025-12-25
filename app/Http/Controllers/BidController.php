<?php

namespace App\Http\Controllers;

use App\Mail\BidAccepted;
use App\Mail\BidRejected;
use App\Mail\BookingConfirmation;
use App\Mail\GuideBookingNotification;
use App\Models\Bid;
use App\Models\Booking;
use App\Models\TouristRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

        return view('tourist.requests.bids.show', compact('touristRequest', 'bid'));
    }

    /**
     * Accept a bid and create a booking
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

            // ========================================
            // CREATE BOOKING FROM ACCEPTED BID
            // ========================================

            // Calculate pricing from bid
            $totalPrice = $bid->total_price;
            $platformFee = $totalPrice * 0.10; // 10% platform fee
            $totalAmount = $totalPrice + $platformFee;
            $guidePayout = $totalPrice * 0.90; // Guide gets 90%

            // Generate unique booking number
            $bookingNumber = 'BK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            // Calculate end date based on estimated days
            $startDate = $touristRequest->start_date;
            $endDate = $touristRequest->end_date ?? $startDate->copy()->addDays($bid->estimated_days - 1);

            // Create the booking
            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'booking_type' => 'custom_request', // From custom request (not guide plan)
                'tourist_id' => $touristRequest->tourist_id,
                'guide_id' => $bid->guide_id,
                'guide_plan_id' => null, // No guide plan - this is custom
                'tourist_request_id' => $touristRequest->id,
                'accepted_bid_id' => $bid->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'num_adults' => $touristRequest->num_adults,
                'num_children' => $touristRequest->num_children,
                'children_ages' => $touristRequest->children_ages,
                'base_price' => $totalPrice,
                'addons_total' => 0, // Custom requests don't have add-ons
                'subtotal' => $totalPrice,
                'platform_fee' => $platformFee,
                'total_amount' => $totalAmount,
                'guide_payout' => $guidePayout,
                'status' => 'pending_payment',
                'tourist_notes' => $touristRequest->special_requests,
                'guide_notes' => $bid->day_by_day_plan, // Store the guide's proposed itinerary
            ]);

            // Generate agreement PDF for custom booking
            $booking->load(['tourist.user', 'guide.user', 'touristRequest', 'acceptedBid']);
            $pdf = Pdf::loadView('pdfs.custom-booking-agreement', [
                'booking' => $booking,
                'bid' => $bid,
                'touristRequest' => $touristRequest,
            ]);

            // Save PDF
            $filename = 'booking-' . $booking->booking_number . '.pdf';
            $filePath = 'agreements/' . $filename;
            Storage::disk('public')->put($filePath, $pdf->output());

            // Update booking with PDF path
            $booking->update(['agreement_pdf_path' => $filePath]);

            // Note: tourist_request status is already set to 'bid_accepted' above
            // The booking relationship links them together

            DB::commit();

            // Send email notification to accepted guide
            try {
                Mail::to($bid->guide->user->email)->send(new BidAccepted($bid));
            } catch (\Exception $e) {
                \Log::error('Failed to send BidAccepted email: ' . $e->getMessage());
            }

            // Send email notifications to rejected guides
            $rejectedBids = Bid::where('tourist_request_id', $touristRequest->id)
                ->where('id', '!=', $bid->id)
                ->where('status', 'rejected')
                ->with('guide.user')
                ->get();

            foreach ($rejectedBids as $rejectedBid) {
                try {
                    Mail::to($rejectedBid->guide->user->email)->send(new BidRejected($rejectedBid));
                } catch (\Exception $e) {
                    \Log::error('Failed to send BidRejected email: ' . $e->getMessage());
                }
            }

            // Send booking confirmation emails
            try {
                Mail::to($booking->tourist->user->email)->send(new BookingConfirmation($booking));
                Mail::to($booking->guide->user->email)->send(new GuideBookingNotification($booking));
            } catch (\Exception $e) {
                \Log::error('Failed to send booking emails: ' . $e->getMessage());
            }

            // Redirect to booking page for payment
            return redirect()->route('tourist.bookings.show', $booking->id)
                ->with('success', 'Proposal accepted! Your booking has been created. Please complete payment to confirm.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to accept bid and create booking: ' . $e->getMessage());

            return back()->with('error', 'Failed to accept proposal. Please try again. Error: ' . $e->getMessage());
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

            return redirect()->route('tourist.requests.show', $touristRequest)
                ->with('success', 'Proposal has been rejected. The guide has been notified.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject proposal. Please try again.');
        }
    }

}
