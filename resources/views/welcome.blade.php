@extends('layouts.public')

@php
    // Fetch featured tour packages
    $featuredPackages = \App\Models\GuidePlan::with('guide')
        ->where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->take(6)
        ->get();

    // Fetch featured tour requests
    $featuredRequests = \App\Models\TouristRequest::with('tourist')
        ->where('status', 'open')
        ->where('expires_at', '>', now())
        ->orderBy('created_at', 'desc')
        ->take(4)
        ->get();

    // Stats
    $totalPackages = \App\Models\GuidePlan::where('status', 'active')->count();
    $totalGuides = \App\Models\Guide::whereHas('user', fn ($q) => $q->where('status', 'active'))->count();
    $totalTravelers = \App\Models\Tourist::count();
@endphp

@section('content')
<style>
    /* ============================================
       PREMIUM TRAVEL HOMEPAGE STYLES
       ============================================ */

    /* Hero Slider Background */
    .hero-slider {
        position: relative;
        min-height: 100vh;
        overflow: hidden;
    }

    .hero-bg-slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .hero-bg-slide.active {
        opacity: 1;
    }

    .hero-bg-slide::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(12, 25, 41, 0.4) 0%, rgba(12, 25, 41, 0.85) 100%);
    }

    /* Hero Slider Card */
    .hero-slide-card {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        opacity: 0;
        transform: translateX(60px);
        transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }

    .hero-slide-card.active {
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
    }

    .hero-slide-card.prev {
        opacity: 0;
        transform: translateX(-60px);
    }

    /* Floating Elements */
    @keyframes float-slow {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(3deg); }
    }

    @keyframes float-reverse {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(20px) rotate(-2deg); }
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(2); opacity: 0; }
    }

    @keyframes text-reveal {
        0% {
            opacity: 0;
            transform: translateY(50px);
            filter: blur(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
            filter: blur(0);
        }
    }

    @keyframes line-grow {
        0% { width: 0; }
        100% { width: 100px; }
    }

    @keyframes fade-up {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .animate-float-slow { animation: float-slow 8s ease-in-out infinite; }
    .animate-float-reverse { animation: float-reverse 7s ease-in-out infinite; }
    .animate-text-reveal { animation: text-reveal 1s ease-out forwards; }
    .animate-fade-up { animation: fade-up 0.8s ease-out forwards; }

    /* Glassmorphism Card */
    .glass-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .glass-card:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
    }

    /* Premium Card Style */
    .premium-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    .premium-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 30px 60px rgba(245, 158, 11, 0.2);
    }

    .premium-card .card-image {
        position: relative;
        overflow: hidden;
    }

    .premium-card .card-image img {
        transition: transform 0.7s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .premium-card:hover .card-image img {
        transform: scale(1.15);
    }

    /* Magnetic Button */
    .magnetic-btn {
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .magnetic-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .magnetic-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .magnetic-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(245, 158, 11, 0.4);
    }

    /* Section Divider */
    .section-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(245, 158, 11, 0.5), transparent);
    }

    /* Scroll Animation */
    .reveal-on-scroll {
        opacity: 0;
        transform: translateY(60px);
        transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .reveal-on-scroll.revealed {
        opacity: 1;
        transform: translateY(0);
    }

    /* Experience Cards */
    .experience-card {
        position: relative;
        border-radius: 32px;
        overflow: hidden;
        aspect-ratio: 3/4;
    }

    .experience-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 40%, rgba(0,0,0,0.8) 100%);
        z-index: 1;
    }

    .experience-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }

    .experience-card:hover img {
        transform: scale(1.1);
    }

    /* Stats Counter */
    .stat-item {
        text-align: center;
        padding: 2rem;
    }

    .stat-number {
        font-size: 4rem;
        font-weight: 800;
        background: linear-gradient(135deg, #F59E0B 0%, #FF6B35 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
    }

    /* Marquee */
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .marquee-container {
        overflow: hidden;
        white-space: nowrap;
    }

    .marquee-content {
        display: inline-flex;
        animation: marquee 40s linear infinite;
    }

    .marquee-container:hover .marquee-content {
        animation-play-state: paused;
    }

    /* Testimonial Card */
    .testimonial-card {
        background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%);
        border-radius: 24px;
        padding: 2rem;
        position: relative;
    }

    .testimonial-card::before {
        content: '"';
        position: absolute;
        top: 1rem;
        left: 1.5rem;
        font-size: 6rem;
        font-family: Georgia, serif;
        color: rgba(245, 158, 11, 0.2);
        line-height: 1;
    }

    /* Cursor Glow Effect */
    .glow-effect {
        position: relative;
    }

    .glow-effect::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.3) 0%, transparent 70%);
        transform: translate(-50%, -50%);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .glow-effect:hover::after {
        opacity: 1;
    }

    /* Animated Underline */
    .animated-underline {
        position: relative;
        display: inline-block;
    }

    .animated-underline::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, #F59E0B, #FF6B35);
        border-radius: 2px;
        transition: width 0.4s ease;
    }

    .animated-underline:hover::after {
        width: 100%;
    }

    /* Image Mask */
    .image-mask {
        -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='45'/%3E%3C/svg%3E");
        mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='45'/%3E%3C/svg%3E");
        -webkit-mask-size: contain;
        mask-size: contain;
        -webkit-mask-repeat: no-repeat;
        mask-repeat: no-repeat;
    }

    /* Blob Shape */
    .blob-shape {
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        animation: blob-morph 8s ease-in-out infinite;
    }

    @keyframes blob-morph {
        0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
        50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
    }
</style>

<!-- ====================================
     HERO SECTION - Full Screen Slider
     ==================================== -->
@php
    $heroSlides = [
        [
            'bg' => 'https://images.pexels.com/photos/15953659/pexels-photo-15953659.jpeg?auto=compress&cs=tinysrgb&w=1920',
            'image' => 'https://images.pexels.com/photos/15953659/pexels-photo-15953659.jpeg?auto=compress&cs=tinysrgb&w=800',
            'title' => 'Sigiriya Rock Fortress',
            'location' => 'Central Province',
            'badge' => 'UNESCO Heritage',
            'description' => 'The 8th wonder of the world - an ancient palace rising 200m into the sky',
            'rating' => '4.9',
            'price' => '299'
        ],
        [
            'bg' => 'https://images.pexels.com/photos/1450353/pexels-photo-1450353.jpeg?auto=compress&cs=tinysrgb&w=1920',
            'image' => 'https://images.pexels.com/photos/1450353/pexels-photo-1450353.jpeg?auto=compress&cs=tinysrgb&w=800',
            'title' => 'Mirissa Beach Paradise',
            'location' => 'Southern Coast',
            'badge' => 'Whale Watching',
            'description' => 'Golden sands, turquoise waters, and the best whale watching in Asia',
            'rating' => '4.8',
            'price' => '199'
        ],
        [
            'bg' => 'https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg?auto=compress&cs=tinysrgb&w=1920',
            'image' => 'https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg?auto=compress&cs=tinysrgb&w=800',
            'title' => 'Ella Mountain Escape',
            'location' => 'Hill Country',
            'badge' => 'Adventure',
            'description' => 'Misty mountains, scenic train rides, and breathtaking hiking trails',
            'rating' => '4.7',
            'price' => '249'
        ],
        [
            'bg' => 'https://images.pexels.com/photos/5364610/pexels-photo-5364610.jpeg?auto=compress&cs=tinysrgb&w=1920',
            'image' => 'https://images.pexels.com/photos/5364610/pexels-photo-5364610.jpeg?auto=compress&cs=tinysrgb&w=800',
            'title' => 'Temple of the Sacred Tooth',
            'location' => 'Kandy',
            'badge' => 'Cultural',
            'description' => 'Sri Lanka\'s most sacred Buddhist temple in the heart of Kandy',
            'rating' => '4.9',
            'price' => '179'
        ],
    ];
@endphp

<section class="hero-slider -mt-20" x-data="{
    currentSlide: 0,
    totalSlides: {{ count($heroSlides) }},
    autoplay: null,
    init() {
        this.startAutoplay();
    },
    startAutoplay() {
        this.autoplay = setInterval(() => {
            this.nextSlide();
        }, 6000);
    },
    stopAutoplay() {
        clearInterval(this.autoplay);
    },
    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
    },
    prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
    },
    goToSlide(index) {
        this.currentSlide = index;
        this.stopAutoplay();
        this.startAutoplay();
    }
}">
    <!-- Background Slides -->
    @foreach($heroSlides as $index => $slide)
        <div class="hero-bg-slide"
             :class="{ 'active': currentSlide === {{ $index }} }"
             style="background-image: url('{{ $slide['bg'] }}');">
        </div>
    @endforeach

    <!-- Content -->
    <div class="relative z-10 min-h-screen flex items-center">
        <div class="w-full px-6 lg:px-[8%] py-32">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Left Content -->
                <div class="text-white">
                    <!-- Tagline -->
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-px w-12 bg-gradient-to-r from-amber-400 to-transparent"></div>
                        <span class="text-amber-400 font-medium tracking-widest uppercase text-sm">Explore Sri Lanka</span>
                    </div>

                    <!-- Main Headline -->
                    <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-bold leading-[0.95] mb-8">
                        <span class="block">Where</span>
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500">Adventure</span>
                        <span class="block">Begins</span>
                    </h1>

                    <!-- Description -->
                    <p class="text-xl text-white/70 max-w-xl mb-10 leading-relaxed">
                        Discover breathtaking landscapes, ancient temples, and pristine beaches.
                        Let our expert local guides craft your perfect Sri Lankan journey.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('tour-packages.index') }}"
                           class="magnetic-btn group px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-full inline-flex items-center gap-3">
                            <span class="relative z-10">Explore Tours</span>
                            <div class="relative z-10 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </a>

                        <a href="#experiences"
                           class="group px-8 py-4 border-2 border-white/30 hover:border-amber-400 text-white font-semibold rounded-full inline-flex items-center gap-3 transition-all duration-300 hover:bg-white/10">
                            <div class="w-10 h-10 border-2 border-white/50 rounded-full flex items-center justify-center group-hover:border-amber-400 group-hover:bg-amber-400/20 transition-all">
                                <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                            <span>Watch Video</span>
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="flex flex-wrap gap-12 mt-16 pt-8 border-t border-white/10">
                        <div>
                            <div class="text-4xl font-bold text-white mb-1">{{ number_format($totalTravelers) }}+</div>
                            <div class="text-white/50 text-sm">Happy Travelers</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-white mb-1">{{ $totalPackages }}+</div>
                            <div class="text-white/50 text-sm">Tour Packages</div>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-white mb-1">{{ $totalGuides }}+</div>
                            <div class="text-white/50 text-sm">Expert Guides</div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Slider Cards -->
                <div class="hidden lg:block">
                    <!-- Cards Container (relative for absolute positioned cards) -->
                    <div class="relative w-[380px] ml-auto" style="height: 420px;">
                        @foreach($heroSlides as $index => $slide)
                            <div class="hero-slide-card"
                             :class="{
                                'active': currentSlide === {{ $index }},
                                'prev': currentSlide === {{ ($index + 1) % count($heroSlides) }}
                             }">
                            <div class="bg-white rounded-2xl overflow-hidden shadow-2xl shadow-black/30">
                                <!-- Image -->
                                <div class="relative h-[220px] overflow-hidden">
                                        <img src="{{ $slide['image'] }}"
                                             alt="{{ $slide['title'] }}"
                                             class="w-full h-full object-cover">
                                        <!-- Gradient Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                                        <!-- Badges -->
                                        <div class="absolute top-4 left-4 flex items-center gap-2">
                                            <span class="px-3 py-1.5 bg-amber-500 rounded-full text-xs font-bold text-white shadow-lg">Featured</span>
                                            <span class="px-3 py-1.5 bg-white/90 backdrop-blur-sm rounded-full text-xs font-semibold text-slate-700">{{ $slide['badge'] }}</span>
                                        </div>

                                        <!-- Rating -->
                                        <div class="absolute top-4 right-4 flex items-center gap-1.5 px-3 py-1.5 bg-black/40 backdrop-blur-sm rounded-full">
                                            <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="text-white font-semibold text-sm">{{ $slide['rating'] }}</span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5">
                                        <!-- Location -->
                                        <div class="flex items-center gap-1.5 text-slate-500 mb-1">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            <span class="text-sm font-medium">{{ $slide['location'] }}</span>
                                        </div>

                                        <!-- Title -->
                                        <h3 class="font-display text-xl font-bold text-slate-900 mb-1">{{ $slide['title'] }}</h3>

                                        <!-- Description -->
                                        <p class="text-slate-500 text-sm mb-3 line-clamp-2">{{ $slide['description'] }}</p>

                                        <!-- Price & CTA -->
                                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                            <div>
                                                <span class="text-slate-400 text-xs">From</span>
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-xl font-bold text-slate-900">${{ $slide['price'] }}</span>
                                                    <span class="text-slate-400 text-sm">/person</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('tour-packages.index') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm rounded-xl transition-colors shadow-lg shadow-amber-500/30">
                                                View Tour
                                            </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Slider Navigation -->
                    <div class="flex items-center justify-center gap-4 mt-6 w-[380px] ml-auto">
                        <!-- Prev Button -->
                        <button @click="prevSlide(); stopAutoplay(); startAutoplay();"
                                class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white hover:text-slate-900 text-white flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <!-- Dots -->
                        <div class="flex items-center gap-3">
                            @foreach($heroSlides as $index => $slide)
                                <button @click="goToSlide({{ $index }})"
                                        class="h-3 rounded-full transition-all duration-300"
                                        :class="currentSlide === {{ $index }} ? 'w-10 bg-amber-500' : 'w-3 bg-white/40 hover:bg-white/60'">
                                </button>
                            @endforeach
                        </div>

                        <!-- Next Button -->
                        <button @click="nextSlide(); stopAutoplay(); startAutoplay();"
                                class="w-12 h-12 rounded-full bg-amber-500 hover:bg-amber-600 text-white flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg shadow-amber-500/40">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-20">
        <a href="#experiences" class="flex flex-col items-center gap-2 text-white/50 hover:text-amber-400 transition-colors">
            <span class="text-xs tracking-widest uppercase">Scroll</span>
            <div class="w-6 h-10 border-2 border-current rounded-full flex justify-center pt-2">
                <div class="w-1 h-2 bg-current rounded-full animate-bounce"></div>
            </div>
        </a>
    </div>
</section>

<!-- ====================================
     DESTINATION MARQUEE
     ==================================== -->
<section class="py-6 bg-slate-900 border-y border-white/10">
    <div class="marquee-container">
        <div class="marquee-content">
            @php
                $destinations = ['Sigiriya', 'Kandy', 'Galle', 'Ella', 'Mirissa', 'Nuwara Eliya', 'Yala', 'Trincomalee', 'Anuradhapura', 'Polonnaruwa'];
            @endphp
            @foreach($destinations as $dest)
                <span class="inline-flex items-center gap-4 px-8 text-white/40 text-lg font-medium">
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                    {{ $dest }}
                </span>
            @endforeach
            @foreach($destinations as $dest)
                <span class="inline-flex items-center gap-4 px-8 text-white/40 text-lg font-medium">
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                    {{ $dest }}
                </span>
            @endforeach
        </div>
    </div>
</section>

<!-- ====================================
     EXPERIENCES SECTION
     ==================================== -->
<section id="experiences" class="py-24 bg-white">
    <div class="w-full px-6 lg:px-[8%]">
        <!-- Section Header -->
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-16" data-aos="fade-up">
            <div class="max-w-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    <span class="text-amber-600 font-semibold uppercase tracking-wider text-sm">Curated Experiences</span>
                </div>
                <h2 class="font-display text-4xl lg:text-6xl font-bold text-slate-900 leading-tight">
                    Unforgettable<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500">Moments</span> Await
                </h2>
            </div>
            <p class="text-slate-500 text-lg max-w-md lg:text-right">
                From misty mountains to golden beaches, experience the diverse beauty of Sri Lanka through our handpicked adventures.
            </p>
        </div>

        <!-- Experiences Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $experiences = [
                    ['title' => 'Wildlife Safari', 'image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=600&q=80', 'count' => '12 Tours'],
                    ['title' => 'Beach Escapes', 'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80', 'count' => '18 Tours'],
                    ['title' => 'Cultural Heritage', 'image' => 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?w=600&q=80', 'count' => '24 Tours'],
                    ['title' => 'Mountain Trails', 'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=80', 'count' => '15 Tours'],
                ];
            @endphp

            @foreach($experiences as $index => $exp)
                <a href="{{ route('tour-packages.index') }}"
                   class="experience-card group"
                   data-aos="fade-up"
                   data-aos-delay="{{ $index * 100 }}">
                    <img src="{{ $exp['image'] }}" alt="{{ $exp['title'] }}" class="absolute inset-0 w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 p-6 z-10">
                        <span class="inline-block px-3 py-1 bg-amber-500/80 backdrop-blur-sm rounded-full text-xs font-medium text-white mb-3">{{ $exp['count'] }}</span>
                        <h3 class="font-display text-2xl font-bold text-white group-hover:text-amber-300 transition-colors">{{ $exp['title'] }}</h3>
                    </div>
                    <!-- Arrow -->
                    <div class="absolute top-4 right-4 w-10 h-10 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all z-10 group-hover:bg-amber-500">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ====================================
     FEATURED PACKAGES SECTION
     ==================================== -->
@if($featuredPackages->isNotEmpty())
<section class="py-24 bg-gradient-to-b from-slate-50 to-white">
    <div class="w-full px-6 lg:px-[8%]">
        <!-- Section Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-8 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                <span class="text-amber-600 font-semibold uppercase tracking-wider text-sm">Popular Packages</span>
                <div class="w-8 h-1 bg-gradient-to-r from-orange-500 to-amber-500 rounded-full"></div>
            </div>
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-slate-900 mb-4">
                Trending Tour Packages
            </h2>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">
                Explore our most loved itineraries, crafted by expert guides and loved by thousands
            </p>
        </div>

        <!-- Packages Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredPackages->take(3) as $index => $plan)
                <a href="{{ route('tour-packages.show', $plan) }}"
                   class="premium-card group"
                   data-aos="fade-up"
                   data-aos-delay="{{ $index * 100 }}">
                    <!-- Image -->
                    <div class="card-image relative h-72">
                        @if($plan->cover_photo)
                            <img src="{{ Storage::url($plan->cover_photo) }}" alt="{{ $plan->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                                <svg class="w-20 h-20 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @endif

                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                        <!-- Duration Badge -->
                        <div class="absolute top-4 left-4 px-4 py-2 bg-white/95 backdrop-blur-sm rounded-full flex items-center gap-2 shadow-lg">
                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-slate-700 font-semibold text-sm">{{ $plan->num_days }} Days</span>
                        </div>

                        <!-- Wishlist Button -->
                        <button class="absolute top-4 right-4 w-10 h-10 bg-white/95 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:bg-red-50 hover:scale-110">
                            <svg class="w-5 h-5 text-slate-400 hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Rating & Location -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-1">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 {{ $i < 4 ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="text-slate-500 text-sm ml-1">({{ rand(15, 99) }})</span>
                            </div>
                            <div class="flex items-center gap-1 text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="text-sm">Sri Lanka</span>
                            </div>
                        </div>

                        <!-- Title -->
                        <h3 class="font-display text-xl font-bold text-slate-900 mb-3 group-hover:text-amber-600 transition-colors line-clamp-2">
                            {{ $plan->title }}
                        </h3>

                        <!-- Guide Info -->
                        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($plan->guide->full_name ?? 'G', 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-slate-700">{{ $plan->guide->full_name ?? 'Expert Guide' }}</div>
                                <div class="text-xs text-slate-400">Local Expert</div>
                            </div>
                        </div>

                        <!-- Price & CTA -->
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-slate-400 text-sm">From</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-bold text-slate-900">${{ number_format($plan->price_per_adult) }}</span>
                                    <span class="text-slate-400 text-sm">/person</span>
                                </div>
                            </div>
                            <div class="px-5 py-2.5 bg-slate-900 hover:bg-amber-500 text-white rounded-full font-medium text-sm transition-colors group-hover:shadow-lg">
                                View Details
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- View All -->
        <div class="text-center mt-12" data-aos="fade-up">
            <a href="{{ route('tour-packages.index') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-semibold text-lg group">
                View All Packages
                <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>
@endif

<!-- ====================================
     WHY CHOOSE US SECTION
     ==================================== -->
<section class="py-24 bg-slate-900 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full">
        <div class="absolute top-20 left-20 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full px-6 lg:px-[8%]">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- Left - Image Grid -->
            <div class="relative" data-aos="fade-right">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <div class="rounded-3xl overflow-hidden h-48">
                            <img src="https://images.unsplash.com/photo-1539635278303-d4002c07eae3?w=400&q=80" alt="Travelers" class="w-full h-full object-cover">
                        </div>
                        <div class="rounded-3xl overflow-hidden h-64 blob-shape">
                            <img src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=400&q=80" alt="Adventure" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="space-y-4 pt-8">
                        <div class="rounded-3xl overflow-hidden h-64">
                            <img src="https://images.unsplash.com/photo-1530789253388-582c481c54b0?w=400&q=80" alt="Beach" class="w-full h-full object-cover">
                        </div>
                        <div class="rounded-3xl overflow-hidden h-48">
                            <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=400&q=80" alt="Nature" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="absolute -bottom-6 -right-6 lg:-right-12 glass-card rounded-2xl p-6 z-10">
                    <div class="text-4xl font-bold text-white mb-1">98%</div>
                    <div class="text-white/60 text-sm">Customer Satisfaction</div>
                </div>
            </div>

            <!-- Right - Content -->
            <div data-aos="fade-left">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    <span class="text-amber-400 font-semibold uppercase tracking-wider text-sm">Why Choose Us</span>
                </div>

                <h2 class="font-display text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                    We Create <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-400">Experiences</span>, Not Just Tours
                </h2>

                <p class="text-white/60 text-lg mb-10 leading-relaxed">
                    Our commitment to excellence and passion for travel has made us the trusted choice for thousands of adventurers exploring Sri Lanka.
                </p>

                <!-- Features -->
                <div class="space-y-6">
                    @php
                        $features = [
                            ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Verified Local Guides', 'desc' => 'All our guides are background-checked and certified professionals'],
                            ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Best Price Guarantee', 'desc' => 'Found it cheaper? We\'ll match the price plus give you 10% off'],
                            ['icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z', 'title' => '24/7 Support', 'desc' => 'Round-the-clock assistance for a worry-free travel experience'],
                        ];
                    @endphp

                    @foreach($features as $feature)
                        <div class="flex gap-4 group">
                            <div class="w-14 h-14 bg-amber-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-amber-500 transition-colors duration-300">
                                <svg class="w-7 h-7 text-amber-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold text-lg mb-1">{{ $feature['title'] }}</h4>
                                <p class="text-white/50">{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    <a href="{{ route('about') }}" class="inline-flex items-center gap-2 text-amber-400 hover:text-amber-300 font-semibold group">
                        Learn More About Us
                        <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ====================================
     TOUR REQUESTS SECTION
     ==================================== -->
@if($featuredRequests->isNotEmpty())
<section class="py-24 bg-gradient-to-b from-amber-50 to-white">
    <div class="w-full px-6 lg:px-[8%]">
        <!-- Section Header -->
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-16" data-aos="fade-up">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    <span class="text-amber-600 font-semibold uppercase tracking-wider text-sm">Open Requests</span>
                </div>
                <h2 class="font-display text-4xl lg:text-5xl font-bold text-slate-900">
                    Travelers Seeking Guides
                </h2>
            </div>
            <a href="{{ route('tour-requests.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-amber-500 text-white rounded-full font-medium transition-colors">
                View All Requests
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        <!-- Requests Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredRequests as $index => $request)
                <a href="{{ route('tour-requests.show', $request) }}"
                   class="group bg-white rounded-3xl p-6 shadow-lg shadow-amber-500/5 hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-500 hover:-translate-y-2 border border-amber-100"
                   data-aos="fade-up"
                   data-aos-delay="{{ $index * 100 }}">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full text-xs font-bold text-white">
                            {{ $request->duration_days }} Days
                        </span>
                        <div class="flex items-center gap-1 text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs">{{ $request->expires_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Title -->
                    <h3 class="font-display font-bold text-lg text-slate-900 mb-4 group-hover:text-amber-600 transition-colors line-clamp-2 min-h-[3.5rem]">
                        {{ $request->title }}
                    </h3>

                    <!-- Budget -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-2xl p-4 mb-4">
                        <div class="text-xs text-slate-500 mb-1">Budget Range</div>
                        <div class="text-xl font-bold text-slate-900">
                            ${{ number_format($request->budget_min) }} -
                            <span class="text-amber-600">${{ number_format($request->budget_max) }}</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                </svg>
                            </div>
                            <span class="text-sm text-slate-500">{{ $request->bids_count ?? 0 }} proposals</span>
                        </div>
                        <div class="w-8 h-8 bg-slate-100 group-hover:bg-amber-500 rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ====================================
     TESTIMONIALS SECTION
     ==================================== -->
<section class="py-24 bg-white">
    <div class="w-full px-6 lg:px-[8%]">
        <!-- Section Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-8 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                <span class="text-amber-600 font-semibold uppercase tracking-wider text-sm">Testimonials</span>
                <div class="w-8 h-1 bg-gradient-to-r from-orange-500 to-amber-500 rounded-full"></div>
            </div>
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-slate-900 mb-4">
                What Our Travelers Say
            </h2>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid md:grid-cols-3 gap-8">
            @php
                $testimonials = [
                    ['name' => 'Sarah Johnson', 'location' => 'United States', 'text' => 'An absolutely magical experience! Our guide knew all the hidden gems and made our Sri Lanka trip unforgettable. The sunrise at Sigiriya was breathtaking!', 'avatar' => 'https://i.pravatar.cc/100?img=1'],
                    ['name' => 'Marcus Weber', 'location' => 'Germany', 'text' => 'Professional service from start to finish. The attention to detail and personalized itinerary exceeded our expectations. Highly recommend!', 'avatar' => 'https://i.pravatar.cc/100?img=3'],
                    ['name' => 'Emma Thompson', 'location' => 'Australia', 'text' => 'The wildlife safari in Yala was incredible! Saw leopards, elephants, and so much more. Our guide\'s knowledge of the local wildlife was amazing.', 'avatar' => 'https://i.pravatar.cc/100?img=5'],
                ];
            @endphp

            @foreach($testimonials as $index => $testimonial)
                <div class="testimonial-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <!-- Stars -->
                    <div class="flex gap-1 mb-4 relative z-10">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-amber-500 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>

                    <!-- Text -->
                    <p class="text-slate-700 leading-relaxed mb-6 relative z-10">
                        "{{ $testimonial['text'] }}"
                    </p>

                    <!-- Author -->
                    <div class="flex items-center gap-4 relative z-10">
                        <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <div class="font-semibold text-slate-900">{{ $testimonial['name'] }}</div>
                            <div class="text-sm text-slate-500">{{ $testimonial['location'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ====================================
     CTA SECTION
     ==================================== -->
<section class="py-24 bg-gradient-to-br from-amber-500 via-orange-500 to-amber-600 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
            </pattern>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>

    <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-xl animate-float-slow"></div>
    <div class="absolute bottom-10 right-10 w-48 h-48 bg-white/10 rounded-full blur-xl animate-float-reverse"></div>

    <div class="relative w-full px-6 lg:px-[8%] text-center">
        <div data-aos="zoom-in">
            <h2 class="font-display text-4xl lg:text-6xl font-bold text-white mb-6">
                Ready to Start Your Adventure?
            </h2>
            <p class="text-white/80 text-xl max-w-2xl mx-auto mb-10">
                Join thousands of happy travelers and create memories that last a lifetime. Your Sri Lankan journey awaits!
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('tour-packages.index') }}"
                   class="magnetic-btn px-10 py-5 bg-white text-amber-600 font-bold rounded-full inline-flex items-center justify-center gap-3 text-lg shadow-2xl shadow-black/20 hover:shadow-black/30">
                    <span class="relative z-10">Explore Tours</span>
                    <svg class="relative z-10 w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>

                @guest
                    <a href="{{ route('register') }}"
                       class="px-10 py-5 border-2 border-white text-white font-bold rounded-full inline-flex items-center justify-center gap-3 text-lg hover:bg-white hover:text-amber-600 transition-all duration-300">
                        <span>Get Started Free</span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>

<!-- ====================================
     JAVASCRIPT
     ==================================== -->
<script>
    // Reveal on scroll animation
    const revealElements = document.querySelectorAll('.reveal-on-scroll');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, { threshold: 0.1 });

    revealElements.forEach(el => revealObserver.observe(el));
</script>
@endsection
