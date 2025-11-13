<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\GuidePlan;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GuidePlanController extends Controller
{
    /**
     * Display a listing of the guide's own plans.
     */
    public function index()
    {
        $guide = $this->getAuthenticatedGuide();

        if (!$guide) {
            return redirect()->route('guide.dashboard')->with('error', 'Guide profile not found.');
        }

        $plans = GuidePlan::where('guide_id', $guide->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total' => $plans->total(),
            'active' => GuidePlan::where('guide_id', $guide->id)->where('status', 'active')->count(),
            'draft' => GuidePlan::where('guide_id', $guide->id)->where('status', 'draft')->count(),
            'inactive' => GuidePlan::where('guide_id', $guide->id)->where('status', 'inactive')->count(),
        ];

        return view('guide.plans.index', compact('plans', 'stats'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create()
    {
        $guide = $this->getAuthenticatedGuide();

        if (!$guide) {
            return redirect()->route('guide.dashboard')->with('error', 'Guide profile not found.');
        }

        $plan = null; // No plan exists yet for create form

        return view('guide.plans.create', compact('plan'));
    }

    /**
     * Store a newly created plan in storage.
     */
    public function store(Request $request)
    {
        $guide = $this->getAuthenticatedGuide();

        if (!$guide) {
            return redirect()->route('guide.dashboard')->with('error', 'Guide profile not found.');
        }

        $validated = $this->validatePlan($request);
        $validated['guide_id'] = $guide->id;

        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            $validated['cover_photo'] = $request->file('cover_photo')->store('guide-plans/covers', 'public');
        }

        // Convert certain fields to arrays for JSON storage
        // Try to use the JSON from hidden field, fallback to parsing text field
        $destinations = json_decode($request->input('destinations', ''), true);
        if (empty($destinations) && $request->filled('destinations_text')) {
            $destinations = array_map('trim', explode(',', $request->input('destinations_text')));
            $destinations = array_filter($destinations); // Remove empty values
        }
        $validated['destinations'] = $destinations ?: [];

        $tripFocusTags = json_decode($request->input('trip_focus_tags', ''), true);
        if (empty($tripFocusTags) && $request->filled('trip_focus_tags_text')) {
            $tripFocusTags = array_map('trim', explode(',', $request->input('trip_focus_tags_text')));
            $tripFocusTags = array_filter($tripFocusTags); // Remove empty values
        }
        $validated['trip_focus_tags'] = $tripFocusTags ?: [];

        $validated['dietary_options'] = $request->input('dietary_options', []);

        $plan = GuidePlan::create($validated);

        return redirect()->route('guide.plans.show', $plan)
            ->with('success', 'Tour plan created successfully!');
    }

    /**
     * Display the specified plan.
     */
    public function show(GuidePlan $plan)
    {
        $this->authorizeGuidePlan($plan);

        return view('guide.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(GuidePlan $plan)
    {
        $this->authorizeGuidePlan($plan);

        return view('guide.plans.edit', compact('plan'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, GuidePlan $plan)
    {
        $this->authorizeGuidePlan($plan);

        $validated = $this->validatePlan($request, $plan->id);

        // Handle cover photo upload
        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo if exists
            if ($plan->cover_photo) {
                Storage::disk('public')->delete($plan->cover_photo);
            }
            $validated['cover_photo'] = $request->file('cover_photo')->store('guide-plans/covers', 'public');
        }

        // Convert certain fields to arrays for JSON storage
        // Try to use the JSON from hidden field, fallback to parsing text field
        $destinations = json_decode($request->input('destinations', ''), true);
        if (empty($destinations) && $request->filled('destinations_text')) {
            $destinations = array_map('trim', explode(',', $request->input('destinations_text')));
            $destinations = array_filter($destinations); // Remove empty values
        }
        $validated['destinations'] = $destinations ?: [];

        $tripFocusTags = json_decode($request->input('trip_focus_tags', ''), true);
        if (empty($tripFocusTags) && $request->filled('trip_focus_tags_text')) {
            $tripFocusTags = array_map('trim', explode(',', $request->input('trip_focus_tags_text')));
            $tripFocusTags = array_filter($tripFocusTags); // Remove empty values
        }
        $validated['trip_focus_tags'] = $tripFocusTags ?: [];

        $validated['dietary_options'] = $request->input('dietary_options', []);

        $plan->update($validated);

        return redirect()->route('guide.plans.show', $plan)
            ->with('success', 'Tour plan updated successfully!');
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroy(GuidePlan $plan)
    {
        $this->authorizeGuidePlan($plan);

        // Check if plan has bookings
        if ($plan->booking_count > 0) {
            return back()->with('error', 'Cannot delete a plan with existing bookings.');
        }

        // Delete cover photo if exists
        if ($plan->cover_photo) {
            Storage::disk('public')->delete($plan->cover_photo);
        }

        $plan->delete();

        return redirect()->route('guide.plans.index')
            ->with('success', 'Tour plan deleted successfully!');
    }

    /**
     * Duplicate the specified plan.
     */
    public function duplicate(GuidePlan $plan)
    {
        $this->authorizeGuidePlan($plan);

        $newPlan = $plan->replicate();
        $newPlan->title = $plan->title . ' (Copy)';
        $newPlan->status = 'draft';
        $newPlan->view_count = 0;
        $newPlan->booking_count = 0;
        $newPlan->save();

        return redirect()->route('guide.plans.edit', $newPlan)
            ->with('success', 'Tour plan duplicated successfully! You can now edit it.');
    }

    /**
     * Change the status of the specified plan.
     */
    public function updateStatus(Request $request, GuidePlan $plan)
    {
        $this->authorizeGuidePlan($plan);

        $request->validate([
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
        ]);

        $plan->update(['status' => $request->status]);

        $statusLabel = ucfirst($request->status);
        return back()->with('success', "Plan status changed to {$statusLabel}.");
    }

    /**
     * Get the authenticated guide.
     */
    private function getAuthenticatedGuide(): ?Guide
    {
        $user = Auth::user();

        if (!$user || $user->user_type !== 'guide') {
            return null;
        }

        return Guide::where('user_id', $user->id)->first();
    }

    /**
     * Authorize that the guide owns the plan.
     */
    private function authorizeGuidePlan(GuidePlan $plan): void
    {
        $guide = $this->getAuthenticatedGuide();

        if (!$guide || $plan->guide_id !== $guide->id) {
            abort(403, 'Unauthorized access to this plan.');
        }
    }

    /**
     * Validate plan data.
     */
    private function validatePlan(Request $request, ?int $planId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'num_days' => ['required', 'integer', 'min:1', 'max:30'],
            'num_nights' => ['required', 'integer', 'min:0', 'max:30'],
            'pickup_location' => ['required', 'string', 'max:255'],
            'dropoff_location' => ['required', 'string', 'max:255'],
            'destinations' => ['nullable', 'json'], // Changed to accept JSON string
            'destinations_text' => ['required', 'string'], // Accept text input
            'trip_focus_tags' => ['nullable', 'json'], // Changed to accept JSON string
            'trip_focus_tags_text' => ['required', 'string'], // Accept text input
            'price_per_adult' => ['required', 'numeric', 'min:0'],
            'price_per_child' => ['required', 'numeric', 'min:0'],
            'min_group_size' => ['required', 'integer', 'min:1', 'max:50'],
            'max_group_size' => ['required', 'integer', 'min:1', 'max:50'],
            'availability_type' => ['required', Rule::in(['always_available', 'date_range'])],
            'available_start_date' => ['nullable', 'date', 'required_if:availability_type,date_range'],
            'available_end_date' => ['nullable', 'date', 'after:available_start_date', 'required_if:availability_type,date_range'],
            'vehicle_type' => ['nullable', 'string', 'max:100'],
            'vehicle_category' => ['nullable', 'string'],
            'vehicle_capacity' => ['nullable', 'integer', 'min:1', 'max:50'],
            'vehicle_ac' => ['boolean'],
            'vehicle_description' => ['nullable', 'string'],
            'dietary_options' => ['nullable', 'array'],
            'accessibility_info' => ['nullable', 'string'],
            'cancellation_policy' => ['nullable', 'string'],
            'inclusions' => ['required', 'string'],
            'exclusions' => ['required', 'string'],
            'cover_photo' => ['nullable', 'image', 'max:5120'], // 5MB max
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
        ]);
    }
}
