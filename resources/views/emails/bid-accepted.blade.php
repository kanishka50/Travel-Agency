<x-mail::message>
# Congratulations! Your Proposal Was Accepted

Dear {{ $bid->guide->full_name }},

We're excited to inform you that your proposal for the tour request **"{{ $request->title }}"** has been accepted by the tourist!

## Accepted Proposal Details

**Your Proposed Price:** ${{ number_format($bid->total_price, 2) }}
**Bid Number:** #{{ $bid->bid_number }}
**Estimated Duration:** {{ $bid->estimated_days ?? $request->duration_days }} days

## Next Steps

1. The tourist will contact you soon to finalize booking details
2. Review your proposed itinerary and make any necessary preparations
3. Confirm availability for the tour dates
4. Prepare all necessary documentation

## Tourist Information

**Contact:** {{ $tourist->user->email }}
**Start Date:** {{ $request->start_date->format('M d, Y') }}
**Travelers:** {{ $request->num_adults }} {{ Str::plural('adult', $request->num_adults) }}@if($request->num_children > 0), {{ $request->num_children }} {{ Str::plural('child', $request->num_children) }}@endif

<x-mail::button :url="route('guide.requests.show', $request->id)">
View Request Details
</x-mail::button>

Congratulations once again! We wish you a successful tour.

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
