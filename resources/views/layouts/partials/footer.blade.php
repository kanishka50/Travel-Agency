<footer class="bg-secondary-900 text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 hero-pattern opacity-5"></div>

    <!-- Gradient Overlay -->
    <div class="absolute top-0 left-0 w-1/2 h-full bg-gradient-to-r from-white/5 via-white/2 to-transparent pointer-events-none"></div>

    <div class="relative w-full px-4 sm:px-6 lg:px-[5%] xl:px-[6%] pt-16 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <!-- Brand -->
            <div class="lg:col-span-1">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 mb-6 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-display font-bold text-white">Travel<span class="text-amber-400">Agency</span></span>
                        <p class="text-xs text-white/50">Discover Sri Lanka</p>
                    </div>
                </a>
                <p class="text-secondary-400 mb-6 leading-relaxed text-sm">
                    Experience a whole new journey with TravelAgency - your smart travel companion that helps you explore beautiful destinations with ease and comfort.
                </p>

                <!-- Social Links -->
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-amber-500/20 hover:bg-amber-500 rounded-full flex items-center justify-center transition-all duration-300 group">
                        <svg class="w-5 h-5 text-amber-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-amber-500/20 hover:bg-amber-500 rounded-full flex items-center justify-center transition-all duration-300 group">
                        <svg class="w-5 h-5 text-amber-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-amber-500/20 hover:bg-amber-500 rounded-full flex items-center justify-center transition-all duration-300 group">
                        <svg class="w-5 h-5 text-amber-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-amber-500/20 hover:bg-amber-500 rounded-full flex items-center justify-center transition-all duration-300 group">
                        <svg class="w-5 h-5 text-amber-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-display font-semibold text-lg mb-6 text-white">Quick Links</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/') }}" class="flex items-center gap-2 text-secondary-400 hover:text-amber-400 transition-colors duration-300 group">
                            <svg class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tour-packages.index') }}" class="flex items-center gap-2 text-secondary-400 hover:text-amber-400 transition-colors duration-300 group">
                            <svg class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Tour Packages</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tour-requests.index') }}" class="flex items-center gap-2 text-secondary-400 hover:text-amber-400 transition-colors duration-300 group">
                            <svg class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Tour Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="flex items-center gap-2 text-secondary-400 hover:text-amber-400 transition-colors duration-300 group">
                            <svg class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>About Us</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="flex items-center gap-2 text-secondary-400 hover:text-amber-400 transition-colors duration-300 group">
                            <svg class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Contact Us</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Get In Touch -->
            <div>
                <h4 class="font-display font-semibold text-lg mb-6 text-white">Get In Touch</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-amber-500/20 rounded-full flex-shrink-0 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-secondary-400 text-sm">+94 11 234 5678</p>
                            <p class="text-secondary-400 text-sm">+94 77 123 4567</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-amber-500/20 rounded-full flex-shrink-0 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-secondary-400 text-sm">info@travelagency.lk</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-amber-500/20 rounded-full flex-shrink-0 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-secondary-400 text-sm">123 Galle Road,<br>Colombo 03, Sri Lanka</p>
                    </li>
                </ul>
            </div>

            <!-- For Guides & Legal -->
            <div>
                <h4 class="font-display font-semibold text-lg mb-6 text-white">For Guides</h4>
                <ul class="space-y-3 mb-8">
                    <li>
                        <a href="{{ route('guide.register') }}" class="flex items-center gap-2 text-secondary-400 hover:text-amber-400 transition-colors duration-300 group">
                            <svg class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Become a Guide</span>
                        </a>
                    </li>
                    @auth
                        @if(Auth::user()->user_type === 'guide')
                            <li>
                                <a href="{{ route('guide.dashboard') }}" class="flex items-center gap-2 text-secondary-400 hover:text-amber-400 transition-colors duration-300 group">
                                    <svg class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    <span>Guide Dashboard</span>
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <h4 class="font-display font-semibold text-lg mb-6 text-white">Legal</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('privacy-policy') }}" class="flex items-center gap-2 text-secondary-400 hover:text-amber-400 transition-colors duration-300 group">
                            <svg class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Privacy Policy</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="flex items-center gap-2 text-secondary-400 hover:text-amber-400 transition-colors duration-300 group">
                            <svg class="w-4 h-4 text-amber-500 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span>Terms of Service</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-secondary-800 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-secondary-400 text-sm">
                    &copy; {{ date('Y') }} TravelAgency. All rights reserved.
                </p>
                <div class="flex items-center gap-4">
                    <span class="text-secondary-400 text-sm">We Accept</span>
                    <div class="flex items-center gap-2">
                        <!-- Visa -->
                        <div class="w-10 h-6 bg-white rounded flex items-center justify-center">
                            <svg class="w-8 h-4" viewBox="0 0 50 16" fill="none">
                                <path d="M19.5 1L16.5 15H13L16 1H19.5Z" fill="#1A1F71"/>
                                <path d="M32 1L28.5 15H25L28.5 1H32Z" fill="#1A1F71"/>
                                <path d="M11 1L6.5 10.5L5.5 5.5C5.3 4.5 4.5 3.5 3.5 3L0 15H4L10 1H11Z" fill="#1A1F71"/>
                                <path d="M35 1C33 1 31.5 2 31 4L36 15H40L45 1H35Z" fill="#1A1F71"/>
                            </svg>
                        </div>
                        <!-- Mastercard -->
                        <div class="w-10 h-6 bg-white rounded flex items-center justify-center">
                            <svg class="w-8 h-5" viewBox="0 0 50 30" fill="none">
                                <circle cx="18" cy="15" r="12" fill="#EB001B"/>
                                <circle cx="32" cy="15" r="12" fill="#F79E1B"/>
                                <path d="M25 6C27.5 8 29 11.5 29 15C29 18.5 27.5 22 25 24C22.5 22 21 18.5 21 15C21 11.5 22.5 8 25 6Z" fill="#FF5F00"/>
                            </svg>
                        </div>
                        <!-- PayPal -->
                        <div class="w-10 h-6 bg-white rounded flex items-center justify-center">
                            <span class="text-xs font-bold text-blue-800">Pay</span>
                        </div>
                    </div>
                </div>
                <p class="text-secondary-400 text-sm flex items-center gap-1">
                    Made with <span class="text-red-500 animate-pulse">&hearts;</span> in Sri Lanka
                </p>
            </div>
        </div>
    </div>
</footer>
