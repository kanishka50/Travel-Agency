<x-mail::message>
# Payment Confirmed!

Dear {{ $tourist->full_name }},

Great news! Your payment has been successfully processed and your booking is now **confirmed**.

## Booking Details

<x-mail::panel>
**Booking Number:** {{ $booking->booking_number }}
**Tour:** {{ $tourTitle }}
**Start Date:** {{ $booking->start_date->format('l, F j, Y') }}
**End Date:** {{ $booking->end_date->format('l, F j, Y') }}
**Duration:** {{ $booking->end_date->diffInDays($booking->start_date) }} days
**Travelers:** {{ $booking->num_adults }} adult(s)@if($booking->num_children > 0), {{ $booking->num_children }} child(ren)@endif
</x-mail::panel>

## Payment Summary

- **Subtotal:** ${{ number_format($booking->subtotal, 2) }}
- **Platform Fee:** ${{ number_format($booking->platform_fee, 2) }}
- **Total Paid:** ${{ number_format($booking->total_amount, 2) }}

## Your Guide

**Name:** {{ $guide->full_name }}
**Email:** {{ $guide->user->email }}
**Phone:** {{ $guide->phone }}

Your guide will contact you soon to coordinate final details for your tour.

## What's Next?

1. **Check your email** for the attached booking agreement
2. **Wait for your guide** to contact you with pickup details
3. **Prepare for your adventure!**

@if($booking->tourist_notes)
## Your Special Requests

{{ $booking->tourist_notes }}
@endif

<x-mail::button :url="route('tourist.bookings.show', $booking->id)">
View Booking Details
</x-mail::button>

If you have any questions, please don't hesitate to contact us or your guide directly.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
