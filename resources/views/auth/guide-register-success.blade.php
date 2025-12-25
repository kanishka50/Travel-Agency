@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 p-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">
                    Registration Submitted Successfully!
                </h1>
                <p class="text-emerald-100">
                    Thank you for your interest in becoming a tour guide with us.
                </p>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-6 mb-6">
                    <h2 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        What happens next?
                    </h2>
                    <ol class="space-y-3">
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-6 h-6 bg-emerald-600 text-white rounded-full text-xs font-semibold mr-3 mt-0.5">1</span>
                            <span class="text-gray-700">Our Registration Manager will review your documents</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-6 h-6 bg-emerald-600 text-white rounded-full text-xs font-semibold mr-3 mt-0.5">2</span>
                            <span class="text-gray-700">We may contact you for additional information if needed</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-6 h-6 bg-emerald-600 text-white rounded-full text-xs font-semibold mr-3 mt-0.5">3</span>
                            <span class="text-gray-700">If approved, we'll schedule an interview with you</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-6 h-6 bg-emerald-600 text-white rounded-full text-xs font-semibold mr-3 mt-0.5">4</span>
                            <span class="text-gray-700">After successful interview, your account will be activated</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-6 h-6 bg-emerald-600 text-white rounded-full text-xs font-semibold mr-3 mt-0.5">5</span>
                            <span class="text-gray-700">You'll receive login credentials via email</span>
                        </li>
                    </ol>
                </div>

                <div class="flex items-center p-4 bg-gray-50 rounded-lg mb-6">
                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-600">
                        A confirmation email has been sent to your registered email address.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ url('/') }}"
                       class="flex-1 px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-semibold text-center transition-colors">
                        Return to Home
                    </a>
                    <a href="{{ route('tour-packages.index') }}"
                       class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold text-center transition-colors">
                        Browse Tour Packages
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">
                Have questions?
                <a href="{{ route('contact') }}" class="text-emerald-600 hover:text-emerald-700 font-semibold">Contact us</a>
            </p>
        </div>
    </div>
</div>
@endsection
