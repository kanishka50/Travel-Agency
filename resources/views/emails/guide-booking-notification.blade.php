<x-mail::message>
# New Booking Request

Dear {{ $booking->guide->full_name }},

Great news! You have a new booking request for your tour.

## Booking Details

**Booking Number:** {{ $booking->booking_number }}
**Status:** {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
**Tour:** {{ $booking->guidePlan->title }}
**Start Date:** {{ $booking->start_date->format('F d, Y') }}
**End Date:** {{ $booking->end_date->format('F d, Y') }}
**Duration:** {{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days

## Tourist Information

**Name:** {{ $booking->tourist->full_name }}
**Email:** {{ $booking->tourist->user->email }}
@if($booking->tourist->phone)
**Phone:** {{ $booking->tourist->phone }}
@endif
**Country:** {{ $booking->tourist->country ?? 'Not specified' }}

## Group Information

**Number of Adults:** {{ $booking->num_adults }}
**Number of Children:** {{ $booking->num_children }}
@if($booking->num_children > 0 && $booking->children_ages)
**Children Ages:** {{ implode(', ', json_decode($booking->children_ages)) }} years old
@endif

@if($booking->tourist_notes)
## Special Requests

{{ $booking->tourist_notes }}
@endif

## Financial Details

**Subtotal:** ${{ number_format($booking->subtotal, 2) }}
**Your Payout (90%):** ${{ number_format($booking->guide_payout, 2) }}

@if($booking->status === 'pending_payment')
## What's Next?

This booking is currently pending payment. Once the tourist completes the payment, you will receive a confirmation and the tourist's contact details will be fully accessible.

Please keep these dates available in your calendar.
@else
The booking has been confirmed! Please prepare for the tour and contact the tourist if needed.
@endif

<x-mail::button :url="route('guide.dashboard')">
View Dashboard
</x-mail::button>

## Important Reminders

- Review the booking details carefully
- Ensure you're available for the entire duration
- Prepare necessary materials and equipment
- Contact the tourist 24-48 hours before the tour start date
- Review the itinerary and make any necessary adjustments

If you need to cancel or modify this booking, please contact us immediately.

Best regards,
{{ config('app.name') }}
</x-mail::message>
