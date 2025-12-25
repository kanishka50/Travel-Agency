<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="col-span-1 md:col-span-2">
                <a href="{{ url('/') }}" class="flex items-center space-x-2 mb-4">
                    <svg class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xl font-bold">SriLanka<span class="text-emerald-500">Tours</span></span>
                </a>
                <p class="text-gray-400 mb-4 max-w-md">
                    Discover the beauty of Sri Lanka with our expert local guides. From ancient temples to pristine beaches,
                    we create unforgettable travel experiences tailored just for you.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-emerald-500 transition-colors">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-emerald-500 transition-colors">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-emerald-500 transition-colors">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-emerald-500 transition-colors">Home</a></li>
                    <li><a href="{{ route('tour-packages.index') }}" class="text-gray-400 hover:text-emerald-500 transition-colors">Tour Packages</a></li>
                    <li><a href="{{ route('tour-requests.index') }}" class="text-gray-400 hover:text-emerald-500 transition-colors">Tour Requests</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-emerald-500 transition-colors">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-emerald-500 transition-colors">Contact Us</a></li>
                </ul>
            </div>

            <!-- For Guides -->
            <div>
                <h3 class="text-lg font-semibold mb-4">For Guides</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('guide.register') }}" class="text-gray-400 hover:text-emerald-500 transition-colors">Become a Guide</a></li>
                    @auth
                        @if(Auth::user()->user_type === 'guide')
                            <li><a href="{{ route('guide.dashboard') }}" class="text-gray-400 hover:text-emerald-500 transition-colors">Guide Dashboard</a></li>
                        @endif
                    @endauth
                </ul>

                <h3 class="text-lg font-semibold mb-4 mt-6">Legal</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('privacy-policy') }}" class="text-gray-400 hover:text-emerald-500 transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-gray-400 hover:text-emerald-500 transition-colors">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm">
                &copy; {{ date('Y') }} SriLankaTours. All rights reserved.
            </p>
            <p class="text-gray-400 text-sm mt-2 md:mt-0">
                Made with <span class="text-red-500">&hearts;</span> in Sri Lanka
            </p>
        </div>
    </div>
</footer>
