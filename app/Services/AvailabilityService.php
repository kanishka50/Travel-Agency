<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\GuidePlan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AvailabilityService
{
    /**
     * Check if a guide is available for specific dates
     *
     * @param int $guideId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return bool
     */
    public function isGuideAvailable(int $guideId, Carbon $startDate, Carbon $endDate): bool
    {
        // Get all confirmed bookings for this guide that overlap with the requested dates
        $conflictingBookings = Booking::where('guide_id', $guideId)
            ->whereIn('status', ['confirmed', 'ongoing'])
            ->where(function ($query) use ($startDate, $endDate) {
                // Check if there's any overlap
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        // Check if the requested dates fall within an existing booking
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        return !$conflictingBookings;
    }

    /**
     * Get all booked dates for a guide
     *
     * @param int $guideId
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return array Array of date ranges with booking info
     */
    public function getGuideBookedDates(int $guideId, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = Booking::where('guide_id', $guideId)
            ->whereIn('status', ['confirmed', 'ongoing']);

        if ($startDate && $endDate) {
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($subQ) use ($startDate, $endDate) {
                      $subQ->where('start_date', '<=', $startDate)
                           ->where('end_date', '>=', $endDate);
                  });
            });
        }

        $bookings = $query->get();

        $bookedRanges = [];
        foreach ($bookings as $booking) {
            $bookedRanges[] = [
                'start' => $booking->start_date->format('Y-m-d'),
                'end' => $booking->end_date->addDay()->format('Y-m-d'), // FullCalendar end date is exclusive
                'title' => 'Booked',
                'color' => '#ef4444', // red
                'booking_id' => $booking->id,
            ];
        }

        return $bookedRanges;
    }

    /**
     * Get availability calendar data for a plan
     *
     * @param GuidePlan $plan
     * @param Carbon|null $month Start of month to check
     * @return array
     */
    public function getPlanAvailability(GuidePlan $plan, ?Carbon $month = null): array
    {
        $month = $month ?: Carbon::now();
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        // Get booked dates for this guide
        $bookedDates = $this->getGuideBookedDates(
            $plan->guide_id,
            $startOfMonth,
            $endOfMonth
        );

        // For date_range plans, add the available range
        $availableRange = null;
        if ($plan->availability_type === 'date_range' && $plan->available_start_date && $plan->available_end_date) {
            $availableRange = [
                'start' => $plan->available_start_date->format('Y-m-d'),
                'end' => $plan->available_end_date->addDay()->format('Y-m-d'), // Exclusive end
            ];
        }

        return [
            'booked_dates' => $bookedDates,
            'available_range' => $availableRange,
            'availability_type' => $plan->availability_type,
            'plan_duration' => $plan->num_days,
        ];
    }

    /**
     * Check if specific dates are available for a plan
     *
     * @param GuidePlan $plan
     * @param Carbon $startDate
     * @return array ['available' => bool, 'message' => string]
     */
    public function checkPlanAvailability(GuidePlan $plan, Carbon $startDate): array
    {
        $endDate = $startDate->copy()->addDays($plan->num_days - 1);

        // Check if plan is within available date range (for seasonal plans)
        if ($plan->availability_type === 'date_range') {
            if (!$plan->available_start_date || !$plan->available_end_date) {
                return [
                    'available' => false,
                    'message' => 'This tour does not have availability dates configured.',
                ];
            }

            if ($startDate->lt($plan->available_start_date) || $endDate->gt($plan->available_end_date)) {
                return [
                    'available' => false,
                    'message' => 'Selected dates are outside the available season (' .
                        $plan->available_start_date->format('M d, Y') . ' - ' .
                        $plan->available_end_date->format('M d, Y') . ').',
                ];
            }
        }

        // Check if dates are in the past
        if ($startDate->lt(Carbon::today())) {
            return [
                'available' => false,
                'message' => 'Cannot book dates in the past.',
            ];
        }

        // Check if guide is available for these dates
        if (!$this->isGuideAvailable($plan->guide_id, $startDate, $endDate)) {
            return [
                'available' => false,
                'message' => 'The guide is not available for the selected dates. Please choose different dates.',
            ];
        }

        return [
            'available' => true,
            'message' => 'Dates are available!',
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
    }

    /**
     * Get all individual dates within a date range
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public function getDatesBetween(Carbon $startDate, Carbon $endDate): array
    {
        $period = CarbonPeriod::create($startDate, $endDate);
        $dates = [];

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }
}
