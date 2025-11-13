<x-mail::message>
# Tour Proposal Update

Dear {{ $bid->guide->full_name }},

Thank you for submitting your proposal for the tour request **"{{ $request->title }}"**.

Unfortunately, we regret to inform you that your proposal has not been selected for this tour.

## Proposal Details

**Your Proposed Price:** ${{ number_format($bid->total_price, 2) }}
**Bid Number:** #{{ $bid->bid_number }}

@if($bid->rejection_reason)
## Feedback from Tourist

{{ $bid->rejection_reason }}
@endif

## Keep Moving Forward

Don't be discouraged! There are many more tour requests waiting for experienced guides like you.

- Browse other open requests and submit new proposals
- Learn from the feedback to improve future proposals
- Continue to provide excellent service on your accepted tours

<x-mail::button :url="route('guide.requests.index')">
Browse New Requests
</x-mail::button>

We appreciate your interest and look forward to working with you on future tours.

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
