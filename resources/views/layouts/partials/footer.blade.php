<footer class="bg-slate-900 text-white relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange-500/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

    <!-- Main Footer Content -->
    <div class="relative w-full px-6 lg:px-[8%]">
        <!-- Top Section - CTA Banner -->
        <div class="py-12 border-b border-white/10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="text-center lg:text-left">
                    <h3 class="text-2xl md:text-3xl font-bold mb-2">Ready to explore Sri Lanka?</h3>
                    <p class="text-slate-400">Start your journey with us today</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('tour-packages.index') }}" class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-full hover:shadow-lg hover:shadow-amber-500/25 transition-all duration-300 text-center">
                        Browse Packages
                    </a>
                    <a href="{{ route('guide.register') }}" class="px-8 py-3 border border-white/20 text-white font-semibold rounded-full hover:bg-white/10 transition-all duration-300 text-center">
                        Become a Guide
                    </a>
                </div>
            </div>
        </div>

        <!-- Middle Section - Links Grid -->
        <div class="py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12">
            <!-- Brand Column -->
            <div class="lg:col-span-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3 mb-6 group">
                    <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold">Travel<span class="text-amber-400">Agency</span></span>
                </a>
                <p class="text-slate-400 leading-relaxed mb-8 max-w-sm">
                    Your gateway to authentic Sri Lankan experiences. Connect with local guides and discover hidden gems across the island.
                </p>

                <!-- Social Links - Clean Icons Only -->
                <div class="flex items-center gap-5">
                    <a href="#" class="text-slate-400 hover:text-amber-400 transition-colors duration-300" aria-label="Facebook">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-slate-400 hover:text-amber-400 transition-colors duration-300" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-slate-400 hover:text-amber-400 transition-colors duration-300" aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-slate-400 hover:text-amber-400 transition-colors duration-300" aria-label="YouTube">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-slate-400 hover:text-amber-400 transition-colors duration-300" aria-label="TikTok">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Explore Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white mb-6">Explore</h4>
                <ul class="space-y-4">
                    <li>
                        <a href="{{ url('/') }}" class="text-slate-400 hover:text-white transition-colors duration-300">Home</a>
                    </li>
                    <li>
                        <a href="{{ route('tour-packages.index') }}" class="text-slate-400 hover:text-white transition-colors duration-300">Tour Packages</a>
                    </li>
                    <li>
                        <a href="{{ route('tour-requests.index') }}" class="text-slate-400 hover:text-white transition-colors duration-300">Tour Requests</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="text-slate-400 hover:text-white transition-colors duration-300">About Us</a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="text-slate-400 hover:text-white transition-colors duration-300">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- For Guides Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white mb-6">For Guides</h4>
                <ul class="space-y-4">
                    <li>
                        <a href="{{ route('guide.register') }}" class="text-slate-400 hover:text-white transition-colors duration-300">Become a Guide</a>
                    </li>
                    @auth
                        @if(Auth::user()->user_type === 'guide')
                            <li>
                                <a href="{{ route('guide.dashboard') }}" class="text-slate-400 hover:text-white transition-colors duration-300">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ route('guide.plans.index') }}" class="text-slate-400 hover:text-white transition-colors duration-300">My Packages</a>
                            </li>
                        @endif
                    @endauth
                    <li>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">Guide Resources</a>
                    </li>
                    <li>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">FAQs</a>
                    </li>
                </ul>
            </div>

            <!-- Legal Links -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white mb-6">Legal</h4>
                <ul class="space-y-4">
                    <li>
                        <a href="{{ route('privacy-policy') }}" class="text-slate-400 hover:text-white transition-colors duration-300">Privacy Policy</a>
                    </li>
                    <li>
                        <a href="{{ route('terms') }}" class="text-slate-400 hover:text-white transition-colors duration-300">Terms of Service</a>
                    </li>
                    <li>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">Cookie Policy</a>
                    </li>
                    <li>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors duration-300">Refund Policy</a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="lg:col-span-2">
                <h4 class="text-sm font-semibold uppercase tracking-wider text-white mb-6">Contact</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                        </svg>
                        <span class="text-slate-400 text-sm">123 Galle Road,<br>Colombo 03, Sri Lanka</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                        <span class="text-slate-400 text-sm">+94 11 234 5678</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                        <span class="text-slate-400 text-sm">info@travelagency.lk</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="relative border-t border-white/10">
        <div class="w-full px-6 lg:px-[8%] py-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Copyright -->
                <p class="text-slate-500 text-sm">
                    &copy; {{ date('Y') }} TravelAgency. All rights reserved.
                </p>

                <!-- Payment Methods -->
                <div class="flex items-center gap-6">
                    <span class="text-slate-500 text-sm">We accept</span>
                    <div class="flex items-center gap-2">
                        <!-- Visa -->
                        <div class="w-12 h-8 bg-white rounded flex items-center justify-center">
                            <svg class="h-4" viewBox="0 0 780 500" fill="none">
                                <path d="M293.2 348.7l33.4-195.8h53.4l-33.4 195.8h-53.4zm246.8-191c-10.6-4-27.2-8.3-47.9-8.3-52.8 0-90 26.6-90.2 64.7-.3 28.2 26.5 43.9 46.8 53.2 20.8 9.6 27.8 15.7 27.7 24.3-.1 13.1-16.6 19.1-32 19.1-21.4 0-32.8-3-50.4-10.3l-6.9-3.1-7.5 44c12.5 5.5 35.6 10.3 59.6 10.5 56.1 0 92.5-26.3 92.9-67 .2-22.3-14-39.3-44.8-53.3-18.6-9.1-30.1-15.1-30-24.3 0-8.1 9.7-16.8 30.6-16.8 17.4-.3 30.1 3.5 40 7.5l4.8 2.3 7.3-42.5zm137.6-4.8h-41.3c-12.8 0-22.4 3.5-28 16.3l-79.3 179.5h56.1s9.2-24.1 11.2-29.4c6.1 0 60.5.1 68.3.1 1.6 6.9 6.5 29.3 6.5 29.3h49.6l-43.1-195.8zm-65.7 126.4c4.4-11.3 21.3-54.7 21.3-54.7-.3.5 4.4-11.3 7.1-18.7l3.6 16.9s10.2 46.8 12.4 56.5h-44.4zM247.1 152.9L194 290.1l-5.7-27.5c-9.9-31.7-40.6-66-75-83.2l47.8 169.1 56.5-.1 84.1-195.5h-54.6z" fill="#1A1F71"/>
                                <path d="M146.9 152.9H62.4l-.6 3.6c67 16.2 111.4 55.4 129.8 102.4l-18.7-90c-3.2-12.4-12.6-15.6-26-16z" fill="#F9A533"/>
                            </svg>
                        </div>
                        <!-- Mastercard -->
                        <div class="w-12 h-8 bg-white rounded flex items-center justify-center">
                            <svg class="h-5" viewBox="0 0 152 100" fill="none">
                                <circle cx="50" cy="50" r="40" fill="#EB001B"/>
                                <circle cx="102" cy="50" r="40" fill="#F79E1B"/>
                                <path d="M76 20.8c-10.4 8.4-17 21.2-17 35.2s6.6 26.8 17 35.2c10.4-8.4 17-21.2 17-35.2S86.4 29.2 76 20.8z" fill="#FF5F00"/>
                            </svg>
                        </div>
                        <!-- PayPal -->
                        <div class="w-12 h-8 bg-white rounded flex items-center justify-center">
                            <svg class="h-4" viewBox="0 0 124 33" fill="none">
                                <path d="M46.2 10.5h-7.8c-.5 0-1 .4-1.1.9l-3.2 19.8c-.1.4.2.8.6.8h3.7c.5 0 1-.4 1.1-.9l.9-5.4c.1-.5.5-.9 1.1-.9h2.5c5.2 0 8.2-2.5 9-7.5.4-2.2 0-3.9-.9-5.1-1.1-1.4-3-2.2-5.9-2.2v.5zm.9 7.4c-.4 2.8-2.6 2.8-4.6 2.8h-1.2l.8-5.1c0-.3.3-.5.6-.5h.6c1.4 0 2.7 0 3.4.8.4.5.5 1.2.4 2zm22.7-.1h-3.7c-.3 0-.6.2-.6.5l-.2 1-.3-.4c-.8-1.2-2.7-1.6-4.6-1.6-4.3 0-8 3.3-8.7 7.9-.4 2.3.1 4.5 1.4 6.1 1.2 1.4 2.8 2 4.8 2 3.4 0 5.3-2.2 5.3-2.2l-.2 1c-.1.4.2.8.6.8h3.4c.5 0 1-.4 1.1-.9l2-12.5c.1-.4-.2-.7-.3-.7zm-5.3 7.7c-.4 2.2-2.1 3.8-4.4 3.8-1.1 0-2-.4-2.6-1-.6-.7-.8-1.6-.6-2.7.3-2.2 2.1-3.8 4.3-3.8 1.1 0 2 .4 2.6 1 .6.7.8 1.6.7 2.7zm27.2-7.7h-3.8c-.4 0-.7.2-.9.5l-5.1 7.5-2.2-7.2c-.1-.5-.6-.8-1-.8h-3.7c-.5 0-.8.4-.7.9l4.1 12-3.9 5.4c-.3.4 0 1 .5 1h3.7c.4 0 .7-.2.9-.5l12.3-17.8c.3-.4 0-1-.2-1z" fill="#253B80"/>
                                <path d="M94.9 10.5h-7.8c-.5 0-1 .4-1.1.9l-3.2 19.8c-.1.4.2.8.6.8h4c.4 0 .7-.3.8-.6l.9-5.7c.1-.5.5-.9 1.1-.9h2.5c5.2 0 8.2-2.5 9-7.5.4-2.2 0-3.9-.9-5.1-1.1-1.4-3-2.2-5.9-2.2v.5zm.9 7.4c-.4 2.8-2.6 2.8-4.6 2.8h-1.2l.8-5.1c0-.3.3-.5.6-.5h.6c1.4 0 2.7 0 3.4.8.4.5.5 1.2.4 2zm22.7-.1h-3.7c-.3 0-.6.2-.6.5l-.2 1-.3-.4c-.8-1.2-2.7-1.6-4.6-1.6-4.3 0-8 3.3-8.7 7.9-.4 2.3.1 4.5 1.4 6.1 1.2 1.4 2.8 2 4.8 2 3.4 0 5.3-2.2 5.3-2.2l-.2 1c-.1.4.2.8.6.8h3.4c.5 0 1-.4 1.1-.9l2-12.5c.1-.4-.2-.7-.3-.7zm-5.3 7.7c-.4 2.2-2.1 3.8-4.4 3.8-1.1 0-2-.4-2.6-1-.6-.7-.8-1.6-.6-2.7.3-2.2 2.1-3.8 4.3-3.8 1.1 0 2 .4 2.6 1 .5.7.8 1.6.7 2.7z" fill="#179BD7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Made in Sri Lanka -->
                <p class="text-slate-500 text-sm flex items-center gap-2">
                    Made with
                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    in Sri Lanka
                </p>
            </div>
        </div>
    </div>
</footer>
