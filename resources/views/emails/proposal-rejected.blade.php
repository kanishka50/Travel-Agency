<x-mail::message>
# Update on Your Tour Proposal

Hello {{ $proposal->tourist->full_name }},

We wanted to let you know that **{{ $guide->full_name }}** has reviewed your proposal for the tour **"{{ $plan->title }}"** and unfortunately was unable to accept it at this time.

@if($proposal->rejection_reason)
## Guide's Response

{{ $proposal->rejection_reason }}
@endif

## Your Original Proposal
- **Proposed Price:** ${{ number_format($proposal->proposed_price, 2) }}
- **Dates:** {{ $proposal->start_date->format('M d, Y') }} - {{ $proposal->end_date->format('M d, Y') }}
- **Travelers:** {{ $proposal->num_adults }} {{ Str::plural('adult', $proposal->num_adults) }}@if($proposal->num_children > 0), {{ $proposal->num_children }} {{ Str::plural('child', $proposal->num_children) }}@endif

## What's Next?

Don't be discouraged! You can:
- Submit a new proposal with different terms or dates
- Explore other similar tours from different guides
- Book the tour at the listed price

<x-mail::button :url="route('plans.show', $plan->id)">
View Tour & Try Again
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
