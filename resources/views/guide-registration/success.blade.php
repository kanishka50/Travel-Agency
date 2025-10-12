<x-guest-layout>
    <div class="max-w-2xl mx-auto p-6">
        <div class="bg-green-50 border border-green-200 rounded p-6 text-center">
            <svg class="mx-auto h-16 w-16 text-green-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>

            <h1 class="text-2xl font-bold text-green-900 mb-4">
                Registration Request Submitted Successfully!
            </h1>

            <p class="text-green-800 mb-6">
                Thank you for your interest in becoming a tour guide with us. 
                We have received your registration request and will review it shortly.
            </p>

            <div class="bg-white p-4 rounded border border-green-200 text-left mb-6">
                <h2 class="font-medium text-gray-900 mb-2">What happens next?</h2>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Our Registration Manager will review your documents</li>
                    <li>We may contact you for additional information if needed</li>
                    <li>If approved, we'll schedule an interview with you</li>
                    <li>After successful interview, your account will be activated</li>
                    <li>You'll receive login credentials via email</li>
                </ol>
            </div>

            <p class="text-sm text-gray-600 mb-6">
                A confirmation email has been sent to your registered email address.
            </p>

            <div class="space-x-4">
                <a href="{{ url('/') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Return to Home
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>