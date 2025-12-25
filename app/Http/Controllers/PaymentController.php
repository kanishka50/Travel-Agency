<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmed;
use App\Mail\GuideNewBooking;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Services\BookingPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function createCheckoutSession(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated tourist
        if ($booking->tourist_id !== Auth::user()->tourist->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        // Check if booking is already paid or cancelled
        if (!in_array($booking->status, ['pending_payment', 'payment_failed'])) {
            return redirect()->route('bookings.show', $booking->id)
                ->with('error', 'This booking cannot be paid at this time.');
        }

        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));

        // Load relationships
        $booking->load(['guidePlan', 'touristRequest']);

        try {
            // Determine product name based on booking type
            if ($booking->booking_type === 'custom_request' && $booking->touristRequest) {
                $productName = $booking->touristRequest->title . ' (Custom Tour)';
            } elseif ($booking->guidePlan) {
                $productName = $booking->guidePlan->title;
            } else {
                $productName = 'Tour Booking #' . $booking->booking_number;
            }

            // Build description including add-ons if any
            $description = "Tour booking from {$booking->start_date->format('M d, Y')} to {$booking->end_date->format('M d, Y')}";
            if ($booking->addons->count() > 0) {
                $addonNames = $booking->addons->pluck('addon_name')->join(', ');
                $description .= " | Add-ons: {$addonNames}";
            }

            // Create line items for the checkout
            // Note: total_amount already includes base price + add-ons + platform fee
            $lineItems = [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $productName,
                            'description' => $description,
                        ],
                        'unit_amount' => intval($booking->total_amount * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ],
            ];

            // Create Stripe Checkout Session
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.success', ['booking' => $booking->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel', ['booking' => $booking->id]),
                'client_reference_id' => $booking->id,
                'customer_email' => $booking->tourist->user->email,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'booking_number' => $booking->booking_number,
                ],
            ]);

            // Store the checkout session ID in the booking for reference
            $booking->update([
                'stripe_session_id' => $checkoutSession->id,
            ]);

            // Redirect to Stripe Checkout
            return redirect($checkoutSession->url);

        } catch (\Exception $e) {
            return redirect()->route('bookings.show', $booking->id)
                ->with('error', 'Payment session could not be created. Please try again.');
        }
    }

    public function success(Request $request, Booking $booking)
    {
        // Ensure the booking belongs to the authenticated tourist
        if ($booking->tourist_id !== Auth::user()->tourist->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('bookings.show', $booking->id)
                ->with('error', 'Payment verification failed.');
        }

        // Verify the session with Stripe and confirm payment directly
        // This handles cases where webhook is delayed or not configured
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($sessionId);

            // If payment is complete but booking not yet updated (webhook delay), update it now
            // Also handle payment_failed status in case user retried payment
            if ($session->payment_status === 'paid' && in_array($booking->status, ['pending_payment', 'payment_failed'])) {
                \Log::info('Payment success: Confirming payment directly (webhook may be delayed)', [
                    'booking_id' => $booking->id,
                    'session_id' => $sessionId,
                ]);

                $booking->update([
                    'status' => 'confirmed',
                    'stripe_payment_id' => $session->payment_intent,
                    'paid_at' => now(),
                ]);

                // Create booking payment record if it doesn't exist
                if (!$booking->payment()->exists()) {
                    BookingPayment::create([
                        'booking_id' => $booking->id,
                        'total_amount' => $booking->total_amount,
                        'platform_fee' => $booking->platform_fee,
                        'guide_payout' => $booking->guide_payout,
                        'guide_paid' => false,
                    ]);
                }

                // Regenerate PDF with full contact details
                try {
                    $pdfService = new BookingPdfService();
                    $pdfService->regeneratePdfAfterPayment($booking);
                } catch (\Exception $e) {
                    \Log::error('Payment success: Failed to regenerate PDF', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                // Send confirmation emails
                try {
                    $booking->load(['tourist.user', 'guide.user', 'guidePlan', 'touristRequest', 'acceptedBid', 'addons']);
                    Mail::to($booking->tourist->user->email)->send(new BookingConfirmed($booking));
                    Mail::to($booking->guide->user->email)->send(new GuideNewBooking($booking));
                } catch (\Exception $e) {
                    \Log::error('Payment success: Failed to send emails', [
                        'booking_id' => $booking->id,
                        'error' => $e->getMessage(),
                    ]);
                }

                // Refresh booking to get updated status
                $booking->refresh();
            }
        } catch (\Exception $e) {
            \Log::error('Payment success: Failed to verify Stripe session', [
                'booking_id' => $booking->id,
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            // Continue anyway - the webhook might still handle it
        }

        // Load relationships needed for the view
        $booking->load(['guidePlan', 'touristRequest', 'tourist.user', 'guide.user']);

        return view('payment.success', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated tourist
        if ($booking->tourist_id !== Auth::user()->tourist->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        // Update booking status to payment_failed
        $booking->update([
            'status' => 'payment_failed',
        ]);

        return redirect()->route('bookings.show', $booking->id)
            ->with('error', 'Payment was cancelled. You can try again when ready.');
    }

    public function webhook(Request $request)
    {
        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            // Verify webhook signature
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                // Additional handling if needed
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentFailed($paymentIntent);
                break;

            default:
                // Log unhandled events but return success to acknowledge receipt
                \Log::info('Webhook: Unhandled event type', ['type' => $event->type]);
                break;
        }

        return response()->json(['status' => 'success'], 200);
    }

    private function handleCheckoutSessionCompleted($session)
    {
        \Log::info('Webhook: checkout.session.completed received', [
            'session_id' => $session->id,
            'metadata' => $session->metadata,
        ]);

        $bookingId = $session->metadata->booking_id ?? null;

        if (!$bookingId) {
            \Log::warning('Webhook: No booking_id in metadata');
            return;
        }

        $booking = Booking::find($bookingId);

        if (!$booking) {
            \Log::error('Webhook: Booking not found', ['booking_id' => $bookingId]);
            return;
        }

        \Log::info('Webhook: Updating booking', [
            'booking_id' => $bookingId,
            'booking_number' => $booking->booking_number,
            'old_status' => $booking->status,
        ]);

        // Update booking status to confirmed
        $booking->update([
            'status' => 'confirmed',
            'stripe_payment_id' => $session->payment_intent,
            'paid_at' => now(),
        ]);

        \Log::info('Webhook: Booking updated successfully', [
            'booking_id' => $bookingId,
            'new_status' => $booking->fresh()->status,
            'stripe_payment_id' => $session->payment_intent,
        ]);

        // Create booking payment record for admin tracking
        try {
            BookingPayment::create([
                'booking_id' => $booking->id,
                'total_amount' => $booking->total_amount,
                'platform_fee' => $booking->platform_fee,
                'guide_payout' => $booking->guide_payout,
                'guide_paid' => false, // Admin will mark this when they pay the guide
            ]);
            \Log::info('Webhook: BookingPayment record created', ['booking_id' => $bookingId]);
        } catch (\Exception $e) {
            \Log::error('Webhook: Failed to create BookingPayment record', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage(),
            ]);
        }

        // Regenerate PDF with full contact details
        try {
            $pdfService = new BookingPdfService();
            $pdfService->regeneratePdfAfterPayment($booking);
            \Log::info('Webhook: PDF regenerated with full contacts', ['booking_id' => $bookingId]);
        } catch (\Exception $e) {
            \Log::error('Webhook: Failed to regenerate PDF', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage(),
            ]);
        }

        // Send confirmation emails
        try {
            // Reload booking with all relationships for emails
            $booking->load(['tourist.user', 'guide.user', 'guidePlan', 'touristRequest', 'acceptedBid', 'addons']);

            // Send email to tourist
            Mail::to($booking->tourist->user->email)->send(new BookingConfirmed($booking));
            \Log::info('Webhook: Confirmation email sent to tourist', [
                'booking_id' => $bookingId,
                'tourist_email' => $booking->tourist->user->email,
            ]);

            // Send notification to guide
            Mail::to($booking->guide->user->email)->send(new GuideNewBooking($booking));
            \Log::info('Webhook: Notification email sent to guide', [
                'booking_id' => $bookingId,
                'guide_email' => $booking->guide->user->email,
            ]);
        } catch (\Exception $e) {
            \Log::error('Webhook: Failed to send emails', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function handlePaymentFailed($paymentIntent)
    {
        // Find booking by payment intent ID
        $booking = Booking::where('stripe_payment_id', $paymentIntent->id)->first();

        if ($booking) {
            $booking->update([
                'status' => 'payment_failed',
            ]);
        }
    }
}
