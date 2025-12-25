<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\GuideBookingNotification;
use App\Models\Booking;
use App\Models\GuidePlan;
use App\Models\GuidePlanAddon;
use App\Services\AvailabilityService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Show the booking form
     */
    public function create(Request $request)
    {
        // Validate required parameters
        $request->validate([
            'plan_id' => 'required|exists:guide_plans,id',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        $plan = GuidePlan::with(['guide.user', 'addons'])->findOrFail($request->plan_id);
        $startDate = Carbon::parse($request->start_date);

        // Final availability check
        $availability = $this->availabilityService->checkPlanAvailability($plan, $startDate);

        if (!$availability['available']) {
            return redirect()
                ->route('plans.show', $plan->id)
                ->with('error', $availability['message']);
        }

        $endDate = Carbon::parse($availability['end_date']);

        return view('tourist.bookings.create', compact('plan', 'startDate', 'endDate'));
    }

    /**
     * Store the booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:guide_plans,id',
            'start_date' => 'required|date|after_or_equal:today',
            'num_adults' => 'required|integer|min:1|max:50',
            'num_children' => 'required|integer|min:0|max:50',
            'children_ages' => 'nullable|array',
            'children_ages.*' => 'integer|min:0|max:17',
            'tourist_notes' => 'nullable|string|max:1000',
            'selected_addons' => 'nullable|array',
            'selected_addons.*.addon_id' => 'required|integer',
            'selected_addons.*.quantity' => 'required|integer|min:1',
            'selected_addons.*.price' => 'required|numeric|min:0',
            'agree_terms' => 'required|accepted',
        ]);

        $plan = GuidePlan::with('guide')->findOrFail($validated['plan_id']);
        $startDate = Carbon::parse($validated['start_date']);

        // Final availability check before creating booking
        $availability = $this->availabilityService->checkPlanAvailability($plan, $startDate);

        if (!$availability['available']) {
            return back()
                ->withInput()
                ->with('error', $availability['message']);
        }

        $endDate = Carbon::parse($availability['end_date']);

        try {
            DB::beginTransaction();

            // Calculate pricing
            $basePrice = ($plan->price_per_adult * $validated['num_adults']) +
                        ($plan->price_per_child * $validated['num_children']);

            $addonsTotal = 0;
            if (!empty($validated['selected_addons'])) {
                foreach ($validated['selected_addons'] as $addon) {
                    $addonsTotal += $addon['price'] * $addon['quantity'];
                }
            }

            $subtotal = $basePrice + $addonsTotal;
            $platformFee = $subtotal * 0.10; // 10% platform fee
            $totalAmount = $subtotal + $platformFee;
            $guidePayout = $subtotal * 0.90; // Guide gets 90%

            // Generate unique booking number
            $bookingNumber = 'BK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            // Get the tourist record for the authenticated user
            $tourist = Auth::user()->tourist;

            if (!$tourist) {
                throw new \Exception('Tourist profile not found for this user.');
            }

            // Create booking
            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'booking_type' => 'guide_plan', // From guide plan (not custom request)
                'tourist_id' => $tourist->id,
                'guide_id' => $plan->guide_id,
                'guide_plan_id' => $plan->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'num_adults' => $validated['num_adults'],
                'num_children' => $validated['num_children'],
                'children_ages' => $validated['children_ages'] ?? null,
                'base_price' => $basePrice,
                'addons_total' => $addonsTotal,
                'subtotal' => $subtotal,
                'platform_fee' => $platformFee,
                'total_amount' => $totalAmount,
                'guide_payout' => $guidePayout,
                'status' => 'pending_payment', // Pending payment
                'tourist_notes' => $validated['tourist_notes'] ?? null,
            ]);

            // Save add-ons if any
            if (!empty($validated['selected_addons'])) {
                foreach ($validated['selected_addons'] as $addonData) {
                    // Find the original GuidePlanAddon to get full details
                    $guidePlanAddon = GuidePlanAddon::find($addonData['addon_id']);

                    $booking->addons()->create([
                        'guide_plan_addon_id' => $addonData['addon_id'],
                        'addon_name' => $guidePlanAddon ? $guidePlanAddon->addon_name : ($addonData['name'] ?? 'Add-on'),
                        'addon_description' => $guidePlanAddon ? $guidePlanAddon->addon_description : '',
                        'day_number' => $guidePlanAddon ? $guidePlanAddon->day_number : 0,
                        'price_per_person' => $addonData['price'],
                        'num_participants' => $addonData['quantity'],
                        'total_price' => $addonData['price'] * $addonData['quantity'],
                    ]);
                }
            }

            // Generate agreement PDF
            $booking->load(['tourist.user', 'guide.user', 'guidePlan', 'addons']);
            $pdf = Pdf::loadView('pdfs.booking-agreement', ['booking' => $booking]);

            // Create filename: booking-BK-20251111-ABC123.pdf
            $filename = 'booking-' . $booking->booking_number . '.pdf';
            $filePath = 'agreements/' . $filename;

            // Save PDF to storage
            Storage::disk('public')->put($filePath, $pdf->output());

            // Update booking with PDF path
            $booking->update(['agreement_pdf_path' => $filePath]);

            // Send confirmation emails
            try {
                // Send email to tourist
                Mail::to($booking->tourist->user->email)
                    ->send(new BookingConfirmation($booking));

                // Send notification to guide
                Mail::to($booking->guide->user->email)
                    ->send(new GuideBookingNotification($booking));
            } catch (\Exception $mailException) {
                // Log the error but don't fail the booking
                \Log::error('Failed to send booking emails: ' . $mailException->getMessage());
            }

            DB::commit();

            return redirect()
                ->route('tourist.bookings.show', $booking->id)
                ->with('success', 'Booking created successfully! Please complete payment to confirm.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to create booking. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated tourist
        $tourist = Auth::user()->tourist;

        if (!$tourist || $booking->tourist_id !== $tourist->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        // Load relationships based on booking type
        $booking->load(['guidePlan', 'guide.user', 'addons', 'touristRequest', 'acceptedBid', 'vehicleAssignment.vehicle.photos']);

        return view('tourist.bookings.show', compact('booking'));
    }

    /**
     * List all bookings for the authenticated tourist
     */
    public function index()
    {
        $tourist = Auth::user()->tourist;

        if (!$tourist) {
            return redirect()->route('tourist.dashboard')
                ->with('error', 'Tourist profile not found.');
        }

        $bookings = Booking::where('tourist_id', $tourist->id)
            ->with(['guidePlan', 'guide.user', 'touristRequest', 'vehicleAssignment.vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tourist.bookings.index', compact('bookings'));
    }

    /**
     * Download booking agreement PDF
     */
    public function downloadAgreement(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated tourist
        $tourist = Auth::user()->tourist;

        if (!$tourist || $booking->tourist_id !== $tourist->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        // Check if PDF exists
        if (!$booking->agreement_pdf_path || !Storage::disk('public')->exists($booking->agreement_pdf_path)) {
            return back()->with('error', 'Agreement PDF not found.');
        }

        // Download the PDF
        return Storage::disk('public')->download(
            $booking->agreement_pdf_path,
            'booking-' . $booking->booking_number . '.pdf'
        );
    }
}
