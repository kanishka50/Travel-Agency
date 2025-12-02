<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    @auth
                        @if(auth()->user()->isTourist())
                            <a href="{{ route('welcome') }}" class="flex items-center group">
                                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-2 mr-2 group-hover:shadow-lg transition-all duration-200">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
                                    <p class="text-xs text-gray-500">Tourism Platform</p>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="flex items-center group">
                                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-2 mr-2 group-hover:shadow-lg transition-all duration-200">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
                                    <p class="text-xs text-gray-500">Tourism Platform</p>
                                </div>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('welcome') }}" class="flex items-center group">
                            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-2 mr-2 group-hover:shadow-lg transition-all duration-200">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
                                <p class="text-xs text-gray-500">Tourism Platform</p>
                            </div>
                        </a>
                    @endauth
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:ml-10 md:flex md:items-center md:space-x-2">
                    @auth
                        @if(auth()->user()->isTourist())
                            <a href="{{ route('tourist.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('tourist.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('bookings.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('bookings.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                My Bookings
                            </a>
                            <a href="{{ route('tourist.proposals.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('tourist.proposals.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                My Proposals
                            </a>
                            <a href="{{ route('tourist-requests.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('tourist-requests.index') || request()->routeIs('tourist-requests.show') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                My Requests
                            </a>
                            <a href="{{ route('tourist.complaints.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('tourist.complaints.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                My Complaints
                            </a>
                            <a href="{{ route('tourist-requests.create') }}" class="flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg text-sm font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg ml-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Request Custom Tour
                            </a>
                        @elseif(auth()->user()->isGuide())
                            <a href="{{ route('guide.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guide.dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('guide.plans.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guide.plans.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                My Plans
                            </a>
                            <a href="{{ route('guide.bookings') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guide.bookings*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                My Bookings
                            </a>
                            <a href="{{ route('guide.proposals.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guide.proposals.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                My Proposals
                            </a>
                            <a href="{{ route('guide.requests.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guide.requests.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Browse Requests
                            </a>
                            <a href="{{ route('guide.payments.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guide.payments.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                My Payments
                            </a>
                            <a href="{{ route('guide.complaints.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guide.complaints.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                My Complaints
                            </a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="/admin" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-gray-700 hover:bg-gray-50">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Admin Panel
                            </a>
                        @endif
                    @else
                        <a href="{{ route('plans.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Browse Tours
                        </a>
                        <a href="{{ route('guide.register') }}" class="flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            Become a Guide
                        </a>
                    @endauth
                </div>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                @auth
                    <div class="flex items-center space-x-4">
                        <!-- User Profile Section -->
                        <div class="hidden md:flex items-center bg-gray-50 rounded-lg px-4 py-2">
                            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-full p-2 mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="hidden lg:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg text-sm font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg">
                            Sign Up
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile Menu Button -->
    <div class="md:hidden px-4 pb-4">
        @auth
            @if(auth()->user()->isTourist())
                <div class="space-y-1">
                    <a href="{{ route('tourist.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('tourist.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('bookings.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('bookings.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        My Bookings
                    </a>
                    <a href="{{ route('tourist.proposals.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('tourist.proposals.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        My Proposals
                    </a>
                    <a href="{{ route('tourist-requests.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('tourist-requests.index') || request()->routeIs('tourist-requests.show') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        My Requests
                    </a>
                    <a href="{{ route('tourist.complaints.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('tourist.complaints.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        My Complaints
                    </a>
                    <a href="{{ route('tourist-requests.create') }}" class="flex items-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg text-sm font-semibold shadow-md">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Request Custom Tour
                    </a>
                </div>
            @elseif(auth()->user()->isGuide())
                <div class="space-y-1">
                    <a href="{{ route('guide.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('guide.dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('guide.plans.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('guide.plans.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        My Plans
                    </a>
                    <a href="{{ route('guide.bookings') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('guide.bookings*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        My Bookings
                    </a>
                    <a href="{{ route('guide.proposals.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('guide.proposals.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        My Proposals
                    </a>
                    <a href="{{ route('guide.requests.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('guide.requests.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Browse Requests
                    </a>
                    <a href="{{ route('guide.payments.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('guide.payments.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        My Payments
                    </a>
                    <a href="{{ route('guide.complaints.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('guide.complaints.*') ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        My Complaints
                    </a>
                </div>
            @endif
        @endauth
    </div>
</nav>
