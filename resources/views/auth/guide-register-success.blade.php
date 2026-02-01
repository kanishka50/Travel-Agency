@extends('layouts.public')

@section('content')
<!-- Dark Header Section -->
<div class="bg-slate-900 py-16 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full px-6 lg:px-[8%] relative text-center">
        <!-- Success Icon -->
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-3xl mb-6 shadow-lg shadow-emerald-500/25">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Registration Submitted Successfully!
        </h1>
        <p class="text-slate-400 text-lg max-w-xl mx-auto">
            Thank you for your interest in becoming a tour guide with us.
        </p>
    </div>
</div>

<!-- Main Content -->
<div class="w-full px-6 lg:px-[8%] py-12">
    <div class="max-w-2xl mx-auto">
        <!-- What Happens Next Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-slate-200">
                <h2 class="font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    What happens next?
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/25">
                            <span class="text-white font-bold">1</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Document Review</h3>
                            <p class="text-slate-600 text-sm mt-1">Our Registration Manager will review your documents</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/25">
                            <span class="text-white font-bold">2</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Additional Information</h3>
                            <p class="text-slate-600 text-sm mt-1">We may contact you for additional information if needed</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/25">
                            <span class="text-white font-bold">3</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Interview</h3>
                            <p class="text-slate-600 text-sm mt-1">If approved, we'll schedule an interview with you</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/25">
                            <span class="text-white font-bold">4</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Account Activation</h3>
                            <p class="text-slate-600 text-sm mt-1">After successful interview, your account will be activated</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/25">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900">Login Credentials</h3>
                            <p class="text-slate-600 text-sm mt-1">You'll receive login credentials via email</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Confirmation Notice -->
        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm border border-slate-200">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-slate-700">
                    A confirmation email has been sent to your registered email address.
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ url('/') }}"
               class="flex-1 px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:from-amber-600 hover:to-orange-600 font-semibold text-center transition-all duration-300 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Return to Home
            </a>
            <a href="{{ route('tour-packages.index') }}"
               class="flex-1 px-6 py-4 bg-white text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-50 font-semibold text-center transition-all duration-200 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Browse Tour Packages
            </a>
        </div>

        <!-- Contact Info -->
        <div class="mt-8 text-center">
            <p class="text-slate-600">
                Have questions?
                <a href="{{ route('contact') }}" class="text-amber-600 hover:text-amber-700 font-semibold transition-colors">Contact us</a>
            </p>
        </div>
    </div>
</div>
@endsection
