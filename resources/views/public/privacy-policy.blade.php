@extends('layouts.public')

@section('content')
<div class="bg-emerald-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Privacy Policy</h1>
        <p class="text-emerald-100 text-lg">
            Last updated: {{ date('F d, Y') }}
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="bg-white rounded-2xl p-8 shadow-sm border prose prose-emerald max-w-none">
        <h2>1. Introduction</h2>
        <p>
            Welcome to SriLankaTours ("we," "our," or "us"). We are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.
        </p>

        <h2>2. Information We Collect</h2>
        <h3>2.1 Personal Information</h3>
        <p>We may collect personal information that you voluntarily provide when you:</p>
        <ul>
            <li>Create an account on our platform</li>
            <li>Book a tour or submit a tour request</li>
            <li>Contact us through our contact form</li>
            <li>Subscribe to our newsletter</li>
            <li>Apply to become a guide</li>
        </ul>
        <p>This information may include:</p>
        <ul>
            <li>Name and email address</li>
            <li>Phone number</li>
            <li>Payment information</li>
            <li>Travel preferences and requirements</li>
            <li>National ID or passport details (for guides)</li>
        </ul>

        <h3>2.2 Automatically Collected Information</h3>
        <p>When you access our platform, we may automatically collect:</p>
        <ul>
            <li>IP address and browser type</li>
            <li>Device information</li>
            <li>Usage data and analytics</li>
            <li>Cookies and similar technologies</li>
        </ul>

        <h2>3. How We Use Your Information</h2>
        <p>We use the collected information to:</p>
        <ul>
            <li>Provide and maintain our services</li>
            <li>Process bookings and payments</li>
            <li>Connect tourists with guides</li>
            <li>Send important updates about your bookings</li>
            <li>Improve our platform and user experience</li>
            <li>Respond to your inquiries and support requests</li>
            <li>Comply with legal obligations</li>
        </ul>

        <h2>4. Information Sharing</h2>
        <p>We may share your information with:</p>
        <ul>
            <li><strong>Guides:</strong> When you book a tour, we share relevant booking details with your guide</li>
            <li><strong>Payment Processors:</strong> To process your payments securely</li>
            <li><strong>Service Providers:</strong> Third-party services that help us operate our platform</li>
            <li><strong>Legal Authorities:</strong> When required by law or to protect our rights</li>
        </ul>

        <h2>5. Data Security</h2>
        <p>
            We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet is 100% secure.
        </p>

        <h2>6. Your Rights</h2>
        <p>You have the right to:</p>
        <ul>
            <li>Access your personal information</li>
            <li>Correct inaccurate data</li>
            <li>Request deletion of your data</li>
            <li>Opt-out of marketing communications</li>
            <li>Data portability</li>
        </ul>

        <h2>7. Cookies</h2>
        <p>
            We use cookies and similar tracking technologies to enhance your experience on our platform. You can control cookie settings through your browser preferences.
        </p>

        <h2>8. Third-Party Links</h2>
        <p>
            Our platform may contain links to third-party websites. We are not responsible for the privacy practices of these external sites.
        </p>

        <h2>9. Children's Privacy</h2>
        <p>
            Our services are not intended for children under 18. We do not knowingly collect personal information from children.
        </p>

        <h2>10. Changes to This Policy</h2>
        <p>
            We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last updated" date.
        </p>

        <h2>11. Contact Us</h2>
        <p>
            If you have any questions about this Privacy Policy, please contact us at:
        </p>
        <ul>
            <li>Email: privacy@srilankatours.com</li>
            <li>Phone: +94 11 234 5678</li>
            <li>Address: 123 Tourism Street, Colombo 03, Sri Lanka</li>
        </ul>
    </div>
</div>
@endsection
