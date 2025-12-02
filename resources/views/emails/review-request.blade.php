<x-mail::message>
# How Was Your Tour?

Dear {{ $booking->tourist->full_name }},

We hope you had an amazing time on your tour **{{ $booking->guidePlan->title }}** with {{ $booking->guide->full_name }}!

Your experience and feedback are incredibly valuable to us and help other travelers make informed decisions.

## Tour Details

<x-mail::panel>
**Tour:** {{ $booking->guidePlan->title }}
**Guide:** {{ $booking->guide->full_name }}
**Dates:** {{ $booking->start_date->format('M j') }} - {{ $booking->end_date->format('M j, Y') }}
**Duration:** {{ $booking->guidePlan->num_days }} days
</x-mail::panel>

## Share Your Experience

We'd love to hear about your adventure! Your review helps:

- **Other Travelers** - Make informed booking decisions
- **Your Guide** - Improve their services and gain recognition
- **Our Platform** - Maintain quality standards

Your honest feedback takes just 2-3 minutes and makes a big difference.

<x-mail::button :url="'#'">
Write a Review
</x-mail::button>

## What to Include in Your Review

- Overall experience and highlights
- Guide's professionalism and knowledge
- Accommodations and meals quality
- Value for money
- Any suggestions for improvement

Your review will be visible to other travelers and will help {{ $booking->guide->full_name }} build their reputation.

---

### Quick Questions

**Would you recommend this tour?** Yes / No

**Would you book with {{ $booking->guide->full_name }} again?** Yes / No

**Overall Rating:** ⭐⭐⭐⭐⭐ (Rate 1-5 stars)

---

Thank you for choosing {{ config('app.name') }} for your travel adventure!

If you have any concerns or issues about your tour, please contact us directly at {{ config('mail.from.address') }}.

Best regards,<br>
{{ config('app.name') }} Team
</x-mail::message>
