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
            <h1 class="text-4xl lg:text-5xl font-bold text-white mb-4">Privacy Policy</h1>
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
                <!-- Introduction -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">1</span>
                        Introduction
                    </h2>
                    <p class="text-slate-600 leading-relaxed">
                        Welcome to TravelAgency ("we," "our," or "us"). We are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our platform.
                    </p>
                </div>

                <!-- Information We Collect -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">2</span>
                        Information We Collect
                    </h2>

                    <h3 class="text-lg font-semibold text-slate-800 mb-3">2.1 Personal Information</h3>
                    <p class="text-slate-600 mb-3">We may collect personal information that you voluntarily provide when you:</p>
                    <ul class="list-none space-y-2 mb-6">
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Create an account on our platform
                        </li>
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Book a tour or submit a tour request
                        </li>
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Contact us through our contact form
                        </li>
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Apply to become a guide
                        </li>
                    </ul>

                    <p class="text-slate-600 mb-3">This information may include:</p>
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-slate-50 rounded-xl p-4 text-slate-600 text-sm">Name and email address</div>
                        <div class="bg-slate-50 rounded-xl p-4 text-slate-600 text-sm">Phone number</div>
                        <div class="bg-slate-50 rounded-xl p-4 text-slate-600 text-sm">Payment information</div>
                        <div class="bg-slate-50 rounded-xl p-4 text-slate-600 text-sm">Travel preferences</div>
                    </div>

                    <h3 class="text-lg font-semibold text-slate-800 mb-3">2.2 Automatically Collected Information</h3>
                    <p class="text-slate-600 mb-3">When you access our platform, we may automatically collect:</p>
                    <ul class="list-none space-y-2">
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            IP address and browser type
                        </li>
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Device information and usage data
                        </li>
                        <li class="flex items-start gap-3 text-slate-600">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Cookies and similar technologies
                        </li>
                    </ul>
                </div>

                <!-- How We Use Your Information -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">3</span>
                        How We Use Your Information
                    </h2>
                    <p class="text-slate-600 mb-4">We use the collected information to:</p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-8 h-8 bg-amber-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-slate-600 text-sm">Provide and maintain our services</span>
                        </div>
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-8 h-8 bg-amber-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-slate-600 text-sm">Process bookings and payments</span>
                        </div>
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-8 h-8 bg-amber-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-slate-600 text-sm">Connect tourists with guides</span>
                        </div>
                        <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-8 h-8 bg-amber-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-slate-600 text-sm">Send booking updates</span>
                        </div>
                    </div>
                </div>

                <!-- Data Security -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">4</span>
                        Data Security
                    </h2>
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-100">
                        <p class="text-slate-700 leading-relaxed">
                            We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet is 100% secure.
                        </p>
                    </div>
                </div>

                <!-- Your Rights -->
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">5</span>
                        Your Rights
                    </h2>
                    <p class="text-slate-600 mb-4">You have the right to:</p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-4 p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <span class="text-slate-700">Access your personal information</span>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <span class="text-slate-700">Correct inaccurate data</span>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <span class="text-slate-700">Request deletion of your data</span>
                        </div>
                        <div class="flex items-center gap-4 p-4 bg-white border border-slate-200 rounded-xl">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                            <span class="text-slate-700">Opt-out of marketing communications</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Us -->
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 font-bold text-sm">6</span>
                        Contact Us
                    </h2>
                    <p class="text-slate-600 mb-4">If you have any questions about this Privacy Policy, please contact us at:</p>
                    <div class="bg-slate-900 rounded-2xl p-6 text-white">
                        <div class="grid md:grid-cols-3 gap-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-white/80 text-sm">privacy@travelagency.lk</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-white/80 text-sm">+94 11 234 5678</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="text-white/80 text-sm">Colombo, Sri Lanka</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
