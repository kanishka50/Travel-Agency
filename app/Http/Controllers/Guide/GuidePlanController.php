<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\GuidePlan;
use App\Models\GuidePlanPhoto;
use App\Models\GuidePlanItinerary;
use App\Models\GuidePlanAddon;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Handle gallery photos upload
        if ($request->hasFile('gallery_photos')) {
            $this->uploadGalleryPhotos($plan, $request->file('gallery_photos'));
        }

        // Handle itineraries
        if ($request->has('itineraries')) {
            $this->saveItineraries($plan, $request->input('itineraries', []));
        }

        // Handle add-ons
        if ($request->has('addons')) {
            $this->saveAddons($plan, $request->input('addons', []));
        }

        return redirect()->route('guide.plans.show', $plan)
            ->with('success', 'Tour plan created successfully!');
    }

    /**
     * Display the specified plan.
     */
    public function show(GuidePlan $plan)
    {
        $this->authorizeGuidePlan($plan);

        $plan->load(['photos', 'itineraries', 'addons']);

        return view('guide.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(GuidePlan $plan)
    {
        $this->authorizeGuidePlan($plan);

        $plan->load(['photos', 'itineraries', 'addons']);

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

        // Handle photo deletions
        if ($request->has('delete_photos')) {
            $this->deleteGalleryPhotos($plan, $request->input('delete_photos', []));
        }

        // Handle new gallery photos upload
        if ($request->hasFile('gallery_photos')) {
            $this->uploadGalleryPhotos($plan, $request->file('gallery_photos'));
        }

        // Handle photo reordering
        if ($request->has('photo_order')) {
            $this->reorderPhotos($plan, $request->input('photo_order', []));
        }

        // Handle itineraries
        if ($request->has('itineraries')) {
            $this->saveItineraries($plan, $request->input('itineraries', []));
        }

        // Handle add-ons
        if ($request->has('addons')) {
            $this->saveAddons($plan, $request->input('addons', []));
        }

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

        // Gallery photos will be deleted automatically via model boot method
        // But we need to delete files first
        foreach ($plan->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
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

        // Duplicate itineraries
        foreach ($plan->itineraries as $itinerary) {
            $newItinerary = $itinerary->replicate();
            $newItinerary->guide_plan_id = $newPlan->id;
            $newItinerary->save();
        }

        // Duplicate add-ons
        foreach ($plan->addons as $addon) {
            $newAddon = $addon->replicate();
            $newAddon->guide_plan_id = $newPlan->id;
            $newAddon->save();
        }

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
            'vehicle_capacity' => ['nullable', 'integer', 'min:1', 'max:50'],
            'vehicle_ac' => ['boolean'],
            'vehicle_description' => ['nullable', 'string'],
            'dietary_options' => ['nullable', 'array'],
            'accessibility_info' => ['nullable', 'string'],
            'inclusions' => ['required', 'string'],
            'exclusions' => ['required', 'string'],
            'cover_photo' => ['nullable', 'image', 'max:5120'], // 5MB max
            'gallery_photos' => ['nullable', 'array', 'max:25'], // Max 25 photos
            'gallery_photos.*' => ['image', 'max:5120'], // Each photo max 5MB
            'delete_photos' => ['nullable', 'array'],
            'delete_photos.*' => ['integer'],
            'photo_order' => ['nullable', 'array'],
            'photo_order.*' => ['integer'],
            'status' => ['required', Rule::in(['draft', 'active', 'inactive'])],
            'allow_proposals' => ['nullable', 'boolean'],
            'min_proposal_price' => ['nullable', 'numeric', 'min:0'],
            // Itinerary validation
            'itineraries' => ['nullable', 'array'],
            'itineraries.*.day_number' => ['required_with:itineraries', 'integer', 'min:1'],
            'itineraries.*.day_title' => ['required_with:itineraries', 'string', 'max:255'],
            'itineraries.*.description' => ['required_with:itineraries', 'string'],
            'itineraries.*.accommodation_name' => ['nullable', 'string', 'max:255'],
            'itineraries.*.accommodation_type' => ['nullable', Rule::in(['hotel', 'guesthouse', 'resort', 'homestay', 'camping', 'other'])],
            'itineraries.*.accommodation_tier' => ['nullable', Rule::in(['budget', 'midrange', 'luxury'])],
            'itineraries.*.breakfast_included' => ['nullable', 'boolean'],
            'itineraries.*.lunch_included' => ['nullable', 'boolean'],
            'itineraries.*.dinner_included' => ['nullable', 'boolean'],
            'itineraries.*.meal_notes' => ['nullable', 'string'],
            // Add-on validation
            'addons' => ['nullable', 'array'],
            'addons.*.addon_name' => ['required_with:addons', 'string', 'max:255'],
            'addons.*.addon_description' => ['required_with:addons', 'string'],
            'addons.*.day_number' => ['nullable', 'integer', 'min:0'],
            'addons.*.price_per_person' => ['required_with:addons', 'numeric', 'min:0'],
            'addons.*.require_all_participants' => ['nullable', 'boolean'],
            'addons.*.max_participants' => ['nullable', 'integer', 'min:1'],
        ]);
    }

    /**
     * Upload gallery photos for a plan.
     */
    private function uploadGalleryPhotos(GuidePlan $plan, array $photos): void
    {
        // Get the current max display order
        $maxOrder = $plan->photos()->max('display_order') ?? -1;

        foreach ($photos as $index => $photo) {
            $path = $photo->store('guide-plans/photos/' . $plan->id, 'public');

            $plan->photos()->create([
                'photo_path' => $path,
                'display_order' => $maxOrder + $index + 1,
            ]);
        }
    }

    /**
     * Delete gallery photos for a plan.
     */
    private function deleteGalleryPhotos(GuidePlan $plan, array $photoIds): void
    {
        foreach ($photoIds as $photoId) {
            $photo = GuidePlanPhoto::find($photoId);
            if ($photo && $photo->guide_plan_id === $plan->id) {
                // File deletion is handled by the model's boot method
                $photo->delete();
            }
        }
    }

    /**
     * Reorder gallery photos for a plan.
     */
    private function reorderPhotos(GuidePlan $plan, array $photoOrder): void
    {
        foreach ($photoOrder as $order => $photoId) {
            GuidePlanPhoto::where('id', $photoId)
                ->where('guide_plan_id', $plan->id)
                ->update(['display_order' => $order]);
        }
    }

    /**
     * Delete a single photo via AJAX.
     */
    public function deletePhoto(GuidePlan $plan, GuidePlanPhoto $photo)
    {
        $this->authorizeGuidePlan($plan);

        if ($photo->guide_plan_id !== $plan->id) {
            abort(403, 'Unauthorized access to this photo.');
        }

        $photo->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Photo deleted successfully.']);
        }

        return back()->with('success', 'Photo deleted successfully.');
    }

    /**
     * Save itineraries for a plan (creates, updates, and deletes as needed).
     */
    private function saveItineraries(GuidePlan $plan, array $itineraries): void
    {
        // Get existing itinerary IDs
        $existingIds = $plan->itineraries()->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($itineraries as $itineraryData) {
            // Skip empty itinerary entries
            if (empty($itineraryData['day_title']) || empty($itineraryData['description'])) {
                continue;
            }

            $data = [
                'guide_plan_id' => $plan->id,
                'day_number' => $itineraryData['day_number'] ?? 1,
                'day_title' => $itineraryData['day_title'],
                'description' => $itineraryData['description'],
                'accommodation_name' => $itineraryData['accommodation_name'] ?? null,
                'accommodation_type' => $itineraryData['accommodation_type'] ?? null,
                'accommodation_tier' => $itineraryData['accommodation_tier'] ?? null,
                'breakfast_included' => isset($itineraryData['breakfast_included']) && $itineraryData['breakfast_included'],
                'lunch_included' => isset($itineraryData['lunch_included']) && $itineraryData['lunch_included'],
                'dinner_included' => isset($itineraryData['dinner_included']) && $itineraryData['dinner_included'],
                'meal_notes' => $itineraryData['meal_notes'] ?? null,
            ];

            if (!empty($itineraryData['id'])) {
                // Update existing itinerary
                $itinerary = GuidePlanItinerary::find($itineraryData['id']);
                if ($itinerary && $itinerary->guide_plan_id === $plan->id) {
                    $itinerary->update($data);
                    $submittedIds[] = $itinerary->id;
                }
            } else {
                // Create new itinerary
                $newItinerary = $plan->itineraries()->create($data);
                $submittedIds[] = $newItinerary->id;
            }
        }

        // Delete itineraries that were not submitted (removed by user)
        $idsToDelete = array_diff($existingIds, $submittedIds);
        if (!empty($idsToDelete)) {
            GuidePlanItinerary::whereIn('id', $idsToDelete)
                ->where('guide_plan_id', $plan->id)
                ->delete();
        }
    }

    /**
     * Save add-ons for a plan (creates, updates, and deletes as needed).
     */
    private function saveAddons(GuidePlan $plan, array $addons): void
    {
        // Get existing addon IDs
        $existingIds = $plan->addons()->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($addons as $addonData) {
            // Skip empty addon entries
            if (empty($addonData['addon_name']) || empty($addonData['addon_description'])) {
                continue;
            }

            $data = [
                'guide_plan_id' => $plan->id,
                'addon_name' => $addonData['addon_name'],
                'addon_description' => $addonData['addon_description'],
                'day_number' => $addonData['day_number'] ?? 0,
                'price_per_person' => $addonData['price_per_person'] ?? 0,
                'require_all_participants' => isset($addonData['require_all_participants']) && $addonData['require_all_participants'],
                'max_participants' => !empty($addonData['max_participants']) ? $addonData['max_participants'] : null,
            ];

            if (!empty($addonData['id'])) {
                // Update existing addon
                $addon = GuidePlanAddon::find($addonData['id']);
                if ($addon && $addon->guide_plan_id === $plan->id) {
                    $addon->update($data);
                    $submittedIds[] = $addon->id;
                }
            } else {
                // Create new addon
                $newAddon = $plan->addons()->create($data);
                $submittedIds[] = $newAddon->id;
            }
        }

        // Delete addons that were not submitted (removed by user)
        $idsToDelete = array_diff($existingIds, $submittedIds);
        if (!empty($idsToDelete)) {
            GuidePlanAddon::whereIn('id', $idsToDelete)
                ->where('guide_plan_id', $plan->id)
                ->delete();
        }
    }
}
