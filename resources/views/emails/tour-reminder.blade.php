<x-mail::message>
# Your Tour Starts in 7 Days!

@if($recipientType === 'tourist')
Dear {{ $booking->tourist->full_name }},

We're excited to remind you that your amazing tour **{{ $booking->guidePlan->title }}** is coming up in just **7 days**!

## Tour Details

<x-mail::panel>
**Booking Number:** {{ $booking->booking_number }}
**Tour:** {{ $booking->guidePlan->title }}
**Start Date:** {{ $booking->start_date->format('l, F j, Y') }}
**End Date:** {{ $booking->end_date->format('l, F j, Y') }}
**Duration:** {{ $booking->guidePlan->num_days }} days / {{ $booking->guidePlan->num_nights }} nights
**Travelers:** {{ $booking->num_adults }} adult(s)@if($booking->num_children > 0), {{ $booking->num_children }} child(ren)@endif
</x-mail::panel>

## Your Guide

**Name:** {{ $booking->guide->full_name }}
**Phone:** {{ $booking->guide->phone }}
**Email:** {{ $booking->guide->user->email }}

## Preparation Checklist

- [ ] Check weather forecast for {{ $booking->guidePlan->pickup_location }}
- [ ] Pack comfortable clothing and walking shoes
- [ ] Bring sunscreen and hat
- [ ] Carry a water bottle
- [ ] Have your booking number ready: **{{ $booking->booking_number }}**
- [ ] Confirm pickup location with your guide

<x-mail::button :url="route('bookings.show', $booking->id)">
View Full Booking Details
</x-mail::button>

Your guide may contact you in the next few days to confirm pickup details and answer any last-minute questions.

We hope you have an unforgettable experience!

@else
Dear {{ $booking->guide->full_name }},

This is a reminder that you have an upcoming tour starting in **7 days**.

## Booking Details

<x-mail::panel>
**Booking Number:** {{ $booking->booking_number }}
**Tour:** {{ $booking->guidePlan->title }}
**Start Date:** {{ $booking->start_date->format('l, F j, Y') }}
**End Date:** {{ $booking->end_date->format('l, F j, Y') }}
**Duration:** {{ $booking->guidePlan->num_days }} days
**Travelers:** {{ $booking->num_adults }} adult(s)@if($booking->num_children > 0), {{ $booking->num_children }} child(ren)@endif
</x-mail::panel>

## Tourist Information

**Name:** {{ $booking->tourist->full_name }}
**Phone:** {{ $booking->tourist->phone }}
**Email:** {{ $booking->tourist->user->email }}

## Preparation Reminders

- Verify your vehicle is serviced and ready
- Review the itinerary and confirm all reservations
- Check accommodations bookings
- Prepare any necessary permits or tickets
- Contact the tourist to confirm pickup details

@if($booking->tourist_notes)
## Special Requests from Tourist

{{ $booking->tourist_notes }}
@endif

<x-mail::button :url="route('guide.bookings.show', $booking->id)">
View Booking Details
</x-mail::button>

Please ensure you contact the tourist in the next few days to finalize all arrangements.

@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
