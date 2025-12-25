<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-800">SriLanka<span class="text-emerald-600">Tours</span></span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-emerald-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->is('/') ? 'text-emerald-600 border-b-2 border-emerald-600' : '' }}">
                    Home
                </a>
                <a href="{{ route('tour-packages.index') }}" class="text-gray-700 hover:text-emerald-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('tour-packages.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : '' }}">
                    Tour Packages
                </a>
                <a href="{{ route('tour-requests.index') }}" class="text-gray-700 hover:text-emerald-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('tour-requests.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : '' }}">
                    Tour Requests
                </a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-emerald-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-emerald-600 border-b-2 border-emerald-600' : '' }}">
                    About
                </a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-emerald-600 px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('contact') ? 'text-emerald-600 border-b-2 border-emerald-600' : '' }}">
                    Contact
                </a>
            </div>

            <!-- Auth Section -->
            <div class="hidden md:flex md:items-center md:space-x-4">
                @auth
                    @include('layouts.partials.profile-dropdown')
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-600 px-4 py-2 text-sm font-medium transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Sign Up
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button type="button" onclick="toggleMobileMenu()" class="text-gray-700 hover:text-emerald-600 p-2">
                    <svg id="menu-icon" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="close-icon" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ url('/') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg {{ request()->is('/') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                Home
            </a>
            <a href="{{ route('tour-packages.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg {{ request()->routeIs('tour-packages.*') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                Tour Packages
            </a>
            <a href="{{ route('tour-requests.index') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg {{ request()->routeIs('tour-requests.*') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                Tour Requests
            </a>
            <a href="{{ route('about') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg {{ request()->routeIs('about') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                About
            </a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg {{ request()->routeIs('contact') ? 'bg-emerald-50 text-emerald-600' : '' }}">
                Contact
            </a>

            @auth
                <div class="border-t pt-3 mt-3">
                    <div class="px-3 py-2 text-sm text-gray-500">
                        {{ Auth::user()->email }}
                    </div>
                    <a href="{{ Auth::user()->user_type === 'guide' ? route('guide.dashboard') : route('tourist.dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg">
                        Dashboard
                    </a>
                    <a href="{{ Auth::user()->user_type === 'guide' ? route('guide.settings') : route('tourist.settings') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg">
                        Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="border-t pt-3 mt-3 space-y-2">
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 bg-emerald-600 text-white text-center rounded-lg hover:bg-emerald-700">
                        Sign Up
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        mobileMenu.classList.toggle('hidden');
        menuIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    }
</script>
