<x-mail::message>
# Booking Confirmation

Dear {{ $booking->tourist->full_name }},

Thank you for booking with us! Your tour booking has been successfully created.

## Booking Details

**Booking Number:** {{ $booking->booking_number }}
**Status:** {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
**Tour:** {{ $booking->guidePlan->title }}
**Start Date:** {{ $booking->start_date->format('F d, Y') }}
**End Date:** {{ $booking->end_date->format('F d, Y') }}
**Duration:** {{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days

## Traveler Information

**Adults:** {{ $booking->num_adults }}
**Children:** {{ $booking->num_children }}
@if($booking->num_children > 0 && $booking->children_ages)
**Children Ages:** {{ implode(', ', json_decode($booking->children_ages)) }} years old
@endif

## Price Summary

**Base Price:** ${{ number_format($booking->base_price, 2) }}
@if($booking->addons_total > 0)
**Add-ons:** ${{ number_format($booking->addons_total, 2) }}
@endif
**Subtotal:** ${{ number_format($booking->subtotal, 2) }}
**Platform Fee (10%):** ${{ number_format($booking->platform_fee, 2) }}
**Total Amount:** ${{ number_format($booking->total_amount, 2) }}

@if($booking->status === 'pending_payment')
## Next Steps

Your booking is currently pending payment. Please complete the payment to confirm your tour.

<x-mail::button :url="route('tourist.bookings.show', $booking->id)">
Complete Payment
</x-mail::button>
@else
## Your Guide

**Name:** {{ $booking->guide->full_name }}
**Email:** {{ $booking->guide->user->email }}
@if($booking->guide->phone)
**Phone:** {{ $booking->guide->phone }}
@endif

<x-mail::button :url="route('tourist.bookings.show', $booking->id)">
View Booking Details
</x-mail::button>
@endif

## Important Information

- Please arrive at the meeting location on time
- Bring necessary travel documents and identification
- Contact your guide if you have any questions

You can download your booking agreement from your booking details page.

If you have any questions, please don't hesitate to contact us.

Best regards,
{{ config('app.name') }}
</x-mail::message>
