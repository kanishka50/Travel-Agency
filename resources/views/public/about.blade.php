@extends('layouts.public')

@section('content')
<!-- Hero Section with Dark Background & Staggered Images -->
<section class="relative min-h-[90vh] bg-slate-900 overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80" alt="Mountains"
             class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/70 via-slate-900/50 to-slate-900"></div>
    </div>

    <div class="relative w-full px-6 lg:px-[8%] py-20 lg:py-32">
        <div class="max-w-4xl mx-auto text-center mb-16">
            <!-- Brand Name -->
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 tracking-tight">
                Travel <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Agency</span>
            </h1>
            <p class="text-slate-300 text-lg md:text-xl leading-relaxed max-w-3xl mx-auto">
                Your trusted travel partner, offering seamless trips and carefully planned itineraries. Our goal is to make every journey easy, safe, and unforgettable. From curated itineraries to seamless support, we ensure your journey starts the moment you dream of travelling.
            </p>
        </div>

        <!-- Staggered Image Gallery -->
        <div class="flex items-center justify-center gap-4 md:gap-6 lg:gap-8 px-4">
            <!-- Left Image - Tilted -->
            <div class="relative w-48 md:w-64 lg:w-72 transform -rotate-6 hover:-rotate-3 transition-all duration-500 group">
                <div class="rounded-2xl overflow-hidden shadow-2xl shadow-black/50 ring-1 ring-white/10">
                    <img src="https://images.unsplash.com/photo-1527631746610-bca00a040d60?w=500&q=80" alt="Hiking Adventure"
                         class="w-full h-56 md:h-72 lg:h-80 object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full opacity-20 blur-xl group-hover:opacity-40 transition-opacity"></div>
            </div>

            <!-- Center Image - Larger -->
            <div class="relative w-56 md:w-72 lg:w-80 transform -translate-y-8 hover:-translate-y-12 transition-all duration-500 group z-10">
                <div class="rounded-2xl overflow-hidden shadow-2xl shadow-black/50 ring-1 ring-white/10">
                    <img src="https://images.unsplash.com/photo-1501555088652-021faa106b9b?w=500&q=80" alt="Bridge Adventure"
                         class="w-full h-64 md:h-80 lg:h-96 object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-32 h-32 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full opacity-20 blur-xl group-hover:opacity-40 transition-opacity"></div>
            </div>

            <!-- Right Image - Tilted -->
            <div class="relative w-48 md:w-64 lg:w-72 transform rotate-6 hover:rotate-3 transition-all duration-500 group">
                <div class="rounded-2xl overflow-hidden shadow-2xl shadow-black/50 ring-1 ring-white/10">
                    <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=500&q=80" alt="Canyon View"
                         class="w-full h-56 md:h-72 lg:h-80 object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full opacity-20 blur-xl group-hover:opacity-40 transition-opacity"></div>
            </div>
        </div>
    </div>
</section>

<!-- Know About Us Section with Background Image -->
<section class="relative py-24 overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1920&q=80" alt="Mountains"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/80 to-slate-900/60"></div>
    </div>

    <div class="relative w-full px-6 lg:px-[8%]">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <!-- Left Content -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-8 h-0.5 bg-amber-500"></span>
                    <span class="text-amber-400 text-sm font-semibold tracking-widest uppercase">Know About Us</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                    We are your trusted travel companion
                </h2>
                <a href="{{ route('tour-packages.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-1">
                    <span>EXPLORE MORE</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <!-- Right Content -->
            <div class="text-slate-300 space-y-6">
                <p class="text-lg leading-relaxed">
                    <span class="text-white font-semibold">Travel Agency</span> is your trusted travel partner, offering seamless trips and carefully planned itineraries. Our goal is to make every journey easy, safe, and unforgettable.
                </p>
                <p class="leading-relaxed">
                    From curated itineraries to seamless eVisa support, we ensure your journey starts the moment you dream of travelling. With expert guidance, reliable service, and a passion for exploration, Travel Agency is your trusted travel companion.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Our Mission Section -->
<section class="py-24 bg-white">
    <div class="w-full px-6 lg:px-[8%]">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Left Content -->
            <div>
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg mb-6">
                    <span class="text-white text-sm font-semibold tracking-widest uppercase">Our Mission</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6 leading-tight">
                    We make travel accessible, enjoyable, and worry-free
                </h2>
                <div class="text-slate-600 space-y-4 leading-relaxed">
                    <p>
                        We aim to empower travelers with simple tools, personalised recommendations, and reliable support — ensuring everyone can explore the world confidently.
                    </p>
                    <p>
                        Our platform connects you with verified, professional guides who are passionate about sharing the beauty, culture, and hidden gems of Sri Lanka. Whether you're looking for a wildlife safari, a cultural journey, or a relaxing beach experience, our guides are here to create unforgettable memories for you.
                    </p>
                </div>
            </div>

            <!-- Right Image -->
            <div class="relative">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&q=80" alt="Hiking"
                         class="w-full h-[500px] object-cover">
                </div>
                <!-- Decorative elements -->
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-amber-100 rounded-2xl -z-10"></div>
                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl -z-10 opacity-20"></div>
            </div>
        </div>
    </div>
</section>

<!-- Our Journey / Stats Section -->
<section class="py-24 bg-slate-50">
    <div class="w-full px-6 lg:px-[8%]">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16">
            <!-- Left Content with Stats -->
            <div>
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg mb-6">
                    <span class="text-white text-sm font-semibold tracking-widest uppercase">Our Journey</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4 leading-tight">
                    How We Help Travelers Explore More
                </h2>
                <p class="text-slate-600 mb-10">
                    Since our beginning, we have helped thousands of travelers plan meaningful adventures with confidence.
                </p>

                <!-- Stats Grid -->
                <div class="grid grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-bold text-slate-900 mb-2">34K+</div>
                        <div class="text-sm text-slate-500">Destinations Searched</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-bold text-slate-900 mb-2">400+</div>
                        <div class="text-sm text-slate-500">Successful Trips</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl md:text-5xl font-bold text-slate-900 mb-2">20+</div>
                        <div class="text-sm text-slate-500">Travel Experts</div>
                    </div>
                </div>

                <!-- Decorative Image Grid -->
                <div class="mt-12 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl overflow-hidden shadow-lg h-64">
                        <img src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=500&q=80" alt="Camping"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-lg mt-8">
                        <img src="https://images.unsplash.com/photo-1530789253388-582c481c54b0?w=500&q=80" alt="Travel"
                             class="w-full h-64 object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
            </div>

            <!-- Right Side Images -->
            <div class="relative">
                <div class="sticky top-32 space-y-6">
                    <div class="rounded-2xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1519904981063-b0cf448d479e?w=800&q=80" alt="Adventure"
                             class="w-full h-[350px] object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1528543606781-2f6e6857f318?w=800&q=80" alt="Exploration"
                             class="w-full h-[250px] object-cover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Expert Services Section -->
<section class="py-24 bg-white">
    <div class="w-full px-6 lg:px-[8%]">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Left Images -->
            <div class="relative">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Main large image -->
                    <div class="rounded-2xl overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1533587851505-d119e13fa0d7?w=800&q=80" alt="Tent View"
                             class="w-full h-[400px] object-cover">
                    </div>
                    <!-- Bottom image with arrow decoration -->
                    <div class="relative">
                        <div class="rounded-2xl overflow-hidden shadow-lg">
                            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80" alt="Beach"
                                 class="w-full h-[250px] object-cover">
                        </div>
                        <!-- Arrow decoration -->
                        <div class="absolute -bottom-6 -right-6 w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content -->
            <div>
                <span class="text-amber-500 text-sm font-semibold tracking-wide">Welcome to Travel Agency</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-3 mb-4 leading-tight">
                    Choose Travel Agency with Our Expert Services
                </h2>
                <p class="text-slate-600 mb-10">
                    Discover the benefits that set us apart and make your travel experience smoother and more enjoyable.
                </p>

                <!-- Service List -->
                <div class="space-y-6">
                    <!-- Service Item 1 -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 mb-1">Expert Travel Planners</h3>
                            <p class="text-slate-600 text-sm">Our experienced travel specialists provide personalised guidance to craft itineraries suited to your budget, interests, and travel style.</p>
                        </div>
                    </div>

                    <!-- Service Item 2 -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 mb-1">Smart, Seamless Tools</h3>
                            <p class="text-slate-600 text-sm">Use our platform to explore destinations, compare travel options, and plan every detail with ease.</p>
                        </div>
                    </div>

                    <!-- Service Item 3 -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 mb-1">Reliable Support Anytime</h3>
                            <p class="text-slate-600 text-sm">From visa guidance to itinerary adjustments, our team is always ready to assist you every step of the way.</p>
                        </div>
                    </div>

                    <!-- Service Item 4 -->
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-6 h-6 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 mb-1">Tailored Travel Programs</h3>
                            <p class="text-slate-600 text-sm">Choose from a variety of curated tours and travel experiences — from budget trips to luxury escapes.</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="mt-10">
                    <a href="{{ route('tour-packages.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-1">
                        <span>Create Your Next Adventure</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us - Editorial Style -->
<section class="py-24 bg-slate-900 overflow-hidden">
    <div class="w-full px-6 lg:px-[8%]">
        <!-- Section Header -->
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-20">
            <div class="max-w-xl">
                <span class="text-amber-400 text-sm font-semibold tracking-widest uppercase">The Travel Agency Difference</span>
                <h2 class="text-4xl md:text-5xl font-bold text-white mt-4 leading-tight">
                    Travel with <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">confidence</span>
                </h2>
            </div>
            <p class="text-slate-400 max-w-md lg:text-right">
                We've reimagined travel planning to put you first. No compromises, no hidden surprises.
            </p>
        </div>

        <!-- Features Grid - Asymmetric Layout -->
        <div class="grid lg:grid-cols-12 gap-6 lg:gap-8">
            <!-- Large Feature 1 -->
            <div class="lg:col-span-7 group relative">
                <div class="relative h-full bg-gradient-to-br from-slate-800 to-slate-800/50 rounded-3xl p-8 lg:p-10 overflow-hidden">
                    <!-- Background accent -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-amber-500/10 to-orange-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

                    <div class="relative">
                        <span class="text-8xl lg:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-b from-amber-500/30 to-transparent leading-none">01</span>
                        <div class="mt-4 lg:mt-6">
                            <h3 class="text-2xl lg:text-3xl font-bold text-white mb-4">Verified Local Guides</h3>
                            <p class="text-slate-400 leading-relaxed max-w-lg">
                                Every guide on our platform passes a thorough verification process. We verify licenses, check backgrounds, and ensure years of local expertise — so you travel with trusted professionals who know their region inside out.
                            </p>
                        </div>
                        <div class="mt-8 flex items-center gap-4">
                            <div class="flex -space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 ring-2 ring-slate-800"></div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-red-500 ring-2 ring-slate-800"></div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-300 to-amber-500 ring-2 ring-slate-800"></div>
                            </div>
                            <span class="text-slate-500 text-sm">500+ verified guides</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stacked Features Right -->
            <div class="lg:col-span-5 flex flex-col gap-6 lg:gap-8">
                <!-- Feature 2 -->
                <div class="group relative flex-1">
                    <div class="h-full bg-gradient-to-br from-amber-500 to-orange-600 rounded-3xl p-8 overflow-hidden">
                        <span class="text-6xl lg:text-7xl font-black text-white/20 leading-none">02</span>
                        <div class="mt-2">
                            <h3 class="text-xl lg:text-2xl font-bold text-white mb-3">Transparent Pricing</h3>
                            <p class="text-white/80 leading-relaxed">
                                What you see is what you pay. No booking fees, no hidden charges. Connect directly with guides and get fair, competitive rates.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="group relative flex-1">
                    <div class="h-full bg-slate-800/80 rounded-3xl p-8 border border-slate-700/50 overflow-hidden">
                        <span class="text-6xl lg:text-7xl font-black text-transparent bg-clip-text bg-gradient-to-b from-slate-600 to-transparent leading-none">03</span>
                        <div class="mt-2">
                            <h3 class="text-xl lg:text-2xl font-bold text-white mb-3">Tailored Experiences</h3>
                            <p class="text-slate-400 leading-relaxed">
                                Your trip, your way. Share your preferences and we'll match you with guides who specialize in exactly what you're looking for.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Feature Strip -->
        <div class="mt-8 grid sm:grid-cols-3 gap-6">
            <div class="flex items-center gap-4 p-6 bg-slate-800/30 rounded-2xl border border-slate-700/30">
                <div class="text-3xl font-bold text-amber-400">24/7</div>
                <div class="text-slate-400 text-sm leading-tight">Support available<br>whenever you need</div>
            </div>
            <div class="flex items-center gap-4 p-6 bg-slate-800/30 rounded-2xl border border-slate-700/30">
                <div class="text-3xl font-bold text-amber-400">100%</div>
                <div class="text-slate-400 text-sm leading-tight">Secure payments<br>& data protection</div>
            </div>
            <div class="flex items-center gap-4 p-6 bg-slate-800/30 rounded-2xl border border-slate-700/30">
                <div class="text-3xl font-bold text-amber-400">4.9★</div>
                <div class="text-slate-400 text-sm leading-tight">Average rating from<br>thousands of travelers</div>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="relative py-24 overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-slate-100 to-amber-50"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-200 rounded-full blur-3xl opacity-30"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-orange-200 rounded-full blur-3xl opacity-30"></div>

    <div class="relative w-full px-6 lg:px-[8%]">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6 leading-tight">
                    Ready for Your Next Adventure?
                </h2>
                <a href="{{ route('tour-packages.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-1">
                    <span>GET STARTED</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <!-- Right Content -->
            <div>
                <h3 class="text-xl font-bold text-slate-900 mb-4">Your dream destination is just a click away.</h3>
                <p class="text-slate-600 leading-relaxed">
                    Start exploring breathtaking landscapes, vibrant cultures, and unforgettable experiences today. With Travel Agency, you're not just booking a trip — you're creating lifelong memories. Our team of travel experts is ready to help you plan the perfect adventure, whether it's a relaxing beach getaway or an adrenaline-pumping mountain expedition.
                </p>
                <div class="mt-6 flex items-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-semibold transition-colors">
                        <span>Contact Us</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('tour-requests.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-semibold transition-colors">
                        <span>Browse Tour Requests</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
