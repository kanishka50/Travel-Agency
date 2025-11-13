<?php

namespace App\Http\Controllers;

use App\Models\GuidePlan;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class PublicPlanController extends Controller
{
    /**
     * Display a listing of active guide plans (public browse).
     */
    public function index(Request $request)
    {
        // Start with only active plans
        $query = GuidePlan::with(['guide.user'])
            ->where('status', 'active');

        // Search by keyword (title or description)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by destinations (JSON array contains)
        if ($request->filled('destination')) {
            $destination = $request->input('destination');
            $query->whereJsonContains('destinations', $destination);
        }

        // Filter by trip focus/tags (JSON array contains)
        if ($request->filled('focus')) {
            $focus = $request->input('focus');
            $query->whereJsonContains('trip_focus_tags', $focus);
        }

        // Filter by duration (days)
        if ($request->filled('days_min')) {
            $query->where('num_days', '>=', $request->input('days_min'));
        }
        if ($request->filled('days_max')) {
            $query->where('num_days', '<=', $request->input('days_max'));
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('price_per_adult', '>=', $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price_per_adult', '<=', $request->input('price_max'));
        }

        // Filter by group size
        if ($request->filled('group_size')) {
            $groupSize = $request->input('group_size');
            $query->where('max_group_size', '>=', $groupSize)
                ->where('min_group_size', '<=', $groupSize);
        }

        // Filter by availability type
        if ($request->filled('availability')) {
            $query->where('availability_type', $request->input('availability'));
        }

        // Sort options
        $sortBy = $request->input('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price_per_adult', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price_per_adult', 'desc');
                break;
            case 'duration_short':
                $query->orderBy('num_days', 'asc');
                break;
            case 'duration_long':
                $query->orderBy('num_days', 'desc');
                break;
            case 'popular':
                $query->orderBy('booking_count', 'desc')
                    ->orderBy('view_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Paginate results
        $plans = $query->paginate(12)->withQueryString();

        // Get unique destinations and tags for filters
        $allDestinations = $this->getUniqueDestinations();
        $allFocusTags = $this->getUniqueFocusTags();

        // View mode (grid or list)
        $viewMode = $request->input('view', 'grid');

        return view('public.plans.index', compact(
            'plans',
            'allDestinations',
            'allFocusTags',
            'viewMode'
        ));
    }

    /**
     * Display the specified plan (public detail page).
     */
    public function show($id)
    {
        $plan = GuidePlan::with(['guide.user'])
            ->where('status', 'active')
            ->findOrFail($id);

        // Increment view count
        $plan->incrementViewCount();

        // Get related plans (same destinations or focus)
        $relatedPlans = GuidePlan::where('status', 'active')
            ->where('id', '!=', $plan->id)
            ->where(function (Builder $query) use ($plan) {
                // Plans with overlapping destinations
                foreach ($plan->destinations as $destination) {
                    $query->orWhereJsonContains('destinations', $destination);
                }
            })
            ->limit(4)
            ->get();

        return view('public.plans.show', compact('plan', 'relatedPlans'));
    }

    /**
     * Get all unique destinations from active plans.
     */
    private function getUniqueDestinations(): array
    {
        $plans = GuidePlan::where('status', 'active')
            ->select('destinations')
            ->get();

        $destinations = [];
        foreach ($plans as $plan) {
            if (is_array($plan->destinations)) {
                $destinations = array_merge($destinations, $plan->destinations);
            }
        }

        return array_unique($destinations);
    }

    /**
     * Get all unique focus tags from active plans.
     */
    private function getUniqueFocusTags(): array
    {
        $plans = GuidePlan::where('status', 'active')
            ->select('trip_focus_tags')
            ->get();

        $tags = [];
        foreach ($plans as $plan) {
            if (is_array($plan->trip_focus_tags)) {
                $tags = array_merge($tags, $plan->trip_focus_tags);
            }
        }

        return array_unique($tags);
    }
}
