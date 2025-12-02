<x-mail::message>
# Your Proposal Was Accepted!

Hello {{ $proposal->tourist->full_name }},

Great news! **{{ $guide->full_name }}** has accepted your proposal for the tour **"{{ $plan->title }}"**.

## Booking Created

A booking has been automatically created for you. Here are the details:

@if($booking)
**Booking Reference:** {{ $booking->booking_reference }}
**Total Amount:** ${{ number_format($booking->total_amount, 2) }}
@endif

### Trip Details
- **Tour:** {{ $plan->title }}
- **Dates:** {{ $proposal->start_date->format('M d, Y') }} - {{ $proposal->end_date->format('M d, Y') }}
- **Duration:** {{ $plan->num_days }} days / {{ $plan->num_nights }} nights
- **Travelers:** {{ $proposal->num_adults }} {{ Str::plural('adult', $proposal->num_adults) }}@if($proposal->num_children > 0), {{ $proposal->num_children }} {{ Str::plural('child', $proposal->num_children) }}@endif

@if($proposal->modifications)
### Agreed Modifications
{{ $proposal->modifications }}
@endif

<x-mail::button :url="route('bookings.show', $booking->id)">
View Booking & Pay
</x-mail::button>

Please complete your payment to confirm the booking. The guide will be ready to welcome you on your adventure!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
