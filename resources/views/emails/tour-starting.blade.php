<x-mail::message>
# Your Tour Starts Today!

@if($recipientType === 'tourist')
Dear {{ $booking->tourist->full_name }},

Today is the day! Your adventure **{{ $booking->guidePlan->title }}** begins today!

## Tour Details

<x-mail::panel>
**Booking Number:** {{ $booking->booking_number }}
**Tour:** {{ $booking->guidePlan->title }}
**Start Date:** **TODAY** - {{ $booking->start_date->format('l, F j, Y') }}
**End Date:** {{ $booking->end_date->format('l, F j, Y') }}
**Duration:** {{ $booking->guidePlan->num_days }} days
**Pickup Location:** {{ $booking->guidePlan->pickup_location }}
</x-mail::panel>

## Your Guide is Ready!

**Name:** {{ $booking->guide->full_name }}
**Phone:** {{ $booking->guide->phone }}

Your guide {{ $booking->guide->full_name }} will be meeting you at **{{ $booking->guidePlan->pickup_location }}**.
If you haven't already, please contact them at {{ $booking->guide->phone }} to confirm the exact pickup time.

## Quick Reminders

- Have your booking number ready: **{{ $booking->booking_number }}**
- Bring your ID and any necessary travel documents
- Carry the guide's phone number: {{ $booking->guide->phone }}
- Have emergency contact numbers saved

<x-mail::button :url="route('bookings.show', $booking->id)">
View Booking Agreement
</x-mail::button>

**Have an amazing experience!** We hope you create wonderful memories on this tour.

If you encounter any issues, please contact us immediately at {{ config('mail.from.address') }}.

@else
Dear {{ $booking->guide->full_name }},

Your tour **{{ $booking->guidePlan->title }}** starts today!

## Booking Details

<x-mail::panel>
**Booking Number:** {{ $booking->booking_number }}
**Tour:** {{ $booking->guidePlan->title }}
**Start Date:** **TODAY** - {{ $booking->start_date->format('l, F j, Y') }}
**End Date:** {{ $booking->end_date->format('l, F j, Y') }}
**Travelers:** {{ $booking->num_adults }} adult(s)@if($booking->num_children > 0), {{ $booking->num_children }} child(ren)@endif
**Pickup:** {{ $booking->guidePlan->pickup_location }}
</x-mail::panel>

## Tourist Contact Information

**Name:** {{ $booking->tourist->full_name }}
**Phone:** {{ $booking->tourist->phone }}
**Email:** {{ $booking->tourist->user->email }}

## Final Checklist

- Confirm you have all necessary permits and tickets
- Verify vehicle fuel and condition
- Check all accommodation confirmations
- Ensure you have first aid kit and emergency supplies
- Review the day's itinerary

@if($booking->tourist_notes)
## Remember - Special Requests

{{ $booking->tourist_notes }}
@endif

<x-mail::button :url="route('guide.bookings.show', $booking->id)">
View Booking Details
</x-mail::button>

Good luck! We know you'll provide an excellent experience.

@endif

Safe travels,<br>
{{ config('app.name') }}
</x-mail::message>
