@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="relative bg-slate-900 py-20 overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-20 right-20 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full px-6 lg:px-[8%]">
        <div class="max-w-3xl">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                <span class="text-amber-400 font-semibold uppercase tracking-wider text-sm">Legal</span>
            </div>
            <h1 class="text-4xl lg:text-5xl font-bold text-white mb-4">Terms of Service</h1>
            <p class="text-white/60 text-lg">
                Last updated: {{ date('F d, Y') }}
            </p>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="py-16 bg-slate-50">
    <div class="w-full px-6 lg:px-[8%]">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-3xl p-8 lg:p-12 shadow-sm">
                <!-- Acceptance of Terms -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">1</span>
                        Acceptance of Terms
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        By accessing and using TravelAgency ("the Platform"), you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.
                    </p>
                </div>

                <!-- Description of Services -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">2</span>
                        Description of Services
                    </h2>
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-100">
                        <p class="text-slate-700 leading-relaxed">
                            TravelAgency is an online platform that connects tourists with local tour guides in Sri Lanka. We facilitate the booking process but are not responsible for the actual tour services provided by guides.
                        </p>
                    </div>
                </div>

                <!-- User Accounts -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">3</span>
                        User Accounts
                    </h2>

                    <h3 class="text-lg font-semibold text-slate-800 mb-3">3.1 Registration</h3>
                    <p class="text-slate-600 mb-4">To use certain features of our Platform, you must register for an account. You agree to:</p>
                    <ul class="list-none space-y-2 mb-6">
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Provide accurate and complete information
                        </li>
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Maintain the security of your account credentials
                        </li>
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Notify us immediately of any unauthorized access
                        </li>
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Be responsible for all activities under your account
                        </li>
                    </ul>

                    <h3 class="text-lg font-semibold text-slate-800 mb-3">3.2 Account Types</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-slate-900">Tourist Accounts</h4>
                            </div>
                            <p class="text-slate-600 text-sm">For travelers seeking tour services and local experiences.</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-slate-900">Guide Accounts</h4>
                            </div>
                            <p class="text-slate-600 text-sm">For verified tour guides offering professional services.</p>
                        </div>
                    </div>
                </div>

                <!-- Booking and Payments -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">4</span>
                        Booking and Payments
                    </h2>

                    <div class="space-y-4">
                        <div class="border-l-4 border-amber-500 pl-4">
                            <h3 class="font-semibold text-slate-800 mb-2">4.1 Booking Process</h3>
                            <p class="text-slate-600 text-sm">When you book a tour through our Platform, you enter into a direct agreement with the guide. We act as an intermediary to facilitate the transaction.</p>
                        </div>
                        <div class="border-l-4 border-amber-500 pl-4">
                            <h3 class="font-semibold text-slate-800 mb-2">4.2 Payments</h3>
                            <p class="text-slate-600 text-sm">All payments are processed securely through our platform. Prices are displayed in the currency specified. We may charge a service fee for using our platform.</p>
                        </div>
                        <div class="border-l-4 border-amber-500 pl-4">
                            <h3 class="font-semibold text-slate-800 mb-2">4.3 Cancellations and Refunds</h3>
                            <p class="text-slate-600 text-sm">Cancellation policies vary by tour package. Please review the specific cancellation policy before booking.</p>
                        </div>
                    </div>
                </div>

                <!-- User Conduct -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">5</span>
                        User Conduct
                    </h2>
                    <p class="text-slate-600 mb-4">You agree not to:</p>
                    <div class="bg-red-50 rounded-2xl p-6 border border-red-100">
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3 text-red-700">
                                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Use the Platform for any illegal purposes
                            </li>
                            <li class="flex items-start gap-3 text-red-700">
                                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Harass, abuse, or harm other users
                            </li>
                            <li class="flex items-start gap-3 text-red-700">
                                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Provide false or misleading information
                            </li>
                            <li class="flex items-start gap-3 text-red-700">
                                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Attempt to circumvent our payment system
                            </li>
                            <li class="flex items-start gap-3 text-red-700">
                                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Interfere with the Platform's operation
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Limitation of Liability -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">6</span>
                        Limitation of Liability
                    </h2>
                    <p class="text-slate-600 mb-4">TravelAgency is not liable for any direct, indirect, incidental, or consequential damages arising from:</p>
                    <div class="grid md:grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-4 text-slate-600 text-sm flex items-center gap-3">
                            <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                            Your use of the Platform
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 text-slate-600 text-sm flex items-center gap-3">
                            <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                            Tour services provided by guides
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 text-slate-600 text-sm flex items-center gap-3">
                            <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                            Actions of other users
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 text-slate-600 text-sm flex items-center gap-3">
                            <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                            Technical issues or interruptions
                        </div>
                    </div>
                </div>

                <!-- Governing Law -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">7</span>
                        Governing Law
                    </h2>
                    <div class="bg-slate-900 rounded-2xl p-6 text-white">
                        <p class="text-white/80 leading-relaxed">
                            These Terms are governed by the laws of Sri Lanka. Any legal proceedings shall be conducted in the courts of Colombo, Sri Lanka.
                        </p>
                    </div>
                </div>

                <!-- Contact Information -->
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">8</span>
                        Contact Information
                    </h2>
                    <p class="text-slate-600 mb-4">For questions about these Terms, please contact us at:</p>
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 text-white">
                        <div class="grid md:grid-cols-3 gap-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-white/90 text-sm">legal@travelagency.lk</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-white/90 text-sm">+94 11 234 5678</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="text-white/90 text-sm">Colombo, Sri Lanka</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
