<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use App\Models\Booking;
use App\Models\Tourist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TouristComplaintController extends Controller
{
    /**
     * Display list of tourist's complaints
     */
    public function index()
    {
        $tourist = Auth::user()->tourist;

        $complaints = Complaint::where(function ($query) use ($tourist) {
            $query->where('filed_by', Auth::id())
                  ->orWhere(function ($q) use ($tourist) {
                      $q->where('complainant_type', 'tourist')
                        ->where('complainant_id', $tourist->id);
                  });
        })
        ->orWhere(function ($query) use ($tourist) {
            $query->where('against_user_id', Auth::id())
                  ->where('visible_to_defendant', true)
                  ->orWhere(function ($q) use ($tourist) {
                      $q->where('against_type', 'tourist')
                        ->where('against_id', $tourist->id)
                        ->where('visible_to_defendant', true);
                  });
        })
        ->with(['booking', 'assignedAdmin', 'responses'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        // Calculate statistics
        $stats = [
            'total' => Complaint::where('filed_by', Auth::id())->count(),
            'open' => Complaint::where('filed_by', Auth::id())->where('status', 'open')->count(),
            'under_review' => Complaint::where('filed_by', Auth::id())->where('status', 'under_review')->count(),
            'resolved' => Complaint::where('filed_by', Auth::id())->where('status', 'resolved')->count(),
        ];

        return view('tourist.complaints.index', compact('complaints', 'stats'));
    }

    /**
     * Show form to create new complaint
     */
    public function create(Request $request)
    {
        $tourist = Auth::user()->tourist;

        // Get tourist's bookings that can be complained about
        $bookings = Booking::where('tourist_id', $tourist->id)
            ->whereIn('status', ['confirmed', 'completed', 'cancelled'])
            ->with('guide.user', 'guidePlan')
            ->orderBy('start_date', 'desc')
            ->get();

        // Get all active guides for manual selection
        $guides = \App\Models\Guide::with('user')
            ->whereHas('user', function($query) {
                $query->where('status', 'active');
            })
            ->get()
            ->map(function($guide) {
                return [
                    'id' => $guide->user_id,
                    'name' => $guide->user->name ?? 'Guide #' . $guide->id
                ];
            });

        // Pre-select booking if provided in query string
        $selectedBooking = null;
        if ($request->has('booking_id')) {
            $selectedBooking = $bookings->firstWhere('id', $request->booking_id);
        }

        return view('tourist.complaints.create', compact('bookings', 'selectedBooking', 'guides'));
    }

    /**
     * Store new complaint
     */
    public function store(Request $request)
    {
        $tourist = Auth::user()->tourist;

        $validated = $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'against_user_id' => 'required|exists:users,id',
            'complaint_type' => 'required|in:service_quality,safety_concern,unprofessional_behavior,payment_issue,cancellation_dispute,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'evidence_files.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        // If booking_id is provided, verify it belongs to this tourist and get guide
        if ($request->booking_id) {
            $booking = Booking::where('id', $request->booking_id)
                ->where('tourist_id', $tourist->id)
                ->firstOrFail();

            // Override against_user_id with the booking's guide
            $validated['against_user_id'] = $booking->guide->user_id;
        } else {
            // No booking - against_user_id must be provided from guide selection
            if (empty($validated['against_user_id'])) {
                return back()->withErrors(['against_user_id' => 'Please select a guide or booking.'])->withInput();
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
            'filed_by_type' => 'tourist',
            'complainant_type' => 'tourist',
            'complainant_id' => $tourist->id,
            'against_user_id' => $validated['against_user_id'],
            'against_type' => 'guide', // Tourists complain against guides
            'against_id' => \App\Models\Guide::where('user_id', $validated['against_user_id'])->first()->id ?? null,
            'complaint_type' => $validated['complaint_type'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'evidence_files' => $evidenceFiles,
            'status' => 'open',
            'priority' => 'medium',
            'visible_to_defendant' => true,
        ]);

        return redirect()
            ->route('tourist.complaints.show', $complaint)
            ->with('success', 'Complaint filed successfully. Our team will review it shortly.');
    }

    /**
     * Show complaint details
     */
    public function show(Complaint $complaint)
    {
        $tourist = Auth::user()->tourist;

        // Verify tourist can view this complaint
        if (!$complaint->canBeViewedBy($tourist, 'tourist')) {
            abort(403, 'You do not have permission to view this complaint.');
        }

        // Load relationships
        $complaint->load([
            'booking.guide.user',
            'booking.guidePlan',
            'assignedAdmin',
            'responses' => function ($query) use ($tourist) {
                $query->where(function ($q) use ($tourist) {
                    // Show public responses that are visible to this user
                    $q->where('internal_only', false)
                      ->where(function ($subQ) use ($tourist) {
                          // Check if tourist is complainant
                          $subQ->where(function ($complainantQ) use ($tourist) {
                              $complainantQ->whereHas('complaint', function ($cQ) use ($tourist) {
                                  $cQ->where('filed_by', Auth::id())
                                     ->orWhere(function ($orQ) use ($tourist) {
                                         $orQ->where('complainant_type', 'tourist')
                                             ->where('complainant_id', $tourist->id);
                                     });
                              })
                              ->where('visible_to_complainant', true);
                          })
                          // Or check if tourist is defendant
                          ->orWhere(function ($defendantQ) use ($tourist) {
                              $defendantQ->whereHas('complaint', function ($cQ) use ($tourist) {
                                  $cQ->where('against_user_id', Auth::id())
                                     ->orWhere(function ($orQ) use ($tourist) {
                                         $orQ->where('against_type', 'tourist')
                                             ->where('against_id', $tourist->id);
                                     });
                              })
                              ->where('visible_to_defendant', true);
                          });
                      });
                })
                ->orderBy('created_at', 'asc');
            }
        ]);

        // Check if tourist is the complainant or defendant
        $isComplainant = $complaint->filed_by == Auth::id() ||
            ($complaint->complainant_type === 'tourist' && $complaint->complainant_id == $tourist->id);

        return view('tourist.complaints.show', compact('complaint', 'isComplainant'));
    }

    /**
     * Add response to complaint
     */
    public function addResponse(Request $request, Complaint $complaint)
    {
        $tourist = Auth::user()->tourist;

        // Verify tourist can respond to this complaint
        if (!$complaint->canBeViewedBy($tourist, 'tourist')) {
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

        // Determine response type based on tourist's role
        $isComplainant = $complaint->filed_by == Auth::id() ||
            ($complaint->complainant_type === 'tourist' && $complaint->complainant_id == $tourist->id);

        $responseType = $isComplainant ? 'complainant_response' : 'defendant_response';

        // Create response
        ComplaintResponse::create([
            'complaint_id' => $complaint->id,
            'responder_type' => 'tourist',
            'responder_id' => $tourist->id,
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
        $tourist = Auth::user()->tourist;

        // Verify tourist is the complainant
        if ($complaint->filed_by !== Auth::id() &&
            !($complaint->complainant_type === 'tourist' && $complaint->complainant_id == $tourist->id)) {
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
            'responder_type' => 'tourist',
            'responder_id' => $tourist->id,
            'response_text' => 'Complaint withdrawn by complainant.',
            'response_type' => 'status_update',
            'visible_to_complainant' => true,
            'visible_to_defendant' => true,
            'internal_only' => false,
        ]);

        return redirect()
            ->route('tourist.complaints.index')
            ->with('success', 'Complaint withdrawn successfully.');
    }
}
