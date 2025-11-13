<x-mail::message>
# New Booking Received!

Hi {{ $guide->full_name }},

Congratulations! You have received a new booking for your tour "**{{ $plan->title }}**".

## Booking Details

<x-mail::panel>
**Booking Number:** {{ $booking->booking_number }}
**Tour:** {{ $plan->title }}
**Start Date:** {{ $booking->start_date->format('l, F j, Y') }}
**End Date:** {{ $booking->end_date->format('l, F j, Y') }}
**Duration:** {{ $booking->end_date->diffInDays($booking->start_date) }} days
**Travelers:** {{ $booking->num_adults }} adult(s)@if($booking->num_children > 0), {{ $booking->num_children }} child(ren)@endif
</x-mail::panel>

## Tourist Information

**Name:** {{ $tourist->full_name }}
**Email:** {{ $tourist->user->email }}
**Phone:** {{ $tourist->phone }}
**Country:** {{ $tourist->country }}

@if($tourist->emergency_contact_name)
**Emergency Contact:** {{ $tourist->emergency_contact_name }} ({{ $tourist->emergency_contact_phone }})
@endif

@if($booking->tourist_notes)
## Special Requests from Tourist

{{ $booking->tourist_notes }}
@endif

## Payment Information

- **Total Amount:** ${{ number_format($booking->total_amount, 2) }}
- **Your Payout:** ${{ number_format($booking->guide_payout, 2) }}
- **Payment Status:** Paid
- **Paid On:** {{ $booking->paid_at->format('F j, Y g:i A') }}

Your payout will be processed according to the agreed payment schedule.

## Next Steps

1. **Review the booking details** in your dashboard
2. **Contact the tourist** to confirm pickup location and time
3. **Prepare for the tour** and review any special requests

<x-mail::button :url="route('guide.bookings.show', $booking->id)">
View Booking Details
</x-mail::button>

Please contact the tourist at {{ $tourist->user->email }} or {{ $tourist->phone }} to finalize tour arrangements.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
