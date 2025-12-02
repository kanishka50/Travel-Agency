<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\Booking;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GuideComplaintController extends Controller
{
    /**
     * Display list of guide's complaints
     */
    public function index()
    {
        $guide = Auth::user()->guide;

        $complaints = Complaint::where(function ($query) use ($guide) {
            // Complaints filed by guide
            $query->where('filed_by', Auth::id())
                  ->orWhere(function ($q) use ($guide) {
                      $q->where('complainant_type', 'guide')
                        ->where('complainant_id', $guide->id);
                  });
        })
        ->orWhere(function ($query) use ($guide) {
            // Complaints against guide (only if visible)
            $query->where('against_user_id', Auth::id())
                  ->where('visible_to_defendant', true)
                  ->orWhere(function ($q) use ($guide) {
                      $q->where('against_type', 'guide')
                        ->where('against_id', $guide->id)
                        ->where('visible_to_defendant', true);
                  });
        })
        ->with(['booking', 'assignedAdmin', 'responses'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        // Calculate statistics
        $stats = [
            'total_filed' => Complaint::where('filed_by', Auth::id())->count(),
            'against_me' => Complaint::where('against_user_id', Auth::id())
                ->where('visible_to_defendant', true)
                ->count(),
            'open' => Complaint::where(function ($q) use ($guide) {
                $q->where('filed_by', Auth::id())
                  ->orWhere('against_user_id', Auth::id());
            })->where('status', 'open')->count(),
            'resolved' => Complaint::where(function ($q) use ($guide) {
                $q->where('filed_by', Auth::id())
                  ->orWhere('against_user_id', Auth::id());
            })->where('status', 'resolved')->count(),
        ];

        return view('guide.complaints.index', compact('complaints', 'stats'));
    }

    /**
     * Show form to create new complaint
     */
    public function create(Request $request)
    {
        $guide = Auth::user()->guide;

        // Get guide's bookings that can be complained about
        $bookings = Booking::where('guide_id', $guide->id)
            ->whereIn('status', ['confirmed', 'completed', 'cancelled'])
            ->with('tourist.user', 'guidePlan')
            ->orderBy('start_date', 'desc')
            ->get();

        // Get all active tourists for manual selection
        $tourists = \App\Models\Tourist::with('user')
            ->whereHas('user', function($query) {
                $query->where('status', 'active');
            })
            ->get()
            ->map(function($tourist) {
                return [
                    'id' => $tourist->user_id,
                    'name' => $tourist->user->name ?? 'Tourist #' . $tourist->id
                ];
            });

        // Pre-select booking if provided in query string
        $selectedBooking = null;
        if ($request->has('booking_id')) {
            $selectedBooking = $bookings->firstWhere('id', $request->booking_id);
        }

        return view('guide.complaints.create', compact('bookings', 'selectedBooking', 'tourists'));
    }

    /**
     * Store new complaint
     */
    public function store(Request $request)
    {
        $guide = Auth::user()->guide;

        $validated = $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'against_user_id' => 'required|exists:users,id',
            'complaint_type' => 'required|in:service_quality,safety_concern,unprofessional_behavior,payment_issue,cancellation_dispute,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'evidence_files.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        // If booking_id is provided, verify it belongs to this guide and get tourist
        if ($request->booking_id) {
            $booking = Booking::where('id', $request->booking_id)
                ->where('guide_id', $guide->id)
                ->firstOrFail();

            // Override against_user_id with the booking's tourist
            $validated['against_user_id'] = $booking->tourist->user_id;
        } else {
            // No booking - against_user_id must be provided from tourist selection
            if (empty($validated['against_user_id'])) {
                return back()->withErrors(['against_user_id' => 'Please select a tourist or booking.'])->withInput();
            }
        }

        // Handle file uploads
        $evidenceFiles = [];
        if ($request->hasFile('evidence_files')) {
            foreach ($request->file('evidence_files') as $file) {
                $path = $file->store('complaint-evidence', 'public');
                $evidenceFiles[] = $path;
            }
        }

        // Create complaint (complaint_number is auto-generated in boot method)
        $complaint = Complaint::create([
            'booking_id' => $validated['booking_id'] ?? null,
            'filed_by' => Auth::id(),
            'filed_by_type' => 'guide',
            'complainant_type' => 'guide',
            'complainant_id' => $guide->id,
            'against_user_id' => $validated['against_user_id'],
            'against_type' => 'tourist', // Guides complain against tourists
            'against_id' => \App\Models\Tourist::where('user_id', $validated['against_user_id'])->first()->id ?? null,
            'complaint_type' => $validated['complaint_type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'evidence_files' => $evidenceFiles,
            'status' => 'open',
            'priority' => 'medium',
            'visible_to_defendant' => true,
        ]);

        return redirect()
            ->route('guide.complaints.show', $complaint)
            ->with('success', 'Complaint filed successfully. Our team will review it shortly.');
    }

    /**
     * Show complaint details
     */
    public function show(Complaint $complaint)
    {
        $guide = Auth::user()->guide;

        // Verify guide can view this complaint
        if (!$complaint->canBeViewedBy($guide, 'guide')) {
            abort(403, 'You do not have permission to view this complaint.');
        }

        // Load relationships
        $complaint->load([
            'booking.tourist.user',
            'booking.guidePlan',
            'assignedAdmin',
            'responses' => function ($query) use ($guide) {
                $query->where(function ($q) use ($guide) {
                    // Show public responses that are visible to this user
                    $q->where('internal_only', false)
                      ->where(function ($subQ) use ($guide) {
                          // Check if guide is complainant
                          $subQ->where(function ($complainantQ) use ($guide) {
                              $complainantQ->whereHas('complaint', function ($cQ) use ($guide) {
                                  $cQ->where('filed_by', Auth::id())
                                     ->orWhere(function ($orQ) use ($guide) {
                                         $orQ->where('complainant_type', 'guide')
                                             ->where('complainant_id', $guide->id);
                                     });
                              })
                              ->where('visible_to_complainant', true);
                          })
                          // Or check if guide is defendant
                          ->orWhere(function ($defendantQ) use ($guide) {
                              $defendantQ->whereHas('complaint', function ($cQ) use ($guide) {
                                  $cQ->where('against_user_id', Auth::id())
                                     ->orWhere(function ($orQ) use ($guide) {
                                         $orQ->where('against_type', 'guide')
                                             ->where('against_id', $guide->id);
                                     });
                              })
                              ->where('visible_to_defendant', true);
                          });
                      });
                })
                ->orderBy('created_at', 'asc');
            }
        ]);

        // Check if guide is the complainant or defendant
        $isComplainant = $complaint->filed_by == Auth::id() ||
            ($complaint->complainant_type === 'guide' && $complaint->complainant_id == $guide->id);

        return view('guide.complaints.show', compact('complaint', 'isComplainant'));
    }

    /**
     * Add response to complaint
     */
    public function addResponse(Request $request, Complaint $complaint)
    {
        $guide = Auth::user()->guide;

        // Verify guide can respond to this complaint
        if (!$complaint->canBeViewedBy($guide, 'guide')) {
            abort(403, 'You do not have permission to respond to this complaint.');
        }

        $validated = $request->validate([
            'response_text' => 'required|string|min:10',
            'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('complaint-attachments', 'public');
                $attachments[] = $path;
            }
        }

        // Determine response type based on guide's role
        $isComplainant = $complaint->filed_by == Auth::id() ||
            ($complaint->complainant_type === 'guide' && $complaint->complainant_id == $guide->id);

        $responseType = $isComplainant ? 'complainant_response' : 'defendant_response';

        // Create response
        ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'responder_type' => 'guide',
            'responder_id' => $guide->id,
            'response_text' => $validated['response_text'],
            'response_type' => $responseType,
            'attachments' => $attachments,
            'visible_to_complainant' => true,
            'visible_to_defendant' => true,
            'internal_only' => false,
        ]);

        return back()->with('success', 'Your response has been added.');
    }

    /**
     * Withdraw complaint (only if still open)
     */
    public function withdraw(Complaint $complaint)
    {
        $guide = Auth::user()->guide;

        // Verify guide is the complainant
        if ($complaint->filed_by !== Auth::id() &&
            !($complaint->complainant_type === 'guide' && $complaint->complainant_id == $guide->id)) {
            abort(403, 'Only the complainant can withdraw a complaint.');
        }

        // Can only withdraw if open
        if ($complaint->status !== 'open') {
            return back()->with('error', 'Only open complaints can be withdrawn.');
        }

        $complaint->update([
            'status' => 'closed',
            'resolution_summary' => 'Withdrawn by complainant',
        ]);

        // Add system response
        ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'responder_type' => 'guide',
            'responder_id' => $guide->id,
            'response_text' => 'Complaint withdrawn by complainant.',
            'response_type' => 'status_update',
            'visible_to_complainant' => true,
            'visible_to_defendant' => true,
            'internal_only' => false,
        ]);

        return redirect()
            ->route('guide.complaints.index')
            ->with('success', 'Complaint withdrawn successfully.');
    }
}
