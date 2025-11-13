<?php

namespace App\Http\Controllers;

use App\Models\GuidePlan;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Get availability calendar data for a plan
     */
    public function getPlanAvailability(Request $request, GuidePlan $plan)
    {
        $month = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();

        $availability = $this->availabilityService->getPlanAvailability($plan, $month);

        return response()->json($availability);
    }

    /**
     * Check if specific dates are available for a plan
     */
    public function checkDates(Request $request, GuidePlan $plan)
    {
        $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $startDate = Carbon::parse($request->input('start_date'));

        $result = $this->availabilityService->checkPlanAvailability($plan, $startDate);

        return response()->json($result);
    }
}
