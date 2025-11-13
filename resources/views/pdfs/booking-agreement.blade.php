<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Agreement - {{ $booking->booking_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        .booking-info {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .booking-info h2 {
            font-size: 16px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #4b5563;
        }

        .info-value {
            display: table-cell;
            width: 65%;
            color: #1f2937;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h3 {
            font-size: 14px;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .grid {
            display: table;
            width: 100%;
        }

        .grid-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .pricing-table th,
        .pricing-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .pricing-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #4b5563;
        }

        .pricing-table .total-row {
            font-weight: bold;
            font-size: 13px;
            background-color: #dbeafe;
        }

        .pricing-table .total-row td {
            padding-top: 12px;
            border-top: 2px solid #2563eb;
        }

        .terms {
            background-color: #fef3c7;
            padding: 12px;
            border-left: 4px solid #f59e0b;
            margin-top: 15px;
            font-size: 10px;
        }

        .terms h4 {
            font-size: 12px;
            color: #92400e;
            margin-bottom: 8px;
        }

        .terms ul {
            margin-left: 20px;
        }

        .terms li {
            margin-bottom: 4px;
        }

        .signatures {
            margin-top: 40px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            padding: 10px;
        }

        .signature-line {
            border-top: 2px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Tourism Platform</h1>
        <p>Tour Booking Agreement</p>
    </div>

    <div class="booking-info">
        <h2>Booking Details</h2>
        <div class="info-row">
            <div class="info-label">Booking Number:</div>
            <div class="info-value">{{ $booking->booking_number }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Booking Date:</div>
            <div class="info-value">{{ $booking->created_at->format('F d, Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status:</div>
            <div class="info-value">
                <span class="status-badge {{ $booking->status === 'confirmed' ? 'status-confirmed' : 'status-pending' }}">
                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                </span>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Party Information</h3>
        <div class="grid">
            <div class="grid-col">
                <strong>Tourist (Client):</strong><br>
                <strong>Name:</strong> {{ $booking->tourist->full_name }}<br>
                <strong>Email:</strong> {{ $booking->tourist->user->email }}<br>
                @if($booking->tourist->phone)
                <strong>Phone:</strong> {{ $booking->tourist->phone }}<br>
                @endif
                <strong>Country:</strong> {{ $booking->tourist->country ?? 'N/A' }}
            </div>
            <div class="grid-col">
                <strong>Tour Guide (Provider):</strong><br>
                <strong>Name:</strong> {{ $booking->guide->full_name }}<br>
                @if(in_array($booking->status, ['confirmed', 'ongoing', 'completed']))
                <strong>Email:</strong> {{ $booking->guide->user->email }}<br>
                @if($booking->guide->phone)
                <strong>Phone:</strong> {{ $booking->guide->phone }}<br>
                @endif
                @else
                <em style="color: #6b7280;">Contact details available after payment confirmation</em>
                @endif
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Tour Information</h3>
        <div class="info-row">
            <div class="info-label">Tour Title:</div>
            <div class="info-value"><strong>{{ $booking->guidePlan->title }}</strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Start Date:</div>
            <div class="info-value">{{ $booking->start_date->format('F d, Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">End Date:</div>
            <div class="info-value">{{ $booking->end_date->format('F d, Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Duration:</div>
            <div class="info-value">{{ $booking->start_date->diffInDays($booking->end_date) + 1 }} days</div>
        </div>
        <div class="info-row">
            <div class="info-label">Location:</div>
            <div class="info-value">{{ $booking->guidePlan->destination }}</div>
        </div>
    </div>

    <div class="section">
        <h3>Traveler Details</h3>
        <div class="info-row">
            <div class="info-label">Number of Adults:</div>
            <div class="info-value">{{ $booking->num_adults }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Number of Children:</div>
            <div class="info-value">{{ $booking->num_children }}</div>
        </div>
        @if($booking->num_children > 0 && $booking->children_ages)
        <div class="info-row">
            <div class="info-label">Children Ages:</div>
            <div class="info-value">{{ implode(', ', json_decode($booking->children_ages)) }} years old</div>
        </div>
        @endif
        @if($booking->tourist_notes)
        <div class="info-row">
            <div class="info-label">Special Requests:</div>
            <div class="info-value">{{ $booking->tourist_notes }}</div>
        </div>
        @endif
    </div>

    <div class="section">
        <h3>Pricing Breakdown</h3>
        <table class="pricing-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Base Price ({{ $booking->num_adults }} {{ Str::plural('Adult', $booking->num_adults) }}
                        @if($booking->num_children > 0)
                        , {{ $booking->num_children }} {{ Str::plural('Child', $booking->num_children) }}
                        @endif
                        )</td>
                    <td style="text-align: right;">${{ number_format($booking->base_price, 2) }}</td>
                </tr>
                @if($booking->addons_total > 0)
                <tr>
                    <td>Add-ons & Extras</td>
                    <td style="text-align: right;">${{ number_format($booking->addons_total, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td>Subtotal</td>
                    <td style="text-align: right;">${{ number_format($booking->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Platform Service Fee (10%)</td>
                    <td style="text-align: right;">${{ number_format($booking->platform_fee, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL AMOUNT</td>
                    <td style="text-align: right;">${{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        @if($booking->addons->count() > 0)
        <div style="margin-top: 15px;">
            <strong>Selected Add-ons:</strong>
            <ul style="margin-left: 20px; margin-top: 5px;">
                @foreach($booking->addons as $addon)
                <li>{{ $addon->addon_name }} (x{{ $addon->quantity }}) - ${{ number_format($addon->total_price, 2) }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="section">
        <div class="terms">
            <h4>Terms and Conditions</h4>
            <ul>
                <li>This booking is subject to payment confirmation and guide acceptance.</li>
                <li>The tourist agrees to pay the total amount of <strong>${{ number_format($booking->total_amount, 2) }}</strong> for the services described above.</li>
                <li>Cancellation policy: Tourists may cancel up to 48 hours before the tour start date for a full refund minus platform fees.</li>
                <li>The guide reserves the right to cancel the tour due to unforeseen circumstances, in which case a full refund will be provided.</li>
                <li>Both parties agree to communicate respectfully and professionally throughout the tour.</li>
                <li>The platform acts as an intermediary and is not responsible for the quality of services provided by the guide.</li>
                <li>Any disputes should be reported to the platform within 24 hours of tour completion.</li>
                <li>The guide will receive <strong>${{ number_format($booking->guide_payout, 2) }}</strong> (90% of subtotal) as payment for services.</li>
                <li>Tourist must arrive on time at the agreed meeting location. Late arrivals may result in shortened tour duration.</li>
                <li>Tourist health and safety is the responsibility of both parties. Any medical conditions should be disclosed prior to the tour.</li>
            </ul>
        </div>
    </div>

    <div class="signatures">
        <div class="signature-box">
            <strong>Tourist Signature</strong>
            <div class="signature-line">
                <p>{{ $booking->tourist->full_name }}</p>
                <p style="font-size: 9px; color: #6b7280;">Date: {{ $booking->created_at->format('F d, Y') }}</p>
            </div>
        </div>
        <div class="signature-box">
            <strong>Guide Signature</strong>
            <div class="signature-line">
                <p>{{ $booking->guide->full_name }}</p>
                <p style="font-size: 9px; color: #6b7280;">Date: {{ $booking->confirmed_at ? $booking->confirmed_at->format('F d, Y') : '_______________' }}</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>This is a computer-generated document. No signature is required for validity.</p>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i:s') }}</p>
        <p>&copy; {{ now()->year }} Tourism Platform. All rights reserved.</p>
    </div>
</body>
</html>
