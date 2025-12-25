<x-mail::message>
# New Proposal Received!

Hello,

Great news! You've received a new proposal for your tour request **"{{ $request->title }}"**.

## Proposal Details

**Guide:** {{ $guide->full_name }}
**Proposed Price:** ${{ number_format($bid->total_price, 2) }}
**Bid Number:** #{{ $bid->bid_number }}

### Proposal Summary
{{ Str::limit($bid->proposal_message, 200) }}

<x-mail::button :url="route('tourist.requests.show', $request->id)">
View Full Proposal
</x-mail::button>

## Request Summary
- **Destinations:** {{ is_array($request->preferred_destinations) ? implode(', ', $request->preferred_destinations) : $request->preferred_destinations }}
- **Duration:** {{ $request->duration_days }} days
- **Start Date:** {{ $request->start_date->format('M d, Y') }}
- **Total Bids:** {{ $request->bid_count }}

The guide is waiting for your response. Review the proposal and either accept it to proceed with the booking, or wait for more proposals before making your decision.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
