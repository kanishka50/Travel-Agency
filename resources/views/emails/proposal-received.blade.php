<x-mail::message>
# New Proposal Received!

Hello {{ $plan->guide->full_name }},

Great news! You've received a new proposal for your tour plan **"{{ $plan->title }}"**.

## Proposal Details

**From:** {{ $tourist->full_name }}
**Proposed Price:** ${{ number_format($proposal->proposed_price, 2) }}
**Original Price:** ${{ number_format($plan->price_per_adult, 2) }} per adult
@if($proposal->discount_percentage > 0)
**Discount Requested:** {{ number_format($proposal->discount_percentage, 1) }}%
@endif

### Trip Details
- **Dates:** {{ $proposal->start_date->format('M d, Y') }} - {{ $proposal->end_date->format('M d, Y') }}
- **Travelers:** {{ $proposal->num_adults }} {{ Str::plural('adult', $proposal->num_adults) }}@if($proposal->num_children > 0), {{ $proposal->num_children }} {{ Str::plural('child', $proposal->num_children) }}@endif

@if($proposal->modifications)
### Requested Modifications
{{ Str::limit($proposal->modifications, 200) }}
@endif

@if($proposal->message)
### Message from Tourist
{{ Str::limit($proposal->message, 200) }}
@endif

<x-mail::button :url="route('guide.proposals.show', $proposal->id)">
View Full Proposal
</x-mail::button>

You can accept this proposal to create a booking, or reject it with a reason. The tourist is waiting for your response!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
