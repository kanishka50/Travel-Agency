<?php

namespace App\Http\Controllers;

use App\Models\TouristRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TouristRequestController extends Controller
{
    /**
     * Display a list of tourist's requests
     */
    public function index()
    {
        $tourist = Auth::user()->tourist;

        $requests = TouristRequest::where('tourist_id', $tourist->id)
            ->with(['bids' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tourist.requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new request
     */
    public function create()
    {
        return view('tourist.requests.create');
    }

    /**
     * Store a newly created request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'duration_days' => 'required|integer|min:1|max:90',
            'preferred_destinations' => 'required|array|min:1',
            'preferred_destinations.*' => 'required|string',
            'must_visit_places' => 'nullable|string',
            'num_adults' => 'required|integer|min:1|max:50',
            'num_children' => 'required|integer|min:0|max:50',
            'children_ages' => 'nullable|array',
            'children_ages.*' => 'integer|min:0|max:17',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'dates_flexible' => 'boolean',
            'flexibility_range' => 'nullable|string|max:255',
            'budget_min' => 'required|numeric|min:50',
            'budget_max' => 'required|numeric|min:50|gte:budget_min',
            'trip_focus' => 'required|array|min:1',
            'trip_focus.*' => 'required|string',
            'transport_preference' => 'nullable|in:public_transport,private_vehicle,luxury_vehicle,no_preference',
            'accommodation_preference' => 'nullable|in:budget,midrange,luxury,mixed',
            'dietary_requirements' => 'nullable|array',
            'dietary_requirements.*' => 'string',
            'accessibility_needs' => 'nullable|string',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $tourist = Auth::user()->tourist;

            // Create the request
            $touristRequest = TouristRequest::create([
                'tourist_id' => $tourist->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'duration_days' => $validated['duration_days'],
                'preferred_destinations' => $validated['preferred_destinations'],
                'must_visit_places' => $validated['must_visit_places'] ?? null,
                'num_adults' => $validated['num_adults'],
                'num_children' => $validated['num_children'],
                'children_ages' => $validated['children_ages'] ?? null,
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'dates_flexible' => $request->has('dates_flexible'),
                'flexibility_range' => $validated['flexibility_range'] ?? null,
                'budget_min' => $validated['budget_min'],
                'budget_max' => $validated['budget_max'],
                'trip_focus' => $validated['trip_focus'],
                'transport_preference' => $validated['transport_preference'] ?? null,
                'accommodation_preference' => $validated['accommodation_preference'] ?? null,
                'dietary_requirements' => $validated['dietary_requirements'] ?? null,
                'accessibility_needs' => $validated['accessibility_needs'] ?? null,
                'special_requests' => $validated['special_requests'] ?? null,
                'status' => 'open',
                'expires_at' => Carbon::now()->addDays(14), // Auto-expire after 14 days
            ]);

            DB::commit();

            return redirect()
                ->route('tourist.requests.show', $touristRequest->id)
                ->with('success', 'Your tour request has been posted! Guides will start submitting proposals soon.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create tourist request: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create request. Please try again.');
        }
    }

    /**
     * Display the specified request with all bids
     */
    public function show(TouristRequest $touristRequest)
    {
        // Ensure the request belongs to the authenticated tourist
        $tourist = Auth::user()->tourist;

        if ($touristRequest->tourist_id !== $tourist->id) {
            abort(403, 'Unauthorized access to this request.');
        }

        // Load bids with guide information
        $touristRequest->load(['bids.guide.user', 'bids' => function($query) {
            $query->orderBy('total_price', 'asc'); // Show cheapest first
        }]);

        return view('tourist.requests.show', compact('touristRequest'));
    }

    /**
     * Show the form for editing the request
     */
    public function edit(TouristRequest $touristRequest)
    {
        $tourist = Auth::user()->tourist;

        if ($touristRequest->tourist_id !== $tourist->id) {
            abort(403, 'Unauthorized access to this request.');
        }

        // Only allow editing if no bids have been received
        if ($touristRequest->bid_count > 0) {
            return redirect()
                ->route('tourist.requests.show', $touristRequest->id)
                ->with('error', 'Cannot edit request after receiving bids.');
        }

        return view('tourist.requests.edit', compact('touristRequest'));
    }

    /**
     * Update the specified request
     */
    public function update(Request $request, TouristRequest $touristRequest)
    {
        $tourist = Auth::user()->tourist;

        if ($touristRequest->tourist_id !== $tourist->id) {
            abort(403, 'Unauthorized access to this request.');
        }

        // Only allow editing if no bids have been received
        if ($touristRequest->bid_count > 0) {
            return redirect()
                ->route('tourist.requests.show', $touristRequest->id)
                ->with('error', 'Cannot edit request after receiving bids.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'duration_days' => 'required|integer|min:1|max:90',
            'preferred_destinations' => 'required|array|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $touristRequest->update($validated);

        return redirect()
            ->route('tourist.requests.show', $touristRequest->id)
            ->with('success', 'Request updated successfully.');
    }

    /**
     * Close the request (stop accepting bids)
     */
    public function close(TouristRequest $touristRequest)
    {
        $tourist = Auth::user()->tourist;

        if ($touristRequest->tourist_id !== $tourist->id) {
            abort(403, 'Unauthorized access to this request.');
        }

        $touristRequest->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()
            ->route('tourist.requests.show', $touristRequest->id)
            ->with('success', 'Request closed successfully. No more bids will be accepted.');
    }

    /**
     * Delete the request
     */
    public function destroy(TouristRequest $touristRequest)
    {
        $tourist = Auth::user()->tourist;

        if ($touristRequest->tourist_id !== $tourist->id) {
            abort(403, 'Unauthorized access to this request.');
        }

        // Only allow deletion if no bids or all bids are rejected
        $pendingBids = $touristRequest->bids()->where('status', 'pending')->count();

        if ($pendingBids > 0) {
            return redirect()
                ->route('tourist.requests.show', $touristRequest->id)
                ->with('error', 'Cannot delete request with pending bids. Please reject or accept them first.');
        }

        $touristRequest->delete();

        return redirect()
            ->route('tourist.requests.index')
            ->with('success', 'Request deleted successfully.');
    }
}
