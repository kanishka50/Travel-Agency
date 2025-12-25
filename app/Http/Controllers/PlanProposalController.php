<?php

namespace App\Http\Controllers;

use App\Mail\ProposalAccepted;
use App\Mail\ProposalRejected;
use App\Mail\ProposalReceived;
use App\Models\Booking;
use App\Models\GuidePlan;
use App\Models\PlanProposal;
use App\Services\AvailabilityService;
use App\Services\BookingPdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PlanProposalController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Show proposal form for a guide plan (Tourist)
     */
    public function create(GuidePlan $plan)
    {
        // Check if plan allows proposals
        if (!$plan->allowsProposals()) {
            return redirect()->route('tour-packages.show', $plan->id)
                ->with('error', 'This tour does not accept proposals.');
        }

        // Check if plan is active
        if (!$plan->isActive()) {
            return redirect()->route('tour-packages.show', $plan->id)
                ->with('error', 'This tour is not currently available.');
        }

        $plan->load('guide.user');

        return view('tourist.proposals.create', compact('plan'));
    }

    /**
     * Store a new proposal (Tourist)
     */
    public function store(Request $request, GuidePlan $plan)
    {
        $tourist = Auth::user()->tourist;

        // Validate request
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'proposed_price' => 'required|numeric|min:1',
            'num_adults' => 'required|integer|min:1|max:' . $plan->max_group_size,
            'num_children' => 'nullable|integer|min:0',
            'children_ages' => 'nullable|array',
            'children_ages.*' => 'nullable|integer|min:0|max:17',
            'modifications' => 'nullable|string|max:2000',
            'message' => 'nullable|string|max:1000',
        ]);

        // Calculate end date based on plan duration
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addDays($plan->num_days - 1);

        // Check guide availability
        if (!$this->availabilityService->isGuideAvailable($plan->guide_id, $startDate, $endDate)) {
            return back()->withInput()->with('error', 'The guide is not available for the selected dates. Please choose different dates.');
        }

        // Check total group size
        $totalTravelers = $validated['num_adults'] + ($validated['num_children'] ?? 0);
        if ($totalTravelers > $plan->max_group_size) {
            return back()->withInput()->with('error', 'Total travelers exceed the maximum group size of ' . $plan->max_group_size);
        }

        // Create proposal
        $proposal = PlanProposal::create([
            'guide_plan_id' => $plan->id,
            'tourist_id' => $tourist->id,
            'proposed_price' => $validated['proposed_price'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'num_adults' => $validated['num_adults'],
            'num_children' => $validated['num_children'] ?? 0,
            'children_ages' => $validated['children_ages'] ?? null,
            'modifications' => $validated['modifications'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        // Send notification email to guide
        try {
            Mail::to($plan->guide->user->email)->send(new ProposalReceived($proposal));
        } catch (\Exception $e) {
            \Log::error('Failed to send proposal notification email', ['error' => $e->getMessage()]);
        }

        return redirect()->route('tourist.proposals.show', $proposal->id)
            ->with('success', 'Your proposal has been submitted successfully! The guide will review it and respond.');
    }

    /**
     * List tourist's proposals (Tourist)
     */
    public function touristIndex(Request $request)
    {
        $tourist = Auth::user()->tourist;

        $query = PlanProposal::where('tourist_id', $tourist->id)
            ->with(['guidePlan.guide.user']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $proposals = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('tourist.proposals.index', compact('proposals'));
    }

    /**
     * Show proposal details (Tourist)
     */
    public function touristShow(PlanProposal $proposal)
    {
        $tourist = Auth::user()->tourist;

        // Ensure proposal belongs to this tourist
        if ($proposal->tourist_id !== $tourist->id) {
            abort(403, 'Unauthorized access to proposal.');
        }

        $proposal->load(['guidePlan.guide.user', 'booking']);

        return view('tourist.proposals.show', compact('proposal'));
    }

    /**
     * Cancel a proposal (Tourist)
     */
    public function cancel(PlanProposal $proposal)
    {
        $tourist = Auth::user()->tourist;

        // Ensure proposal belongs to this tourist
        if ($proposal->tourist_id !== $tourist->id) {
            abort(403, 'Unauthorized access to proposal.');
        }

        // Can only cancel pending proposals
        if (!$proposal->isPending()) {
            return back()->with('error', 'Only pending proposals can be cancelled.');
        }

        $proposal->update(['status' => 'cancelled']);

        return redirect()->route('tourist.proposals.index')
            ->with('success', 'Proposal has been cancelled.');
    }

    /**
     * List proposals for guide's plans (Guide)
     */
    public function guideIndex(Request $request)
    {
        $guide = Auth::user()->guide;

        $query = PlanProposal::whereHas('guidePlan', function ($q) use ($guide) {
            $q->where('guide_id', $guide->id);
        })->with(['guidePlan', 'tourist.user']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        } else {
            // Default to showing pending first
            $query->orderByRaw("FIELD(status, 'pending', 'accepted', 'rejected', 'cancelled')");
        }

        $proposals = $query->orderBy('created_at', 'desc')->paginate(10);

        // Count pending proposals
        $pendingCount = PlanProposal::whereHas('guidePlan', function ($q) use ($guide) {
            $q->where('guide_id', $guide->id);
        })->where('status', 'pending')->count();

        return view('guide.proposals.index', compact('proposals', 'pendingCount'));
    }

    /**
     * Show proposal details (Guide)
     */
    public function guideShow(PlanProposal $proposal)
    {
        $guide = Auth::user()->guide;

        // Ensure proposal is for this guide's plan
        if ($proposal->guidePlan->guide_id !== $guide->id) {
            abort(403, 'Unauthorized access to proposal.');
        }

        $proposal->load(['guidePlan', 'tourist.user', 'booking']);

        // Check if dates are still available
        $datesAvailable = $this->availabilityService->isGuideAvailable(
            $guide->id,
            $proposal->start_date,
            $proposal->end_date
        );

        return view('guide.proposals.show', compact('proposal', 'datesAvailable'));
    }

    /**
     * Accept a proposal (Guide)
     */
    public function accept(PlanProposal $proposal)
    {
        $guide = Auth::user()->guide;

        // Ensure proposal is for this guide's plan
        if ($proposal->guidePlan->guide_id !== $guide->id) {
            abort(403, 'Unauthorized access to proposal.');
        }

        // Can only accept pending proposals
        if (!$proposal->isPending()) {
            return back()->with('error', 'Only pending proposals can be accepted.');
        }

        // Re-check availability before accepting
        if (!$this->availabilityService->isGuideAvailable($guide->id, $proposal->start_date, $proposal->end_date)) {
            return back()->with('error', 'You already have a booking for these dates. Cannot accept this proposal.');
        }

        try {
            DB::beginTransaction();

            // Calculate pricing
            $plan = $proposal->guidePlan;
            $basePrice = $proposal->proposed_price;
            $platformFeeRate = 0.10; // 10% platform fee
            $platformFee = $basePrice * $platformFeeRate;
            $totalAmount = $basePrice + $platformFee;
            $guidePayout = $basePrice;

            // Create booking
            $booking = Booking::create([
                'booking_number' => 'BK' . strtoupper(Str::random(8)),
                'booking_type' => 'plan_proposal',
                'tourist_id' => $proposal->tourist_id,
                'guide_id' => $guide->id,
                'guide_plan_id' => $plan->id,
                'accepted_proposal_id' => $proposal->id,
                'start_date' => $proposal->start_date,
                'end_date' => $proposal->end_date,
                'num_adults' => $proposal->num_adults,
                'num_children' => $proposal->num_children,
                'children_ages' => $proposal->children_ages,
                'base_price' => $basePrice,
                'addons_total' => 0,
                'subtotal' => $basePrice,
                'platform_fee' => $platformFee,
                'total_amount' => $totalAmount,
                'guide_payout' => $guidePayout,
                'status' => 'pending_payment',
                'tourist_notes' => $proposal->modifications ? "Modifications requested: " . $proposal->modifications : null,
            ]);

            // Update proposal
            $proposal->update([
                'status' => 'accepted',
                'booking_id' => $booking->id,
            ]);

            // Generate PDF agreement
            try {
                $pdfService = new BookingPdfService();
                $pdfPath = $pdfService->generateAgreementPdf($booking, false);
                $booking->update(['agreement_pdf_path' => $pdfPath]);
            } catch (\Exception $e) {
                \Log::error('Failed to generate PDF for proposal booking', ['error' => $e->getMessage()]);
            }

            DB::commit();

            // Send acceptance email to tourist
            try {
                Mail::to($proposal->tourist->user->email)->send(new ProposalAccepted($proposal));
            } catch (\Exception $e) {
                \Log::error('Failed to send proposal acceptance email', ['error' => $e->getMessage()]);
            }

            return redirect()->route('guide.proposals.show', $proposal->id)
                ->with('success', 'Proposal accepted! A booking has been created and the tourist has been notified to proceed with payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to accept proposal', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to accept proposal. Please try again.');
        }
    }

    /**
     * Reject a proposal (Guide)
     */
    public function reject(Request $request, PlanProposal $proposal)
    {
        $guide = Auth::user()->guide;

        // Ensure proposal is for this guide's plan
        if ($proposal->guidePlan->guide_id !== $guide->id) {
            abort(403, 'Unauthorized access to proposal.');
        }

        // Can only reject pending proposals
        if (!$proposal->isPending()) {
            return back()->with('error', 'Only pending proposals can be rejected.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $proposal->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Send rejection email to tourist
        try {
            Mail::to($proposal->tourist->user->email)->send(new ProposalRejected($proposal));
        } catch (\Exception $e) {
            \Log::error('Failed to send proposal rejection email', ['error' => $e->getMessage()]);
        }

        return redirect()->route('guide.proposals.index')
            ->with('success', 'Proposal has been rejected and the tourist has been notified.');
    }
}
