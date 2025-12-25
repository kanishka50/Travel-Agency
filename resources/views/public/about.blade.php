@extends('layouts.public')

@section('content')
<div class="bg-emerald-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">About Us</h1>
        <p class="text-emerald-100 text-lg max-w-2xl mx-auto">
            Connecting travelers with authentic Sri Lankan experiences through expert local guides
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Our Story -->
    <div class="mb-16">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Story</h2>
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div>
                <p class="text-gray-600 mb-4">
                    SriLankaTours was founded with a simple mission: to bridge the gap between travelers seeking authentic experiences and the incredible local guides who call Sri Lanka home.
                </p>
                <p class="text-gray-600 mb-4">
                    We believe that the best way to explore any destination is through the eyes of someone who truly knows it. Our platform connects you with verified, professional guides who are passionate about sharing the beauty, culture, and hidden gems of Sri Lanka.
                </p>
                <p class="text-gray-600">
                    Whether you're looking for a wildlife safari in Yala, a cultural journey through Kandy, or a relaxing beach experience in the south, our guides are here to create unforgettable memories for you.
                </p>
            </div>
            <div class="bg-emerald-50 rounded-2xl p-8">
                <div class="grid grid-cols-2 gap-6 text-center">
                    <div>
                        <div class="text-4xl font-bold text-emerald-600">500+</div>
                        <div class="text-gray-600">Verified Guides</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-emerald-600">10K+</div>
                        <div class="text-gray-600">Happy Travelers</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-emerald-600">50+</div>
                        <div class="text-gray-600">Destinations</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-emerald-600">4.8</div>
                        <div class="text-gray-600">Average Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="mb-16">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Why Choose Us</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border hover:shadow-md transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Verified Guides</h3>
                <p class="text-gray-600">
                    All our guides go through a rigorous verification process. We check licenses, experience, and background to ensure your safety.
                </p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border hover:shadow-md transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Best Prices</h3>
                <p class="text-gray-600">
                    Connect directly with guides to get competitive prices. No hidden fees or middlemen inflating costs.
                </p>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border hover:shadow-md transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Personalized Tours</h3>
                <p class="text-gray-600">
                    Every tour is customized to your preferences. Tell us what you want, and we'll match you with the perfect guide.
                </p>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 rounded-2xl p-8 text-center text-white">
        <h2 class="text-2xl font-bold mb-4">Ready to Explore Sri Lanka?</h2>
        <p class="text-emerald-100 mb-6 max-w-2xl mx-auto">
            Start your journey today. Browse our tour packages or create your own custom tour request.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('tour-packages.index') }}" class="px-6 py-3 bg-white text-emerald-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                Browse Tour Packages
            </a>
            <a href="{{ route('contact') }}" class="px-6 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-emerald-600 transition">
                Contact Us
            </a>
        </div>
    </div>
</div>
@endsection
