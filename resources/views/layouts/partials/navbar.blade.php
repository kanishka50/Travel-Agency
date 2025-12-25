<nav x-data="{
    mobileMenuOpen: false,
    scrolled: false,
    isHeroPage: {{ request()->is('/') ? 'true' : 'false' }}
}"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
    :class="{
        'navbar-scrolled': scrolled,
        'bg-transparent': !scrolled && isHeroPage,
        'bg-white shadow-lg': !isHeroPage || scrolled
    }"
    class="fixed w-full top-0 z-50 transition-all duration-300">

    <div class="w-full px-4 sm:px-6 lg:px-[5%] xl:px-[6%]">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                    <!-- Bee/Honey themed logo -->
                    <div class="relative">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        <!-- Floating bee accent -->
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-amber-300 rounded-full animate-pulse"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-display font-bold" :class="{ 'text-white': !scrolled && isHeroPage, 'text-secondary-800': scrolled || !isHeroPage }">
                            Travel<span class="text-amber-500">Agency</span>
                        </span>
                        <span class="text-xs font-medium" :class="{ 'text-white/70': !scrolled && isHeroPage, 'text-secondary-400': scrolled || !isHeroPage }">
                            Discover Sri Lanka
                        </span>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="{{ url('/') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 rounded-full group"
                   :class="{
                       'text-white hover:text-amber-300': !scrolled && isHeroPage,
                       'text-secondary-700 hover:text-amber-600': scrolled || !isHeroPage,
                       'text-amber-500': {{ request()->is('/') ? 'true' : 'false' }}
                   }">
                    Home
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-amber-500 group-hover:w-1/2 transition-all duration-300 rounded-full"></span>
                    @if(request()->is('/'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1/2 h-0.5 bg-amber-500 rounded-full"></span>
                    @endif
                </a>

                <a href="{{ route('tour-packages.index') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 rounded-full group"
                   :class="{
                       'text-white hover:text-amber-300': !scrolled && isHeroPage,
                       'text-secondary-700 hover:text-amber-600': scrolled || !isHeroPage,
                       'text-amber-500': {{ request()->routeIs('tour-packages.*') ? 'true' : 'false' }}
                   }">
                    Tour Packages
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-amber-500 group-hover:w-1/2 transition-all duration-300 rounded-full"></span>
                    @if(request()->routeIs('tour-packages.*'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1/2 h-0.5 bg-amber-500 rounded-full"></span>
                    @endif
                </a>

                <a href="{{ route('tour-requests.index') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 rounded-full group"
                   :class="{
                       'text-white hover:text-amber-300': !scrolled && isHeroPage,
                       'text-secondary-700 hover:text-amber-600': scrolled || !isHeroPage,
                       'text-amber-500': {{ request()->routeIs('tour-requests.*') ? 'true' : 'false' }}
                   }">
                    Tour Requests
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-amber-500 group-hover:w-1/2 transition-all duration-300 rounded-full"></span>
                    @if(request()->routeIs('tour-requests.*'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1/2 h-0.5 bg-amber-500 rounded-full"></span>
                    @endif
                </a>

                <a href="{{ route('about') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 rounded-full group"
                   :class="{
                       'text-white hover:text-amber-300': !scrolled && isHeroPage,
                       'text-secondary-700 hover:text-amber-600': scrolled || !isHeroPage,
                       'text-amber-500': {{ request()->routeIs('about') ? 'true' : 'false' }}
                   }">
                    About
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-amber-500 group-hover:w-1/2 transition-all duration-300 rounded-full"></span>
                    @if(request()->routeIs('about'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1/2 h-0.5 bg-amber-500 rounded-full"></span>
                    @endif
                </a>

                <a href="{{ route('contact') }}"
                   class="relative px-4 py-2 text-sm font-medium transition-all duration-300 rounded-full group"
                   :class="{
                       'text-white hover:text-amber-300': !scrolled && isHeroPage,
                       'text-secondary-700 hover:text-amber-600': scrolled || !isHeroPage,
                       'text-amber-500': {{ request()->routeIs('contact') ? 'true' : 'false' }}
                   }">
                    Contact
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-amber-500 group-hover:w-1/2 transition-all duration-300 rounded-full"></span>
                    @if(request()->routeIs('contact'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1/2 h-0.5 bg-amber-500 rounded-full"></span>
                    @endif
                </a>
            </div>

            <!-- Auth Section -->
            <div class="hidden lg:flex items-center space-x-4">
                @auth
                    @include('layouts.partials.profile-dropdown')
                @else
                    <a href="{{ route('login') }}"
                       class="flex items-center gap-2 px-5 py-2.5 rounded-full font-medium text-sm transition-all duration-300"
                       :class="{
                           'text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/20': !scrolled && isHeroPage,
                           'text-secondary-700 hover:text-amber-600 bg-secondary-100 hover:bg-secondary-200': scrolled || !isHeroPage
                       }">
                        <span>Login</span>
                        <div class="w-6 h-6 rounded-full bg-amber-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </a>
                    <a href="{{ route('register') }}"
                       class="btn-primary flex items-center gap-2 px-6 py-2.5 text-white rounded-full font-medium text-sm shadow-lg hover:shadow-xl">
                        <span>Get Started</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center lg:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="p-2 rounded-xl transition-all duration-300"
                        :class="{
                            'text-white bg-white/10 hover:bg-white/20': !scrolled && isHeroPage,
                            'text-secondary-700 bg-secondary-100 hover:bg-secondary-200': scrolled || !isHeroPage
                        }">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="mobileMenuOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden absolute top-full left-0 right-0 bg-white/95 backdrop-blur-xl shadow-2xl border-t border-secondary-100">
        <div class="px-4 py-6 space-y-2">
            <a href="{{ url('/') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->is('/') ? 'bg-amber-50 text-amber-600' : 'text-secondary-700 hover:bg-secondary-50' }}">
                <div class="w-10 h-10 rounded-xl {{ request()->is('/') ? 'bg-amber-500 text-white' : 'bg-secondary-100 text-secondary-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="font-medium">Home</span>
            </a>

            <a href="{{ route('tour-packages.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('tour-packages.*') ? 'bg-amber-50 text-amber-600' : 'text-secondary-700 hover:bg-secondary-50' }}">
                <div class="w-10 h-10 rounded-xl {{ request()->routeIs('tour-packages.*') ? 'bg-amber-500 text-white' : 'bg-secondary-100 text-secondary-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="font-medium">Tour Packages</span>
            </a>

            <a href="{{ route('tour-requests.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('tour-requests.*') ? 'bg-amber-50 text-amber-600' : 'text-secondary-700 hover:bg-secondary-50' }}">
                <div class="w-10 h-10 rounded-xl {{ request()->routeIs('tour-requests.*') ? 'bg-amber-500 text-white' : 'bg-secondary-100 text-secondary-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="font-medium">Tour Requests</span>
            </a>

            <a href="{{ route('about') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('about') ? 'bg-amber-50 text-amber-600' : 'text-secondary-700 hover:bg-secondary-50' }}">
                <div class="w-10 h-10 rounded-xl {{ request()->routeIs('about') ? 'bg-amber-500 text-white' : 'bg-secondary-100 text-secondary-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="font-medium">About</span>
            </a>

            <a href="{{ route('contact') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('contact') ? 'bg-amber-50 text-amber-600' : 'text-secondary-700 hover:bg-secondary-50' }}">
                <div class="w-10 h-10 rounded-xl {{ request()->routeIs('contact') ? 'bg-amber-500 text-white' : 'bg-secondary-100 text-secondary-600' }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="font-medium">Contact</span>
            </a>

            <!-- Mobile Auth Section -->
            <div class="pt-4 mt-4 border-t border-secondary-100">
                @auth
                    <div class="px-4 py-2 mb-2">
                        <p class="text-xs text-secondary-400 uppercase tracking-wider">Signed in as</p>
                        <p class="text-sm font-medium text-secondary-700">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ Auth::user()->user_type === 'guide' ? route('guide.dashboard') : route('tourist.dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-secondary-700 hover:bg-secondary-50 transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ Auth::user()->user_type === 'guide' ? route('guide.settings') : route('tourist.settings') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-secondary-700 hover:bg-secondary-50 transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-secondary-100 text-secondary-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium">Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition-all duration-300">
                            <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="font-medium">Logout</span>
                        </button>
                    </form>
                @else
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('login') }}"
                           class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-secondary-100 text-secondary-700 font-medium hover:bg-secondary-200 transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}"
                           class="btn-primary flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-white font-medium shadow-lg">
                            <span>Get Started</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div class="h-20"></div>
