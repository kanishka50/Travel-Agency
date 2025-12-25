@extends('layouts.public')

@section('content')
<div class="bg-emerald-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Terms of Service</h1>
        <p class="text-emerald-100 text-lg">
            Last updated: {{ date('F d, Y') }}
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-2xl p-8 shadow-sm border prose prose-emerald max-w-none">
        <h2>1. Acceptance of Terms</h2>
        <p>
            By accessing and using SriLankaTours ("the Platform"), you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.
        </p>

        <h2>2. Description of Services</h2>
        <p>
            SriLankaTours is an online platform that connects tourists with local tour guides in Sri Lanka. We facilitate the booking process but are not responsible for the actual tour services provided by guides.
        </p>

        <h2>3. User Accounts</h2>
        <h3>3.1 Registration</h3>
        <p>To use certain features of our Platform, you must register for an account. You agree to:</p>
        <ul>
            <li>Provide accurate and complete information</li>
            <li>Maintain the security of your account credentials</li>
            <li>Notify us immediately of any unauthorized access</li>
            <li>Be responsible for all activities under your account</li>
        </ul>

        <h3>3.2 Account Types</h3>
        <p>We offer different account types:</p>
        <ul>
            <li><strong>Tourist Accounts:</strong> For travelers seeking tour services</li>
            <li><strong>Guide Accounts:</strong> For verified tour guides offering services</li>
        </ul>

        <h2>4. Booking and Payments</h2>
        <h3>4.1 Booking Process</h3>
        <p>
            When you book a tour through our Platform, you enter into a direct agreement with the guide. We act as an intermediary to facilitate the transaction.
        </p>

        <h3>4.2 Payments</h3>
        <ul>
            <li>All payments are processed securely through our platform</li>
            <li>Prices are displayed in the currency specified</li>
            <li>We may charge a service fee for using our platform</li>
        </ul>

        <h3>4.3 Cancellations and Refunds</h3>
        <p>
            Cancellation policies vary by tour package. Please review the specific cancellation policy before booking. Refunds, if applicable, will be processed according to the stated policy.
        </p>

        <h2>5. Guide Requirements</h2>
        <p>Guides on our platform must:</p>
        <ul>
            <li>Hold valid Sri Lanka Tourism Development Authority licenses (where applicable)</li>
            <li>Maintain appropriate insurance coverage</li>
            <li>Provide accurate information about their services</li>
            <li>Deliver services as described in their listings</li>
            <li>Maintain professional conduct at all times</li>
        </ul>

        <h2>6. User Conduct</h2>
        <p>You agree not to:</p>
        <ul>
            <li>Use the Platform for any illegal purposes</li>
            <li>Harass, abuse, or harm other users</li>
            <li>Provide false or misleading information</li>
            <li>Attempt to circumvent our payment system</li>
            <li>Interfere with the Platform's operation</li>
            <li>Violate any applicable laws or regulations</li>
        </ul>

        <h2>7. Reviews and Ratings</h2>
        <p>
            Users may leave reviews and ratings after completing tours. Reviews must be honest, accurate, and comply with our community guidelines. We reserve the right to remove reviews that violate these guidelines.
        </p>

        <h2>8. Intellectual Property</h2>
        <p>
            All content on the Platform, including text, graphics, logos, and software, is the property of SriLankaTours or its licensors and is protected by intellectual property laws.
        </p>

        <h2>9. Limitation of Liability</h2>
        <p>
            SriLankaTours is not liable for any direct, indirect, incidental, or consequential damages arising from:
        </p>
        <ul>
            <li>Your use of the Platform</li>
            <li>Any tour services provided by guides</li>
            <li>Actions or omissions of other users</li>
            <li>Technical issues or service interruptions</li>
        </ul>

        <h2>10. Indemnification</h2>
        <p>
            You agree to indemnify and hold harmless SriLankaTours, its officers, directors, employees, and agents from any claims, damages, or expenses arising from your use of the Platform or violation of these Terms.
        </p>

        <h2>11. Dispute Resolution</h2>
        <p>
            Any disputes arising from these Terms or your use of the Platform shall be resolved through:
        </p>
        <ul>
            <li>Initial good-faith negotiation</li>
            <li>Mediation, if negotiation fails</li>
            <li>Arbitration under Sri Lankan law, if mediation fails</li>
        </ul>

        <h2>12. Modifications</h2>
        <p>
            We reserve the right to modify these Terms at any time. Continued use of the Platform after changes constitutes acceptance of the modified Terms.
        </p>

        <h2>13. Termination</h2>
        <p>
            We may terminate or suspend your account at any time for violation of these Terms or for any other reason at our discretion.
        </p>

        <h2>14. Governing Law</h2>
        <p>
            These Terms are governed by the laws of Sri Lanka. Any legal proceedings shall be conducted in the courts of Colombo, Sri Lanka.
        </p>

        <h2>15. Contact Information</h2>
        <p>
            For questions about these Terms, please contact us at:
        </p>
        <ul>
            <li>Email: legal@srilankatours.com</li>
            <li>Phone: +94 11 234 5678</li>
            <li>Address: 123 Tourism Street, Colombo 03, Sri Lanka</li>
        </ul>
    </div>
</div>
@endsection
